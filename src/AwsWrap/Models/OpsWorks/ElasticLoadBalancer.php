<?php
/**
 * File ElasticLoadBalancer.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\OpsWorks;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\AbstractModel;

/**
 * Class ElasticLoadBalancer
 *
 * @method OpsWorksClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class ElasticLoadBalancer extends AbstractModel
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
    protected $primaryKey = 'ElasticLoadBalancerId';

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        return $this->getID();
    }

    /**
     * Create ELB on AWS
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        //@todo create ELB

        return false;
    }

    public function refresh()
    {
        //@todo implement method
    }

    /**
     * Test if required stack data is valid
     *
     * @return bool
     */
    public function isValid()
    {
        // @todo add more intelligent stack data validation
        $requiredKeys = [];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }

}