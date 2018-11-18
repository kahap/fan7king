<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();

date_default_timezone_set('Asia/Taipei');

$rc = new API("real_cases");
$tb = new API("transfer_bank");
$mem = new API("member");
$bar = new API("barcode");
$tpi = new API("tpi");
$sup = new API("supplier");

if(isset($_POST["rcNo"])){
	foreach($_POST["rcNo"] as $rcNo){
		//先確認是否已經產了本息表
		$tpi->setWhereArray(array("rcNo"=>$rcNo));
		$tpiData = $tpi->getWithConditions();
		if($tpiData == null){
			$rcData = $rc->getOne($rcNo);
			$tbData = $tb->getOne($rcData[0]["tbNo"]);
			$memData = $mem->getOne($rcData[0]["memNo"]);
			$supData = $sup->getOne($rcData[0]["supNo"]);
			//如果是CMC的話先建立撥款檔
			if($tbData[0]["tbName"] == "CMC"){
				switch($rcData[0]["rcType"]){
					case "0":
						$productType = "3C家電";
						break;
					case "1":
						$productType = "手機分期";
						break;
					default:
						$productType = "機車再分期";
						break;
				}
				if($rcData[0]["rcType"] > 0){
					$moto = new API("motorbike_cellphone_orders");
					$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
					$motoData = $moto->getWithConditions();
				}
			
				//建立撥款檔
				$str = "<?xml version='1.0' encoding='utf-8' standalone='yes'?>\n";
				$str .= "<applyRec>\n";
				$str .= "\t<dlrNo>TA21</dlrNo>\n";
				$str .= "\t<productType>".$productType."</productType>\n";
				$str .= "\t<caseNo>".substr($rcData[0]["rcCaseNo"],2,12)."</caseNo>\n";
				$str .= "\t<receiveDate>".date("Y-m-d\TH:i:s.000+08:00",strtotime($rcData[0]["rcDate"]))."</receiveDate>\n";
				$str .= "\t<approveDate>".date("Y-m-d\TH:i:s.000+08:00",strtotime($rcData[0]["rcStatus3Time"]))."</approveDate>\n";
				$str .= "\t<instCap>".(($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]:$motoData['0']['mcoMinMonthlyTotal']*$rcData[0]["rcPeriodAmount"])."</instCap>\n";
				$str .= "\t<firstPayment>".$rcData[0]["rcBankTransferAmount"]."</firstPayment>\n";
				$str .= "\t<repayDate>".date("Y-m-d\TH:i:s.000+08:00",strtotime($rcData[0]["rcFirstPayDate"]))."</repayDate>\n";
				$str .= "\t<brand>PRODUCT</brand>\n";

                if($productType == "機車再分期")
                {
                      $str .= "\t<carNo>".$rcData[0]["mcoCarNum"]."</carNo>\n";
                }
                else
                {
			    	$str .= "\t<carNo>".substr($rcData[0]["rcCaseNo"],2,12)."</carNo>\n";
				}

				if($rcData[0]["rcType"] == "0"){
					$str .= "\t<payeeInfo>\n";
					$str .= "\t\t<mid>".$supData[0]["supNo"]."</mid>\n";
					$str .= "\t\t<bankCode>".$supData[0]["supTransferBankCode"]."</bankCode>\n";
					$str .= "\t\t<bankDetailCode>".$supData[0]["supTransferSubBankCode"]."</bankDetailCode>\n";
					$str .= "\t\t<accountIdNo>".$supData[0]["supTransferAccIdNum"]."</accountIdNo>\n";
					$str .= "\t\t<accountName>".$supData[0]["supName"]."</accountName>\n";
					$str .= "\t\t<accountNo>".$supData[0]["supTransferAcc"]."</accountNo>\n";
					$str .= "\t\t<depositor>".$supData[0]["supTransferAccName"]."</depositor>\n";
					$str .= "\t</payeeInfo>\n";
				}else{
					$str .= "\t<payeeInfo>\n";
					$str .= "\t\t<mid>10052</mid>\n";
					$str .= "\t\t<bankCode>050</bankCode>\n";
					$str .= "\t\t<bankDetailCode>1105</bankDetailCode>\n";
					$str .= "\t\t<accountIdNo>80311391</accountIdNo>\n";
					$str .= "\t\t<accountName>景華有限公司</accountName>\n";
					$str .= "\t\t<accountNo>11012050188</accountNo>\n";
					$str .= "\t\t<depositor>景華有限公司</depositor>\n";
					$str .= "\t</payeeInfo>\n";
				}
				
				$str .= "\t<billAddressInfo>\n";
				$str .= "\t\t<postCode>".$memData[0]["memPostCode"]."</postCode>\n";
				$str .= "\t\t<address>".$memData[0]["memAddr"]."</address>\n";
				$str .= "\t</billAddressInfo>\n";
				
				$str .= "\t<examinePhasePays>\n";
				$str .= "\t\t<eachPay>\n";
				$str .= "\t\t\t<seqNo>0</seqNo>\n";
				$str .= "\t\t\t<instNo>".($rcData[0]["rcPeriodAmount"]-1)."</instNo>\n";
				$str .= "\t\t\t<instAmt>".(($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'])."</instAmt>\n";
				$str .= "\t\t</eachPay>\n";
				
				$str .= "\t\t<eachPay>\n";
				$str .= "\t\t\t<seqNo>1</seqNo>\n";
				$str .= "\t\t\t<instNo>1</instNo>\n";
				$str .= "\t\t\t<instAmt>".(($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'])."</instAmt>\n";
				$str .= "\t\t</eachPay>\n";
				$str .= "\t</examinePhasePays>\n";
				
				$str .= "\t<customers>\n";
				$str .= "\t\t<customer>\n";
				$str .= "\t\t\t<customerIndex>0</customerIndex>\n";
				$str .= "\t\t\t<custId>".$memData[0]["memIdNum"]."</custId>\n";
				$str .= "\t\t\t<custName>".$memData[0]["memName"]."</custName>\n";
				$str .= "\t\t\t<sex>".($memData[0]["memGender"] == "0" ? "FEMALE" : "MALE")."</sex>\n";
				$bdayArr = explode("-", $memData[0]["memBday"]."T00:00:00.000+08:00");
				$bdayArr[0] = $bdayArr[0]+1911;
				$bdayStr = implode("-", $bdayArr);
				$str .= "\t\t\t<birthday>".$bdayStr."</birthday>\n";
				$str .= "\t\t\t<email>".($memData[0]["memAccount"] != "" ? $memData[0]["memAccount"] : $memData[0]["memSubEmail"])."</email>\n";
				$str .= "\t\t\t<mobile>".$memData[0]["memCell"]."</mobile>\n";
				$str .= "\t\t\t<residentInfo>\n";
				$str .= "\t\t\t\t<telArea></telArea>\n";
				$str .= "\t\t\t\t<telNumber></telNumber>\n";
				$str .= "\t\t\t\t<postCode>".$rcData[0]["rcBirthAddrPostCode"]."</postCode>\n";
				$str .= "\t\t\t\t<address>".$rcData[0]["rcBirthAddr"]."</address>\n";
				$str .= "\t\t\t</residentInfo>\n";
				$str .= "\t\t\t<contactInfo>\n";
				$str .= "\t\t\t\t<telArea></telArea>\n";
				$str .= "\t\t\t\t<telNumber></telNumber>\n";
				$str .= "\t\t\t\t<postCode>".$memData[0]["memPostCode"]."</postCode>\n";
				$str .= "\t\t\t\t<address>".$memData[0]["memAddr"]."</address>\n";
				$str .= "\t\t\t</contactInfo>\n";
				$str .= "\t\t</customer>\n";
				$str .= "\t</customers>\n";
				$str .= "</applyRec>";
				$filename = date("Ymd",strtotime($rcData[0]["rcPredictGetDate"]));
				if (!file_exists("../../appro_file/".$filename)) {
					mkdir("../../appro_file/".$filename,0777,true);
				}
				$file = fopen("../../appro_file/".$filename."/CMC_".substr($rcData[0]["rcCaseNo"], 2).".xml","w");
				fwrite($file,pack("CCC",0xef,0xbb,0xbf));
				fwrite($file,$str);
				fclose($file); 

				//更改撥款狀態
				$rc->update(array("rcApproStatus"=>3), $rcNo);
	
			}else{
				//直接產TPI本息表
				$curDate = $rcData[0]["rcFirstPayDate"];
				$curDateArr = explode("-",$rcData[0]["rcFirstPayDate"]);
				if($rcData[0]["rcType"] > 0){
					$moto = new API("motorbike_cellphone_orders");
					$moto->setWhereArray(array("mcoNo"=>$rcData[0]['rcRelateDataNo']));
					$motoData = $moto->getWithConditions();
				} 
				$tpiPeriodTotal = ($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'];
				for($i=0; $i<$rcData[0]["rcPeriodAmount"]; $i++){
					$tpiDataInput = array(
							"rcNo"=>$rcNo,
							"tpiPeriod"=>$i+1,
							"tpiSupposeDate"=>$curDate,
							"tpiActualDate"=>"",
							"tpiPeriodTotal"=>$tpiPeriodTotal,
							"tpiPenalty"=>0,
							"tpiPaidTotal"=>"",
							"tpiOverdueDays"=>0
					);
					$tpiNewNo = $tpi->insert($tpiDataInput);
						
					//產條碼
					$url = 'http://'.DOMAIN.'/admin_advanced/ajax/appropriation/generate_barcode.php';
					$myvars = 'bank=21數位&MonthlyPayment='.$tpiPeriodTotal.'&ExpectedRepaymentDate='.str_replace("-", "", $curDate).'&ID='.$rcData[0]["rcCaseNo"];
						
					$ch = curl_init( $url );
					curl_setopt( $ch, CURLOPT_POST, 1);
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
					curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt( $ch, CURLOPT_HEADER, 0);
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
						
					$response = curl_exec( $ch );
						
					$barcodeJson = json_decode($response);
					foreach($barcodeJson as $keyBar=>$valueBar){
						$barInputData = array(
								"tpiNo"=>$tpiNewNo,
								"barIndex"=>substr($keyBar,-1),
								"barBarcode"=>$valueBar
						);
						$bar->insert($barInputData);
					}
						
					if($curDateArr[2] == "31"){
						$curDate = getNextMonthDate($curDate,true);
					}else if($curDateArr[2] == "30" || $curDateArr[2] == "29"){
						$curDate = getNextMonthDate($curDate,false,$curDateArr[2]);
					}else{
						$curDate = getNextMonthDate($curDate);
					}
				}
				//更改撥款狀態
				$rc->update(array("rcFinishStatus"=>5,"rcApproStatus"=>4), $rcNo);
				//增加萬用帳號
				$rc->update(array("rcVirtualAccount"=>"959170".substr($rcData[0]["rcCaseNo"],2)), $rcNo);
				//若為NoWait改到已完成
				if($rcData[0]["rcType"] == "0"){
					$or = new API("orders");
					$or->update(array("orStatus"=>"10","orReportPeriod10Date"=>date('Y-m-d H:i:s',time())),$rcData[0]["rcRelateDataNo"]);
				} 
			}
			//人員操作紀錄
			$apiSd = new API("service_record");
			$SdDataInput = array(
								"rcNo"=>$rcNo,
								"aauNoService"=>$_SESSION['adminUserData']['aauNo'],
								"content"=>"確認撥款完成", 
								"time"=>date('Y-m-d H:i:s',time())); 
			$apiSd->insert($SdDataInput);
		}
	}
}else{
	$errMsg = "請先勾選要匯出Excel的案件。";
}

if(!isset($errMsg)){
	echo "OK";
}else{
	echo $errMsg;
}




?>