<?php
namespace Hokuken\Haik\Page;

interface PageMetaInterface {

    /**
     * Get page name
     *
     * @return string page name
     */
    public function getPage();

    /**
     * Set YAML to meta data
     *
     * @param string $yaml yaml
     * @return $this
     */
    public function setYaml($yaml);

    /**
     * Get all meta data as YAML
     *
     * @return string meta data formatted YAML
     */
    public function toYaml();

}
