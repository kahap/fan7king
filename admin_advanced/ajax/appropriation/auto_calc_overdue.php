<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

date_default_timezone_set('Asia/Taipei');

//逾期天數
$tpi = new API("tpi");
$rc = new API("real_cases");
$bar = new API("barcode");
$delay = new API("other_setting");
$delaydata = $delay->getOne("1");

$overdues = $tpi->getAllOverdue();


$delayDate = $delaydata['0']['delayDay']-1;
if($overdues != null){
	foreach($overdues as $key=>$value){
		$days = (strtotime(date('Y-m-d',time())) - strtotime($value["tpiSupposeDate"]))/86400;
		$tpi->update(array("tpiOverdueDays"=>$days), $value["tpiNo"]);
		//大於2天則加上滯納金
		if($days > $delayDate){
			if($value["tpiIfCancelPenalty"] == "0"){
				$tpi->update(array("tpiPenalty"=>$tpi->getPenaltyAmount()), $value["tpiNo"]);
				//並更新條碼
				$rcData = $rc->getOne($value["rcNo"]);
				switch($rcData[0]["tbNo"]){
					case "1":
						//CMC
						//確認是否已經加了條碼
						$bar->setWhereArray(array("tpiNo"=>$value["tpiNo"],"barType"=>"1"));
						$barPenaltyDaya = $bar->getWithConditions();
						if($barPenaltyDaya == null){
							//直接增加條碼
							$url = 'http://'.DOMAIN.'/admin_advanced/ajax/appropriation/generate_barcode.php';
							$myvars = 'bank=penalty&MonthlyPayment='.$rc->getPenaltyAmount().'&ExpectedRepaymentDate='.str_replace("-", "", $value["tpiSupposeDate"]).'&ID='.$rcData[0]["rcCaseNo"];
								
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
										"tpiNo"=>$value["tpiNo"],
										"barIndex"=>substr($keyBar,-1),
										"barBarcode"=>$valueBar,
										"barType"=>1
								);
								$bar->insert($barInputData);
							}
						}
						break;
					case "2":
						//彰銀
						//在原有的條碼直接加300上去
						//產條碼
						$url = 'http://'.DOMAIN.'/admin_advanced/ajax/appropriation/generate_barcode.php';
						$myvars = 'bank=21數位&MonthlyPayment='.($value["tpiPeriodTotal"]+$rc->getPenaltyAmount()).'&ExpectedRepaymentDate='.str_replace("-", "", $value["tpiSupposeDate"]).'&ID='.$rcData[0]["rcCaseNo"];
							
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
									"barBarcode"=>$valueBar
							);
							$bar->setWhereArray(array("tpiNo"=>$value["tpiNo"],"barIndex"=>substr($keyBar,-1)));
							$barData = $bar->getWithConditions();
							$bar->update($barInputData,$barData[0]["barNo"]);
						}
						break;
				}
			}else{
				$apiClass->update(array("tpiPenalty"=>"0"), $value["tpiNo"]);
				//並更新條碼
				$rcData = $rc->getOne($value["rcNo"]);
				$bar = new API("barcode");
				switch($rcData[0]["tbNo"]){
					case "1":
						//CMC
						//直接刪除條碼
						$bar->delete(array("tpiNo"=>$value["tpiNo"],"barType"=>"1"));
						break;
					case "2":
						//彰銀
						//在原有的條碼直接扣300
						//產條碼
						$url = 'http://'.DOMAIN.'/admin_advanced/ajax/appropriation/generate_barcode.php';
						$myvars = 'bank=21數位&MonthlyPayment='.$value["tpiPeriodTotal"].'&ExpectedRepaymentDate='.str_replace("-", "", $value["tpiSupposeDate"]).'&ID='.$rcData[0]["rcCaseNo"];
				
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
									"barBarcode"=>$valueBar
							);
							$bar->setWhereArray(array("tpiNo"=>$value["tpiNo"],"barIndex"=>substr($keyBar,-1)));
							$barData = $bar->getWithConditions();
							$bar->update($barInputData,$barData[0]["barNo"]);
						}
						break;
				}
			}
		}
	}
}


?>