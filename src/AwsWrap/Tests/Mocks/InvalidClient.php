<?php
/**
 * File InvalidClient.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Mocks;

use AwsWrap\Clients\AbstractClient;

/**
 * Class InvalidClient
 *
 * Mock class used to simulate extension of abstract client with invalid class properties. This mock class is used
 * during unit testing to validate that the client construct method is throwing exceptions in these cases.
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Tests
 */
class InvalidClient extends AbstractClient
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientClass;

    /**
     * Map of model class names to AWS OpsWorks Client methods
     * @var array
     */
    protected $modelsMap = [];

    /**
     * @inheritdoc
     */
    protected function getClientClass()
    {
        return 'Invalid\\Class\\Name';
    }

    /**
     * @inheritdoc
     */
    protected function getModelNamespace()
    {
        return self::DEFAULT_MODEL_CLASS;
    }

}