<?php
namespace Hokuken\Haik\Plugin\Repository;

use App;
use Michelf\MarkdownInterface;
use Hokuken\HaikMarkdown\Plugin\Repositories\AbstractPluginRepository;

class ParserPluginRepository extends AbstractPluginRepository {

    public function __construct(MarkdownInterface $parser)
    {
        $this->parser = $parser;
        parent::__construct($parser);
        $this->repositoryPath = App::make('path.plugin');
    }

    /**
     * Get HaikMarkdown Plugin Class Name
     *
     * @param string $id Plugin ID
     * @return string class FQDN
     */
    protected function getClassName($id)
    {
        $class_name = studly_case($id);
        return $class_name = 'Hokuken\Haik\Plugin\\' . $class_name . '\\' . $class_name . 'Plugin';
    }

}
