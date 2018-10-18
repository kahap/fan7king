<?php
	header('Content-type:application/vnd.ms-excel');  //宣告網頁格式
    header('Content-Disposition: attachment; filename=happyfan7.xls');  //設定檔案名稱
	include('../model/php_model.php');
	$member = new Member();
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	define('FINANCIAL_MAX_ITERATIONS', 128);
	define('FINANCIAL_PRECISION', 1.0e-08);
	/*一般會員繳費
	$sql = "SELECT `memNo`,`memSubEmail` FROM `member`  WHERE `memAccount` != '' && `memFBtoken` = '' && `memSubEmail` != 'tom200e@hotmail.com'";
	$data = $db->selectRecords($sql);
	
	
	
	$sql1 = "SELECT memNo FROM `orders` where orStatus = '10'";
	$data1 = $db->selectRecords($sql1);
	
	foreach($data1 as $k => $v){
		$not_order[] = $v['memNo'];
	}
	
	$i=1;
	echo "<table border='1'><thead><th>會員編號</th><th>Email</th></thead><tbody>";
	foreach($data as $key => $value){
		if(in_array($value['memNo'],$not_order)){
			
			echo "<tr><td>".$value['memNo']."</td><td>".$value['memSubEmail']."</td></tr>";
			$i++;
		}
		
	}
	echo "</tbody></table>";
	*/
	$sql = "SELECT `memNo`,`memSubEmail`,`memClass` FROM `member`  WHERE `memFBtoken` != '' && memSubEmail != ''";
	$data = $db->selectRecords($sql);
	
	foreach($data as $key => $value){
		$sql1 = "SELECT memNo FROM `orders` where memNo = '".$value['memNo']."' && `orStatus` != '110'";
		$data1 = $db->selectRecords($sql1);
		if(count($data1) > 0){
			$array[$value['memNo']] = $value['memSubEmail'];
		}
	}
	$i=1;
	echo "<table border='1'><thead><th>會員編號</th><th>Email</th><th>身分別</th></thead><tbody>";
	foreach($data as $key => $value){
			echo "<tr><td>".$value['memNo']."</td><td>".$value['memSubEmail']."</td><td>".(($value['memClass'] == 0) ? '學生':'非學生')."</td></tr>";
		
	}
	echo "</tbody></table>";
	
	/*$sql = "SELECT * FROM `real_cases` WHERE `rcStatus` = '3'  && `rcApproDate` between '2017-05-01' and '5017-05-30'";
	$data = $db->selectRecords($sql);
	echo "<table border='1'><thead><th>案件編號</th><th>分期數</th><th>申請金額</th><th>撥款金額</th><th>利率</th><th>撥款日期</th></thead><tbody>";
	foreach($data as $key => $value){
		$actualRate = number_format((float)RATE($value["rcPeriodAmount"],$value["rcPeriodTotal"]/$value["rcPeriodAmount"],-$value["rcBankTransferAmount"])*12*100, 5, '.', '');
		echo "<tr><td>".$value['rcCaseNo']."</td><td>".$value['rcPeriodAmount']."</td><td>".number_format($value['rcPeriodTotal'])."</td><td>".number_format($value['rcBankTransferAmount'])."</td><td>".$actualRate."%</td><td>".$value['rcApproDate']."</td></tr>";
	}
	echo "</tbody></table>";
	*/
	
function RATE($nper, $pmt, $pv, $fv = 0.0, $type = 0, $guess = 0.1) {

	$rate = $guess;
	if (abs($rate) < FINANCIAL_PRECISION) {
		$y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
	} else {
		$f = exp($nper * log(1 + $rate));
		$y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
	}
	$y0 = $pv + $pmt * $nper + $fv;
	$y1 = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;

	// find root by secant method
	$i  = $x0 = 0.0;
	$x1 = $rate;
	while ((abs($y0 - $y1) > FINANCIAL_PRECISION) && ($i < FINANCIAL_MAX_ITERATIONS)) {
		$rate = ($y1 * $x0 - $y0 * $x1) / ($y1 - $y0);
		$x0 = $x1;
		$x1 = $rate;

		if (abs($rate) < FINANCIAL_PRECISION) {
			$y = $pv * (1 + $nper * $rate) + $pmt * (1 + $rate * $type) * $nper + $fv;
		} else {
			$f = exp($nper * log(1 + $rate));
			$y = $pv * $f + $pmt * (1 / $rate + $type) * ($f - 1) + $fv;
		}

		$y0 = $y1;
		$y1 = $y;
		++$i;
	}
	return $rate;
}
	
?>