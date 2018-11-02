<?php 
session_start();
/*if(!isset($_SESSION['userdata'])){
	echo "<script>location.href='../index.php';</script>";
}*/

function __autoload($ClassName){
	include_once('../cls/'.$ClassName.".cls.php");
}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$or = new Orders();
$sr = new Status_Record();
$mem = new Member();

$mcoNo = $_GET["mcoNo"];

$orData = $or->getMcoData($mcoNo);
$rcData = $or->getrcData($_GET['rcNo']);
$sql2 = "SELECT * FROM `service_record` where rcNo = '".$_GET['rcNo']."' && content = '列印'  ORDER BY `service_record`.`id` asc limit 1";
$print_time = $or->getSql($sql2);
$sql3 = "SELECT * FROM `service_record` where rcNo = '".$_GET['rcNo']."' && content = '儲存案件並進入徵信'  ORDER BY `service_record`.`id` asc limit 1";
$print1_time = $or->getSql($sql3);
$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
if($memData[0]["memRecommCode"] != ""){
	$memRecData = $mem->getOneMemberByNo($memData[0]["memRecommCode"]);
}else{
	$memRecData = null;
}

	$sql = "SELECT * FROM `assure` where rcNo = '".$_GET['rcNo']."'";
	$asusData = $or->getSql($sql);


if($_GET['aauNoService'] != ""){
	$or->addservice_record($_GET['rcNo'], $_GET['aauNoService']);
	$or->updateMcoIfProcess(1, $mcoNo);
}
//訂單日期
$orDateNoTime = explode(" ",$orData[0]["mcoDate"]);
$orDateArr = explode("-", $orDateNoTime[0]);

//信用卡日期
$orCardExpDateArr = explode("/", $orData[0]["mcoCreditDueDate"]);

//聯絡人陣列
$relaNameArr = is_array(json_decode($orData[0]["mcoContactName"])) ? json_decode($orData[0]["mcoContactName"]) : "";
$relaRelationArr = is_array(json_decode($orData[0]["mcoContactRelation"])) ? json_decode($orData[0]["mcoContactRelation"]) : "";
$relaPhoneArr = is_array(json_decode($orData[0]["mcoContactPhone"])) ? json_decode($orData[0]["mcoContactPhone"]) : "";
$relaCellArr = is_array(json_decode($orData[0]["mcoContactCell"])) ? json_decode($orData[0]["mcoContactCell"]) : "";

$frdNameArr = is_array(json_decode($orData[0]["mcoContactFrdName"])) ? json_decode($orData[0]["mcoContactFrdName"]) : "";
$frdRelationArr = is_array(json_decode($orData[0]["mcoContactFrdRelation"])) ? json_decode($orData[0]["mcoContactFrdRelation"]) : "";
$frdPhoneArr = is_array(json_decode($orData[0]["mcoContactFrdPhone"])) ? json_decode($orData[0]["mcoContactFrdPhone"]) : "";
$frdCellArr = is_array(json_decode($orData[0]["mcoContactFrdCell"])) ? json_decode($orData[0]["mcoContactFrdCell"]) : "";

$mem->changeToReadable($memData[0]);


