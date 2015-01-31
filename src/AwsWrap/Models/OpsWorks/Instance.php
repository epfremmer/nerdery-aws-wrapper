<?php
/**
 * File Instance.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\OpsWorks;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\Ec2\Instance as BaseModel;

/**
 * Class Instance
 *
 * @method OpsWorksClient getClient()
 *
 * @uses          BaseModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class Instance extends BaseModel
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType = 'opsworks';

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey = 'InstanceId';

}