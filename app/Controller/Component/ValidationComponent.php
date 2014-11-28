<?php
class ValidationComponent extends Component{
	public function __construct(ComponentCollection $collection,
      $settings = array()) {
        parent::__construct($collection, $settings);
    }	
	/**
	 * to validate input field from an entry 
	 * @param mixed $variable contains input value want to be checked
	 * @param string $flag contains validation option
	 * @return boolean true, false 
	 * @public
	 **/
	function blazeValidate ($variable, $flag , $field) 
	{
		$errMsg = "";
		$flag = strtoupper($flag);
		$field = string_unslug(  substr($field, 5)  );
		$variable = is_array($variable)?array_filter($variable):trim($variable);
		
		if(empty($variable))
		{
			if($flag == 'NOT_EMPTY')
			{
				$errMsg = "Please complete all required fields.";
			}
		}
		else
		{
			if($flag == 'IS_EMAIL')
			{
				if (!preg_match('|^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$|i', $variable)) $errMsg = 'Please enter valid email address for '.$field.'. Example: "lorem_ipsum@yahoo.com".';
			}
			else if(substr($flag, 0 , 10) == 'MIN_LENGTH')
			{			
				$temp = explode('_', $flag);
				$limit = $temp[count($temp)-1];
				if (strlen($variable) < $limit) $errMsg = $field." must be at least ".$limit." characters long.";
			}
			else if(substr($flag, 0 , 10) == 'MAX_LENGTH')
			{
				$temp = explode('_', $flag);
				$limit = $temp[count($temp)-1];
				if (strlen($variable) > $limit) $errMsg = $field." must be at most ".$limit." characters long.";
			}
			else if($flag == 'IS_NUMERIC')
			{			
				if (!is_numeric($variable)) $errMsg = $field." must be in numeric value.";
			}
			else if($flag == 'IS_ALNUM')
			{
				if (!ctype_alnum($variable)) $errMsg = $field." must be in alphanumeric value.";
			}
			else if($flag == 'IS_URL')
			{
			    if (!filter_var($variable, FILTER_VALIDATE_URL )) $errMsg = "Invalid URL address for ".$field.".";
			}
		}
		
		if(!empty($errMsg))
		{
			$errMsg .= "<br/>";
		}
		return $errMsg;
	}
}

?>