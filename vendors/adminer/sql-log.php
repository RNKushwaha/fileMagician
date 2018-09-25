<?php

class AdminerSqlLog {
	var $filename;
	var $dir;
	
	/**
	* @param string defaults to "$database.sql"
	*/
	function __construct($dir = "") {
		$this->dir = $dir;
	}
	
	function messageQuery($query, $time) {
		$this->_log($query);
	}

	function sqlCommandQuery($query) {
		$this->_log($query);
	}

	function _log($query) {
		$adminer = adminer();
		$this->filename = $this->dir.$adminer->database()."_logs_".date('m_Y').".sql";
		$fp = fopen($this->filename, "a");
		flock($fp, LOCK_EX);
		fwrite($fp, $query);
		fwrite($fp, "\n\n");
		flock($fp, LOCK_UN);
		fclose($fp);
	}

}