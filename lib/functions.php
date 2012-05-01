<?php

/*-- check whether file exists in remote location */

function checkRemoteFile($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if(curl_exec($ch)!==FALSE)
    {
        return true;
    }
    else
    {
        return false;
    }
}

/*-- search and remove value from array */

function remove_item_by_value($array, $val = '', $preserve_keys = true) {
	if (empty($array) || !is_array($array)) return false;
	if (!in_array($val, $array)) return $array;

	foreach($array as $key => $value) {
		if(empty($value))unset($array[$key]);
		if ($value == $val) unset($array[$key]);
	}

	return ($preserve_keys === true) ? $array : array_values($array);
}

/*-- check whether value is an integer */

function check_int($int){
    
    // First check if it's a numeric value as either a string or number
    if(is_numeric($int) === TRUE){
        
        // It's a number, but it has to be an integer
        if((int)$int == $int){

            return TRUE;
            
        // It's a number, but not an integer, so we fail
        }else{
        
            return FALSE;
        }
    
    // Not a number
    }else{
    
        return FALSE;
    }
}

function  array_duplicates($array)
	{
		if(!is_array($array))
		return false;
		$duplicates = array();
		$unique = array_unique($array);
		if(count($array) > count($unique))
		for($i = 0; $i < count($array); $i++)
		if(!array_key_exists($i, $unique))
		$duplicates[] = $array[$i];
		return $duplicates;
	}

