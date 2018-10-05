<?php
function __autoload($ClassName){

	include_once('../cls/'.$ClassName.".cls.php");

}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$rc = new API("real_cases");

// FILE NAME
$file = "本月每日註冊人數" . date('Y-m-d_His') . ".xls";

$row = '';

if($_GET['searchDate'] != ""){
	$datetime = date('Y/m/d',strtotime($_GET['searchDate']));
	$str = "DATE_FORMAT('".$datetime."','%Y-%m')";
}else{
	$str = "DATE_FORMAT(NOW(),'%Y-%m')";
}
$sql = "SELECT
DATE_FORMAT(申請日期,'%Y-%m-%d %W') AS 申請日期,
身分別,

註冊方式,
COUNT(姓名) AS 人數
FROM
(SELECT
CASE memClass WHEN 0 THEN '學生' ELSE '非學生' END AS 身分別,
CASE WHEN memDeviceToken IS NULL THEN '網頁註冊' ELSE '手機註冊' END AS 註冊方式,
memName AS 姓名,
CASE memGender WHEN 1 THEN '男' ELSE '女' END AS 性別,
DATE_FORMAT(memRegistDate,'%Y-%m-%d') AS 申請日期
FROM
member) AS mem
WHERE
DATE_FORMAT(申請日期,'%Y-%m') = ".$str."
GROUP BY
申請日期,身分別,註冊方式
ORDER BY
申請日期 DESC,身分別";

$data = $rc->customSql($sql);
foreach($data as $key => $value){
		$row .=  '
				<tr>
     				<td>'.$value['申請日期'].'</td>
     				<td>'.$value['身分別'].'</td>
     				<td>'.$value['註冊方式'].'</td>
     				<td>'.$value['人數'].'</td>
     			</tr>';
}

header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=" . $file . "");

$html = '<HTML xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'."\n";
$html .= '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><style>table{border-collapse:collapse;table-layout:auto;}td,th{border:1px solid #000;white-space: nowrap;}.text{mso-number-format:"\@";}</style></head>'."\n";
$html .=  '<body>';
$html .=  '<table><tbody>';
$html .=  '<tr>
				<th>申請日期</th>
				<th>身分別</th>
				<th>註冊方式</th>
	    		<th>人數</th>
			</tr>';			
$html .=  '%s';
$html .=  "</tbody></table>";
$html .=  '</body></html>';
$txt = sprintf($html,$row);
echo $txt;

?>