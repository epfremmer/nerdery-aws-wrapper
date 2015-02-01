<?php
/**
 * File Iam.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Clients;

use Aws\Iam\IamClient;
use AwsWrap\Models\ModelCollection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Iam
 *
 * @uses          ClientAbstract
 * @package       AwsWrap
 * @subpackage    Clients
 */
class Iam extends AbstractClient
{

    // Base AWS Wrapper model class namesudo
    const MODEL_CLASS_NAMESPACE = 'AwsWrap\\Models\\Iam\\';

    /**
     * Map of model class names to AWS IAM Client methods
     * @var array
     */
    protected $modelsMap = [
        'Role'             => 'Role',
        'Roles'            => 'Role',
        'RolePolicy'       => 'RolePolicy',
        'RolePolicies'     => 'RolePolicy',
        'InstanceProfile'  => 'InstanceProfile',
        'InstanceProfiles' => 'InstanceProfile',
    ];

    /**
     * @inheritdoc
     */
    protected function getClientClass()
    {
        return IamClient::class;
    }

    /**
     * @inheritdoc
     */
    protected function getModelNamespace()
    {
        return self::MODEL_CLASS_NAMESPACE;
    }

    /**
     * @inheritdoc
     */
    public function hydrateModel($response) {
        $response->remove('ResponseMetadata')
                 ->remove('IsTruncated');

        return parent::hydrateModel($response);
    }

}