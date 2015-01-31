<?php
/**
 * File App.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models\OpsWorks;

use Aws\OpsWorks\OpsWorksClient;
use AwsWrap\Models\AbstractModel;

/**
 * Class App
 *
 * @method OpsWorksClient getClient()
 *
 * @uses          AbstractModel
 * @package       AwsWrap
 * @subpackage    Models
 */
class App extends AbstractModel
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
    protected $primaryKey = 'AppId';

    /**
     * @inheritdoc
     */
    public function toArgs()
    {
        return $this->getID();
    }

    /**
     * Save app data on AWS
     *
     * Update: if app already exists
     * Create: if no app ID present
     *
     * @return bool
     */
    public function save()
    {
        $client = $this->getClient();

        if ($this->isValid()) {
            $result = $this->getID()
                ? $client->updateApp($this->data)
                : $client->createApp($this->data)
            ;

            if ($result instanceof self) {
                $this->setData($result->getData());
            }

            return $this->refresh();
        }

        return false;
    }

    /**
     * Refresh layer data
     *
     * @return self
     */
    public function refresh()
    {
        $client = $this->getClient();
        $apps   = $client->describeApps([
            'AppIds' => [$this->getID()]
        ]);

        if ($apps->count()) {
            $this->setData($apps->get(0)->getData());
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
        $requiredKeys = [];

        foreach ($requiredKeys as $key) {
            if (!array_key_exists($key, $this->data)) {
                return false;
            }
        }

        return true;
    }

}