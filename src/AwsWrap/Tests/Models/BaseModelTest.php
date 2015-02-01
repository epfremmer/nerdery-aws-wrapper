<?php
/**
 * File BaseModelTest.php
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
 * Class BaseModelTest
 *
 * @uses          PHPUnit_Framework_TestCase
 * @package       AwsWrap
 * @subpackage    Tests
 */
class BaseModelTest extends PHPUnit_Framework_TestCase
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
    public function setUp() {}

    /**
     * {@inheritdoc}
     */
    public function tearDown() {}

    /**
     * Test getting model data
     *
     * @return void
     */
    public function testGetData()
    {
        $model = new BaseModel($this->stubData);

        $this->assertInternalType('array', $model->getData());
        $this->assertEquals($this->stubData, $model->getData());
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
     * Test new BaseModel object
     *
     * @depends testSetData
     * @depends testGetData
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
     * Test setting new data merges with current model data
     *
     * @return void
     */
    public function testMergeData()
    {
        $data = [
            'key' => 'value',
        ];

        $model = new BaseModel($this->stubData);

        $model->setData($data);

        $this->assertNotEquals($this->stubData, $model->getData());
        $this->assertEquals(array_merge($this->stubData, $data), $model->getData());
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
        $model = new BaseModel($this->stubData);

        $json = $model->toJson();

        $this->assertInternalType('string', $json);
        $this->assertJson($json);
        $this->assertEquals($this->stubData, json_decode($json, true));

        $json = json_encode($model);

        $this->assertInternalType('string', $json);
        $this->assertJson($json);
        $this->assertEquals($this->stubData, json_decode($json, true));
    }

}
