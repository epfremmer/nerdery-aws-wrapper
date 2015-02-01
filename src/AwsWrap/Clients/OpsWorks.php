<?php
/**
 * File OpsWorks.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Clients;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\ModelCollection;
use AwsWrap\Models\OpsWorks\App;
use AwsWrap\Models\OpsWorks\Deployment;
use AwsWrap\Models\OpsWorks\ElasticLoadBalancer;
use AwsWrap\Models\OpsWorks\Instance;
use AwsWrap\Models\OpsWorks\Layer;
use AwsWrap\Models\OpsWorks\Stack;
use Guzzle\Service\Resource\Model;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class OpsWorks
 *
 * @method App                 createApp(array $args = array())
 * @method Model               updateApp(array $args = array())
 * @method Stack               createStack(array $args = array())
 * @method Model               updateStack(array $args = array())
 * @method Layer               createLayer(array $args = array())
 * @method Model               updateLayer(array $args = array())
 * @method Instance            createInstance(array $args = array())
 * @method Model               updateInstance(array $args = array())
 * @method Deployment          createDeployment(array $args = array())
 * @method Deployment          describeDeployment(array $args = array())
 * @method ElasticLoadBalancer createElasticLoadBalancer(array $args = array())
 * @method ModelCollection     describeElasticLoadBalancers(array $args = array())
 *
 * @uses          ClientAbstract
 * @package       AwsWrap
 * @subpackage    Clients
 */
class OpsWorks extends AbstractClient
{

    // Base AWS Wrapper model class namesudo
    const MODEL_CLASS_NAMESPACE = 'AwsWrap\\Models\\OpsWorks\\';

    /**
     * Map of model class names to AWS OpsWorks Client methods
     * @var array
     */
    protected $modelsMap = [
        'Apps'                  => 'App',
        'AppId'                 => 'App',
        'Stacks'                => 'Stack',
        'StackId'               => 'Stack',
        'StackSummary'          => 'Stack',
        'Layers'                => 'Layer',
        'LayerId'               => 'Layer',
        'Instances'             => 'Instance',
        'InstanceId'            => 'Instance',
        'Deployments'           => 'Deployment',
        'DeploymentId'          => 'Deployment',
        'ElasticLoadBalancers'  => 'ElasticLoadBalancer',
        'ElasticLoadBalancerId' => 'ElasticLoadBalancer',
    ];

    /**
     * @inheritdoc
     */
    protected function getClientClass()
    {
        return OpsWorksClient::class;
    }

    /**
     * @inheritdoc
     */
    protected function getModelNamespace()
    {
        return self::MODEL_CLASS_NAMESPACE;
    }

}