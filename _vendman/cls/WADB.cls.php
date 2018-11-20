<?php

	class WADB
	{
		/* Database Host */
		var $sDbHost;           
		var $sDbName;           // Database Name
		var $sDbUser;           // Database User
		var $sDbPwd;            // Database Password
		var $sDbDetail;         // Database Details
		var $iNoOfRecords;      // Total No of Records
		var $oQueryResult;      // Results of sql query
		var $aSelectRecords;    // Array
		var $aArrRec;           // Array
		var $bInsertRecords;    // Boolean
		var $iInsertRecId;      // Integer - the primary key for inserted record
		var $bUpdateRecords;    // Boolean

        var $oDbLink;
		
		/* Constructor */
		function WADB ($sDbHost, $sDbName, $sDbUser, $sDbPwd)
		{
            $this->oDbLink = mysqli_connect ($sDbHost, $sDbUser, $sDbPwd, $sDbName) or die ("MySQL DB could not be connected");
//			@mysqli_select_db ($sDbName, $oDbLink)or die ("MySQL DB could not be selected");
			@mysqli_query($this->oDbLink, "set names 'utf8'");
		}
		
		/* seelct Record Object */
		function selectRecordsObject($sSqlQuery){
			unset($this->aSelectRecords);
			$this->oQueryResult = mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
			$this->iNoOfRecords = mysqli_num_rows($this->oQueryResult);
			if ($this->iNoOfRecords > 0) {
				while($obj = mysqli_fetch_object($this->oQueryResult)) {
					$this->aSelectRecords[] = $obj;
				}	
				mysqli_free_result($this->oQueryResult);				
			}						
			$this->aArrRec = $this->aSelectRecords;
			return $this->aArrRec;	
		}

		
	    /* Select Records */
		function selectRecords ($sSqlQuery)
		{
			unset($this->aSelectRecords);
			$this->oQueryResult = mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
			$this->iNoOfRecords = mysqli_num_rows($this->oQueryResult);
			if ($this->iNoOfRecords > 0) {
				while ($oRow = mysqli_fetch_array($this->oQueryResult,MYSQLI_ASSOC)) {
					$this->aSelectRecords[] = $oRow;
				}
				mysqli_free_result($this->oQueryResult);
			}else{
				$this->aSelectRecords = null;
			}
			$this->aArrRec = $this->aSelectRecords;
			return $this->aArrRec;
		}
	
		/*Get Number of Records */
		function getNumberOfRecords () {
			return $this->iNoOfRecords;
		}
	
		/* Get selected data */
		function getSelectedData (){
			return $this->aSelectRecords;
		}
	
		/* Insert Records */
		function insertRecords($sSqlQuery)
		{
			$this->bInsertRecords = mysqli_query ($this->oDbLink, $sSqlQuery) or die (mysqli_error($this->oDbLink));
			$this->iInsertRecId = mysqli_insert_id($this->oDbLink);
			return $this->iInsertRecId;
		}
	
		/* Find Inserted Id */
		function getIdForInsertedRecord()
		{
			return $this->iInsertRecId;
		}
	
		/* Update Records */
		function updateRecords($sSqlQuery)
		{
			return mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
		}
		function deleteRecords($sSqlQuery)
		{
			return mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
		}
		/* 測試新增用 */
		function insertUser($sSqlQuery)
		{
			return mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
		}
		
		/* 建立資料表 */
		function creatTable($sSqlQuery)
		{
			return mysqli_query($this->oDbLink, $sSqlQuery) or die(mysqli_error($this->oDbLink));
		}
		
		
	}
?>