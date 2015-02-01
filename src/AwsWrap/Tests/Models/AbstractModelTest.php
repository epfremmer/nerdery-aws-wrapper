<?php
/**
 * File AbstractModelTest.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Models;

use Exception;
use AwsWrap\Models\AbstractModel;
use AwsWrap\Models\BaseModel;
use AwsWrap\Tests\Mocks\InvalidModel;
use PHPUnit_Framework_TestCase;

/**
 * Class AbstractModelTest
 *
 * @uses          PHPUnit_Framework_TestCase
 * @package       AwsWrap
 * @subpackage    Tests
 */
class AbstractModelTest extends PHPUnit_Framework_TestCase
{

    /**
     * Model stub data
     * @var array
     */
    protected $stubData = [
        'foo' => 'bar'
    ];

    /**
     * Test model
     * @var BaseModel
     */
    protected $model;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass() {}

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->model = new BaseModel($this->stubData);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown() {}

    /**
     * Test new BaseModel object
     *
     * @return void
     */
    public function testConstructor()
    {
        $modelFromArray = new BaseModel($this->stubData);
        $modelFromJson  = new BaseModel(json_encode($this->stubData));

        $this->assertInstanceOf(AbstractModel::class, $modelFromArray);
        $this->assertInstanceOf(AbstractModel::class, $modelFromJson);
        $this->assertEquals($this->stubData, $modelFromArray->getData());
        $this->assertEquals($this->stubData, $modelFromJson->getData());
    }

    /**
     * Test getting model data
     *
     * @return void
     */
    public function testGetData()
    {
        $this->assertInternalType('array', $this->model->getData());
        $this->assertEquals($this->stubData, $this->model->getData());
    }

    /**
     * Test setting model data
     *
     * @return void
     */
    public function testSetData()
    {
        $model = new BaseModel();

        $model->setData($this->stubData);

        $this->assertInternalType('array', $model->getData());
        $this->assertEquals($this->stubData, $model->getData());
    }

    /**
     * Test setting new data merges with current model data
     *
     * @return void
     */
    public function testMergeData()
    {
        $data = [
            'key' => 'value',
        ];

        $this->model->setData($data);

        $this->assertNotEquals($this->stubData, $this->model->getData());
        $this->assertEquals(array_merge($this->stubData, $data), $this->model->getData());
    }

    /**
     * Test setting model data from json
     *
     * @depends testSetData
     * @return void
     */
    public function testFromJson()
    {
        $model = new BaseModel();

        $model->fromJson(json_encode($this->stubData));

        $this->assertInternalType('array', $model->getData());
        $this->assertEquals($this->stubData, $model->getData());
    }

    /**
     * Test setting model data from json
     *
     * @depends testSetData
     * @return void
     */
    public function testToJson()
    {
        $json = $this->model->toJson();

        $this->assertInternalType('string', $json);
        $this->assertJson($json);
        $this->assertEquals($this->stubData, json_decode($json, true));

        $json = json_encode($this->model);

        $this->assertInternalType('string', $json);
        $this->assertJson($json);
        $this->assertEquals($this->stubData, json_decode($json, true));
    }

    /**
     * Test magic getter method
     *
     * @return void
     */
    public function testGetter()
    {
        $this->assertEquals($this->stubData['foo'], $this->model->foo);
    }

    /**
     * Test magic setter method
     *
     * @depends testGetter
     * @return void
     */
    public function testSetter()
    {
        $this->model->bar = 'baz';

        $this->assertEquals($this->model->bar, 'baz');
        $this->assertEquals($this->model->foo, 'bar');
    }

}
