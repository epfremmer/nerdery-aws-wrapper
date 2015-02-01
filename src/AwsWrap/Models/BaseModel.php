<?php
/**
 * File BaseModel.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models;

/**
 * Class BaseModel
 *
 * Generic model class for AWS response data not currently supported by the AWS Wrapper library. Supports basic
 * data storage and serialization functionality only.
 *
 * This class should not be used in place of a supported model class or extended.
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
final class BaseModel extends AbstractModel
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType = false;

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey = false;

    /**
     * Return empty ID
     *
     * @return string
     */
    public function getID()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    public function getClient()
    {
        return false;
    }

    /**
     * @inheritdoc
     */
    public function isValid()
    {
        return is_array($this->data);
    }

    public function save() {}
    public function refresh() {}

}