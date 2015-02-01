<?php
/**
 * File ClientServiceTest.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests;

use Exception;
use Aws\Common\Aws;
use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Clients\OpsWorks;
use AwsWrap\ClientService;
use PHPUnit_Framework_TestCase;

/**
 * Class ClientServiceTest
 *
 * @uses          PHPUnit_Framework_TestCase
 * @package       AwsWrap
 * @subpackage    Tests
 */
class ClientServiceTest extends PHPUnit_Framework_TestCase
{

    // client test constants
    const AWS_ACCESS_KEY_ID     = 'aws_access_key_id';
    const AWS_SECRET_ACCESS_KEY = 'aws_secret_access_key';
    const AWS_REGION            = 'us-east-1';

    /**
     * Test AWS params
     * @var array
     */
    protected $clientParams = [
        'key'    => self::AWS_ACCESS_KEY_ID,
        'secret' => self::AWS_SECRET_ACCESS_KEY,
        'region' => self::AWS_REGION
    ];

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass() {}

    /**
     * {@inheritdoc}
     */
    public function setUp() {}

    /**
     * {@inheritdoc}
     */
    public function tearDown()
    {
        ClientService::reset();
    }

    /**
     * Test getting AWS client instance
     *
     * @return void
     */
    public function testGetInstance()
    {
        ClientService::setParams($this->clientParams);

        $awsClient = ClientService::getInstance();

        $this->assertInstanceOf(Aws::class, $awsClient);
    }

    /**
     * Test getting client service instance missing
     * configuration settings exception
     *
     * @return void|true
     */
    public function testGetInstanceException()
    {
        try {
            ClientService::getInstance();
        } catch (Exception $e) {
            $this->anything($e);
            $this->assertInstanceOf(Exception::class, $e);

            return true;
        }

        $this->fail('Missing params exception not thrown!!!');
    }

    /**
     * Test setting default AWS params
     *
     * @depends testGetInstance
     * @return void
     */
    public function testSetParams()
    {
        ClientService::setParams($this->clientParams);

        $awsClient = ClientService::getInstance();
        $config    = $awsClient->getConfig();

        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('default_settings', $config);
        $this->assertArrayHasKey('params', $config['default_settings']);

        $params = $config['default_settings']['params'];

        $this->assertEquals($this->clientParams, $params);
    }

    /**
     * Test configuration file
     *
     * @depends testSetParams
     * @return void
     */
    public function testConfigFile()
    {
        $file = realpath(__DIR__) . '/../Resources/config.php';

        $this->assertFileExists($file);

        ClientService::setParams($this->clientParams);

        $config = require $file;

        $this->assertInternalType('array', $config);
        $this->assertArrayHasKey('includes', $config);
        $this->assertArrayHasKey('class', $config);
        $this->assertArrayHasKey('services', $config);
        $this->assertArrayHasKey('default_settings', $config['services']);
        $this->assertArrayHasKey('params', $config['services']['default_settings']);

        $params = $config['services']['default_settings']['params'];

        $this->assertEquals($this->clientParams, $params);
    }

    /**
     * Test getting client params
     *
     * @depends testSetParams
     * @return void
     */
    public function testGetParams()
    {
        ClientService::setParams($this->clientParams);

        $params = ClientService::getParams();

        $this->assertInternalType('array', $params);
        $this->assertEquals($this->clientParams, $params);
    }

    /**
     * Test resetting client params
     *
     * @return void
     */
    public function testReset()
    {
        ClientService::setParams($this->clientParams);

        $awsClient = ClientService::getInstance();

        ClientService::reset();
        ClientService::setParams([
            'key' => '',
            'secret' => '',
        ]);

        $newClient = ClientService::getInstance();

        $this->assertNotEquals($awsClient, $newClient);
        $this->assertNotEquals($awsClient->getConfig(), $newClient->getConfig());
    }

    /**
     * Test getting wrapper client
     *
     * @return void
     */
    public function testGetAwsClient()
    {
        ClientService::setParams($this->clientParams);

        $opsworksClient = ClientService::getClient('opsworks');

        $this->anything($opsworksClient);
        $this->assertInstanceOf(OpsWorks::class, $opsworksClient);
        $this->assertNotInstanceOf(OpsWorksClient::class, $opsworksClient);
    }

    /**
     * Test getting standard AWS client
     *
     * @return void
     */
    public function testGetWrapperClient()
    {
        ClientService::setParams($this->clientParams);

        $awsClient = ClientService::getInstance();

        // force default builder client
        $awsClient->set('opsworks', [
            'class'  => OpsWorksClient::class,
            'params' => $this->clientParams,
        ]);

        $opsworksClient = ClientService::getClient('opsworks');

        $this->anything($opsworksClient);
        $this->assertInstanceOf(OpsWorksClient::class, $opsworksClient);
        $this->assertNotInstanceOf(OpsWorks::class, $opsworksClient);
    }

}
