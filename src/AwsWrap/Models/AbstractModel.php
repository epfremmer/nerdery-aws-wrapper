<?php
/**
 * File AbstractModel.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models;

use Exception;
use AwsWrap\ClientService;
use Aws\Common\Client\AbstractClient;

/**
 * Class AbstractModel
 *
 * @uses          ModelInterface
 * @package       AwsWrap
 * @subpackage    Models
 */
abstract class AbstractModel implements ModelInterface
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType;

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey;

    /**
     * Model data
     * @var array
     */
    protected $data = [];

    /**
     * Constructor
     *
     * Prepares model data if provided
     *
     * @param array|string $data
     * @throws Exception
     */
    public function __construct($data = [])
    {
        if (!isset($this->clientType)) {
            throw new Exception(
                sprintf('No AWS client type specified for model "%s"', get_class($this))
            );
        }

        if (!isset($this->primaryKey)) {
            throw new Exception(
                sprintf('No primary specified for model "%s"', get_class($this))
            );
        }

        if (is_array($data)) {
            $this->setData($data);
        }

        if (is_string($data)) {
            $this->fromJson($data);
        }
    }

    /**
     * Return the AWS client
     *
     * @return AbstractClient
     */
    protected function getClient()
    {
        return ClientService::getClient($this->clientType);
    }

    /**
     * Return the primary model key
     *
     * @return int|string
     */
    protected function getPrimaryKey()
    {
        return $this->primaryKey;
    }

    /**
     * Return the model ID
     *
     * @return int|string
     */
    public function getID()
    {
        $primaryKey = $this->getPrimaryKey();

        return array_key_exists($primaryKey, $this->data) ? $this->data[$primaryKey] : null;
    }

    /**
     * Return serialized model
     *
     * @return string
     */
    public function toJson() {
        return json_encode($this->data);
    }

    /**
     * Hydrate model from JSON
     *
     * @param string $json
     * @return $this
     */
    public function fromJson($json = "") {
        $data = json_encode($json);

        return $this->setData($data);
    }

    /**
     * Set model data
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data = [])
    {
        $this->data = array_merge($this->data, $data);

        return $this;
    }

    /**
     * Return model data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Return the model's argument representation
     *
     * @return mixed
     */
    abstract public function toArgs();

    /**
     * Test if model data is valid
     *
     * @return bool
     */
    abstract public function isValid();

    /**
     * Save model data
     *
     * @return self
     */
    abstract public function save();

    /**
     * Refresh model data
     *
     * @return self
     */
    abstract public function refresh();

    /**
     * Return model data
     *
     * @param string $prop
     * @return null|mixed
     */
    public function __get($prop)
    {
        $result = null;

        if (property_exists($this, $prop)) {
            $result = $this->{$prop};
        } elseif (array_key_exists($prop, $this->data)) {
            $result = $this->data[$prop];
        }

        return $result;
    }

    /**
     * Set model data
     *
     * @param string $prop
     * @param mixed $value
     */
    public function __set($prop, $value)
    {
        $this->data[$prop] = $value;
    }

}