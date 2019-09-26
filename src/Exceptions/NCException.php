<?php

namespace NameChecker\Exceptions;

class NCException extends \Exception{
	var $e_codes_list=[
		E_ERROR => 'Error',
		E_WARNING  => 'Warning',
		E_PARSE => 'Parsing Error',
		E_NOTICE => 'Notice',
		E_CORE_ERROR => 'Core Error',
		E_CORE_WARNING => 'Core Warning',
		E_COMPILE_ERROR => 'Compile Error',
		E_COMPILE_WARNING => 'Compile Warning',
		E_USER_ERROR => 'User Error',
		E_USER_WARNING => 'User Warning',
		E_USER_NOTICE => 'User Notice',
		E_STRICT => 'Runtime Notice'
	];

	public function __construct($msg, $e_code = 0){
		$this->message = $msg;

		foreach($this->getTrace() as $trace){
			if (strpos($trace['file'],'name-checker') === false){
				$caller = $trace;
				$this->error_trace = "Error in \"$trace[file]\" on line ($trace[line]).";
				break;
			}
		}

		if (isset($caller, $this->e_codes_list[$e_code]))
			echo "<br />\n<b>$this->e_codes_list[$e_code]</b>: $msg in <b>$caller[file]</b> on line <b>$caller[line]</b><br />";

		parent::__construct();
	}

	public function __toString(){
		return "$this->message $this->error_trace";
	}
}