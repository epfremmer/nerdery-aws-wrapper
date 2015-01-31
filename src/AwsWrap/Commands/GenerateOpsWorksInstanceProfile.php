<?php
/**
 * File GenerateOpsWorksInstanceProfile.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Commands;

use AwsWrap\Models\Iam\InstanceProfile;
use AwsWrap\Models\Iam\Role;

/**
 * Class GenerateOpsWorksInstanceProfile
 *
 * @uses
 * @package       AwsWrap
 * @subpackage    Clients
 */
class GenerateOpsWorksInstanceProfile
{

    // instance profile constants
    const IAM_EC2_PROFILE_ROLE_NAME = 'aws-opsworks-ec2-role';

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var InstanceProfile
     */
    protected $instanceProfile;

    /**
     * Constructor
     *
     * @param Role $role
     * @param InstanceProfile $instanceProfile
     */
    public function __construct(Role $role = null, InstanceProfile $instanceProfile = null)
    {
        $this->role            = $role;
        $this->instanceProfile = $instanceProfile;

        $this->setDefaults();
    }

    /**
     * Execute the command
     *
     * @return self
     */
    public function run()
    {
        if (!$this->role->refresh()->getArn()) {
            $this->role->save();
        }

        if (!$this->instanceProfile->refresh()->getArn()) {
            $this->instanceProfile->save();
        }

        if (!$this->role->getInstanceProfiles()->count()) {
            $this->role->addInstanceProfile($this->instanceProfile);
        }

        return $this;
    }

    /**
     * Return role
     *
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Return instance profile
     *
     * @return InstanceProfile
     */
    public function getInstanceProfile()
    {
        return $this->instanceProfile;
    }

    /**
     * Setup default OpsWorks Instance Profile & Role if no Role or
     * Instance Profile have been specified
     *
     * @reutnr self
     */
    protected function setDefaults()
    {
        if (!$this->role) {
            $this->role = new Role([
                'RoleName' => IAM_EC2_PROFILE_ROLE_NAME,
                'AssumeRolePolicyDocument' => '{
                    "Statement": [
                        {
                            "Sid": "",
                            "Effect": "Allow",
                            "Principal": {
                              "Service": "ec2.amazonaws.com"
                            },
                            "Action": "sts:AssumeRole"
                        }
                      ]
                }'
            ]);
        }

        if (!$this->instanceProfile) {
            $this->instanceProfile = new InstanceProfile([
                'InstanceProfileName' => IAM_EC2_PROFILE_ROLE_NAME
            ]);
        }

        return $this;
    }

}