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
    public function setUp()
    {
        $this->model = new BaseModel($this->stubData);
    }

    /**
     * {@inheritdoc}
     */
    public function tearDown() {}

    /**
     * Test getting unavailable base model ID
     *
     * @return void
     */
    public function testGetID()
    {
        $this->assertNull($this->model->getID());
    }

    /**
     * Test getting unavailable base model ID
     *
     * @return void
     */
    public function testToArgs()
    {
        $this->assertInternalType('array', $this->model->toArgs());
        $this->assertEquals($this->stubData, $this->model->toArgs());
    }

    /**
     * Test getting base model client false
     *
     * @return void
     */
    public function testGetClient()
    {
        $this->assertFalse($this->model->getCLient());
    }

    /**
     * Test isValid always true
     *
     * @return void
     */
    public function testisValid()
    {
        $this->assertTrue($this->model->isValid());
    }

    /**
     * Test save does nothing
     *
     * @return void
     */
    public function testSave()
    {
        $this->assertEmpty($this->model->save());
    }

    /**
     * Test refresh does nothing
     *
     * @return void
     */
    public function testRefresh()
    {
        $this->assertEmpty($this->model->refresh());
    }

}
