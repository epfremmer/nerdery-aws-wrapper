<?php
/**
 * File ClientAbstract.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Clients;

use Exception;
use AwsWrap\Models\AbstractModel;
use AwsWrap\Models\ModelCollection;
use Aws\Common\Client\AbstractClient;
use Guzzle\Service\Resource\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ClientAbstract
 *
 * @package       AwsWrap
 * @subpackage    Clients
 */
abstract class ClientAbstract
{

    // Base data model class name
    const DEFAULT_MODEL_CLASS = 'AwsWrap\\Models\\BaseModel';

    /**
     * AWS client config
     * @var array
     */
    protected $config;

    /**
     * AWS Client
     * @var AbstractClient
     */
    protected $awsClient;

    /**
     * Model name map
     * @var array
     */
    protected $modelsMap = [];

    /**
     * Constructor
     *
     * @param array $config
     * @throws Exception
     */
    public function __construct(array $config)
    {
        /** @var AbstractClient $clientClass */
        $clientClass = $this->getClientClass();

        if (!class_exists($clientClass)) {
            throw new Exception(
                sprintf('Invalid AWS client class "%s" specified', $clientClass)
            );
        }

        $this->config    = $config;
        $this->awsClient = $clientClass::factory($this->config);
    }

    /**
     * Return new instance of the AWS wrapper client
     *
     * @param array $config
     * @return ClientAbstract
     */
    public static function factory(array $config = [])
    {
        return new static($config);
    }

    /**
     * Return the AWS client
     *
     * @return AbstractClient
     * @throws Exception
     */
    public function getClient()
    {
        return $this->awsClient;
    }

    /**
     * Return the fully qualified class name
     * from the response data key
     *
     * @param string $key
     * @return bool|string
     */
    protected function getModelClass($key)
    {
        $className = self::DEFAULT_MODEL_CLASS;

        if (array_key_exists($key, $this->modelsMap)) {
            $modelName = $this->modelsMap[$key];
            $className = $this->getModelNamespace() . $modelName;
        }

        return class_exists($className) ? $className : false;
    }

    /**
     * Test if the response data array is numerically
     * indexed as a collection
     *
     * @param array $data
     * @return bool
     */
    public function isCollection(array $data)
    {
        return array_keys($data) === range(0, count($data) - 1);
    }

    /**
     * Return AWS pre-hydrated response model data
     *
     * Hydrate response data into appropriate model or model collection
     *
     * @param Model $response
     * @return AbstractModel|Model|ModelCollection|ArrayCollection
     */
    public function hydrateModel($response) {
        $result = $response;

        foreach ($response as $key => $data) {
            $modelClass = $this->getModelClass($key);

            if (!is_array($data)) {
                $data = [$key => $data];
            }

            if ($this->isCollection($data)) {
                $data = $modelClass ? new ModelCollection($data, $modelClass) : new ArrayCollection($data);
            } else {
                $data = $modelClass ? new $modelClass($data) : $data;
            }

            $result = $data;
        }

        return $result;
    }

    /**
     * Call the internal AWS client method
     *
     * @param string $method
     * @param array $arguments
     * @return AbstractModel|Model|ModelCollection|ArrayCollection
     */
    public function __call($method, $arguments)
    {
        $result = call_user_func_array(array($this->getClient(), $method), $arguments);

        return $this->hydrateModel($result);
    }

    /**
     * Return fully qualified AWS client class name
     *
     * @return string
     */
    protected abstract function getClientClass();

    /**
     * Return qualified base model namespace
     *
     * @return string
     */
    protected abstract function getModelNamespace();

}