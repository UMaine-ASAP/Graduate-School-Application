<?php
/************************************************************************
 Class: TEMPLATE
 Purpose: Allows for templates to be used:  As in, the designers can 
 		  design without ever having to see the backend code of the 
 		  PHP.
************************************************************************/

class Template
{
	var $template;
	var $prefix;
	var $suffix;
	var $replaceArray;
	
	function Template()
	{
		$this->template = "UNSET";
		$this->prefix = "{{";
		$this->suffix = "}}";
		$this->replaceArray = array();		
	}
	
	function changeAll($temp, $pre, $suf, $arry)
	{
		$this->template = $temp;
		$this->prefix = $pre;
		$this->suffix = $suf;	
		$this->replaceArray = $arry;
	}

	function changeTemplate($templ)
	{
		$this->template = $templ;	
	}
	
	function changePrefix($pref)
	{
		$this->prefix = $pref;
	}
	
	function changeSuffix($suff)
	{
		$this->suffix = $suff;	
	}
	
	function changeArray($arry)
	{
		if(!is_array($arry)) $arry = Array();
		$this->replaceArray = $arry;	
	}
	function parse()
	{
		If($this->template == "UNSET" || !isset($this->prefix) || !isset($this->suffix) || count($this->replaceArray)==0)
		{
			//Parse function cannot continue...
			$retMsg="An Unknown Error Occurred...";
			if ($this->template == "UNSET") $retMsg = "The Template filename is not set.";
			if (!isset($this->prefix)) $retMsg = "There is no Prefix defined.";
			if (!isset($this->suffix)) $retMsg = "There is no Suffix defined.";
			if (count($this->replaceArray)==0) $retMsg = "The Replace Array is Empty.";
			return $retMsg;
				
		}
		$findreplace = $this->replaceArray;
		$data_file = fopen($this->template, "r") or die("Could not open Template file ('$this->template').");
		if(!isset($template_html)) $template_html = "";
		while(!feof($data_file)) $template_html .= fgets($data_file, 4096);
		fclose($data_file);
		
		if(!is_array($findreplace)) $findreplace = array();	
		$newfindreplace = array();	
		foreach($findreplace as $key => $value)
		{
			$newfindreplace[$this->prefix . $key . $this->suffix] = $value;	
		}
		$findreplace = $newfindreplace;
		$template_html = str_replace(array_keys($findreplace), array_values($findreplace), $template_html);
		return $template_html;	
	}

}
?>