//補件資料陣列
$orAppAuthenExtraInfoArr = json_decode($orData[0]["mcoExtraInfoUpload"]);
$memberClass_array = array('學生'=>'學生','1'=>'上班族','2'=>'家管','3'=>'其他','4'=>'非學生','無'=>'無');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nowait管理後台</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
</head>
<style>
* {
    -webkit-text-size-adjust: none;
}
.page{
	//width:842px;
	//height:1230px;
	//margin:0 auto;
}
table{
	border-collapse:collapse;
}
table tr td{
	border:1px solid #000;
	padding:4px;
	font-size:12px;
}
.main-title td{
	font-size:22px;
	border:none;
}
#logo{
    width: 24px;
    vertical-align: middle;
    margin-right: 10px;
}
.center{
	text-align:center;
	font-weight:bold;
}
.no-bold{
	font-weight:normal;
}
.blue-bg{
	background-color:rgb(121,220,255);
}
.blue-text{
	color:rgb(0,112,192);
}
.green-bg{
	background-color:rgb(197,224,179);
}
.green-bg{
	color:rgb(56,86,35);
}
.yellow-bg{
	background-color:rgb(255,242,204);
}
.yellow-text{
	color:rgb(89,89,89);
}
.comp-info{
	font-size:12px !important;
	text-align:right;
}
.title-row td{
	font-size:18px;
	font-weight:bold;
	border:none;
}
.secret{
	position:relative;
}
.secret img{
	position:absolute;
	right:0;
	bottom:0;
	display:none;
}
.provement-spacing td{
	border:none;
	font-size:12px;
}
.provement-img{
	max-height:80px;
}
.promise-signature-area{
	position:relative;
}
.promise-signature-area .title{
	display: inline-block;
    height: 50px;
}
.promise-signature-area img{
    display: block;
    margin: 0 auto;
    max-height: 48px;
}
.promise-signature-area div{
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    margin: auto;
    height: 95%;
    width: 60%;
    border-radius: 5px;
}
.promise-signature-area .blue-bg{
	background-color:rgb(157,195,230);
}
.promise-signature-area .green-bg{
	background-color:rgb(197,224,180);
}
.promise-date{
    position: absolute;
    left: 0;
    bottom: 0;
}
.id-pic{
	max-width:800px;
	max-height:1150px;
	display:block;
}
.wide-id{
	width:100%;
}
.high-id{
	height:100%;
}
.supplier-stamp{
	height:100px;
	display:block;
	margin:0 auto;
}
.page p{
    margin: 6px 0;
    font-size: 12px;
}
.clarification{
    border: 1px solid #000;
    padding: 5px;
    font-size: 15px;
}
.clarification span{
    display: inline-block;
    width: 735px;
}
.clarification img{
    width: 90px;
    vertical-align: top;
}
.assure-signature{
    position: absolute;
    bottom: 10px;
    right: 10px;
}
.assure-signature>div{
    margin-left: 10px;
}
.assure-signature div{
    display: inline-block;
}
.assure-signature div span{
    line-height: 45px;
    display: inline-block;
}
.assure-signature div div{
    width: 185px;
    height: 45px;
    text-align: center;
    vertical-align: middle;
    border-radius: 5px;
}
.assure-signature .blue-bg{
	background-color:rgb(157,195,230);
}
.assure-signature .green-bg{
	background-color:rgb(197,224,180);
}
.assure-signature img{
    max-height: 100%;
}
</style>
<body>

