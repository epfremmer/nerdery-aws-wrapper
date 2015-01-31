<?php
/**
 * File Stack.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\OpsWorks;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Clients\OpsWorks;
use AwsWrap\Models\ModelCollection;
use AwsWrap\Models\AbstractModel;

/**
 * Class Stack
 *
 * @property string Arn
 * @property array  Attributes
 * @property array  ChefConfiguration
 * @property array  ConfigurationManager
 * @property string CreatedAt
 * @property array  CustomCookbooksSource
 * @property string DefaultAvailabilityZone
 * @property string DefaultInstanceProfileArn
 * @property string DefaultOs
 * @property string DefaultRootDeviceType
 * @property string HostnameTheme
 * @property string Name
 * @property string Region
 * @property string ServiceRoleArn
 * @property string StackId
 * @property bool   UseCustomCookbooks
 * @property bool   UseOpsworksSecurityGroups
 * @property string VpcId
 *
 * @method OpsWorks|OpsWorksClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class Stack extends AbstractModel
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
    protected $primaryKey = 'StackId';

    /**
     * Stack Apps
     * @var ModelCollection
     */
    protected $apps;

    /**
     * Stack Layers
     * @var ModelCollection
     */
    protected $layers;

    /**
     * Stack Instances
     * @var ModelCollection
     */
    protected $instances;

    /**
     * Stack Deployments
     * @var ModelCollection
     */
    protected $deployments;

    /**
     * Stack elastic load balancers (ELBs)
     * @var ModelCollection
     */
    protected $elasticLoadBalancers;

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        $data = $this->getData();

        return array_filter($data);
    }

    /**
     * Set stack data
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data = [])
    {
        if (array_key_exists('Apps', $data)) {
            $this->setApps($data['Apps']);
            unset($data['Apps']);
        }

        if (array_key_exists('Layers', $data)) {
            $this->setLayers($data['Layers']);
            unset($data['Layers']);
        }

        if (array_key_exists('Instances', $data)) {
            $this->setInstances($data['Instances']);
            unset($data['Instances']);
        }

        if (array_key_exists('Deployments', $data)) {
            $this->setDeployments($data['Deployments']);
            unset($data['Deployments']);
        }

        if (array_key_exists('ElasticLoadBalancers', $data)) {
            $this->setElasticLoadBalancers($data['ElasticLoadBalancers']);
            unset($data['ElasticLoadBalancers']);
        }

        return parent::setData($data);
    }

    /**
     * Return stack layers
     *
     * @return ModelCollection
     */
    public function getLayers()
    {
        if (!$this->layers) {
            $client = $this->getClient();
            $layers = $client->describeLayers([
                'StackId' => $this->getID()
            ]);

            $this->layers = $layers;
        }

        return $this->layers;
    }

    /**
     * Set layers
     *
     * @param array $layers
     * @return self
     */
    public function setLayers(array $layers)
    {
        $this->layers = new ModelCollection($layers, Layer::class);

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
                'StackId' => $this->getID()
            ]);

            $this->instances = $instances;
        }

        return $this->instances;
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
     * Return stack apps
     *
     * @return ModelCollection
     */
    public function getApps()
    {
        if (!$this->apps) {
            $client = $this->getClient();
            $apps   = $client->describeApps([
                'StackId' => $this->getID()
            ]);

            $this->apps = $apps;
        }

        return $this->apps;
    }

    /**
     * Set apps
     *
     * @param array $apps
     * @return self
     */
    public function setApps(array $apps)
    {
        $this->apps = new ModelCollection($apps, App::class);

        return $this;
    }

    /**
     * Return stack deployments
     *
     * @return ModelCollection
     */
    public function getDeployments()
    {
        if (!$this->deployments) {
            $client      = $this->getClient();
            $deployments = $client->describeDeployments([
                'StackId' => $this->getID()
            ]);

            $this->deployments = $deployments;
        }

        return $this->deployments;
    }

    /**
     * Set deployments
     *
     * @param array $deployments
     * @return self
     */
    public function setDeployments(array $deployments)
    {
        $this->deployments = new ModelCollection($deployments, Deployment::class);

        return $this;
    }

    /**
     * Return elastic load balancers (ELBs)
     *
     * @return ModelCollection
     */
    public function getElasticLoadBalancers()
    {
        if (!$this->elasticLoadBalancers) {
            $client    = $this->getClient();
            $balancers = $client->describeElasticLoadBalancers([
                'StackId' => $this->getID()
            ]);

            $this->elasticLoadBalancers = $balancers;
        }

        return $this->elasticLoadBalancers;
    }

    /**
     * Set elastic Load balancers (ELBs)
     *
     * @param array $elasticLoadBalancers
     * @return self
     */
    public function setElasticLoadBalancers(array $elasticLoadBalancers)
    {
        $this->elasticLoadBalancers = new ModelCollection($elasticLoadBalancers, ElasticLoadBalancer::class);

        return $this;
    }

    /**
     * Save stack data on AWS
     *
     * Update: if stack already exists
     * Create: if no stack ID present
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $result = $this->getID()
                ? $client->updateStack($this->toArgs())
                : $client->createStack($this->toArgs())
            ;

            if ($result instanceof self) {
                $this->setData($result->getData());
            }

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh stack data
     *
     * @return self
     */
    public function refresh()
    {
        $client = $this->getClient();
        $stacks = $client->describeStacks([
            'StackIds' => [$this->getID()]
        ]);

        if ($stacks->count()) {
            $this->setData($stacks->get(0)->getData());
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
        $requiredKeys = ['Name', 'Region', 'ServiceRoleArn', 'DefaultInstanceProfileArn'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }

}