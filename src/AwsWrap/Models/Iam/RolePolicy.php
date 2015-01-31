<?php
/**
 * File RolePolicy.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\Iam;

use Aws\Iam\IamClient;
use AwsWrap\Models\ModelCollection;
use AwsWrap\Models\AbstractModel;

/**
 * Class RolePolicy
 *
 * @property string Arn
 * @property string RoleName
 * @property string PolicyName
 * @property string PolicyDocument (JSON)
 *
 * @method IamClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class RolePolicy extends AbstractModel
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
    protected $primaryKey = 'PolicyId';

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
    public function getRoleName()
    {
        return $this->RoleName;
    }

    /**
     * Return role name
     *
     * @return mixed
     */
    public function getPolicyName()
    {
        return $this->PolicyName;
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
     * Save new role on AWS
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $client->PutRolePolicy($this->toArgs());

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh policy data
     *
     * @return self
     */
    public function refresh()
    {
        $client = $this->getClient();
        $policy = $client->GetRolePolicy([
            'RoleName'   => $this->getRoleName(),
            'PolicyName' => $this->getPolicyName(),
        ]);

        if ($policy instanceof self) {
            $this->setData($policy->getData());
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
        $requiredKeys = ['RoleName', 'PolicyName', 'PolicyDocument'];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }

}