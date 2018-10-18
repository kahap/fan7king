<?php
//業務人員報表
	include('../model/php_model.php');
	header("Content-Type:text/html; charset=utf-8");
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'手機貸款報表.xls');  //設定檔案名稱
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	$sql = "SELECT a.rcCaseNo,a.rcStatus,a.rcRelateDataNo,a.rcBankTransferAmount,a.`rcDate`,a.rcStatus3Time,a.rcPeriodTotal,a.rcPeriodAmount,b.mcoNo,b.mcoMinMonthlyTotal,d.memName FROM `real_cases` a,`motorbike_cellphone_orders` b,`member` d WHERE a.`rcDate` BETWEEN CONCAT(SUBSTRING(DATE(NOW()),1,8),'01') AND NOW() and a.`rcCaseNo` != '' and a.rcType = '1' and b.mcoNo = a.rcRelateDataNo and b.memNo = d.memNo order by a.rcCaseNo asc";
	$data = $db->selectRecords($sql);
	
	$statusArr = array(0=>"已下單，Email未驗證",110=>"未完成下單",1=>"未進件",2=>"審查中",3=>"核准",4=>"婉拒",
				5=>"待補",6=>"補件",7=>"取消訂單",701=>"客戶自行撤件",8=>"出貨中",9=>"已收貨",10=>"已完成",
				11=>"換貨中",12=>"退貨中",13=>"完成退貨");
?>
<style>  
td {mso-number-format: \@}  
</style>  
	<table border='1'>
		<thead>
			<th>案件編號</th>
			<th>貸款項目</th>
			<th>客戶姓名</th>
			<th>分期總金額</th>
			<th>期數</th>
			<th>期金</th>
			<th>撥款金額</th>
			<th>案件狀態</th>
			<th>進件日期</th>
			<th>核准日期</th>
		</thead>
		<tbody>
		<?php foreach($data as $key => $value){ ?>
		<tr>
			<td class="num"><?=$value['rcCaseNo'] ?></td>
			<td><?="手機貸款" ?></td>
			<td><?=$value['memName'] ?></td>
			<td><?=$value['rcPeriodTotal'] ?></td>
			<td><?=$value['rcPeriodAmount'] ?></td>
			<td><?=$value['mcoMinMonthlyTotal']; ?></td>
			<td><?=$value['rcBankTransferAmount'] ?></td>
			<td><?=$statusArr[$value['rcStatus']] ?></td>
			<td><?=$value['rcDate'] ?></td>
			<td><?=(($value['rcStatus'] >= 3) ? $value['rcStatus3Time']:''); ?></td>
		</tr>
		<?php } ?>
		</tbody>
	</table>