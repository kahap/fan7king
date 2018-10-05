<?php
switch($type){
	case "all_ques":
		$api->getAll("qaNo, qaQues, qaDate, qaOrder, qaIfShow");
		break;
	case "single_ans":
		$apiQa = new API("qa_app");
		$apiQa->getOne($no,"qaaAnsw");
		$data = $apiQa->getData();
		$html = '<!DOCTYPE html>
					<html lang="zh-Hant">
					  <head>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1, user-scalable=no" />
						<style>
						body{
							width:auto;
						}
						img{
							max-width:100%;
						}
						</style>
					  </head>
					  <body>';
		$html .= $data[0]["qaaAnsw"];
		$html .= '
		  </body>
		</html>';
		echo $html;
		break;
	case "single_product":
		$api->getOne($no,"proNo,proCaseNo,catNo,braNo,proName,proModelID,proSpec,proImage");
		break;
	case "product_detail":
		$api->getOne($no,"proDetail");
		$data = $api->getData();
		$html = '<!DOCTYPE html>
					<html lang="zh-Hant">
					  <head>
						<meta charset="utf-8">
						<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1, user-scalable=no" />
						<style>
						body{
							width:auto;
						}
						img{
							max-width:100%;
						}
						</style>
					  </head>
					  <body>';
		$html .= $data[0]["proDetail"];
		$html .= '
		  </body>
		</html>';
		echo $html;
		break;
}



?>