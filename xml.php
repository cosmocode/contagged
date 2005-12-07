<?php
###################################################################################
#
# XML Library, by Keith Devens, version 1.0
# http://keithdevens.com/software/phpxml
#
# This code is Open Source, released under terms similar to the Artistic License.
# Read the license at http://keithdevens.com/software/license
#
###################################################################################

###################################################################################
# XML_unserialize: takes raw XML as a parameter (a string)
# and returns an equivalent PHP data structure
###################################################################################
function & XML_unserialize(&$xml){
	$xml_parser = &new XML();
	$data = &$xml_parser->parse($xml);
	$xml_parser->destruct();
	return $data;
}
###################################################################################
# XML_serialize: serializes any PHP data structure into XML
# Takes one parameter: the data to serialize. Must be an array.
###################################################################################
function & XML_serialize(&$data, $level = 0, $prior_key = NULL){
	#assumes a hash, keys are the tag names
	$xml_parts = '';
	while(list($key, $value) = each($data)){
		if(!strpos($key, ' attr')){ #if it's not an attribute
			#... we don't treat attributes by themselves.
			#note that implies that for an empty element that has attributes, you still
			#need to set the element to NULL

			$attributes = array();
			if(array_key_exists("$key attr", $data)){ #if there's an attribute for this element
				while(list($attr_name, $attr_value) = each($data["$key attr"]))
					$attributes[] = $attr_name.'="'.htmlspecialchars($attr_value).'"';
				reset($data["$key attr"]);
			}

			if(is_array($value) and array_key_exists(0, $value)){
				#numeric array (note that you can't have numeric keys at two levels in a row)
				$xml_parts .= XML_serialize($value, $level, $key);
			}else{
				if($prior_key) $key = $prior_key;
				#(i.e. if we're in a numeric array, replace the number with the actual tag)

				$xml_parts .= str_repeat("\t", $level).'<'.$key;
				if($attributes) $xml_parts .= ' '.join(' ',$attributes);

				if(is_null($value))
					$xml_parts .= " />\r\n";
				elseif(!is_array($value))
					$xml_parts .= '>'.htmlspecialchars($value)."</$key>\r\n";
				else
					$xml_parts .= ">\r\n".XML_serialize($value, $level+1).str_repeat("\t", $level)."</$key>\r\n";
			}
		}
	}
	reset($data);
	if($level == 0) return "<?xml version=\"1.0\" ?>\r\n".$xml_parts;
	return $xml_parts;
}
###################################################################################
# XML class: utility class to be used with PHP's XML handling functions
###################################################################################
class XML {
	var $parser;   #a reference to the XML parser
	var $document; #the entire XML structure built up so far
	var $parent;   #a pointer to the current parent - the parent will be an array
	var $stack;    #a stack of the most recent parent at each nesting level
	var $last_opened_tag; #keeps track of the last tag opened.

	function XML(){
 		$this->parser = &xml_parser_create();
		xml_parser_set_option(&$this->parser, XML_OPTION_CASE_FOLDING, 0);
		xml_set_object(&$this->parser, &$this);
		xml_set_element_handler(&$this->parser, 'open','close');
		xml_set_character_data_handler(&$this->parser, 'data');
	}
	function destruct(){
		xml_parser_free(&$this->parser);
	}
	function & parse(&$data){
		$this->document = array();
		$this->parent   = &$this->document;
		$this->stack    = array();
		return xml_parse(&$this->parser, &$data, true) ? $this->document : NULL;
	}
	function open(&$parser, $tag, $attributes){
		$this->data = array(); #stores temporary cdata
		$this->last_opened_tag = $tag;
		if(is_array($this->parent) and array_key_exists($tag,$this->parent)){ #if you've seen this tag before
			if(is_array($this->parent[$tag]) and array_key_exists(0,$this->parent[$tag])){ #if the keys are numeric
				#this is the third or later instance of $tag we've come across
				$key = count_numeric_items($this->parent[$tag]);
			}else{
				#this is the second instance of $tag that we've seen
				#shift around
				if(array_key_exists("$tag attr",$this->parent)){
					$arr = array('0 attr'=>&$this->parent["$tag attr"], &$this->parent[$tag]);					unset($this->parent["$tag attr"]);
				}else{
					$arr = array(&$this->parent[$tag]);
				}
				$this->parent[$tag] = &$arr;
				$key = 1;
			}
			$this->parent = &$this->parent[$tag];
		}else{
			$key = $tag;
		}
		if($attributes)
			$this->parent["$key attr"] = $attributes;

		$this->parent[$key] = NULL; #it turns out you can take a reference to NULL :)
		$this->parent       = &$this->parent[$key];
		$this->stack[]      = &$this->parent;
	}
	function data(&$parser, $data){
		if($this->last_opened_tag != NULL) #you don't need to store whitespace in between tags
			$this->data[] = $data;
	}
	function close(&$parser, $tag){
		static $just_closed = false;
		if($this->last_opened_tag == $tag){
			if($this->data)
				$this->parent = join('',$this->data);
			$this->last_opened_tag = NULL;
		}
		array_pop($this->stack);
		if($this->stack)
			$this->parent = &$this->stack[count($this->stack)-1];
	}
}
function count_numeric_items(&$array){
	return is_array($array) ? count(array_filter(array_keys($array), 'is_numeric')) : 0;
}
?>