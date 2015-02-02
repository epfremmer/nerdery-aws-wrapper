<?php
/**
 * File AbstractClientTest.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Clients;

use Aws\Common\Client\AbstractClient;
use Aws\Common\Enum\Region;
use AwsWrap\Models\BaseModel;
use AwsWrap\Models\ModelCollection;
use AwsWrap\Tests\Mocks\GenericModel;
use Exception;
use Aws\Common\Enum\ClientOptions;
use AwsWrap\Tests\Mocks\GenericClient;
use Guzzle\Service\Resource\Model;
use PHPUnit_Framework_TestCase;

/**
 * Class AbstractClientTest
 *
 * @uses          PHPUnit_Framework_TestCase
 * @package       AwsWrap
 * @subpackage    Tests
 */
class AbstractClientTest extends PHPUnit_Framework_TestCase
{

    // public AWS endpoints resource
    const AWS_COMMON_RESOURCES = '/../../../../vendor/aws/aws-sdk-php/src/Aws/Common/Resources/public-endpoints.php';

    /**
     * Test Client
     * @var GenericClient
     */
    protected static $client;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass()
    {
        self::$client = GenericClient::factory([
            ClientOptions::REGION              => Region::US_EAST_1,
            ClientOptions::SERVICE             => 'unittest',
            ClientOptions::SIGNATURE           => 'v4',
            ClientOptions::SERVICE_DESCRIPTION => realpath(__DIR__) . self::AWS_COMMON_RESOURCES,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function setUp() {}

    /**
     * {@inheritdoc}
     */
    public function tearDown() {}

    /**
     * Test getting AWS client directly
     *
     * @return void
     */
    public function testGetClient()
    {
        $client = self::$client->getClient();

        $this->assertInstanceOf(GenericClient::class, self::$client);
        $this->assertInstanceOf(AbstractClient::class, $client);
    }

    /**
     * Test hydrating single model AWS response to the
     * proper specified wrapper model type
     *
     * @return void
     */
    public function testHydrateSingleModel()
    {
        $response = new Model([
            'Item' => ['ItemID' => 1],
        ]);

        $model = self::$client->hydrateModel($response);

        $this->assertInstanceOf(GenericModel::class, $model);
    }

    /**
     * Test hydrating a collection of specified models to
     * the proper model & collection types
     *
     * @return void
     */
    public function testHydrateModelCollection()
    {
        $response = new Model([
            'Items' => [
                ['ItemID' => 1],
                ['ItemID' => 2],
                ['ItemID' => 3],
                ['ItemID' => 4],
                ['ItemID' => 5]
            ],
        ]);

        $collection = self::$client->hydrateModel($response);

        $this->assertInstanceOf(ModelCollection::class, $collection);
        $this->assertContainsOnlyInstancesOf(GenericModel::class, $collection);
    }

    /**
     * Test hydrating a scalar AWS response to the proper
     * specified model type
     *
     * @return void
     */
    public function testHydrateScalarResponse()
    {
        $response = new Model([
            'Item' => '1234-56789-000000-000',
        ]);

        $model = self::$client->hydrateModel($response);

        $this->assertInstanceOf(GenericModel::class, $model);
    }

    /**
     * Test base model hydration for non-specified model response
     *
     * @return void
     */
    public function testHydrateNonModel()
    {
        $response = new Model([
            'NonItem' => ['ItemID' => 1],
        ]);

        $model = self::$client->hydrateModel($response);

        $this->assertInstanceOf(BaseModel::class, $model);
    }

    /**
     * Test base model hydration for non-specified model
     * collection responses
     *
     * @return void
     */
    public function testHydrateNonModelCollection()
    {
        $response = new Model([
            'NonItems' => [
                ['NonItemID' => 1],
                ['NonItemID' => 2],
                ['NonItemID' => 3],
                ['NonItemID' => 4],
                ['NonItemID' => 5]
            ],
        ]);

        $collection = self::$client->hydrateModel($response);

        $this->assertInstanceOf(ModelCollection::class, $collection);
        $this->assertContainsOnlyInstancesOf(BaseModel::class, $collection);
    }

    /**
     * Test base model hydration for non-specified scalar response
     *
     * @return void
     */
    public function testHydrateNonModelScalarResponse()
    {
        $response = new Model([
            'NonItem' => '1234-56789-000000-000',
        ]);

        $model = self::$client->hydrateModel($response);

        $this->assertInstanceOf(BaseModel::class, $model);
    }

}
