<?php
class MyDatabase{

	/*variables used internally with in the class.*/
	private $connect;
	private $calls;
	private $last_query;

	/*################################################
	name:__construct
	sent vars: string $host='', string $database='', string $username='', string $passwd=''
	what does it do: If there are variables it will create the Mydatabase class and automatically connect to the database,
		if not variables exsist it will not connect to a database.
	On failure: N/A
	returns: N/A
	################################################*/
	public function __construct($host='', $database='', $username='', $passwd=''){
		if(!(empty($hosts) && empty($database) && empty($username)&& empty($psswd))){
			$this->OpenDatabaseConnect($host, $database, $username, $passwd);
		}
		$this->calls=0;
	}
	
	/*################################################
	name:OpenDatabaseConnect
	sent vars:string $host='localhost', string $database='test', string $username='root', string $passwd=''
	what does it do: Connects to a database.
	On failure returns: dies and returns database error.
	returns: true
	################################################*/
	public function OpenDatabaseConnect($host='localhost', $database='test', $username='root', $passwd=''){
		$this->connect = mysql_connect($host,$username,$passwd);
		if (!$this->connect)
		{
			die('Could not connect: ' . $this->DisplayError());
		}
		mysql_select_db($database, $this->connect);
		return true;
	}

	/*################################################
	name:CloseDatabaseConnect
	sent vars: N/A
	what does it do: Closes the database connection
	On failure returns: Prints out an sql message
	returns: Number of calls made to the database.   including 0.
	################################################*/	
	public function CloseDatabaseConnect(){
		mysql_query("COMMIT", $this->connect);
		if(mysql_close($this->connect)){
			echo "Database calls:".$this->calls;
			return true;
		}
		else{
			return $this->DisplayError();
		}
	}
	
	/*################################################
	name:GetSqlAll
	sent vars:string $what, string $from, string $where='', string $sort=''
	what does it do: Runs a query on the database and returns all the rows.
	On failure returns: false
	returns: arrays of information, 1 per row
	################################################*/
	public function GetSqlAll($what, $from, $where='', $sort=''){
		if(empty($what) || empty($from)){
			if(empty($what)){
				echo "No select information!";
				return false;
			}
			else{
				echo "No tables specified!";
				return false;
			}
		}
		$this->last_query="SELECT ".$what." from ".$from." ".$where." ".$sort;
	//	echo $this->last_query;
		$results=mysql_query("SELECT ".$what." from ".$from." ".$where." ".$sort, $this->connect);
		if($results){
			$this->calls++;
			$row_num=mysql_num_rows($results);
			$count=0;
			if($row_num>1){
				while($row_num!=$count){
					$rows[]=mysql_fetch_array($results);
					$count++;
				}
				return $rows;
			}
			else{
				return array(mysql_fetch_array($results));
			}
		}
		else{
			echo $this->DisplayError();
			return false;
		}
	}
	
	/*################################################
	name:GetSqlRow
	sent vars:string $what, string $from, string $where='', string $sort=''
	what does it do: Runs a query on the database and returns all the rows.
	On failure returns: false
	returns: 1 array of information for 1 row.
	################################################*/
	public function GetSqlRow($what, $from, $where='', $sort=''){
		if(empty($what) || empty($from)){
			if(empty($what)){
				echo "No select information!";
				return false;
			}
			else{
				echo "No tables specified!";
				return false;
			}
		}
		$this->last_query = "SELECT ".$what." from ".$from." ".$where." ".$sort;
	//	echo $this->last_query;
		$results=mysql_query("SELECT ".$what." from ".$from." ".$where." ".$sort, $this->connect);
		if($results){
			$this->calls++;
			return mysql_fetch_assoc($results);
		}
		else{
			echo $this->DisplayError();
			return false;
		}
	}
	
	/*################################################
	name:DML
	sent vars:string $statement, string $table, string $info=''
	what does it do: Runs a query on the database and returns all the rows.
	On failure returns: false
	returns: true
	################################################*/
	public function DML($statement, $table, $info=''){
		if(empty($statement) || empty($table)){
			if(empty($statement)){
				echo "No statement specified!";
				return false;
			}
			else{
				echo "No tables specified!";
				return false;
			}
		}
		$this->last_query = $statement." ".$table." ".$info;
	//	echo $this->last_query;
		$results=mysql_query($statement." ".$table." ".$info, $this->connect);
		if($results){
			$this->calls++;
			return true;
		}
		else{
			echo $this->DisplayError();
			return false;
		}
	}
	
	/*################################################
	name:SetDatabase
	sent vars:string $database
	what does it do:sets the database
	On failure returns: false
	returns: true
	################################################*/
	public function SetDatabase($database){
		if(empty($database)){
			return false;
		}
		return mysql_select_db($database, $this->connect);
	}
	
	/*################################################
	name:EarlyCommit
	sent vars:N/A
	what does it do:runs a commit
	On failure returns: false
	returns: true
	################################################*/	
	public function EarlyCommit(){
		return mysql_query("COMMIT", $this->connect);
	}	
	
	/*################################################
	name:rowcount
	sent vars:N/A
	what does it do:gets a count of affected rows.
	On failure returns: N/A
	returns: a count of 0 or more rows.
	################################################*/	
	public function rowcount(){
		return mysql_affected_rows ($this->connect);
	}
	
	/*################################################
	name:ReturnLastQuery
	sent vars:N/A
	what does it do:returns the last query that was run.
	On failure returns: N/A
	returns: string
	################################################*/
	public function ReturnLastQuery(){
		return $this->last_query;
	}
	
		/*################################################
	name:DisplayError
	sent vars:N/A
	what does it do:gets the error string
	On failure returns: N/A
	returns: string
	################################################*/
	private function DisplayError(){
		return "Error Message::".mysql_error()."::Last Query ran::".$this->ReturnLastQuery();
	}
	
}

?>