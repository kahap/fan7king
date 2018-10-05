<?php
function __autoload($ClassName){

	include_once('../cls/'.$ClassName.".cls.php");

}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$rc = new API("real_cases");

// FILE NAME
$file = "本月撥款明細檔案" . date('Y-m-d_His') . ".xls";

$row = '';

if($_GET['searchDate'] != ""){
	$datetime = substr($_GET['searchDate'],0,8);
	$str = "BETWEEN CONCAT('".$datetime."','01') AND last_day('".$_GET['searchDate']."')";
	$day = $_GET['searchDate'];
}else{
	$str = "BETWEEN CONCAT(SUBSTRING(DATE(NOW()),1,8),'01') AND NOW() ";
	$day = date("Y-m-d",time());
}
$sql = "SELECT
rcCaseNo AS 案件編號,
rcPeriodAmount AS 期數,
rcBankTransferAmount AS 撥款金額,
CASE rcType WHEN 0 THEN rcPeriodTotal ELSE rcPeriodAmount * t.tpiPeriodTotal END AS 應繳金額,
rcPredictGetDate AS 撥款日期,
CASE WHEN tbNo = 1 THEN 'CMC' ELSE '彰銀' END AS 撥款銀行,
CASE rcType WHEN 0 THEN '3C' WHEN 1 THEN '手機' WHEN 2 THEN '機車' END AS 產品別
FROM
real_cases
LEFT JOIN (SELECT rcNo,tpiPeriodTotal FROM tpi GROUP BY rcNo) AS t ON real_cases.rcNo = t.rcNo 
WHERE
rcStatus = '3' AND 
rcPredictGetDate ".$str." AND
t.tpiPeriodTotal != ''
ORDER BY
rcPredictGetDate,rcCaseNo";
$data = $rc->customSql($sql);
foreach($data as $key => $value){
		$row .=  '
				<tr>
     				<td class="text">'.$value['案件編號'].'</td>
     				<td>'.$value['期數'].'</td>
     				<td>'.$value['撥款金額'].'</td>
     				<td>'.$value['應繳金額'].'</td>
					<td>'.$value['撥款日期'].'</td>
					<td>'.$value['撥款銀行'].'</td>
					<td>'.$value['產品別'].'</td>
     			</tr>';
}

header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=" . $file . "");

$html = '<HTML xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'."\n";
$html .= '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><style>table{border-collapse:collapse;table-layout:auto;}td,th{border:1px solid #000;white-space: nowrap;}.text{mso-number-format:"\@";}</style></head>'."\n";
$html .=  '<body>';
$html .=  '<table><tbody>';
$html .=  '<tr>
				<th>案件編號</th>
				<th>期數</th>
				<th>撥款金額</th>
	    		<th>應繳金額</th>
				<th>撥款日期</th>
				<th>撥款銀行</th>
				<th>產品別</th>
			</tr>';			
$html .=  '%s';
$html .=  "</tbody></table>";
$html .=  '</body></html>';
$txt = sprintf($html,$row);
echo $txt;

?>