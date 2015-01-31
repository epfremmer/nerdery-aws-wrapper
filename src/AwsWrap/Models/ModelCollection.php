<?php
/**
 * File ModelCollection.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models;

use Exception;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class ModelCollection
 *
 * Used to manage the instantiation of a collection of model objects
 *
 * Provided an array of elements & a fully qualified class name for the individual element model type each
 * item in the elements array will be instantiated with the target model class.
 *
 * @uses          ArrayCollection
 * @package       AwsWrap
 * @subpackage    Collections
 */
class ModelCollection extends ArrayCollection
{

    /**
     * Constructor
     *
     * @param array $elements
     * @param string $modelClass
     * @throws Exception
     */
    public function __construct(array $elements = [], $modelClass)
    {
        if (!class_exists($modelClass)) {
            throw new Exception(
                sprintf('Invalid model class "%s" specified', $modelClass)
            );
        }

        foreach ($elements as $key => $data) {
            $this->set($key, new $modelClass($data));
        }
    }

}