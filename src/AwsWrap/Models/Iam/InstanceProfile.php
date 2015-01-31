<?php
/**
 * File InstanceProfile.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\Iam;

use Aws\Iam\IamClient;
use AwsWrap\Models\AbstractModel;

/**
 * Class InstanceProfile
 *
 * @property string Arn
 * @property string Name
 *
 * @method IamClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class InstanceProfile extends AbstractModel
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType = 'iam';

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey = 'InstanceProfileId';

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        $data = $this->getData();

        return array_filter($data);
    }

    /**
     * Return instance profile name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Return IAM Arn
     *
     * @return string
     */
    public function getArn()
    {
        return $this->Arn;
    }

    /**
     * Save new instance profile to AWS
     *
     * @return self
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $client->createInstanceProfile($this->toArgs());

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh instance profile data
     *
     * @return self
     */
    public function refresh()
    {
        $client          = $this->getClient();
        $instanceProfile = $client->GetInstanceProfile($this->toArgs());

        if ($instanceProfile instanceof self) {
            $this->setData($instanceProfile->getData());
        }

        return $this;
    }

    /**
     * Test if required role data is valid
     *
     * @return bool
     */
    public function isValid()
    {
        // @todo add more intelligent stack data validation
        $requiredKeys = ['InstanceProfileName'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }
}