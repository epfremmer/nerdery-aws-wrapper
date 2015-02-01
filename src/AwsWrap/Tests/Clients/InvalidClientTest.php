<?php
/**
 * File InvalidClientTest.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Clients;

use Exception;
use AwsWrap\Tests\Mocks\InvalidClient;
use PHPUnit_Framework_TestCase;

/**
 * Class InvalidClientTest
 *
 * @uses          PHPUnit_Framework_TestCase
 * @package       AwsWrap
 * @subpackage    Tests
 */
class InvalidClientTest extends PHPUnit_Framework_TestCase
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
    public function testMissingClientClassException()
    {
        try {
            new InvalidClient([]);
        } catch (Exception $e) {
            $this->anything($e);
            $this->assertInstanceOf(Exception::class, $e);
            return true;
        }

        $this->fail('Model exception not caught!!!');
    }

}
