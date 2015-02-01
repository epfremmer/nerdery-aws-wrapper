<?php
/**
 * File InvalidModel.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Mocks;

use AwsWrap\Models\AbstractModel;

/**
 * Class InvalidModel
 *
 * Stub class used to simulate extension of abstract models with invalid class properties. This mock class is used
 * during unit testing to validate that the model construct method is throwing exceptions in these cases.
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Tests
 */
class InvalidModel extends AbstractModel
{

    /**
     * AWS Client type
     * @var string
     */
    protected $clientType;

    /**
     * Model Primary ID
     * @var int|string
     */
    protected $primaryKey;

    /**
     * Constructor
     *
     * Used to test error handling for models with invalid
     * clientType or primaryKey values
     *
     * @param array $data
     * @param null $clientType
     * @param null $primaryKey
     */
    public function __construct($data = [], $clientType = null, $primaryKey = null)
    {
        if ($clientType) $this->clientType = $clientType;
        if ($primaryKey) $this->primaryKey = $primaryKey;

        parent::__construct($data);
    }

    /**
     * @inheritdoc
     */
    public function toArgs() {}

    /**
     * @inheritdoc
     */
    public function getClient() {}

    /**
     * @inheritdoc
     */
    public function isValid() {}

    /**
     * @inheritdoc
     */
    public function save() {}

    /**
     * @inheritdoc
     */
    public function refresh() {}

}