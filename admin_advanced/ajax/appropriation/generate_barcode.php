<?php
date_default_timezone_set('Asia/Taipei');

barcode();

/**
 * 判斷撥款銀行並回傳超商條碼
 */
function barcode(){
	switch($_POST["bank"]){
		case "CMC":
			echo cmc();
			break;
		case "21數位":
			echo _21();
			break;
		case "21數位小額支付":
			echo _21pay();
			break;
		default:
			echo penalty();     //滯納金
	}
}
/**
 * 產生CMC超商條碼，並轉成JSON回傳。
 * @return string
 */
function cmc(){
	$_ShopCode1 = ShopCode1($_POST["bank"],$_POST["MonthlyPayment"],$_POST["ExpectedRepaymentDate"]);

	$_ShopCode2 = $_POST["shopCode2"];

	$_ShopCode3 = ShopCode3($_POST["ExpectedRepaymentDate"],$_POST["MonthlyPayment"],$_ShopCode1,$_ShopCode2);

	$arr = array("ShopCode1" => $_ShopCode1, "ShopCode2" => $_ShopCode2, "ShopCode3" => $_ShopCode3);

	return json_encode($arr);
}
/**
 * 產生21數位超商條碼，並轉成JSON回傳。
 * @return string
 */
function _21(){
	$_ShopCode1 = ShopCode1($_POST["bank"],$_POST["MonthlyPayment"],$_POST["ExpectedRepaymentDate"]);

	$_ShopCode2 = ShopCode2($_POST["bank"],$_POST["ID"]);

	$_ShopCode3 = ShopCode3($_POST["ExpectedRepaymentDate"],$_POST["MonthlyPayment"],$_ShopCode1,$_ShopCode2);

	$arr = array("ShopCode1" => $_ShopCode1, "ShopCode2" => $_ShopCode2, "ShopCode3" => $_ShopCode3);

	return json_encode($arr);
}
/**
 * 產生21數位小額支付超商條碼，並轉成JSON回傳。
 * @return string
 */
function _21pay(){
	$_ShopCode1 = ShopCode1($_POST["bank"],$_POST["MonthlyPayment"],$_POST["ExpectedRepaymentDate"]);

	$_ShopCode2 = ShopCode2($_POST["bank"],$_POST["ID"]);

	$_ShopCode3 = ShopCode3($_POST["ExpectedRepaymentDate"],$_POST["MonthlyPayment"],$_ShopCode1,$_ShopCode2);

	$arr = array("ShopCode1" => $_ShopCode1, "ShopCode2" => $_ShopCode2, "ShopCode3" => $_ShopCode3);

	return json_encode($arr);
}
/**
 * 產生CMC滯納金超商條碼，並轉成JSON回傳。
 * @return string
 */
function penalty(){
	$_ShopCode1 = ShopCode1($_POST["bank"],$_POST["MonthlyPayment"],$_POST["ExpectedRepaymentDate"]);

	$_ShopCode2 = ShopCode2($_POST["bank"],$_POST["ID"]);

	$_ShopCode3 = ShopCode3($_POST["ExpectedRepaymentDate"],$_POST["MonthlyPayment"],$_ShopCode1,$_ShopCode2);

	$arr = array("ShopCode1" => $_ShopCode1, "ShopCode2" => $_ShopCode2, "ShopCode3" => $_ShopCode3);

	return json_encode($arr);
}

/**
 * 傳入撥款銀行、應繳金額、應繳款日，回傳第一段超商條碼。
 * string $bank 撥款銀行
 * int $money 應繳金額
 * date $date 應繳款日
 * return string 第一段超商條碼
 * @param string $bank
 * @param int $money
 * @param string $date
 * @return string
 */
