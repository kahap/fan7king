<?php
class TPI{
	private $tpiData;
	private $curTotal = 0;
	private $finalTotal;
	private $payment;
	
	public function __construct($tpiData){
		$this->tpiData = $tpiData;
		foreach($this->tpiData as $period=>$periodInfo){
			if(!empty($periodInfo["tpiPaidTotal"]) && is_numeric($periodInfo["tpiPaidTotal"])){
				$this->curTotal += $periodInfo["tpiPaidTotal"];
			}
		}
	}
	
	public function recalculate($apiClass,$payDate){
		$lastKey = array_pop(array_keys($this->tpiData));
		//應付總額
		$shouldPayTotal = 0;
		//付款總額
		$paidTotal = $this->finalTotal;
		foreach($this->tpiData as $period=>$periodInfo){
			$overdue = (strtotime($payDate) - strtotime($periodInfo["tpiSupposeDate"]))/86400;
			if($periodInfo["tpiIfCancelPenalty"] == "0" && $periodInfo["tpiOverdueDays"] >= '3'){
				$payTotal = $this->payment;
				$number = $payTotal%$periodInfo["tpiPeriodTotal"];
				$curShouldPay = $periodInfo["tpiPeriodTotal"] + (($number == 0) ? '0':$periodInfo["tpiPenalty"]);
			}else{
				$curShouldPay = $periodInfo["tpiPeriodTotal"];
			}
			$shouldPayTotal += $curShouldPay;
			$curFinal = $this->payment;
			
			//判斷還款日是否是空
			if($periodInfo["tpiActualDate"] == ""){
				//判斷還款金額扣掉應繳金額是否大於0
				if($curFinal - $curShouldPay >= 0){
					if($periodInfo["tpiPaidTotal"] != ""){
						$this->payment += $periodInfo["tpiPaidTotal"];
						$apiClass->update(array("tpiActualDate"=>$payDate,"tpiPaidTotal"=>$curShouldPay), $periodInfo["tpiNo"]);
						$this->payment -= $curShouldPay;
					}else{
						$apiClass->update(array("tpiActualDate"=>$payDate,"tpiPaidTotal"=>$curShouldPay), $periodInfo["tpiNo"]);
						$this->payment -= $curShouldPay;
					}
				//判斷還款金額是否大於等於期金，剩餘的未滿滯納金沖下期	
				}elseif($curFinal >= $periodInfo["tpiPeriodTotal"]){
					$apiClass->update(array("tpiActualDate"=>$payDate,"tpiPaidTotal"=>$periodInfo["tpiPeriodTotal"]), $periodInfo["tpiNo"]);
					$this->payment -= $periodInfo["tpiPeriodTotal"];
				//不足金額都沖下一期	
				}else{
					if($periodInfo["tpiPaidTotal"] != ""){
						$curFinal += $periodInfo["tpiPaidTotal"];
						$apiClass->update(array("tpiPaidTotal"=>$curFinal), $periodInfo["tpiNo"]);
						break;
						
					}else{
						$apiClass->update(array("tpiPaidTotal"=>$curFinal), $periodInfo["tpiNo"]);
						break;
					}
				}
			}
		}

		//計算溢收款
		$rc = new API("real_cases");
		if($paidTotal > $shouldPayTotal){
			$extraPay = $paidTotal - $shouldPayTotal;
			$rc->update(array("rcExtraPayTotal"=>$extraPay,"rcIfReturnExtraPay"=>1),$this->tpiData[0]["rcNo"]);
		}else{
			$rc->update(array("rcExtraPayTotal"=>0,"rcIfReturnExtraPay"=>0),$this->tpiData[0]["rcNo"]);
		}
		
		//並重新計算逾期天數
		// $overdues = $apiClass->getAllOverdue();
		// if($overdues != null){
			foreach($this->tpiData as $key=>$value){
				$days = (strtotime(date('Y-m-d',time())) - strtotime($value["tpiSupposeDate"]))/86400;
				if($days > 2){
					//$apiClass->update(array("tpiOverdueDays"=>$days), $value["tpiNo"]);
					//$apiClass->update(array("tpiPenalty"=>$apiClass->getPenaltyAmount()), $value["tpiNo"]);
					if($value["tpiIfCancelPenalty"] == "0"){
						//並更新條碼
						$rcData = $rc->getOne($value["rcNo"]);
						$bar = new API("barcode");
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
									//fix time
									
									$barcodeJson = json_decode($response, true);

									$newBarcodeArray = array($barcodeJson['ShopCode1'], $barcodeJson['ShopCode2'], $barcodeJson['ShopCode3']);
									
									$bar->insertCMCBarCode($newBarcodeArray, $value['tpiNo']);
									/*
									$barcodeJson = json_decode($response);
									foreach($barcodeJson as $keyBar=>$valueBar){
										$barInputData = array(
												"tpiNo"=>$value["tpiNo"],
												"barIndex"=>substr($keyBar,-1),
												"barBarcode"=>$valueBar,
												"barType"=>1
										);
										$bar->insert($barInputData);
									}*/
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
								// fix time
								$barcodeJson = json_decode($response, true);

								$barcodes = $bar->selectBarCode($value['tpiNo']);
								$barcodeArray = array($barcodes[0]['barNo'],$barcodes[1]['barNo'],$barcodes[2]['barNo']);
								$newBarcodeArray = array($barcodeJson['ShopCode1'], $barcodeJson['ShopCode2'], $barcodeJson['ShopCode3']);

								$bar->updateBarCode($barcodeArray, $newBarcodeArray, $value['tpiNo']);
								
								/*
								$barcodeJson = json_decode($response);
								foreach($barcodeJson as $keyBar=>$valueBar){
									$barInputData = array(
											"barBarcode"=>$valueBar
									);
									$bar->setWhereArray(array("tpiNo"=>$value["tpiNo"],"barIndex"=>substr($keyBar,-1)));

									$barData = $bar->getWithConditions();
									$bar->update($barInputData,$barData[0]["barNo"]);
								}*/
								break;
						}
					}else{
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
						
								// fix time
								$barcodeJson = json_decode($response, true);

								$barcodes = $bar->selectBarCode($value['tpiNo']);
								$barcodeArray = array($barcodes[0]['barNo'],$barcodes[1]['barNo'],$barcodes[2]['barNo']);
								$newBarcodeArray = array($barcodeJson['ShopCode1'], $barcodeJson['ShopCode2'], $barcodeJson['ShopCode3']);

								$bar->updateBarCode($barcodeArray, $newBarcodeArray, $value['tpiNo']);
								/*
								$barcodeJson = json_decode($response);
								foreach($barcodeJson as $keyBar=>$valueBar){
									$barInputData = array(
											"barBarcode"=>$valueBar
									);
									$bar->setWhereArray(array("tpiNo"=>$value["tpiNo"],"barIndex"=>substr($keyBar,-1)));
									$barData = $bar->getWithConditions();
									$bar->update($barInputData,$barData[0]["barNo"]);
								}*/
								break;
						}
					}
				}
			}
		// }
	}
	
	//當金額到達或超過應繳總額後
	public function checkIfFinishPaying($rcNo){
		$rc = new API("real_cases");
		$tpi = new API("tpi");
		$tpi->setWhereArray(array("rcNo"=>$rcNo));
		$tpi->setOrderArray(array("tpiSupposeDate"=>false));
		$tpiData = $tpi->getWithConditions();
		$arrKeys = array_keys($tpiData);
		$lastArrKey = array_pop($arrKeys);
		if($tpiData != null){
			$totalShouldPay = 0;
			$totalAlreadyPay = 0;
			$lastOverdue = 0;
			foreach($tpiData as $key=>$value){
				$totalShouldPay += $value["tpiPeriodTotal"];
				$totalAlreadyPay += $value["tpiPaidTotal"];
				if($lastArrKey == $key){
					$lastOverdue = $value["tpiOverdueDays"];
				}
			}
			if($totalAlreadyPay >= $totalShouldPay){
				if($lastOverdue < -30){
					$finishStatus = 1;
				}else if($lastOverdue >= -30 && $lastOverdue <= 30){
					$finishStatus = 2;
				}else if($lastOverdue > 30){
					$finishStatus = 3;
				}
				$inputData = array(
					"rcFinishStatus"=>$finishStatus
				);
				$rc->update($inputData,$rcNo);
			}else{
				$inputData = array(
						"rcFinishStatus"=>5
				);
				$rc->update($inputData,$rcNo);
			}
		}
	}
	
	public function setFinalTotal($newTotal){
		 $this->finalTotal = $this->curTotal + $newTotal;
	}
	
	public function setPayTotal($newTotal){
		 $this->payment = $newTotal;
	}
	
	public function setDeleteFinalTotal($newTotal){
		$this->finalTotal = $this->curTotal - $newTotal;
	}
}