<?php
/**
 * File Layer.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\OpsWorks;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\ModelCollection;
use AwsWrap\Models\AbstractModel;

/**
 * Class Layer
 *
 * @property array  Attributes
 * @property bool   AutoAssignElasticIps
 * @property bool   AutoAssignPublicIps
 * @property string CreatedAt
 * @property array  CustomRecipes @keys: {'Configure', 'Deploy', 'Setup', 'Shutdown', 'Undeploy'}
 * @property array  CustomSecurityGroupIds
 * @property array  DefaultRecipes
 * @property array  DefaultSecurityGroupNames
 * @property bool   EnableAutoHealing
 * @property string LayerId
 * @property array  LifecycleEventConfiguration
 * @property string Name
 * @property array  Packages
 * @property string Shortname
 * @property string StackId
 * @property string Type
 * @property bool   UseEbsOptimizedInstances
 * @property array  VolumeConfigurations
 *
 * @method OpsWorksClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class Layer extends AbstractModel
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
    protected $primaryKey = 'LayerId';

    /**
     * Stack Instances
     * @var ModelCollection
     */
    protected $instances;

    /**
     * Elastic load balancer (ELB)
     * @var ElasticLoadBalancer
     */
    protected $loadBalancer;

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        $data = $this->getData();

        return array_filter($data);
    }

    /**
     * @inheritdoc
     */
    public function setData(array $data = [])
    {
        if (array_key_exists('Instances', $data)) {
            $this->setInstances($data['Instances']);
            unset($data['Instances']);
        }

        return parent::setData($data);
    }

    /**
     * Set instances
     *
     * @param array $instances
     * @return self
     */
    public function setInstances(array $instances)
    {
        $this->instances = new ModelCollection($instances, Instance::class);

        return $this;
    }

    /**
     * Return stack instances
     *
     * @return ModelCollection
     */
    public function getInstances()
    {
        if (!$this->instances) {
            $client    = $this->getClient();
            $instances = $client->describeInstances([
                'LayerId' => $this->getID()
            ]);

            $this->instances = $instances;
        }

        return $this->instances;
    }

    /**
     * Return elastic load balancer (ELB)
     *
     * @return ElasticLoadBalancer
     */
    public function getLoadBalancer()
    {
        if (!$this->loadBalancer) {
            $client   = $this->getClient();
            $balancer = $client->describeElasticLoadBalancers([
                'LayerId' => $this->getID()
            ]);

            $this->loadBalancer = $balancer;
        }

        return $this->loadBalancer;
    }

    /**
     * Save layer data on AWS
     *
     * Update: if layer already exists
     * Create: if no layer ID present
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $result = $this->getID()
                ? $client->updateLayer($this->toArgs())
                : $client->createLayer($this->toArgs())
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
        $client = $this->getClient();
        $layers = $client->describeLayers([
            'LayerIds' => [$this->getID()]
        ]);

        if ($layers->count()) {
            $this->setData($layers->get(0)->getData());
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