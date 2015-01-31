<?php
/**
 * File GenerateOpsWorksServiceRole.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Commands;

use AwsWrap\Models\Iam\Role;
use AwsWrap\Models\Iam\RolePolicy;

/**
 * Class GenerateOpsWorksServiceRole
 *
 * @uses
 * @package       AwsWrap
 * @subpackage    Clients
 */
class GenerateOpsWorksServiceRole
{

    // role/policy constants
    const DEFAULT_IAM_SERVICE_ROLE_NAME        = 'aws-opsworks-service-role';
    const DEFAULT_IAM_SERVICE_ROLE_POLICY_NAME = 'aws-opsworks-service-policy';

    /**
     * @var Role
     */
    protected $role;

    /**
     * @var RolePolicy
     */
    protected $rolePolicy;

    /**
     * Constructor
     *
     * @param Role $role
     * @param RolePolicy $rolePolicy
     */
    public function __construct(Role $role = null, RolePolicy $rolePolicy = null)
    {
        $this->role       = $role;
        $this->rolePolicy = $rolePolicy;

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

        if (!$this->rolePolicy->refresh()->getArn()) {
            $this->rolePolicy->save();
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
     * Return role policy
     *
     * @return Role
     */
    public function getrolePolicy()
    {
        return $this->rolePolicy;
    }

    /**
     * Setup default OpsWorks Role & Role Policy if no Role or Policy specified
     *
     * @return self
     */
    protected function setDefaults()
    {
        if (!$this->role) {
            $this->role = new Role([
                'RoleName' => self::DEFAULT_IAM_SERVICE_ROLE_NAME,
                'AssumeRolePolicyDocument' => '{
                    "Statement": [
                        {
                            "Sid": "",
                            "Effect": "Allow",
                            "Principal": {
                                "Service": "opsworks.amazonaws.com"
                            },
                            "Action": "sts:AssumeRole"
                        }
                      ]
                }'
            ]);
        }

        if (!$this->rolePolicy) {
            $this->rolePolicy = new RolePolicy([
                'RoleName'   => self::DEFAULT_IAM_SERVICE_ROLE_NAME,
                'PolicyName' => self::DEFAULT_IAM_SERVICE_ROLE_POLICY_NAME,
                'PolicyDocument' => '{
                    "Statement": [{
                        "Action": ["ec2:*", "iam:PassRole","cloudwatch:GetMetricStatistics","elasticloadbalancing:*","rds:*"],
                        "Effect": "Allow",
                        "Resource": ["*"]
                    }]
                }'
            ]);
        }

        return $this;
    }

}