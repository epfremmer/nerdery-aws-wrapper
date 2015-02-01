<?php
/**
 * File InvalidModelTest.php
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
 * Class InvalidModelTest
 *
 * @uses          PHPUnit_Framework_TestCase
 * @package       AwsWrap
 * @subpackage    Tests
 */
class InvalidModelTest extends PHPUnit_Framework_TestCase
{

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
     * Test proper constructor exceptions thrown
     *
     * @return void|bool
     */
    public function testMissingClientTypeException()
    {
        try {
            new InvalidModel([], null, 'primaryKey');
        } catch (Exception $e) {
            $this->anything($e);
            return true;
        }

        $this->fail('Model exception not caught!!!');
    }

    /**
     * Test proper constructor exceptions thrown
     *
     * @return void|bool
     */
    public function testMissingPrimaryKeyException()
    {
        try {
            new InvalidModel([], 'clientType', null);
        } catch (Exception $e) {
            $this->anything($e);
            return true;
        }

        $this->fail('Model exception not caught!!!');
    }

}
