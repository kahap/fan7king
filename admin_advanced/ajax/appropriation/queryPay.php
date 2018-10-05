<?php
//業務人員報表
	require_once('../../../cls/WADB.cls.php');
	require_once('../../../cfg/cfg.inc.php');
	//header("Content-Type:text/html; charset=utf-8");
	//header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    //header('Content-Disposition: attachment; filename='.date('Y-m-d',time()).'每月區間繳款明細.xls');  //設定檔案名稱*/
	//$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	//$sql = "SELECT a.pmSupPrice,a.pmPeriodAmnt,a.pmUpDate,b.proName,c.supName FROM `product_manage` a,`product` b, `supplier` c where a.pmUpDate BETWEEN '2018-01-01' AND now() and a.pmStatus = '1' and a.pmMainSup = '1' and a.proNo = b.proNo and a.supNo = c.supNo";
	//date("d",time())
	$today = date("d",strtotime($_POST['Todate']));
	$dayFormate = date("Y-m-d",strtotime($_POST['Todate']));
	$day = ($today < 6) ? ($today+30):$today;
	switch($day){
		case ($day < 16):
			$during = "CONCAT(SUBSTRING('".$dayFormate."',1,8),'01') AND CONCAT(SUBSTRING('".$dayFormate."',1,8),'10')";
		break;
		
		case ($day < 26):
			$during = "CONCAT(SUBSTRING('".$dayFormate."',1,8),'11') AND CONCAT(SUBSTRING('".$dayFormate."',1,8),'20')";
		break;
		
		case ($day < 36):
			if($today < 6){
				$during = "CONCAT(SUBSTRING(date_sub('".$dayFormate."',interval 1 month) ,1,8),'21') AND last_day(date_sub('".$dayFormate."',interval 1 month))";
			}else{
				$during = "CONCAT(SUBSTRING('".$dayFormate."',1,8),'21') AND last_day('".$dayFormate."')";
			}
			
		break;
	}
	if($_POST['type'] == '0'){
		$sql = "SELECT a.tpiActualDate,a.rcNo,a.tpiPaidTotal,b.rcCaseNo,b.rcVirtualAccount,b.memNo,c.memName,c.memIdNum FROM `tpi` a,real_cases b ,member c,pay_records d where a.rcNo = b.rcNo and b.tbNo = '2' and b.memNo = c.memNo and b.rcNo = d.rcNo and d.prSource != '超商(萬用)' and a.tpiActualDate = d.prDate and a.tpiActualDate BETWEEN ".$during." group by a.tpiPeriod ,a.rcNo order by a.tpiActualDate asc";
	}else{
		$sql = "SELECT a.prDate as tpiActualDate,a.rcNo,a.prActualPay as tpiPaidTotal,b.rcCaseNo,b.rcVirtualAccount,b.memNo,c.memName,c.memIdNum FROM `pay_records` a,real_cases b,member c where a.rcNo = b.rcNo and b.memNo = c.memNo and a.prActualPay = '300' and a.prDate BETWEEN ".$during." order by a.prDate asc";
	}
	
	//echo $sql;
	$data = $db->selectRecords($sql);

?>
<style>  
td {mso-number-format: \@}  
</style>  
	<div class="card">
				<div class="card-content">
					<div style="padding-bottom:20px;">
						<a href="http://happyfan7.com/report/casebySup.php?date=<?php echo $_POST['Todate'] ?>&type=<?php echo $_POST['type']; ?>" class="waves-effect waves-light btn green m-b-xs confirm-save">匯出Excel</a>
					</div>
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
							<th>繳款日期</th>
							<th>案件編號</th>
							<th>身分證字號</th>
							<th>姓名</th>
							<th>虛擬帳號</th>
							<th>入帳金額</th>
						</thead>
						<tbody>
						<?php foreach($data as $key => $value){ ?>
						<tr>
							<td class="num"><?=$value['tpiActualDate'] ?></td>
							<td><?=$value['rcCaseNo'] ?></td>
							<td><?=$value['memIdNum'] ?></td>
							<td><?=$value['memName'] ?></td>
							<td><?=$value['rcVirtualAccount'] ?></td>
							<td><?=$value['tpiPaidTotal'] ?></td>
						</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>
			</div>