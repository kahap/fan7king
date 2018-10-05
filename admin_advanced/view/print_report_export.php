<?php
function __autoload($ClassName){
	include_once('../cls/'.$ClassName.".cls.php");
}

require_once('../cfg/cfg.inc.php');
require_once('../lib/function.php');

$rc = new API("real_cases");
$tb = new API("transfer_bank");
$mem = new API("member");
$bar = new API("barcode");
$tpi = new API("tpi");
$sup = new API("supplier");
$pm = new API("product_manage");
$pro = new API("product");
$aau = new API("admin_advanced_user");
$sc = new API("status_comment");
$scr = new API("status_comment_records");

// FILE NAME
$rcData = $rc->getOne($_GET['no']);
$memData = $mem->getOne($rcData[0]['memNo']);
$scr->setWhereArray(array("rcNo"=>$_GET['no']));
$scrData = $scr->getWithConditions(true);
/*$sc->setWhereArray(array("rcNo"=>$_GET['no'],"scStatusNo"=>$rcData[0]["rcStatus"]));
$scData = $sc->getWithConditions(true);*/
	If($rcData['0']['rcType'] == '0'){
		$or = new API("orders");
		$or->setWhereArray(array("orNo"=>$rcData[0]["rcRelateDataNo"]));
		$orData = $or->getWithConditions();
		$pmData = $pm->getOne($orData[0]["pmNo"]);
		$proData = $pro->getOne($pmData[0]["proNo"]);
		$supData = $sup->getOne($pmData[0]['supNo']);
	}else{
		$moto = new API("motorbike_cellphone_orders");
		$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
		$motoData = $moto->getWithConditions();
	}
	$str = "rcStatus".$rcData['0']['rcStatus']."Time";
?>
<html>
	<head>
		<meta charset="UTF-8">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	</head>
	<body>
		<h1 style="text-align: center;">分期付款申請案件回覆書</h1>
		<table width="100%"  style="border:3px #FFAC55 solid;padding:5px;" rules="all" cellpadding='8'>
			<tr>
				<td>回覆日期</td>
				<td><?=$rcData['0'][$str];?></td>
				<td>案件編號</td>
				<td><?=$rcData['0']['rcCaseNo'];?></td>
			</tr>
			<tr>
				<td>特約商</td>
				<td><?php echo ($rcData['0']['rcType'] == '0') ? $supData['0']['supName']:''; ?></td>
				<td>顧客姓名</td>
				<td><?=$memData['0']['memName'];?></td>
			</tr>
			<tr>
				<td>電話</td>
				<td><?php echo ($rcData['0']['rcType'] == '0') ? $supData['0']['supPhone']:''; ?></td>
				<td>傳真</td>
				<td><?php echo ($rcData['0']['rcType'] == '0') ? $supData['0']['supFax']:''; ?></td>
			</tr>
			<tr>
				<td>專案類別</td>
				<td><?=$rc->category[$rcData['0']['rcType']];?></td>
				<td>授信人員</td>
				<td><?php 
						$rcuserData = $aau->getOne($rcData['0']["aauNoAuthen"]);
						echo $rcuserData['0']['aauName'];  
					?></td>
			</tr>
			<?php if($rcData['0']['rcType'] > '0'){ ?>
			<tr>
				<td>期付款</td>
				<td><?=number_format($motoData['0']['mcoMinMonthlyTotal'])?></td>
				<td>期數</td>
				<td><?=$motoData['0']['mcoPeriodAmount']?></td>
			</tr>
			
			<?php } ?>
			<tr>
				<td>撥款金額</td>
				<td><?=number_format($rcData['0']['rcBankTransferAmount']);?></td>
				<td>分期總價</td>
				<td><?=number_format($rcData['0']['rcPeriodTotal']);?></td>
			</tr>
			<tr>
				<td>產品名稱</td>
				<td colspan="3" height="150px"><?php echo ($rcData[0]['rcType'] == '0') ? htmlspecialchars($proData[0]["proName"]):""; ?></td>
			</tr>
			<tr>
				<td>審核結果</td>
				<td colspan="3"><?=$rc->statusArr[$rcData['0']['rcStatus']];?></td>
			</tr>
			<tr>
				<td colspan="4" height="250px">
				案件補充說明：<br>
				<?php
					echo $scrData['0']['scrInfo'];
				?>
				</td>
			</tr>
		</table>
			<img src="../../assets/images/logo.png" style="margin: 0 auto;display: block;"/>
			<p style="text-align:center">
				廿一世紀數位有限公司<br>
				電話:(02)7721-2177 傳真:(02) 2765-1011<br>
				110台北市信義區基隆路一段163號5樓之2<br>
			</p>	
		
	</body>
</html>
<script>
	$(document).ready(function () {
		window.print();
	});
</script>