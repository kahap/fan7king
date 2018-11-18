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

// FILE NAME
$file = "撥款明細表_" . date('Y-m-d_His') . ".xls";

$row = '';
$count = 0;
$transferTotal = 0;
foreach($_POST["rcNo"] as $key => $rcNo){
	$sql = "select a.rcCaseNo,a.rcType,a.rcPeriodTotal,a.rcPeriodAmount,a.tbNo,a.memNo, a.supNo, a.rcBankTransferAmount, a.rcRelateDataNo, a.rcBankRiskFeeMonth, a.rcBankRiskFeeTotal,b.memName,b.memIdNum from real_cases a, member b where a.rcNo ='".$rcNo."' && a.memNo = b.memNo";
	$rcData = $rc->customSql($sql);
	$tbData = $tb->getOne($rcData[0]["tbNo"]);
	//$memData = $mem->getOne($rcData[0]["memNo"]);
	$transferTotal += $rcData[0]['rcBankTransferAmount'];
	$count++;
	if($rcData[0]["rcType"] > 0){
		$moto = new API("motorbike_cellphone_orders");
		$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
		$motoData = $moto->getWithConditions();
	}else{
		$supData = $sup->getOne($rcData[0]["supNo"]);
	}		
	$row .=  '<tr>
				<td>' . $count . '</td>
				<td class="text">' . $rcData[0]['rcCaseNo'] . '</td>
				<td>' . $rc->caseTypeArr[$rcData[0]['rcType']] . '</td>
			    <td>' . $rcData[0]['memIdNum'] . '</td>
			    <td>' . $rcData[0]['memName'] . '</td>
				<td class="text">' . number_format((($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'])) . '</td>
	    		<td class="text">' . $rcData[0]['rcPeriodAmount'] . '</td>
	    		<td class="text">' . number_format((($rcData['0']['rcType'] == 0) ? $rcData[0]['rcPeriodTotal']:$motoData['0']['mcoMinMonthlyTotal']*$rcData[0]['rcPeriodAmount'])) . '</td>
	    		<td class="text">' . number_format($rcData[0]['rcBankTransferAmount']) . '</td>
	    		<td class="text">' . number_format($rcData[0]['rcBankRiskFeeMonth']) . '</td>
	    		<td class="text">' . number_format($rcData[0]['rcBankRiskFeeTotal']) . '</td>
	    		<td>' . $tbData[0]["tbName"] . '</td>
				<td>' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supName']:"") . '</td>
	    		<td>' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supTransferAccIdNum']:"") . '</td>
	    		<td class="text">' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supTransferAccName']:"") . '</td>
	    		<td>' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supTransferBank']:"") . '</td>
	    		<td class="text">' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supTransferBankCode']:"") . '</td>
	    		<td>' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supTransferSubBank']:"") . '</td>
	    		<td class="text">' . (($rcData['0']['rcType'] == 0) ? $supData[0]['supTransferAcc']:"") . '</td>
			  </tr>';
			  
} 
$title = ($rcData[0]['rcType'] == 2) ? "景華有限公司帳款申請撥款明細表":"NoWait帳款申請撥款明細表";
header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=" . $file . "");

$html = '<HTML xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'."\n";
$html .= '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><style>table{border-collapse:collapse;table-layout:auto;}td,th{border:1px solid #000;white-space: nowrap;}.text{mso-number-format:"\@";}</style></head>'."\n";
$html .=  '<body>';
$html .=  '<table style="font-family:標楷體;"><tbody>';
$html .=  '<tr>
					<td colspan="15" style="border:none;text-align:center;font-size:24px">'.$title.' - ' . date('Y/m/d') . '</td>
				</tr>';
$html .=  '	<tr>
	    		<th>編號</th>
			    <th>進件編號</th>
			    <th>案件類型</th>
			    <th>身分證號</th>
			    <th>客戶姓名</th>
			    <th>期金</th>
			    <th>期數</th>
			    <th>分期總金額</th>
			    <th>首撥金額</th>
			    <th>風管費(月)</th>
			    <th>風管費(總)</th>
				<th>撥款銀行</th>
			    <th>經銷商</th>
			    <th>帳戶ID</th>
			    <th>撥款戶名</th>
			    <th>銀行名稱</th>
			    <th>銀行代號</th>
			    <th>分行名稱</th>
			    <th>撥款帳號</th>
			  </tr>';
$html .=  '%s';
$html .= '<tr><td colspan="7" style="border:none;"></td><td>撥款合計：</td><td class="text">'.number_format($transferTotal).'</td><td colspan="6" style="border:none;"></td></tr>';
$html .= '<tr><td colspan="15" style="border:none;"></td></tr>';
$html .= '<tr>
			<td rowspan="3" colspan="4">經辦：</td>
			<td rowspan="3" colspan="3">覆核：</td>
			<td rowspan="3" colspan="3">主管：</td>
			<td rowspan="3" colspan="5" style="border:none;"></td>
		</tr>';
$html .=  "</tbody></table>";
$html .=  '</body></html>';
$txt = sprintf($html,$row);
echo $txt;

?>