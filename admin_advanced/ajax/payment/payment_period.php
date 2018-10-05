<?php 
header('Content-Type: text/html; charset=utf8');
require_once('../../cfg/cfg.inc.php');
require_once('../../lib/function.php');
require_once('../../cls/WADB.cls.php');
require_once('../../cls/API.cls.php');
require_once('BarcodeGenerator.php');
require_once('BarcodeGeneratorPNG.php');

$tpiNo = $_GET["id"];
$bar = new API("barcode");
$tpi = new API("tpi");
$rc = new API("real_cases");
$mem = new API("member");

$tpiData = $tpi->getOne($tpiNo);

$rcData = $rc->getOne($tpiData[0]["rcNo"]);

$memData = $mem->getOne($rcData[0]["memNo"]);

$bar->setWhereArray(array("tpiNo"=>$tpiNo));
$bar->setOrderArray(array("barNo"=>false));
$barData = $bar->getWithConditions();

$generator = new \Picqer\Barcode\BarcodeGeneratorPNG();

$delay = new API("other_setting");
$delaydata = $delay->getOne("1");

if($barData != null){
?>
<style>
body,html{
	margin:0;
	padding:0;
}
.header{
	background-color:#000;
	color:#FFF;
}
.header h1{
	font-size: 1em;
    min-height: 1.1em;
    text-align: center;
    display: block;
    margin: 0 30%;
    padding: .7em 0;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    outline: 0!important;
}
.body{
    padding: 1em;
}
.body img{
	width:100%;
	height:100px;
}
</style>
<div>
	<div class="header">
		<h1>超商繳款條碼</h1>
	</div>
	<div class="body">
		<p><?php echo $memData[0]["memName"]; ?>   先生/小姐您好：</p>
		<div class="body">
			本期帳單應繳金額為：<span style="color:red;"><?php echo $tpiData[0]["tpiPeriodTotal"]+$tpiData[0]["tpiPenalty"] ;?></span> 元
		</div>
		<div class="body">
            <?php
			$lastKey = array_pop(array_keys($barData));
			foreach($barData as $key=>$value){
                echo $value["barBarcode"];
				echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode($value["barBarcode"], $generator::TYPE_CODE_39)) . '">';
				if($key != $lastKey) echo "<br><br><br><br>";
				if(count($barData) > 3 && $key == "2") echo "<hr><br><br><div class='header'><h1 style='text-align:center;'>滯納金</h1></div><br><br><br><br>";
			}
            ?>
		</div>
		<div class="body">
			貼心提醒： <br>
			可持本條碼至全省 全家、萊爾富、OK便利商店繳款(單筆最高代收金額2萬元)，須額外負擔手續費15元。
		</div>
	</div>
	<div class="header">
		<h1 style="color: #2ad;text-decoration:underline;">客服專線：(02)7721-2177</h1>
	</div>
</div>
<?php 
}
?>