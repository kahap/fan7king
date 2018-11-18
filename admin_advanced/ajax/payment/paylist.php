<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

$rc = new API("real_cases");
$tpi = new API("tpi");

$rc->setWhereArray(array("rcRelateDataNo"=>$_GET["id"],"rcType"=>$_GET["rctype"]));
$rcData = $rc->getWithConditions();

$tpi->setWhereArray(array("rcNo"=>$rcData[0]["rcNo"]));
$tpiData = $tpi->getWithConditions();

if($tpiData != null){
?>
<style>
tr:nth-child(2n) td{
	background-color:#E3EAEB;
}
</style>
<table cellspacing="0" cellpadding="4" id="GridView1" style="color:#333333;width:100%;border-collapse:collapse;">
	<tbody>
		<tr style="color:White;background-color:#1C5E55;font-weight:bold;">
			<th scope="col">期數</th>
			<th scope="col">應繳款日</th>
			<th scope="col">實際繳款日</th>
			<th scope="col">應繳金額</th>
			<th scope="col">還款金額</th>
			<th scope="col">超商繳費</th>
		</tr>
		<?php foreach($tpiData as $key=>$value){?>
		<tr align="center">
			<td><?php echo $value["tpiPeriod"]; ?></td>
			<td><?php echo $value["tpiSupposeDate"]; ?></td>
			<td><?php echo $value["tpiActualDate"]; ?></td>
			<td><?php echo $value["tpiPeriodTotal"]+(($value["tpiIfCancelPenalty"] == 0) ? $value["tpiPenalty"]:0); ?></td>
			<td><?php echo $value["tpiPaidTotal"]; ?></td>
			<td><a href="http://nowait.shop/admin_advanced/ajax/payment/payment_period.php?id=<?php echo $value["tpiNo"]; ?>" target="_blank">開啟</a></td>
		</tr>
		<?php }?>
	</tbody>
</table>

<?php 
}
?>