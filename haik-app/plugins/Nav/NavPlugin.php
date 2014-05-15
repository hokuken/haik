<?php
namespace Hokuken\Haik\Plugin\Nav;

use Michelf\MarkdownInterface;
use Hokuken\Haik\Plugin\BasePlugin;
use Hokuken\HaikMarkdown\Plugin\SpecialAttributeInterface;
use Hokuken\Haik\Plugin\Nav\Parser\NavParser;
use Hokuken\Haik\Plugin\Nav\Parser\PluginRepository;

class NavPlugin extends BasePlugin implements SpecialAttributeInterface {

    const PREFIX_CLASS_ATTRIBUTE = 'haik-plugin-nav';

    /** @var Michelf\MarkdownInterface nav specific syntax markdown parser */
    protected $parser;

    /** @var string alignment of nav list right|left */
    protected $align;

    /** @var boolean nav has form and set .navbar-form */
    protected $hasForm;

    /** @var string parsed HTML content */
    protected $content;

    protected $specialIdAttribute;
    protected $specialClassAttribute;

    public function __construct(MarkdownInterface $parser)
    {
        parent::__construct($parser);

        $this->align = null;
        $this->hasForm = false;
        $this->setUpParser();
        $this->specialIdAttribute = $this->specialClassAttribute = '';
    }

    public function setUpParser()
    {
        $parser = new NavParser();
        $plugin_repo = new PluginRepository($parser);
        // !TODO: register default plugin repository in app
        $parser->registerPluginRepository($plugin_repo);
        $this->parser = $parser;
    }

    public function setSpecialIdAttribute($id)
    {
        $this->specialIdAttribute = $id;
    }

    /**
     * Set special class attribute
     *
     * @param string $class special class attribute
     */
    public function setSpecialClassAttribute($class)
    {
        $this->specialClassAttribute = $class;
    }

    /**
     * @inheritdoc
     */
    public function convert($params = array(), $body = '')
    {
        // Nav Specific Syntax
        //
        // ::: nav {param}           # right|left, form
        // * Item: URL               # text link to URL
        // * Item                    # Dropdown trigger
        //     * Sub-Item: URL       # text Link to URL
        // * ![alt](URL): URL        # image link to URL
        // :::
        //
        // ::: nav
        // /[Action](button URL)    # Button link to URL
        // :::
        //
        // ::: nav
        // Some Text                # Some text in p.navbar-text
        // :::
        //
        // ::: nav {.navbar-left}   # Special Attributes
        // * Item: URL              # Left alignment link list
        // * Item: URL              #
        // :::
        //

        $this->params = $params;
        $this->body = $body;
        $this->parseParams()->parseBody();

        return $this->content;
    }

    protected function parseParams()
    {
        if ($this->isHash($this->params))
        {
            $this->parseHashParams();
        }
        else
        {
            $this->parseArrayParams();
        }
        return $this;
    }

    protected function parseArrayParams()
    {
        foreach ($this->params as $param)
        {
            $param = trim($param);
            switch ($param)
            {
                case 'left':
                case 'right':
                    $this->align = $param;
                    break;
                case 'form':
                    $this->hasForm = true;
            }
        }
    }

    protected function parseHashParams()
    {
        foreach ($this->params as $key => $value)
        {
            $value = trim($value);
            switch ($key)
            {
                case 'align':
                    if (in_array($value, array('left', 'right')))
                    {
                        $this->align = $value;
                    }
                    break;
                case 'form':
                case 'hasForm':
                    if ( !! $value)
                    {
                        $this->hasForm = true;
                    }
                    break;
            }
        }
    }

    protected function parseBody()
    {
        $this->content = ltrim($this->parser->transform($this->body));

        // replace ul attributes
        $this->content = preg_replace_callback('/
                ^<ul\b([^>]*)>  # $1: list element attributes
                (.*)              # $2: list body
                $
            /xs',
            array(&$this, '_replaceListAttributes'), $this->content);

        if ($this->hasForm)
        {
            // replace form attributes
            $this->content = preg_replace_callback('/
                    ^<form\b([^>]*)>  # $1: form element attributes
                    (.*)                # $2: form body
                    $
                /xs',
                array(&$this, '_replaceFormAttributes'), $this->content);
        }

        if ($this->align !== null)
        {
            $this->content = preg_replace('/<p class="navbar-text">/i',
                '<p class="navbar-text navbar-'. $this->align .'">', $this->content);
        }

        return $this;
    }

    protected function _replaceListAttributes($matches)
    {
        $self = $this;
        $ul_attrs = $matches[1];
        $body = $matches[2];

        $ul_attrs = preg_replace_callback('/\bid\b\s*=\s*(\'|")(.*?)\1/i', function($matches) use ($self)
        {
            $value = $matches[2];
            if ($this->specialIdAttribute !== '')
            {
                $value = $self->specialIdAttribute;
            }
            return 'id="'. $value .'"';
        }, $ul_attrs);
        // When without id attr, add id attr
        if ($this->specialIdAttribute !== '' && $matches[1] === $ul_attrs)
        {
            $ul_attrs .= ' id="' . $this->specialIdAttribute . '"';
        }

        $ul_attrs = preg_replace_callback('/\bclass\b\s*=\s*(\'|")(.*?)\1/i', function($matches) use ($self)
        {
            $value = $matches[2];
            if ($self->align !== null)
            {
                $value .= ' navbar-' . $this->align;
            }
            if ($self->specialClassAttribute !== '')
            {
                $value .= ' ' . $self->specialClassAttribute;
            }
            return 'class="'. $value .'"';
        }, $ul_attrs);

        return '<ul' . $ul_attrs . '>' . $body;
    }

    protected function _replaceFormAttributes($matches)
    {
        $self = $this;
        $form_attrs = $matches[1];
        $body = $matches[2];

        $form_attrs = preg_replace_callback('/\bid\b\s*=\s*(\'|")(.*?)\1/i', function($matches) use ($self)
        {
            $value = $matches[2];
            if ($this->specialIdAttribute !== '')
            {
                $value = $self->specialIdAttribute;
            }
            return 'id="'. $value .'"';
        }, $form_attrs);
        // When without id attr, add id attr
        if ($this->specialIdAttribute !== '' && $matches[1] === $form_attrs)
        {
            $form_attrs .= ' id="' . $this->specialIdAttribute . '"';
        }

        $attrs = preg_replace_callback('/\bclass\b\s*=\s*(\'|")(.*?)\1/i', function($matches) use ($self)
        {
            $value = $matches[2];
            if ($self->hasForm)
            {
                $value .= ' navbar-form';
            }
            if ($self->specialClassAttribute !== '')
            {
                $value .= ' ' . $self->specialClassAttribute;
            }
            return 'class="'. $value .'"';
        }, $form_attrs);
        // When without id attr, add id attr
        if ($attrs === $form_attrs)
        {
            $value = '';
            if ($this->hasForm)
            {
                $value .= 'navbar-form';
            }
            if ($this->specialClassAttribute !== '')
            {
                $value .= ' ' . $this->specialClassAttribute;
            }
            $attrs .= ' class="' . $value . '"';
        }

        return '<form' . $attrs . '>' . $body;
    }

}
