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
 * Mock class used to simulate extension of abstract client with with testing model mappings specified. Only for
 * testing the hydration of various general AWS response model types & formats.
 *
 * This is for testing general client & response hydration functionality. Specific client methods unique to each
 * client type should be tested separately.
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Tests
 */
class GenericClient extends AbstractClient
{

    // Base AWS Wrapper model class name
    const MODEL_CLASS_NAMESPACE = 'AwsWrap\\Tests\\Mocks\\';

    /**
     * Map of model class names to AWS OpsWorks Client methods
     * @var array
     */
    protected $modelsMap = [
        'Item'  => 'GenericModel',
        'Items' => 'GenericModel',
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
        return self::MODEL_CLASS_NAMESPACE;
    }

}