<?php
include('classes/db.class.php');

function connect($host='localhost', $database='test', $username='root', $passwd=''){
	global $my_db;
	$my_db = new myDatabase($host, $database, $username, $passwd);
	return true;
}

function ModifySql($do,$table,$values){
	global $my_db;
	if($my_db->DML($do,$table,$values)){
		return true;
	}
	else{
		//echo "<br />Last Query:".$my_db->ReturnLastQuery();
		return false;
	}
}

function GetAllRows($select, $table, $where='', $sort=''){
	global $my_db;
	$results= $my_db->GetSqlAll($select, $table, $where, $sort);
	if($results){
		return $results;
	}
	else{
		return false;
	}
}

function GetOneRow($select, $table, $where='', $sort=''){
	global $my_db;
	
	$results = $my_db->GetSqlRow($select, $table, $where, $sort);
	if($results){
		return $results;
	}
	else{
		return false;
	}
}

function count_totals($count, $table, $where='', $sort=''){
	global $my_db;
	$results=$my_db->GetSqlRow("count(".$count.")", $table, $where, $sort);
	return $results['count('.$count.')'];
}

function CloseConnection(){
	global $my_db;
	$my_db->CloseDatabaseConnect();
	return true;
}

?>