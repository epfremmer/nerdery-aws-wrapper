<?php
/**
 * File ClientService.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap;

use Exception;
use Aws\Common\Aws;
use Aws\Common\Client\AbstractClient;

/**
 * Class ClientService
 *
 * @uses          Aws
 * @package       AwsWrap
 * @subpackage    Models
 */
class ClientService extends Aws
{

    // config file path
    const CONFIG_PATH = '/Resources/config.php';

    /**
     * AWS config params
     *
     * example: [
     *   'key'    => '',
     *   'secret' => '',
     *   'region' => '',
     * ]
     *
     * @var array
     */
    protected static $settings = [];

    /**
     * AWS Instance
     * @var Aws
     */
    protected static $instance;

    /**
     * Return the AWS client by type
     *
     * @param string $service
     * @return AbstractClient
     */
    public static function getClient($service)
    {
        $aws = self::getInstance();

        return $aws->get($service);
    }

    /**
     * Return the AWS client instance
     *
     * @return Aws
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = self::factory(realpath(__DIR__) . self::CONFIG_PATH);
        }

        return self::$instance;
    }

    /**
     * Set default AWS client params
     *
     * @param array $settings
     */
    public static function setParams(array $settings)
    {
        self::reset();

        self::$settings = $settings;
    }

    /**
     * Return client config params
     *
     * @return array
     * @throws Exception
     */
    public static function getParams()
    {
        if (empty(self::$settings)) {
            throw new Exception('Missing AWS configuration settings');
        }

        return self::$settings;
    }

    /**
     * Reset client service
     *
     * @return void
     */
    public static function reset()
    {
        self::$instance = null;
        self::$settings = [];
    }

}