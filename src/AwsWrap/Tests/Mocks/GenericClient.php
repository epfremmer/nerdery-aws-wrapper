<?php
/**
 * File InvalidClient.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Mocks;

use Aws\Common\Client\DefaultClient;
use AwsWrap\Clients\AbstractClient;

/**
 * Class GenericClient
 *
 * Mock class used to simulate extension of abstract client with invalid class properties. This mock class is used
 * during unit testing to validate that the client construct method is throwing exceptions in these cases.
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Tests
 */
class GenericClient extends AbstractClient
{

    // Base AWS Wrapper model class name
    const MODEL_CLASS_NAMESPACE = null;

    /**
     * Map of model class names to AWS OpsWorks Client methods
     * @var array
     */
    protected $modelsMap = [
        'Foo' => ''
    ];

    /**
     * @inheritdoc
     */
    protected function getClientClass()
    {
        return DefaultClient::class;
    }

    /**
     * @inheritdoc
     */
    protected function getModelNamespace()
    {
        return self::DEFAULT_MODEL_CLASS;
    }

}