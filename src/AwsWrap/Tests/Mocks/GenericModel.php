<?php
/**
 * File GenericModel.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Tests\Mocks;

use AwsWrap\Models\AbstractModel;

/**
 * Class GenericModel
 *
 * Mock class used to simulate a service model class referred to by a specific AWS client wrapper object. Used
 * by the generic test client to validate client model map working properly during response model hydration.
 *
 * @see GenericClient
 * @see AbstractCLientTest
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Tests
 */
class GenericModel extends AbstractModel
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