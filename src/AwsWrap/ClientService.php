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
 * @uses
 * @package       AwsWrap
 * @subpackage    Models
 */
class ClientService
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
    protected static $params = [];

    /**
     * Custom AWS configuration
     *
     * @see http://docs.aws.amazon.com/aws-sdk-php/guide/latest/configuration.html
     * @var array
     */
    protected static $config = [];

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
            self::$instance = Aws::factory(realpath(__DIR__) . self::CONFIG_PATH);
        }

        return self::$instance;
    }

    /**
     * Set default AWS client params
     *
     * @param array $params
     */
    public static function setParams(array $params)
    {
        self::reset();
        self::$params = $params;
    }

    /**
     * Return client config params
     *
     * @return array
     * @throws Exception
     */
    public static function getParams()
    {
        if (empty(self::$params)) {
            throw new Exception('Missing AWS configuration settings');
        }

        return self::$params;
    }

    /**
     * Set custom AWS configuration
     *
     * @param array $config
     */
    public static function setConfig(array $config)
    {
        self::reset();
        self::$config = $config;
    }

    /**
     * Return custom configuration
     *
     * @return array
     */
    public static function getConfig()
    {
        return self::$config;
    }

    /**
     * Reset client service
     *
     * @param bool $hard
     * @return void
     */
    public static function reset($hard = false)
    {
        self::$instance = null;

        if ($hard) {
            self::$params = [];
            self::$config = [];
        }
    }

}