function ShopCode1($bank,$money,$date){
	//用switch免得以後又要加金流還要再改，應繳金額是因為原本合庫可以繳到6萬所以需要帶入，未來也許還會用到合庫。
	switch($_POST["bank"]){
		case "CMC":
			$date = yearToROC4($date);
			return substr($date,2).$_POST["shopCode1Later3"];
			break;
		case "21數位":
			$date = yearToROC4($date);
			return substr($date,2)."6JM";
			break;
		case "21數位小額支付":
			$date = yearToROC4($date);
			return substr($date,2)."6JM";
			break;
		default:
			$date = yearToROC4($date);      //滯納金用彰銀收款，目前只有CMC案件會用到。
			return substr($date,2)."6JM";
	}
	//下面是原本合庫規則
	//if($bank == "CMC"){
	//    $date = yearToROC4($date);
	//    return substr($date,2).$_POST["shopCode1Later3"];
	//}else{
	//    if($money <= 20000){
	//        $date = yearToROC4($date);
	//        return substr($date,2)."6IF";
	//    }
	//    else if($money >20000 && $money <= 40000){
	//        $date = yearToROC4($date);
	//        return substr($date,2)."6IH";
	//    }
	//    else if($money >40000 && $money <= 60000){
	//        $date = yearToROC4($date);
	//        return substr($date,2)."6IB";
	//    }
	//}
}

/**
 * 傳入銀行、案件編號或著會員編號，產生第二段超商條碼
 * string $bank 銀行
 * string $id 會員編號&案件編號
 * return string 第二段超商條碼
 * @param string $bank
 * @param string $id
 * @return string
 */
function ShopCode2($bank,$id){
	switch($bank){
		case "21數位":
			return "540001".substr($id,2);      //彰銀虛擬帳號為案件編號12碼截去前2碼201612130044改為1612130044
			break;
		case "21數位小額支付":
			return "540001".str_pad($id,10,"0",STR_PAD_LEFT);       //小額支付使用會員編號左邊補0到10碼。
			break;
		default:
			return "540002".substr($id,2);      //滯納金用彰銀收款，目前只有CMC案件用到。
	}

	//原合庫規則
	//if($bank == "21數位"){
	//    return "B997".idToInt($id);
	//}else{
	//    return "B998".$id;  //滯納金
	//}
}

/**
 * 傳入應繳款日期、應繳金額、第一段超商條碼、第二段超商條碼，產生第三段超商條碼。
 * string $ExpectedRepaymentDate    應繳款日期
 * string $MonthlyPayment   應繳金額
 * string $_ShopCode1   第一段超商條碼
 * string $_ShopCode2  第二段超商條碼
 * return string    第三段超商條碼
 * @param string $ExpectedRepaymentDate
 * @param string $MonthlyPayment
 * @param string $_ShopCode1
 * @param string $_ShopCode2
 * @return string 第三段超商條碼
 */
function ShopCode3($ExpectedRepaymentDate,$MonthlyPayment,$_ShopCode1,$_ShopCode2){

	$strExpectedRepaymentDate = substr($ExpectedRepaymentDate,4);   //擷取應繳款日期的月日

	$MonthlyPayment = str_pad($MonthlyPayment,9,"0",STR_PAD_LEFT);  //應繳金額靠左補0到9個字元

	return $strExpectedRepaymentDate.getCheckCode($_ShopCode1, $_ShopCode2, $strExpectedRepaymentDate, $MonthlyPayment).$MonthlyPayment;
}

/**
 * 傳入西元年轉換成民國年後+4年，格式"20161027"
 * string $date 日期
 * return string 民國年
 * @param string $date
 * @return string
 */
function yearToROC4($date){

	$date = new DateTime($date);

	//轉成民國年(-1911)後在加4年為繳費截止日
	$date->modify("-1907 year");

	return $date->format('Ymd');
}
/**
 * 身份證字號第一位英文轉成數字，並靠左補0補滿12個字元。(原本合庫用身份證字號當虛擬帳號，目前沒用)
 * string $id   身份證字號
 * return string
 * @param string $id
 * @return string
 */
function idToInt($id){

	$strArr = array(array('A','01'),array('B','02'),array('C','03'),array('D','04'),
			array('E','05'),array('F','06'),array('G','07'),array('H','08'),array('I','09'),
			array('J','10'),array('K','11'),array('L','12'),array('M','13'),array('N','14'),
			array('O','15'),array('P','16'),array('Q','17'),array('R','18'),array('S','19'),
			array('T','20'),array('U','21'),array('V','22'),array('W','23'),array('X','24'),
			array('Y','25'),array('Z','26'));

	foreach($strArr as $value){

		if(strtoupper(substr($id,0,1)) == $value[0]) $id = $value[1].substr($id,1);
	}
	//靠左補0補滿12個字元
	return str_pad($id,12,"0",STR_PAD_LEFT);
}

