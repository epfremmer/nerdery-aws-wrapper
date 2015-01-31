<?php
/**
 * File Role.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\Iam;

use Aws\Iam\IamClient;
use AwsWrap\Models\ModelCollection;
use AwsWrap\Models\AbstractModel;

/**
 * Class Role
 *
 * @property string Arn
 * @property string RoleName
 *
 * @method IamClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class Role extends AbstractModel
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
    protected $primaryKey = 'RoleId';

    /**
     * Role Instance Profiles
     * @var ModelCollection
     */
    protected $instanceProfiles;

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        $data = $this->getData();

        return array_filter($data);
    }

    /**
     * Return role name
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->RoleName;
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
     * Return instance profiles for this role
     *
     * @return ModelCollection
     */
    public function getInstanceProfiles()
    {
        if (!$this->instanceProfiles) {
            $client           = $this->getClient();
            $instanceProfiles = $client->listInstanceProfilesForRole([
                'RoleName' => $this->getName()
            ]);

            $this->instanceProfiles = $instanceProfiles;
        }

        return $this->instanceProfiles;
    }

    /**
     * Add a new instance profile to the Role
     *
     * @param InstanceProfile $instanceProfile
     * @return \Guzzle\Service\Resource\Model
     */
    public function addInstanceProfile(InstanceProfile $instanceProfile)
    {
        $client = $this->getClient();
        $result = $client->addRoleToInstanceProfile([
            'RoleName'            => $this->getName(),
            'InstanceProfileName' => $instanceProfile->getName(),
        ]);

        return $result;
    }

    /**
     * Save new role on AWS
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $client->CreateRole($this->data);

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh role data
     *
     * @return self
     */
    public function refresh()
    {
        $client = $this->getClient();
        $role   = $client->GetRole($this->toArgs());

        if ($role instanceof self) {
            $this->setData($role->getData());
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
        $requiredKeys = ['RoleName', 'AssumeRolePolicyDocument'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }

}