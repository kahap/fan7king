<?php 
session_start();

function __autoload($ClassName){
	include_once('../cls/'.$ClassName.".cls.php");
}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$or = new Orders();
$sr = new Status_Record();
$pm = new Product_Manage();
$pro = new Product();
$sup = new Supplier();
$mem = new Member();

$orNo = $_GET["orno"];

$orData = $or->getOneOrderByNo($orNo);
$supData = $sup->getOneSupplierByNo($orData[0]["supNo"]);
$pmData = $pm->getOnePMByNo($orData[0]["pmNo"]);
$memData = $mem->getOneMemberByNo($orData[0]["memNo"]);
$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
if($memData[0]["memRecommCode"] != ""){
	$memRecData = $mem->getOneMemberByNo($memData[0]["memRecommCode"]);
}else{
	$memRecData = null;
}
	
	$sql = "SELECT * FROM `assure` where rcNo = '".$_GET['rcNo']."'";
	$asusData = $or->getSql($sql);


//更改處理狀態
if($orData[0]["orIfProcessInCurrentStatus"] == 0){
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d H:i:s', time());
	$or->updateIfProcess(1, $orNo);
	$or->updateProcessTime($date, $orNo);
}
if($_GET['aauNoService'] != ""){
	$or->addservice_record($_GET['rcNo'], $_GET['aauNoService']);
}
//訂單日期
$orDateNoTime = explode(" ",$orData[0]["orDate"]);
$orDateArr = explode("-", $orDateNoTime[0]);

//信用卡日期
$orCardExpDateArr = explode("/", $orData[0]["orAppApplierCreditDueDate"]);

//聯絡人陣列
$relaNameArr = is_array(json_decode($orData[0]["orAppContactRelaName"])) ? json_decode($orData[0]["orAppContactRelaName"]) : "";
$relaRelationArr = is_array(json_decode($orData[0]["orAppContactRelaRelation"])) ? json_decode($orData[0]["orAppContactRelaRelation"]) : "";
$relaPhoneArr = is_array(json_decode($orData[0]["orAppContactRelaPhone"])) ? json_decode($orData[0]["orAppContactRelaPhone"]) : "";
$relaCellArr = is_array(json_decode($orData[0]["orAppContactRelaCell"])) ? json_decode($orData[0]["orAppContactRelaCell"]) : "";

$frdNameArr = is_array(json_decode($orData[0]["orAppContactFrdName"])) ? json_decode($orData[0]["orAppContactFrdName"]) : "";
$frdRelationArr = is_array(json_decode($orData[0]["orAppContactFrdRelation"])) ? json_decode($orData[0]["orAppContactFrdRelation"]) : "";
$frdPhoneArr = is_array(json_decode($orData[0]["orAppContactFrdPhone"])) ? json_decode($orData[0]["orAppContactFrdPhone"]) : "";
$frdCellArr = is_array(json_decode($orData[0]["orAppContactFrdCell"])) ? json_decode($orData[0]["orAppContactFrdCell"]) : "";

$mem->changeToReadable($memData[0]);

//價錢轉換成中文
$totalArr = array_reverse(str_split($orData[0]["orPeriodTotal"]));
foreach($totalArr as $key=>$value){
	$chineseChar = "";
	switch($value){
		case 0:
			$chineseChar = "零";
			break;
		case 1:
			$chineseChar = "壹";
			break;
		case 2:
			$chineseChar = "貳";
			break;
		case 3:
			$chineseChar = "參";
			break;
		case 4:
			$chineseChar = "肆";
			break;
		case 5:
			$chineseChar = "伍";
			break;
		case 6:
			$chineseChar = "陸";
			break;
		case 7:
			$chineseChar = "柒";
			break;
		case 8:
			$chineseChar = "捌";
			break;
		case 9:
			$chineseChar = "玖";
			break;
	}
	switch($key){
		case 0:
			$sing = $chineseChar;
			break;
		case 1:
			$ten = $chineseChar;
			break;
		case 2:
			$hund = $chineseChar;
			break;
		case 3:
			$thou = $chineseChar;
			break;
		case 4:
			$tenThou = $chineseChar;
			break;
		case 5:
			$hundThou = $chineseChar;
			break;
	}
}
if(!isset($hundThou)){
	$hundThou = "零";
}
if(!isset($tenThou)){
	$tenThou = "零";
}
if(!isset($thou)){
	$thou = "零";
}
if(!isset($hund)){
	$hund = "零";
}
if(!isset($ten)){
	$ten = "零";
}
if(!isset($sing)){
	$sing = "零";
}