<div class="page">
	<table>
		<tr class="main-title">
			<td colspan="17"><img id="logo" src="../images/21cent-logo.png">手機貸款申請書</td>
			<td class="comp-info" colspan="3">
				廿一世紀數位科技有限公司<br>
				Twenty-One Century Digital<br>
				服務電話：(02)7721-2177<br>
				送件傳真：(02)5555-7288
			</td>
		</tr>
		<tr class="title-row">
			<td colspan="3" class="blue-bg blue-text">●申請人基本資料</td>
			<td colspan="10" class="blue-bg blue-text">客服列印時間:<font size="5"><?=$print_time['0']['time']; ?></font></td>
			<td colspan="8" class="blue-bg blue-text">送審時間:<?=$print1_time['0']['time']; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">申請人姓名</td>
			<td colspan="3"><?php echo $memData[0]["memName"]; ?></td>
			<td colspan="2" class="center">身分證字號</td>
			<td colspan="8"><?php echo $memData[0]["memIdNum"]; ?></td>
			<td colspan="2" class="center">出生日期</td>
			<?php 
				$bdayStrArr = explode("-",$memData[0]["memBday"]); 
			?>
			<td colspan="3"><?php if(strlen($bdayStrArr[0]) != 4){ echo "民國 "; } echo $bdayStrArr[0]; ?> 年 <?php echo $bdayStrArr[1]; ?> 月 <?php echo $bdayStrArr[2]; ?> 日</td>
		</tr>
		<tr>
			<td colspan="2" class="center">身分別</td>
			<td colspan="13">
				<?php echo $memberClass_array[$memData[0]["memClass"]]; ?>
				<?php 
				if($memData[0]["memClass"] == 0){
					echo "，".$memData[0]["memSchool"];
				}
				if($memData[0]["memOther"] != "" && $memData[0]["memOther"] != "無"){
					echo "，".$memData[0]["memOther"];
				} 
				echo "，".$memData[0]["memAccount"];
				?>
			</td>
			<td colspan="2" class="center">行動電話</td>
			<td colspan="3"><?php echo $memData[0]["memCell"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">戶籍地址</td>
			<td colspan="13"><?php echo $orData[0]["mcoBirthAddr"]; ?></td>
			<td colspan="2" class="center">戶籍電話</td>
			<td colspan="3" class="secret"><?php echo $orData[0]["mcoBirthPhone"]; ?><img src="../images/secret.png"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">現住地址</td>
			<td colspan="13"><?php echo $memData[0]["memAddr"]; ?></td>
			<td colspan="2" class="center">現住電話</td>
			<td colspan="3" class="secret"><?php echo $memData[0]["memPhone"]; ?><img src="../images/secret.png"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">住房所有權</td>
			<td colspan="18"><?php echo $orData[0]["mcoLivingOwnership"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">公司名稱</td>
			<td style="max-width:100px;" colspan="7"><?php echo $memData[0]["memCompanyName"]; ?></td>
			<td colspan="1" class="center">年資</td>
			<td colspan="2"><?php echo $memData[0]["memSalary"]; ?></td>
			<td colspan="1" class="center">月薪</td>
			<td colspan="2"><?php echo $memData[0]["memYearWorked"]; ?></td>
			<td colspan="2" class="center">公司電話</td>
			<td colspan="3" style="max-width:1px;" class="secret"><?php echo $orData[0]["mcoCompanyPhone"]; ?> 分機 <?php echo $orData[0]["mcoCompanyPhoneExt"]; ?> <img src="../images/secret.png"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">信用卡卡號</td>
			<td colspan="6">
			<?php 
			if(strrpos($orData[0]["mcoCreditNum"], "--") === false){
				echo $orData[0]["mcoCreditNum"]; 
			}
			?>
			</td>
			<td colspan="2" class="center">發卡銀行</td>
			<td colspan="5"><?php echo $orData[0]["mcoCreditIssueBank"]; ?></td>
			<td colspan="2" class="center">有效期限</td>
			<td colspan="3">
			<?php if(!empty($orCardExpDateArr) && sizeof($orCardExpDateArr)>1){ ?>
				<?php echo $orCardExpDateArr[0]." 月 ".$orCardExpDateArr[1]." 年"; ?>
			<?php } ?>
			</td>
		</tr>
		<tr class="title-row" class="center">
			<td colspan="5" class="blue-bg blue-text">●聯絡人資料</td>
		</tr>
		<?php 
		if(!empty($relaNameArr)){
			foreach($relaNameArr as $key=>$value){ 
		?>
		<tr>
			<td colspan="2" class="center">親屬姓名</td>
			<td colspan="2"><?php echo $value; ?></td>
			<td colspan="2" class="center">關係</td>
			<td colspan="2"><?php echo $relaRelationArr[$key]; ?></td>
			<td colspan="2" class="center">市內電話</td>
			<td colspan="5" class="secret"><?php echo $relaPhoneArr[$key]; ?><img src="../images/secret.png"></td>
			<td colspan="2" class="center">行動電話</td>
			<td colspan="3" class="secret"><?php echo $relaCellArr[$key]; ?><img src="../images/secret.png"></td>
		</tr>
		<?php 
			} 
		}else{
		?>
		<tr>
			<td colspan="2" class="center">親屬姓名</td>
			<td colspan="2"></td>
			<td colspan="2" class="center">關係</td>
			<td colspan="2"></td>
			<td colspan="2" class="center">市內電話</td>
			<td colspan="5"></td>
			<td colspan="2" class="center">行動電話</td>
			<td colspan="3"></td>
		</tr>
		<?php 
		}
		?>
		<tr class="title-row">
			<td colspan="5" class="green-bg green-text">●連帶保證人</td>
		</tr>
		<tr>
			<td colspan="2" class="center">姓名</td>
			<td colspan="2"><?php echo $asusData[0]["assAppApplierName"]; ?></td>
			<td colspan="2" class="center">關係</td>
			<td colspan="3"><?php echo $asusData[0]["assAppApplierRelation"]; ?></td>
			<td colspan="2" class="center">身分證字號</td>
			<td colspan="9"><?php echo $asusData[0]["assAppApplierIdNum"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">出生日期</td>
			<td colspan="7"><?php echo $asusData[0]["assAppApplierBday"]; ?></td>
			<td colspan="2" class="center">戶籍電話</td>
			<td colspan="9"><?php echo $asusData[0]["assAppApplierBirthPhone"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">現住地址</td>
			<td colspan="13"><?php echo $asusData[0]["assAppApplierCurAddr"]; ?></td>
			<td colspan="2" class="center">現住電話</td>
			<td colspan="3"><?php echo $asusData[0]["assAppApplierCurPhone"]; ?></td>
		</tr>
		<tr>
			<td style="max-width:100px;" colspan="2" class="center">公司名稱</td>
			<td colspan="7"><?php echo $asusData[0]["assAppApplierCompanyName"]; ?></td>
			<td colspan="2" class="center">公司電話</td>
			<td colspan="4"><?php echo $asusData[0]["assAppApplierCompanyPhone"]; ?></td>
			<td colspan="2" class="center">行動電話</td>
			<td colspan="3"><?php echo $asusData[0]["assAppApplierCell"]; ?></td>
		</tr>
		<tr class="provement-spacing">
			<td colspan="20"> </td>
		</tr>
		<tr>
			<?php
				$year = explode('-',$memData[0]['memBday']);
				$dault = $year['0']+1911;
				$didf = (time()-strtotime($dault."-".$year[1]."-".$year[2]))/(86400*365);
				if($didf < 20){
 			?>
					<input type="checkbox" checked disabled>我已徵求父母或法定代理人同意分期購買此商品<br>
 			<?php
				}
 			?>
			<?php 
				if($orData[0]["mcoIfSecret"] == 1){ 
			?>
					<input type="checkbox" checked disabled>申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）
			<?php 
				} 
			?>
			</td>
		</tr>
		<tr>
			<td rowspan="2" colspan="2" class="center">貸款內容</td>
			<td colspan="6" class="center">手機廠牌</td>
			<td colspan="2" class="center">手機型號</td>
			<td colspan="2" class="center">分期總金額</td>
			<td colspan="4" class="center">期數</td>
			<td colspan="2" class="center">每期期金</td>
		</tr>
		<tr>
			<td style="max-width:100px;" colspan="6"><?php echo $orData[0]["mcoCellBrand"]; ?></td>
			<td colspan="2" class="center no-bold"><?php echo $orData[0]["mcoCellphoneSpec"]; ?></td>
			<td colspan="2" class="center no-bold"><?php echo  number_format($orData[0]["mcoPeriodTotal"]); ?></td>
			<td colspan="4" class="center no-bold"><?php echo $orData[0]["mcoPeriodAmount"]; ?></td>
			<td colspan="2" class="center no-bold"><?php echo $orData[0]["mcoMinMonthlyTotal"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">資金用途</td>
			<td colspan="16"><?php echo $orData[0]["mcoApplyPurpose"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">可照會時間</td>
			<td colspan="16"><?php echo $orData[0]["mcoAvailTime"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">注意事項</td>
			<td colspan="16"><?php echo $orData[0]["mcoExtraInfo"]; ?></td>
		</tr>
	</table>
</div>

<?php if(!empty($orData[0]["mcoIdImgTop"])){ ?>
<div class="page">
	<h2>申請人身分證正面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoIdImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoIdImgBot"])){ ?>
<div class="page">
	<h2>申請人身分證反面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoIdImgBot"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoStudentIdImgTop"]) && $memData[0]["memClass"] != "4"){ ?>
<div class="page">
	<h2>申請人學生證正面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoStudentIdImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoStudentIdImgBot"]) && $memData[0]["memClass"] != "4"){ ?>
<div class="page">
	<h2>申請人學生證反面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoStudentIdImgBot"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoSubIdImgTop"])){ ?>
<div class="page">
	<h2>申請人健保卡照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoSubIdImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoCarIdImgTop"])){ ?>
<div class="page">
	<h2>申請人行照照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoCarIdImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoBankBookImgTop"])){ ?>
<div class="page">
	<h2>申請人存摺照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoBankBookImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["mcoRecentTransactionImgTop"])){ ?>
<div class="page">
	<h2>申請人近六個月薪轉照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["mcoRecentTransactionImgTop"]; ?>">
</div>
<?php } ?>






<?php if(!empty($orAppAuthenExtraInfoArr)){ ?>
<div class="page">
	<h2>申請人補件資料照片</h2>
	<?php 
	if(!empty($orAppAuthenExtraInfoArr)){ 
		foreach($orAppAuthenExtraInfoArr as $key=>$value){
	?>
	<img class="id-pic" src="<?php echo "../../".$value; ?>">
	<?php 
		}
	}
	?>
</div>
<?php } ?>

<script>
$(function(){
	<?php if($orData[0]["mcoIfSecret"] == 1){ ?>
	$(".secret img").show();
	<?php } ?>
	$(window).load(function(){
		$(".id-pic").each(function(){
			if($(this).width()>$(this).height()){
				$(this).addClass("wide-id");
			}else{
				$(this).addClass("high-id");
			}
		});
	});
});
</script>
</body>
</html>