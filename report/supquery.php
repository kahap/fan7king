<?php
include('../model/php_model.php');

	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);

/*$rc->setWhereArray(array("rcRelateDataNo"=>$_GET["id"],"rcType"=>$_GET["rctype"]));
$rcData = $rc->getWithConditions();

$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
$tpiData = $tpi->getWithConditions();
*/ 
$data = array('201804085065','201807055088','201807045114','201807035153','201807055088','201807055061','201807045016','201807055067','201807055056','201807055074','201807055086','201807055085','201807065114','201807055088','201807065069','201807055088','201807065114','201807065126','201806265103','201807065089','201807065084','201807065094','201807065110','201807065126','201807075019','201807055061','201807055061','201807075006','201807075007','201807055061','201807075054','201801215046','201807075052','201807075051','201807075054','201805315033','201807065126','201807075007','201807075006','201807075051','201807075052','201807055086','201807075019','201807075006','201807075019','201807075080','201807075080','201807035075','201807075052','201807075019','201807075052','201807075080');

?>
<style>
tr:nth-child(2n) td{
	background-color:#E3EAEB;
}
</style>
<table cellspacing="0" cellpadding="4" id="GridView1" style="color:#333333;width:100%;border-collapse:collapse;">
	<tbody>
		<tr style="color:White;background-color:#1C5E55;font-weight:bold;">
			<th scope="col">案件編號</th>
			<th>供應商名稱</th>
			<th>業務人員</th>
		</tr>
		<?php
			foreach($data as $key => $value){
				$sql = "SELECT a.`rcCaseNo`,a.`rcNo`,a.supNo,b.supName,c.aauNo,c.aauName FROM `real_cases` a, supplier b, admin_advanced_user c WHERE `rcCaseNo` = '".$value."' && a.supNo = b.supNo && b.aauNo = c.aauNo";
				$data1 = $db->selectRecords($sql);
				if($data1 != ""){
					echo "<tr align='center'>
						<td>".$value."</td>
						<td>".$data1['0']['supName']."</td>
						<td>".$data1['0']['aauName']."</td>
					</tr>";
				}
			}
		?>
	</tbody>
</table>

<?php 

?>