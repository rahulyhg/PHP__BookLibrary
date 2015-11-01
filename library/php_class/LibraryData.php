<?php

/*
	Method:
		boolean setValPosInt(ref, int)
			- set the inputted variable reference with the inputted integer while the integer is a non-zero positive interger.
		boolean setValStr(ref, string)
			- compile the inputted string for sql execution if there is any special character and save it to the inputted variable reference.
		boolean getValStr(ref, string)
			- compile the inputted string for human readable if there is any special character and save it to the inputted variable reference.
*/

class LibraryData
{
	protected function setValPosInt(&$var, $val){
		// set value with non-zero positive integer.
		if ( $val > 0 ){
			$var = $val;
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: setValPosInt

	protected function setValStr(&$var, $val){
		// set value to sting with special character replacement.
		if ( strlen($val) > 0 ){
			$var = $val;
			$var = str_replace("'","&rsquo;",$var);
			$var = str_replace('"',"&quot;",$var);
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: setValString

	protected function getValStr(&$var){
		// get back special character string.
		if ( strlen($val) > 0 ){
			$var = str_replace("&rsquo;","'",$var);
			$var = str_replace("&quot;",'"',$var);
			$execution = true;
		} else {
			$execution = false;
		}
		return $execution;
	} // End-function: getValString
}

?>