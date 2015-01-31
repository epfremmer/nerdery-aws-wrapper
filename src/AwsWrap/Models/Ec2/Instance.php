<?php
/**
 * File Instance.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\Ec2;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\AbstractModel;

/**
 * Class Instance
 *
 * @property string   StackId
 * @property string[] LayerIds
 * @property string   InstanceType
 * @property string   RootDeviceType
 * @property string   InstanceId
 * @property string   AmiId
 * @property string   Architecture
 * @property string   AvailabilityZone
 * @property array    BlockDeviceMappings
 * @property string   CreatedAt
 * @property bool     EbsOptimized
 * @property string   Hostname
 * @property string   InfrastructureClass
 * @property bool     InstallUpdatesOnBoot
 * @property string   Os
 * @property array    ReportedOs
 * @property string[] SecurityGroupIds
 * @property string   Status
 * @property string   SubnetId
 * @property string   VirtualizationType
 *
 * @method OpsWorksClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class Instance extends AbstractModel
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType = 'ec2';

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey = 'InstanceId';

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        return $this->getID();
    }

    /**
     * Save instance data on AWS
     *
     * Update: if instance already exists
     * Create: if no instance ID present
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $result = $this->getID()
                ? $client->updateInstance($this->data)
                : $client->createInstance($this->data)
            ;

            if ($result instanceof self) {
                $this->setData($result->getData());
            }

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh layer data
     *
     * @return self
     */
    public function refresh()
    {
        $client    = $this->getClient();
        $instances = $client->describeInstances([
            'InstanceIds' => [$this->getID()]
        ]);

        if ($instances->count()) {
            $this->setData($instances->get(0)->getData());
        }

        return $this;
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