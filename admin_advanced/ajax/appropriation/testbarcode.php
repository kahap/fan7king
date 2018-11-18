<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');
/*	$url = 'http://nowait.shop/admin_advanced/ajax/appropriation/generate_barcode.php';
					$myvars = 'bank=21數位&MonthlyPayment=1941&ExpectedRepaymentDate=20181204&ID=201706165018';
						
					$ch = curl_init( $url );
					curl_setopt( $ch, CURLOPT_POST, 1);
					curl_setopt( $ch, CURLOPT_POSTFIELDS, $myvars);
					curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
					curl_setopt( $ch, CURLOPT_HEADER, 0);
					curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
						
					$response = curl_exec( $ch );
						
					$barcodeJson = json_decode($response);
					print_r($response);

	$moto = new API("motorbike_cellphone_orders");
	$moto->setWhereArray(array("mcoNo"=>'46'));
	$motoData = $moto->getWithConditions();
	//print_r($motoData);
	$rcData['0']['rcType'] = 1;				
	$tpiPeriodTotal = ($rcData['0']['rcType'] == 0) ? $rcData[0]["rcPeriodTotal"]/$rcData[0]["rcPeriodAmount"]:$motoData['0']['mcoMinMonthlyTotal'];
	//echo $tpiPeriodTotal;
	
	$bar= new API("barcode");
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	$barData = $bar->getAll();
	foreach($barData as $key => $value){
		if($value['tpiNo'] > 25482){
			switch($value['barIndex']){
			case 1:
				//$str[$value['tpiNo']] .= substr($value['barBarcode'],0,2);
				$year = (substr($value['barBarcode'],0,2) == 10) ? '2017':"";
				//$year = (substr($value['barBarcode'],0,2) == 11) ? '2018':'2019';
				if(substr($value['barBarcode'],0,2) == 10){
					$year = "2017";
				}elseif(substr($value['barBarcode'],0,2) == 11){
					$year = "2018";
				}else{
					$year = "2019"; 
				}
			break;
			
			case 2:
				$aaa = "20".substr($value['barBarcode'],6,10);
				$sql6 = "SELECT rcNo FROM `real_cases` WHERE `rcCaseNo` = '".$aaa."'"; 
				$data = $db->selectRecords($sql6);
				$str[$value['tpiNo']] .= $data['0']['rcNo'].",";
			break;
			
			case 3:
				$str[$value['tpiNo']] .= $year."-".substr($value['barBarcode'],0,2)."-".substr($value['barBarcode'],2,2).",".substr($value['barBarcode'],-4);
			break;
			}
		}
		
	}
	$i = 1;
	foreach($str as $key => $value){
		$data = explode(',',$value);
		if($rcNo == $data[0]){
			echo "INSERT INTO `tpi`(`tpiNo`, `rcNo`, `tpiPeriod`, `tpiSupposeDate`, `tpiActualDate`, `tpiPeriodTotal`, `tpiPenalty`, `tpiPaidTotal`, `tpiOverdueDays`, `tpiIfCancelPenalty`) VALUES (".$key.",".$data[0].",".$i.",'".$data[1]."','',".$data[2].",'','','','0');<br>";
			$rcNo = $data[0];
		}else{
			$i = 1;
			echo "INSERT INTO `tpi`(`tpiNo`, `rcNo`, `tpiPeriod`, `tpiSupposeDate`, `tpiActualDate`, `tpiPeriodTotal`, `tpiPenalty`, `tpiPaidTotal`, `tpiOverdueDays`, `tpiIfCancelPenalty`) VALUES (".$key.",".$data[0].",".$i.",'".$data[1]."','',".$data[2].",'','','','0');<br>";
			$rcNo = $data[0];
		}
		$i++;
	}
	*/
	$rc = new API("real_cases");
	$rcData = $rc->getOne('53327');
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
					//$tpiNewNo = $tpi->insert($tpiDataInput);
						
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
					print_r($response);
					echo "<br>";

					
					/*$barcodeJson = json_decode($response);
					foreach($barcodeJson as $keyBar=>$valueBar){
						$barInputData = array(
								"tpiNo"=>$tpiNewNo,
								"barIndex"=>substr($keyBar,-1),
								"barBarcode"=>$valueBar
						);
						$bar->insert($barInputData);
					}*/
						
					if($curDateArr[2] == "31"){
						$curDate = getNextMonthDate($curDate,true);
					}else if($curDateArr[2] == "30" || $curDateArr[2] == "29"){
						$curDate = getNextMonthDate($curDate,false,$curDateArr[2]);
					}else{
						$curDate = getNextMonthDate($curDate);
					}
				}
?>