/**
 * 傳入第一段超商條碼、第二段超商條碼、繳費期限月日、9碼繳費金額，產稱第三段條碼檢查碼。
 * string $_ShopCode1   第一段超商條碼
 * string $_ShopCode2   第二段超商條碼
 * string $_strExpectedRepaymentDate    繳費期限月日
 * string $_MonthlyPayment  9碼繳費金額
 * return string    2碼檢查碼
 * @param string $_ShopCode1
 * @param string $_ShopCode2
 * @param string $_strExpectedRepaymentDate
 * @param string $_MonthlyPayment
 * @return string
 */
function getCheckCode($_ShopCode1, $_ShopCode2, $_strExpectedRepaymentDate, $_MonthlyPayment){

	$strValue = "";     //檢查碼

	$iOdd = 0;  //奇數

	$iEven = 0; //偶數

	//將第一段超商條碼每個字元轉換成數字並按奇數與偶數加總
	for($i = 0;$i < strlen($_ShopCode1);$i++){
		if(($i%2) == 0){
			$iOdd += substrToNumber($i,$_ShopCode1);
		}else{
			$iEven += substrToNumber($i,$_ShopCode1);
		}
	}

	//將第二段超商條碼每個字元轉換成數字並按奇數與偶數加總
	for($i = 0;$i < strlen($_ShopCode2);$i++){
		if(($i%2) == 0){
			$iOdd += substrToNumber($i,$_ShopCode2);
		}else{
			$iEven += substrToNumber($i,$_ShopCode2);
		}
	}

	//將應繳款月日每個字元轉換成數字並按奇數與偶數加總
	for($i = 0;$i < strlen($_strExpectedRepaymentDate);$i++){
		if(($i%2) == 0){
			$iOdd += substrToNumber($i,$_strExpectedRepaymentDate);
		}else{
			$iEven += substrToNumber($i,$_strExpectedRepaymentDate);
		}
	}

	//將應繳金額(9碼)每個字元轉換成數字並按奇數與偶數加總
	for($i = 0;$i < strlen($_MonthlyPayment);$i++){
		if(($i%2) == 0){
			$iOdd += substrToNumber($i,$_MonthlyPayment);
		}else{
			$iEven += substrToNumber($i,$_MonthlyPayment);
		}
	}

	//奇數總和除11餘數0為A，餘數10為B其他為餘數
	switch ($iOdd % 11){
		case 0:
			$strValue = "A";
			break;
		case 10:
			$strValue = "B";
			break;
		default:
			$strValue = ($iOdd % 11);
			break;
	}

	//偶數總和除11餘數0為X，餘數10為Y其他為餘數
	switch ($iEven % 11){
		case 0:
			$strValue .= "X";
			break;
		case 10:
			$strValue .= "Y";
			break;
		default:
			$strValue .= ($iEven % 11);
			break;
	}

	return $strValue;
}

/**
 * 將字串指定字元轉換成數字
 *int $i 第幾個字元
 *string $_str 傳入的字串
 *int 轉換後的數字
 * @param int $i 第幾個字元
 * @param string $_str 傳入的字串
 * @return int 轉換後的數字
 */
function substrToNumber($i,$_str){

	$_int = convertNumber(substr($_str,$i,1));

	return (int)$_int;
}

/**
 * 將傳入的特定字元轉換為數字
 * string $str  傳入的字元
 * return string    轉換成的數字
 * @param string $str
 * @return string
 */
function convertNumber($str){

	$strArr = array(array("A", "1"),array("B", "2"),array("C", "3"),array("D", "4"),
			array("E", "5"),array("F", "6"),array("G", "7"),array("H", "8"),array("I", "9"),
			array("J", "1"),array("K", "2"),array("L", "3"),array("M", "4"),array("N", "5"),
			array("O", "6"),array("P", "7"),array("Q", "8"),array("R", "9"),array("S", "2"),
			array("T", "3"),array("U", "4"),array("V", "5"),array("W", "6"),array("X", "7"),
			array("Y", "8"),array("Z", "9"),array("+", "1"),array("%", "2"),array("-", "6"),
			array(".", "7"),array(" ", "8"),array("$", "9"),array("/", "0"));

	foreach($strArr as $value){

		if(strtoupper($str) == $value[0]) $str = $value[1];
	}

	return $str;
}
?>