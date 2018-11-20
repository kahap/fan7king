<?php 
function __autoload($ClassName){
	include_once('../cls/'.$ClassName.".cls.php");
}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$or = new Orders();

//時間
date_default_timezone_set('Asia/Taipei');
$date = date('Y-m-d', time());

$allOrData = array();
$ulrIframe = "a=a";

foreach($_GET as $key=>$value){
	$$key = $value;
	$ulrIframe .= "&".$key."=".$value;
}
if(isset($orDateFrom) && isset($orDateTo)){
	$allOrData = $or->getAllOrderForReport($orDateFrom, $orDateTo);
}




?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
<style>
table{
	border-collapse:collapse;
}
table th, table td{
	border:1px solid #000;
	padding:5px;
}
</style>
</head>
<body>
	<table>
		<tr>
			<td colspan="6">案件審查時間統計表</td>
		</tr>
		<tr>
			<td colspan="6"> </td>
		</tr>
		<tr>
			<td colspan="6">日期: <?php echo $orDateFrom; ?> 至 <?php echo $orDateTo; ?></td>
		</tr>
		<tr>
			<td colspan="6"> </td>
		</tr>
		<tr>
			<th>訂單編號 </th>
            <th>下單時間</th>
            <th>審查中時間 </th>
            <th>核准時間 </th>
            <th>婉拒時間 </th>
            <th>待補時間 </th>
		</tr>
		<?php 
			if($allOrData != null && !empty(array_filter($allOrData))){
	        	foreach($allOrData as $key=>$value){
        ?>
            <tr class="pointer">
            	<td><?php echo $value["orCaseNo"]; ?></td>
                <td><?php echo $value["orDate"]; ?></td>
        		<td><?php echo $value["orReportPeriod2Date"]; ?></td>
        		<td><?php echo $value["orReportPeriod3Date"]; ?></td>
        		<td><?php echo $value["orReportPeriod4Date"]; ?></td>
        		<td><?php echo $value["orReportPeriod5Date"]; ?></td>
        	</tr>
        <?php 
            	}
        	}
        ?>
	</table>
</body>
</html>