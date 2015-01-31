<?php
/**
 * File Deployment.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\OpsWorks;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\AbstractModel;

/**
 * Class Deployment
 *
 * @method OpsWorksClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class Deployment extends AbstractModel
{

    // deployment status
    const SUCCESS = 'success';
    const FAILURE = 'failure';
    const RUNNING = 'running';

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType = 'opsworks';

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey = 'DeploymentId';

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        return $this->getID();
    }

    /**
     * Create new AWS deployment
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $result = $client->createDeployment($this->data);

            if ($result instanceof self) {
                $this->setData($result->getData());
            }

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh deployment data
     *
     * @return self
     */
    public function refresh()
    {
        $client      = $this->getClient();
        $deployments = $client->describeDeployments([
            'DeploymentId' => $this->getID(),
        ]);

        if ($deployments->count()) {
            $this->setData($deployments->get(0)->getData());
        }

        return $this;
    }

    /**
     * Test deployment completed
     *
     * @return bool
     */
    public function isComplete()
    {
        return $this->refresh()->getStatus() !== self::RUNNING;
    }

    /**
     * Return deployment status
     *
     * @return mixed|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Test deployment success
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->getStatus() === self::SUCCESS;
    }

    /**
     * Test deployment failure
     *
     * @return bool
     */
    public function isFailure()
    {
        return $this->getStatus() === self::FAILURE;
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