<?php
/**
 * File ModelInterface.php
 *
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */

namespace AwsWrap\Models;

/**
 * Class ModelInterface
 *
 * @package       AwsWrap
 * @subpackage    Models
 */
interface ModelInterface
{

    /**
     * Return model ID
     *
     * @return int|string
     */
    public function getID();

    /**
     * Return AWS model arguments
     *
     * Used for saving only applicable data to AWS
     *
     * @return mixed
     */
    public function toArgs();

    /**
     * Return model JSON data
     *
     * @return string
     */
    public function toJson();

    /**
     * Set data from JSON
     *
     * @param string $json
     * @return mixed
     */
    public function fromJson($json = "");

    /**
     * Set model data
     *
     * @param array $data
     * @return self
     */
    public function setData(array $data = []);

    /**
     * Return model data
     *
     * @return array
     */
    public function getData();

    /**
     * Validate model data
     *
     * @return bool
     */
    public function isValid();

}