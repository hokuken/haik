<?php
namespace Hokuken\Haik\Plugin\Nav;

use Hokuken\Haik\Markdown\HaikMarkdown;

class NavParser extends HaikMarkdown {

    public function __construct()
    {
        parent::__construct();

        $this->hardWrap = false;

        $this->document_gamut = array(
            'doConvertPlugins'     => 27,
            "runBasicBlockGamut"   => 30,
        );

        $this->block_gamut = array(
            'doConvertPlugins'  => 10,
            "doLists"           => 40,
        );

        $this->span_gamut = array(
            "parseSpan"           => -30,
            'doInlinePlugins'     =>   2,
            "doImages"            =>  10,
            "doAnchors"           =>  20,
            "encodeAmpsAndAngles" =>  40,
            "doItalicsAndBold"    =>  50,
            "doHardBreaks"        =>  60,
        );
    }

    /**
     * @inheritdoc
     */
	protected function doLists($text) {
	#
	# Form HTML ordered (numbered) and unordered (bulleted) lists.
	#
		$less_than_tab = $this->tab_width - 1;

		# Re-usable patterns to match list item bullets and number markers:
		$marker_ul_re  = '[*+-]';

		# Re-usable pattern to match any entirel ul or ol list:
		$whole_list_re = '
			(								# $1 = whole list
			  (								# $2
				([ ]{0,'.$less_than_tab.'})	# $3 = number of spaces
				('.$marker_ul_re.')			# $4 = first list item marker
				[ ]+
			  )
			  (?s:.+?)
			  (								# $5
				  \z
				|
				  \n{2,}
				  (?=\S)
				  (?!						# Negative lookahead for another list item marker
					[ ]*
					'.$marker_ul_re.'[ ]+
				  )
			  )
			)
		'; // mx
		
		# We use a different prefix before nested lists than top-level lists.
		# See extended comment in _ProcessListItems().
	
		if ($this->list_level) {
			$text = preg_replace_callback('{
					^
					'.$whole_list_re.'
				}mx',
				array(&$this, '_doLists_callback'), $text);
		}
		else {
			$text = preg_replace_callback('{
					(?:(?<=\n)\n|\A\n?) # Must eat the newline
					'.$whole_list_re.'
				}mx',
				array(&$this, '_doLists_callback'), $text);
		}

		return $text;
	}

    protected $first_level_list_default_attr = '.nav .navbar-nav';

    protected $first_level_list_attr = '';

    protected $second_level_list_attr = '.dropdown-menu';

	protected function _doLists_callback($matches) {
		# Re-usable patterns to match list item bullets and number markers:
		$marker_ul_re  = '[*+-]';
		$list = $matches[1];
		$list .= "\n";
		$result = $this->processListItems($list, $marker_ul_re);

        $attr = '';
        if ($this->list_level === 0)
        {
            $attr = $this->first_level_list_default_attr . ' ' . $this->first_level_list_attr;
            $attr = $this->doExtraAttributes('ul', $attr);
        }
        else if ($this->list_level === 1)
        {
            $attr = $this->second_level_list_attr;
            $attr = $this->doExtraAttributes('ul', $attr);
        }
		$result = $this->hashBlock("<ul{$attr}>\n" . $result . "</ul>");
		return "\n". $result ."\n\n";
	}

	protected function processListItems($list_str, $marker_ul_re) {
	#
	#	Process the contents of a single ordered or unordered list, splitting it
	#	into individual list items.
	#
		# The $this->list_level global keeps track of when we're inside a list.
		# Each time we enter a list, we increment it; when we leave a list,
		# we decrement. If it's zero, we're not in a list anymore.
		#
		# We do this because when we're not inside a list, we want to treat
		# something like this:
		#
		#		I recommend upgrading to version
		#		8. Oops, now this line is treated
		#		as a sub-list.
		#
		# As a single paragraph, despite the fact that the second line starts
		# with a digit-period-space sequence.
		#
		# Whereas when we're inside a list (or sub-list), that line will be
		# treated as the start of a sub-list. What a kludge, huh? This is
		# an aspect of Markdown's syntax that's hard to parse perfectly
		# without resorting to mind-reading. Perhaps the solution is to
		# change the syntax rules such that sub-lists must start with a
		# starting cardinal number; e.g. "1." or "a.".
		
		$this->list_level++;

		# trim trailing blank lines:
		$list_str = preg_replace("/\n{2,}\\z/", "\n", $list_str);

		$list_str = preg_replace_callback('{
			(\n)?							# leading line = $1
			(^[ ]*)							# leading whitespace = $2
			('.$marker_ul_re.'				# list marker and space = $3
				(?:[ ]+|(?=\n))	# space only required if item is not empty
			)
			((?s:.*?))						# list item text   = $4
			(?:(\n+(?=\n))|\n)				# tailing blank line = $5
			(?= \n* (\z | \2 ('.$marker_ul_re.') (?:[ ]+|(?=\n))))
			}xm',
			array(&$this, '_processListItems_callback'), $list_str);

		$this->list_level--;
		return $list_str;
	}
	protected function _processListItems_callback($matches) {
		$item = $matches[4];
		$leading_line =& $matches[1];
		$leading_space =& $matches[2];
		$marker_space = $matches[3];
		$tailing_blank_line =& $matches[5];
		$trigger_item = false;

		if ($leading_line || $tailing_blank_line || 
			preg_match('/\n{2,}/', $item))
		{
			# Replace marker with the appropriate whitespace indentation
			$item = $leading_space . str_repeat(' ', strlen($marker_space)) . $item;
			$item = $this->runBlockGamut($this->outdent($item)."\n");
		}
		else {
			# Recursion for sub-lists:
			$item = $this->doLists($this->outdent($item));
		    if (strpos($item, "\n") === false)
		    {
    		    $item = $this->_parseListItemAsLink($item);
		    }
		    else if (preg_match('/
		        \n
    			(?:[ ]* '.$this->id_class_attr_catch_re.' )? # $1: special attributes
    			(?:\n|\z)
		    /x', $item, $mts) && isset($mts[1]))
		    {
    		    list($item) = explode("\n", $item, 2);
    		    $item = $this->_parseListItemAsLink($item);
    		    $attr = $mts[1];
    		    $this->first_level_list_attr = $attr;
		    }
		    else
		    {
    			$item = preg_replace('/\n+$/', '', $item);
    			$item = $this->runSpanGamut($item);
    			$trigger_item = true;
                $item = preg_replace_callback('/
                    (.+?)   # $1: dropdown toggle trigger
                    (       # $2
                        \n\n
                        (?:.+)
                        (?:\n|\z)
                    )
                /x',
                array(&$this, '_dropdownTrigger_callback'), $item);
		    }
		}

        $attr = '';
        if ($trigger_item)
        {
            $attr = ' class="dropdown"';
        }
		return "<li{$attr}>" . $item . "</li>\n";
	}

    protected function _parseListItemAsLink($item)
    {
        // text: URL
        // -> <a href="URL">text</a>

        $self = $this;
        return preg_replace_callback('/

                ^
                (.+?)
                :
                [ ]*
                (
                    (?:(?:ftp|https?):\/\/.+)
                    |
                    [^:]+
                )
                $
            
            /x', function($matches) use ($self)
            {
                $text = $self->runSpanGamut($matches[1]);
                $url = trim($matches[2]);
                return '<a href="'. e($url) .'">'.$text.'</a>';
            },
            $item);
    }

    protected function _dropdownTrigger_callback($matches)
    {
        $trigger = $matches[1];
        $sub_list = $matches[2];

        $trigger = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'. $this->runSpanGamut($trigger) .' <b class="caret"></b></a>';
        return $trigger . $sub_list;
    }

	protected function formParagraphs($text) {
	#
	#	Params:
	#		$text - string to process with html <p> tags
	#
		# Strip leading and trailing lines:
		$text = preg_replace('/\A\n+|\n+\z/', '', $text);

		$grafs = preg_split('/\n{1,}/', $text, -1, PREG_SPLIT_NO_EMPTY);

		#
		# Wrap <p> tags and unhashify HTML blocks
		#
		foreach ($grafs as $key => $value) {
			if (!preg_match('/^B\x1A[0-9]+B$/', $value)) {
				# Is a paragraph.
				$value = $this->runSpanGamut($value);
				
				$value = preg_replace_callback('/
                    (                 # $1 = whole match
    				    ^(?:[ ]*)
    				    (.*?)         # $2 = paragraph content
                        (?:[ ]? '.$this->id_class_attr_catch_re.' )?	 # $3 = id or class attributes
                        (?:\n|\z)
    				 )   
    				/x', array(&$this, '_formParagraph_callback'), $value);
				$grafs[$key] = $this->unhash($value);
			}
			else {
				# Is a block.
				# Modify elements of @grafs in-place...
				$graf = $value;
				$block = $this->html_hashes[$graf];
				$graf = $block;
				$grafs[$key] = $graf;
			}
		}

		return implode("\n\n", $grafs);
	}

    protected $paragraph_attr = '.navbar-text';

    protected function _formParagraph_callback($matches)
    {
        $attr = $this->paragraph_attr . (isset($matches[3]) ? $matches[3] : '');
		$attr  = $this->doExtraAttributes("p", $attr);
        $content = '<p'.$attr.'>' . $matches[2] . '</p>';
        return $content;
    }

}
