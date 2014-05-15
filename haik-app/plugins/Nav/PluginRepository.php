<?php
namespace Hokuken\Haik\Plugin\Nav\Parser;

use Michelf\MarkdownInterface;
use Hokuken\HaikMarkdown\Plugin\Repositories\PluginRepositoryInterface;

class PluginRepository implements PluginRepositoryInterface {

    /**
     * @var Michelf\MarkdownInterface
     */
    protected $parser;

    public function __construct(MarkdownInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @inheritdoc
     */
    public function exists($id)
    {
        return $id === 'button';
    }

    /**
     * @inheritdoc
     */
    public function load($id)
    {
        return new ButtonPlugin($this->parser);
    }

    /**
     * @inheritdoc
     */
    public function getAll()
    {
        return array('button');
    }

}
