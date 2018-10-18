<?
class co_company
{
	/**	建構函式

	**/
	public function __construct(){
		$this->db = new WADB(SYSTEM_DBHOST,SYSTEM_DBNAME,SYSTEM_DBUSER,SYSTEM_DBPWD);			
	}
	/**	加入我的最愛(旅遊清單)
	**/
	public function inser_co_company($array){	
		foreach($array as $key => $value){
			$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
		}
		
		$sql = "INSERT INTO  `co_company` (
					`company_name` ,
					`contact_name` ,
					`email` ,
					`phone`,
					`topic`,
					`content`,
					`time`)
				VALUES (
					 '".$company_name."', '".$contact_name."','".$email."',  '".$phone."','".$topic."','".$message."','".date('Y-m-d',time())."'
					)";
	 	$this->db->insertRecords($sql);
	}
	
	public function inser_loanVIP($array){	
		foreach($array as $key => $value){
			$$key = mysqli_real_escape_string($this->db->oDbLink, $value);
		}
		
		$sql = "INSERT INTO  `loan_vip` (
					`name` ,
					`category` ,
					`email` ,
					`phone`,
					`class`,
					`content`,
					`time`)
				VALUES (
					 '".$name."', '".$category."','".$email."',  '".$phone."','".$class."','".$message."','".$datetime."'
					)";
		$this->db->insertRecords($sql);
	 	
	}
}
?>