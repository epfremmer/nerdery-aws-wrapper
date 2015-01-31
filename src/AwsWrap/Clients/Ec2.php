<?php
/**
 * File Ec2.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Clients;

use Aws\Ec2\Ec2Client;
use AwsWrap\Models\ModelCollection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Ec2
 *
 * @uses          ClientAbstract
 * @package       AwsWrap
 * @subpackage    Clients
 */
class Ec2 extends ClientAbstract
{

    // Base AWS Wrapper model class namesudo
    const MODEL_CLASS_NAMESPACE = 'AwsWrap\\Models\\Ec2\\';

    /**
     * Map of model class names to AWS OpsWorks Client methods
     * @var array
     */
    protected $modelsMap = [
        // EC2 Models
    ];

    /**
     * @inheritdoc
     */
    protected function getClientClass()
    {
        return Ec2Client::class;
    }

    /**
     * @inheritdoc
     */
    protected function getModelNamespace()
    {
        return self::MODEL_CLASS_NAMESPACE;
    }

}