//補件資料陣列
$orAppAuthenExtraInfoArr = json_decode($orData[0]["orAppAuthenExtraInfo"]);
$memberClass_array = array('學生'=>'學生','1'=>'上班族','2'=>'家管','3'=>'其他','4'=>'非學生','無'=>'無');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>樂分期管理後台</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
</head>
<style>
* {
    -webkit-text-size-adjust: none;
}
.page{
	width:842px;
	height:1230px;
	margin:0 auto;
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
			<td colspan="17"><img id="logo" src="../images/21cent-logo.png">分期付款申請書暨約定書</td>
			<td class="comp-info" colspan="3">
				廿一世紀數位科技有限公司<br>
				Twenty-One Century Digital<br>
				服務電話：(02)7721-2177<br>
				送件傳真：(02))2765-1011
			</td>
		</tr>
		<tr class="title-row">
			<td colspan="5" class="blue-bg blue-text">●申請人基本資料</td>
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
			<td colspan="13"><?php echo $orData[0]["orAppApplierBirthAddr"]; ?></td>
			<td colspan="2" class="center">戶籍電話</td>
			<td colspan="3" class="secret"><?php echo $orData[0]["orAppApplierBirthPhone"]; ?><img src="../images/secret.png"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">現住地址</td>
			<td colspan="13"><?php echo $memData[0]["memAddr"]; ?></td>
			<td colspan="2" class="center">現住電話</td>
			<td colspan="3" class="secret"><?php echo $memData[0]["memPhone"]; ?><img src="../images/secret.png"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">住房所有權</td>
			<td colspan="18"><?php echo $orData[0]["orAppApplierLivingOwnership"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">公司名稱</td>
			<td style="max-width:100px;" colspan="7"><?php echo $orData[0]["orAppApplierCompanyName"] == "" ? $memData[0]["memCompanyName"] : $orData[0]["orAppApplierCompanyName"]; ?></td>
			<td colspan="1" class="center">年資</td>
			<td colspan="2"><?php echo $orData[0]["orAppApplierYearExperience"] == "" ? $memData[0]["memSalary"] : $orData[0]["orAppApplierYearExperience"]; ?></td>
			<td colspan="1" class="center">月薪</td>
			<td colspan="2"><?php echo $orData[0]["orAppApplierMonthSalary"] == "" ? $memData[0]["memYearWorked"] : $orData[0]["orAppApplierMonthSalary"]; ?></td>
			<td colspan="2" class="center">公司電話</td>
			<td colspan="3" style="max-width:1px;" class="secret"><?php echo $orData[0]["orAppApplierCompanyPhone"]; ?> 分機 <?php echo $orData[0]["orAppApplierCompanyPhoneExt"]; ?> <img src="../images/secret.png"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">帳單地址</td>
			<td colspan="18"><?php echo $orData[0]["orBillAddr"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">信用卡卡號</td>
			<td colspan="6">
			<?php 
			if(strrpos($orData[0]["orAppApplierCreditNum"], "--") === false){
				echo $orData[0]["orAppApplierCreditNum"]; 
			}
			?>
			</td>
			<td colspan="2" class="center">發卡銀行</td>
			<td colspan="5"><?php echo $orData[0]["orAppApplierCreditIssueBank"]; ?></td>
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
			$total = count($relaNameArr);
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
		<?php 
		if(!empty($frdNameArr)){
			$total = count($frdNameArr);
			foreach($frdNameArr as $key=>$value){
		?>
		<tr>
			<td colspan="2" class="center">朋友姓名</td>
			<td colspan="2"><?php echo $value; ?></td>
			<td colspan="2" class="center">關係</td>
			<td colspan="2"><?php echo $frdRelationArr[$key]; ?></td>
			<td colspan="2" class="center">市內電話</td>
			<td colspan="5" class="secret"><?php echo $frdPhoneArr[$key]; ?><img src="../images/secret.png"></td>
			<td colspan="2" class="center">行動電話</td>
			<td colspan="3" class="secret"><?php echo $frdCellArr[$key]; ?><img src="../images/secret.png"></td>
		</tr>
		<?php
			}
		}else{
		?>
		<tr>
			<td colspan="2" class="center">朋友姓名</td>
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
			<td colspan="1" class="center">本票</td>
			<td colspan="19" style="position:relative;">
				憑票於中華民國_____年_____月_____日無條件支付廿一世紀數位科技有限公司或指定人<br><br>
				新台幣　<span style="color:red;"><?php echo $hundThou; ?></span>　拾　<span style="color:red;"><?php echo $tenThou; ?></span>　萬　<span style="color:red;"><?php echo $thou; ?></span>　仟　<span style="color:red;"><?php echo $hund; ?></span>　佰　<span style="color:red;"><?php echo $ten; ?></span>　拾　<span style="color:red;"><?php echo $sing; ?></span>　元整<br>
				此本票免除作成拒絕證書及票據法第八十九條之通知義務，利息自到期日迄清償日止按年利率百分之二十計付，發票人授權持票人得填載到期日。<br>
				付款地：台北市信義區基隆路一段163號2樓之2<br>
				此據<br><br>
				中華民國　<?php echo $orDateArr[0]-1911;?>　年　<?php echo $orDateArr[1];?>　月　<?php echo $orDateArr[2];?>　日
				<div class="assure-signature">
					<div>
						<span>發票人：</span>
						<div class="blue-bg">
							<img src="<?php echo "../../".$orData[0]["orAppAuthenProvement"]; ?>">
						</div>
					</div>
					<div>
						<span>發票人：</span>
						<div class="green-bg">
							
						</div>
					</div>
				</div>
			</td>
		</tr>
		<tr class="provement-spacing">
			<td colspan="20">「此本票係供為分期付款買賣之分期付總額憑證，俟分期付款完全清償完畢時，此本票自動失效，但如有一期未付，發票人願意就全部本票債務負責清償。」<br>本人同意依法令規定應以書面為之者，得以電子文件為之依法令規定應簽名或蓋章，得以電子簽章為之。</td>
		</tr>
		<tr class="title-row">
			<td colspan="5" class="yellow-bg yellow-text">●分期付款約定事項&nbsp&nbsp&nbsp&nbsp</td><td colspan="15"><b style="color:#F00">*分期付款期間未繳清以前禁止出售或典當，以免觸法</b></td>
		</tr>
		<tr>
			<td colspan="20">一、申請人(即買方)及其連帶保證人向商品經銷商(即賣方)以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人對本條約<u style="color:red;">所有條款</u>均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守<a href="../../?item=fmPeriodDeclare" target="_blank">「分期付款約定書」</a>之各項約定條款。二、申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期金額之權利及依本約定書約定所有之其他一切權利及利益轉讓與<span class="blue-text">廿一世紀數位科技有限公司</span>及其帳款收買人，受讓人對於分期付款買賣案件擁有核准與否同意權，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商指定銀行帳戶，相關手續費金額之約定則按商品經銷商與<span class="blue-text">廿一世紀數位科技有限公司</span>所簽訂相關之合約約定之，申請人及其連帶保證人絕無異議。三、申請人（即買方）及其連帶保證人聲明確實填寫及簽訂本「分期付款申請書暨約定書」內容，且交付商品經銷商之任何文件中並無不實之陳述或說明之情事。
			<br><input type="checkbox" checked disabled>我已詳細閱讀並同意以上條款及<a href="../../?item=fmPeriodDeclare" target="_blank">「分期付款約定書」</a>之內容及所有條款
			<br><input type="checkbox" checked disabled>我已詳細閱讀並同意<a href="../../?item=fmFreeRespons" target="_blank">免責聲明</a>、<a href="../../?item=fmServiceRules" target="_blank">服務條款</a>、<a href="../../?item=fmPrivacy" target="_blank">隱私權聲明</a>等條款<br>
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
				if($orData[0]["orIfSecret"] == 1){ 
			?>
					<input type="checkbox" checked disabled>申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）
			<?php 
				} 
			?>
			</td>
		</tr>
		<tr>
			<td colspan="10" style="width:50%;">
				<div class="promise-signature-area">
					<span class="title">申請人中文正楷簽名：</span>
					<div class="blue-bg">
						<img src="<?php echo "../../".$orData[0]["orAppAuthenPromiseLetter"]; ?>">
					</div>
					<span class="promise-date"><?php echo $orDateArr[0];?> 年 <?php echo $orDateArr[1];?> 月 <?php echo $orDateArr[2];?> 日</span>
				</div>
			</td>
			<td colspan="10" style="width:50%;">
				<div class="promise-signature-area">
					<span class="title">連帶保證人中文正楷簽名：</span>
					<div class="green-bg">
					
					</div>
					<span class="promise-date"><?php echo $orDateArr[0];?> 年 <?php echo $orDateArr[1];?> 月 <?php echo $orDateArr[2];?> 日</span>
				</div>
			</td>
		</tr>
		<tr class="title-row">
			<td colspan="5" class="green-bg green-text">●分期付款買賣契約書</td>
		</tr>
		<tr>
			<td rowspan="2" colspan="2" class="center">付款內容</td>
			<td colspan="6" class="center">商品名稱/型號</td>
			<td colspan="2" class="center">規格</td>
			<td colspan="2" class="center">數量</td>
			<td colspan="4" class="center">分期總金額</td>
			<td colspan="2" class="center">期數</td>
			<td colspan="2" class="center">每期應繳金額</td>
		</tr>
		<tr>
			<td style="max-width:100px;" colspan="6"><?php echo $proData[0]["proName"]; ?></td>
			<td colspan="2" class="center no-bold"><?php echo ($orData[0]["orProSpec"] == "") ? "無":$orData[0]["orProSpec"]; ?></td>
			<td colspan="2" class="center no-bold"><?php echo $orData[0]["orAmount"]; ?></td>
			<td colspan="4" class="center no-bold"><?php echo number_format($orData[0]["orPeriodTotal"]); ?></td>
			<td colspan="2" class="center no-bold"><?php echo $orData[0]["orPeriodAmnt"]; ?></td>
			<td colspan="2" class="center no-bold"><?php echo number_format($orData[0]["orPeriodTotal"]/$orData[0]["orPeriodAmnt"]); ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">經銷商名稱</td>
			<td colspan="14"><?php echo $supData[0]["supName"]; ?></td>
			<td rowspan="5" colspan="4"><img class="supplier-stamp" src="<?php echo "../".$supData[0]["supStampImg"]; ?>"></td>
		</tr>
		<tr>
			<td colspan="2" class="center">經銷商電話</td>
			<td colspan="7"><?php echo $supData[0]["supPhone"]; ?></td>
			<td colspan="2" class="center">經銷商傳真</td>
			<td colspan="5" class="center no-bold"><?php echo $supData[0]["supFax"]; ?></td>
		</tr>
		<tr>
			<td rowspan="3" colspan="2" class="center">備註</td>
			<td colspan="2" class="center">可照會時間</td>
			<td colspan="12"><?php echo $orData[0]["orAppExtraAvailTime"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">注意事項</td>
			<td colspan="12"><?php echo $orData[0]["orAppExtraInfo"]; ?></td>
		</tr>
		<tr>
			<td colspan="2" class="center">推薦人</td>
			<td colspan="5">
			<?php 
				if($memRecData != null){
					echo $memRecData[0]["memName"];
				}else{
					echo "無";
				}
			?>
			</td>
			<td colspan="2" class="center">供應價</td>
			<td colspan="5" class="center no-bold"><?php echo number_format($orData[0]["orSupPrice"]); ?></td>
		</tr>
	</table>
</div>

<div class="page">
	<h2 style="text-align:center;">分　期　付　款　約　定　書</h2>
	<p>申請人（即買方）及其連帶保證人向商品經銷商（即賣方）以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人於本契約所有條款均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守約定條款如下:</p>
	<p>第一條：申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期價款之權利及依本約定書約定所有之其他一切權利及利益轉讓與廿一世紀數位科技有限公司及其帳款收買人，帳款受讓人廿一世紀數位科技有限公司對於分期付款買賣案件擁有核准與否同意權，並無異議帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商及帳款受讓人廿一世紀數位科技有限公司指定銀行帳戶，以作為收買本分期付款之應收帳款債權，相關手續費金額之約定則按商品經銷商與廿一世紀數位科技有限公司所簽訂相關之合約約定之。申請人及其連帶保證人理解並同意分期付款價款應依約定繳付予帳款受讓人或收買人之指定繳款帳戶。申請人及其連帶保證人對上開分期價款與撥款日期悉數承認，絕不以申請人及其連帶保證人與商品經銷商間之法律關係存在與否，或其他任何紛爭等事由對抗帳款受讓人廿一世紀數位科技有限公司或帳款收買人。</p>
	<p>第二條：申請人及其連帶保證人向商品經銷商購買前開「分期付款申請書」所載消費性商品分期內容，即商品名稱、商品總價、分期金額、期數及每期應繳金額，均依前開「分期付款申請書」所載表示。另有關消費性商品標的物之商品規格、品質及數量等，均依商品經銷商所提供之品質保證書或出（交）貨憑證所載。</p>
	<p>第三條：申請人及其連帶保證人對前開消費性商品同意依「分期付款申請書暨約定書」承買，於契約成立後，消費性商品雖「借名登記」於申請人名下，實際所有權仍歸廿一世紀數位科技有限公司所有，申請人及其連帶保證人僅得先行占有消費性商品；全部分期價款及本契約約定未完成履行清償前，申請人及其連帶保證人須依善良管理人注意義務僅為先行保管、占有使用，不得擅自將消費性商品遷移、讓與、移轉、質押、典當或其他處分，違反本條款將依刑法詐欺罪或侵占罪，追究相關刑事責任。</p>
	<p>第四條：申請人及其連帶保證人於交付消費性商品時應即驗收，如發現消費性商品有瑕疪時應即通知商品經銷商，如申請人及其連帶保證人怠為此通知者，視為承認所受領之物。消費性商品之危險自申請人及其連帶保證人占有消費性商品時起，由申請人及其連帶保證人自行承擔。</p>
	<p>第五條：申請人及其連帶保證人了解帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人非商品、服務之進口人、出售人或經銷人，與商品經銷商無任何代理、合夥、經銷關係，相關商品、服務之瑕疵擔保、保固、保證、售後服務或其他契約上之責任，概由商品經銷商負責。相關商品退貨或服務取消之終止事宜，申請人及其連帶保證人應先洽詢商品經銷商尋求解決，如無法達成解決共識者，而發生非訴訟或訴訟糾紛時，得檢具理由及證明文件通知帳款受讓人，未依前述方式通知帳款受讓人，則推定交易無誤。申請人及其連帶保證人並須於發生爭議期間，繼續依約繳付分期款項，不得逕行止付分期款項。</p>
	<p>第六條：申請人及其連帶保證人應於簽訂本約定書時，共同簽發面額與分期總價款相同的未載到期日之本票乙紙，交付帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人以擔保契約之履行，惟限於需要，到期日未予填載，申請人及其連帶保證人認知並同意帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人自行填載到期日及其他有效行使本票之權利所應記載之項，俾利行使票據上之權利。</p>
	<p>第七條：申請人及其連帶保證人應按前開契約所列日期及金額，分期支付同項所列之分期付款價款，申請人及其連帶保證人未按期支付期付款之任一期逾期繳款時，應自逾期之日按年利率百分之二十計付遲延利息及每期滯納金新台幣三百元，並喪失期限利益，全部分期債務視為到期，申請人及其連帶保證人應一次清償該筆未償分期餘額、利息、違約金或相關費用等總債權。</p>
	<p>第八條：申請人及其連帶保證人發生任何逾期清償或違反本契約情事時，除應加計給付前條所規定之遲延利息、滯納金外，申請人及其連帶保證人了解廿一世紀數位科技有限公司之風險及作業成本考量，同意支付依分期餘額百分之十計算之違約金。</p>
	<p>第九條：申請人及其連帶保證人如有延遲付款、退票、銀行拒往、信用貶落、不履行或怠於履行本契約之任一義務及規定；因其他債務關係而受假扣押、假處分、終局執行或其他公權力處分；進行重整、合併、清算、解散等程序或受破產宣告；死亡、失蹤或發生繼承而其繼承人聲明限定或拋棄繼承；於本契約或由申請人及其連帶保證人交由商品經銷商之任何文件(含所填載之顧客申購契約書)中有不實之陳述或說明之情事之一者，帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人除得依法律或契約約定行使權利外，所有未到期分期價款視為提前全部到期，應即清償，帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人亦得逕行向申請人及其連帶保證人及其連帶保證人取回本件消費性商品或逕行強制執行。</p>
	<p>第十條：帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人依契約取回消費性商品或聲請強制執行時，衍生費用由申請人及其連帶保證人負擔，消費性商品委賣、拍賣、或變賣所得之價金，應先充抵費用，次充利息，再充本金，如有剩餘，應返還申請人及其連帶保證人，如有不足，帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人得繼續向申請人及其連帶保證人追償。</p>
	<p>第十一條：連帶保證人茲無條件同意連帶保證申請人應完全履行本契約之各項義務，而與申請人負連帶債務關係，於申請人未依約履行時，連帶保證人應負責全部清償該筆分期餘額、利息、違約金或相關費用等總債權。並聲明拋棄民法第七百四十五條之先訴抗辯權。</p>
	<p>第十二條：申請人及其連帶保證人因名稱、組織、代表人及通知地址(營業所)等之變更或其他不關影響權益之變更，應立即以書面將變更事項通知帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人，如未通知則得將有關文書向本契約所載或其知悉之最後地址投郵寄送後，經通常之郵遞期間即視為已合法送達，另申請人及連帶保證人如未為通知致生糾葛或因而造成帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人損害時，概由申請人及其連帶保證人負責。</p>
	<p>第十三條：申請人及其連帶保證人同意帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人對申請人及其連帶保證人之徵信、授信及其他達成授信及催收等所取得之資料，提供予帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人得蒐集電腦處理及利用本人個人資料。申請人及連帶保證人另同意帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人得將其基本資料、帳務資料、信用資料、投資資料、保險資料等個人資料，提供揭露予所屬關係企業，供各該公司蒐集、電腦處理及為共同行銷利用，或提供予受帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人委任代為處理事務之人。</p>
	<p>第十四條：申請人及其連帶保證人在本契約有效期間內發生任何逾期清償或違約情事時，同意帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人或其他催收公司代為處理相關催收事宜，申請人及其連帶保證人與商品經銷商同意如逾期未清償本契約項下任何債務時，帳款受讓人或收買人得將本契約項下之相關權益，包括但不限於申請人及其連帶保證人未付之分期餘額、利息及違約金等債權及相關擔保權益，相關書面資料包括但不限於本票，分期契約與分期有關之任何文件轉讓予帳款受讓人廿一世紀數位科技有限公司或其指定人。</p>
	<p>第十五條：申請人及其連帶保證人取得所購買消費性商品時無異議之表示，即同意帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人得於商品經銷商備齊各項撥款文件，經審核無誤後，逕行將分期價款撥付予商品經銷商及帳款受讓人廿一世紀數位科技有限公司之指定銀行帳戶內，絕無異議。倘因此發生任何損失或事故，均由申請人及其連帶保證人自付全責，概與帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人無涉，本項撥款非經帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人之書面同意，不得持任何理由提出異議。</p>
	<p>第十六條：申請人及其連帶保證人與商品經銷商同意帳款受讓人廿一世紀數位科技有限公司得將請求申請人及其連帶保證人支付分期價款之權利，讓與帳款收買人，申請人及其連帶保證人仍受本契約之約束，茲確認已接受讓與之通知，且同意不得以其對商品經銷商之任何債權向帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人主張抵銷。關於因契約之所生之消費性商品瑕疵擔保、保固、保證、售後服務或其他契約上之責任，仍應由商品經銷商負責；帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人就商品標的物無任何明示或默示之承諾或保證，申請人及其連帶保證人應向商品經銷商請求履行此等責任與義務。</p>
	<p>第十七條：因本契約發生之爭訟時，雙方同意以帳款受讓人廿一世紀數位科技有限公司及其指定人或帳款收買人所在地之地方法院或台灣台北地方法院(包括其簡易庭)為第一審管轄法院，並適用中華民國法律。</p>
	<div class="clarification">
		<span>
		本公司同意將商品經銷商讓與之申請人及其連帶保證人，支付分期價款之權利及依本約定書約定所有之其他一切權利及利益讓與帳款收買人，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商及本公司指定銀行帳戶，以作為收買本分期付款之應收帳款債權，相關手續費金額之約定則按本公司與帳款收買人所簽訂相關之合約約定之。本公司同意與商品經銷商、申請人及其連帶保證人共同遵守「分期付款約定書」之各項約定條款。
		</span>
		<img src="../images/21cent-stamp.png">
	</div>
</div>
<?php if(!empty($orData[0]["orAppAuthenIdImgTop"])){ ?>
<div class="page">
	<h2>申請人身分證正面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["orAppAuthenIdImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["orAppAuthenIdImgBot"])){ ?>
<div class="page">
	<h2>申請人身分證反面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["orAppAuthenIdImgBot"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["orAppAuthenStudentIdImgTop"]) && $memData[0]["memClass"] != "4"){ ?>
<div class="page">
	<h2>申請人學生證正面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["orAppAuthenStudentIdImgTop"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orData[0]["orAppAuthenStudentIdImgBot"]) && $memData[0]["memClass"] != "4"){ ?>
<div class="page">
	<h2>申請人學生證反面照片</h2>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["orAppAuthenStudentIdImgBot"]; ?>">
</div>
<?php } ?>
<?php if(!empty($orAppAuthenExtraInfoArr) || (!empty($orData[0]["orAppAuthenExtraInfo"]) && $orData[0]["orAppAuthenExtraInfo"] != "[]")){ ?>
<div class="page">
	<h2>申請人補件資料照片</h2>
	<?php 
	if(!empty($orAppAuthenExtraInfoArr)){ 
		foreach($orAppAuthenExtraInfoArr as $key=>$value){
	?>
	<img class="id-pic" src="<?php echo "../../".$value; ?>">
	<?php 
		}
	}else{
		if(!empty($orData[0]["orAppAuthenExtraInfo"])){
	?>
	<img class="id-pic" src="<?php echo "../../".$orData[0]["orAppAuthenExtraInfo"]; ?>">
	<?php 
		}
	}
	?>
</div>
<?php } ?>

<script>
$(function(){
	<?php if($orData[0]["orIfSecret"] == 1){ ?>
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