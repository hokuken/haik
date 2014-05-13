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
     * Get all meta data as YAML
     *
     * @return string meta data formatted YAML
     */
    public function toYaml();

}
