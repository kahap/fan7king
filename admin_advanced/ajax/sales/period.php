<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');


session_start();

$start = date("Ymd",strtotime($_POST['startday']))."0001";
$end = date("Ymd",strtotime($_POST['endday']))."9999";

$_POST['endday'] = $_POST['endday']." 23:59:59";

$api = new API("real_cases");
$sql = "SELECT b.aauNo,count(a.rcCaseNo) as '申請案件數',sum(CASE a.rcStatus WHEN 3 THEN 1 END) as '核准件',c.已簽約家數 FROM `real_cases` a,supplier b left join (SELECT aauNo,COUNT(supNo) as '已簽約家數' FROM `supplier` group by aauNo) as c on b.aauNo = c.aauNo where a.rcCaseNo BETWEEN '".$start."' and '".$end."' and a.supNo != '' and a.supNo = b.supNo group by b.aauNo";
$data = $api->customSql($sql);

$sql2 = "SELECT b.aauNo,count(a.supNo) as 'total' FROM `real_cases` a,supplier b where a.supNo = b.supNo && a.rcDate BETWEEN '".$_POST['startday']."' and '".$_POST['endday']."' group by a.supNo";
$applyData = $api->customSql($sql2);
foreach($applyData as $key => $value){
	$value['aauNo'] = ($value['aauNo'] != "") ? $value['aauNo']:"樂分期";
	$userApply[$value['aauNo']] +=1;
}


$sql3 = "SELECT b.aauNo,count(a.rcCaseNo) as '撥款件數',sum(a.rcBankTransferAmount) as '撥款總金額',AVG(rcPeriodTotal) as '平均單筆金額',AVG(rcPeriodAmount) as '平均期數' FROM `real_cases` a,supplier b where a.supNo = b.supNo && a.rcPredictGetDate  BETWEEN '".$_POST['startday']."' and '".$_POST['endday']."' && a.rcStatus = '3' && a.rcApproStatus = '4' group by a.supNo";
$MoneyData = $api->customSql($sql3);
foreach($MoneyData as $key => $value){
	
	$communication['撥款件數'] += ($value['aauNo'] != "") ? $value['撥款件數']:0;
	$communication['撥款總金額'] += ($value['aauNo'] != "") ? $value['撥款總金額']:0;
	$communication['平均單筆金額'] += ($value['aauNo'] != "") ? $value['平均單筆金額']:0;
	$communication['平均期數'] +=  ($value['aauNo'] != "") ? $value['平均期數']:0;
	$communication['總件數']+= ($value['aauNo'] != "") ? 1:0;
	$value['aauNo'] = ($value['aauNo'] != "") ? $value['aauNo']:"樂分期";
	
	
	
	$count[$value['aauNo']]['撥款件數'] +=$value['撥款件數'];
	$Usermoney[$value['aauNo']]['撥款總金額'] += $value['撥款總金額'];
	$Usermoney[$value['aauNo']]['平均單筆金額'] += $value['平均單筆金額'];
	$Usermoney[$value['aauNo']]['平均期數'] +=$value['平均期數'];
	$count[$value['aauNo']]['總件數'] +=1;
	
	$total_give +=$value['撥款件數'];
	$Usermoney['company']['平均單筆金額'] += $value['平均單筆金額'];
	$Usermoney['company']['平均期數'] +=$value['平均期數'];
	$count['company']['總件數']+=1;
}

$sql1 = "SELECT aauNo,aauName FROM `admin_advanced_user`";
$user = $api->customSql($sql1);
foreach($user as $key => $value){
	$user[$value['aauNo']] = $value['aauName']; 
}

$sql4 = "SELECT a.rcType,count(a.rcCaseNo) as '申請案件數',sum(CASE a.rcStatus WHEN 3 THEN 1 END) as '核准件',b.`撥款件數`,b.`撥款總金額`,b.`平均單筆金額`,b.`平均期數` from real_cases a left JOIN (SELECT rcType,count(rcCaseNo) as '撥款件數',sum(rcBankTransferAmount) as '撥款總金額',AVG(rcPeriodTotal) as '平均單筆金額',AVG(rcPeriodAmount) as '平均期數' FROM `real_cases` where rcPredictGetDate BETWEEN '".$_POST['startday']."' and '".$_POST['endday']."' && rcStatus = '3' && rcApproStatus = '4' group by rcType) as b on a.rcType = b.rcType where a.rcCaseNo BETWEEN '".$start."' and '".$end."' group by a.rcType";
$total_other = $api->customSql($sql4);

$user['樂分期'] = "樂分期";
?>

			<div class="card">
				<div class="card-content">
   					<table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>業務名稱</th>
								<th>申請件數</th>
								<th>核准件數</th>
								<th>核准率</th>
								<th>送件家數</th>
								<th>已簽約家數</th>
								<th>平均送件數</th>
								<th>撥款件數</th>
								<th>撥款總金額</th>
								<th>平均單筆金額</th>
								<th>平均期數</th>
     						</tr>
						</thead>
     					<tbody>
						
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){	
							$communication['申請案件數'] += ($value['aauNo'] != '') ? $value['申請案件數']:0;
							$communication['核准件'] += ($value['aauNo'] != '') ? $value['核准件']:0;
							$communication['已送店家數'] += $userApply[(($value['aauNo'] != "") ? $value['aauNo']:"")];
							$communication['已簽約家數'] += ($value['aauNo'] != '') ? $value['已簽約家數']:0;
							
							$value['aauNo'] = ($value['aauNo'] != '') ? $value['aauNo']:"樂分期";
							$total +=$value['申請案件數'];
							$total_agree +=$value['核准件'];
							$total_sent +=$userApply[(($value['aauNo'] != "") ? $value['aauNo']:"樂分期")];
							$total_asign +=$value['已簽約家數'];
							
							$total_money +=$Usermoney[$value['aauNo']]['撥款總金額'];
     					?>
     						<tr>
								<td><?=$user[$value['aauNo']]; ?></td>
								<td><?=$value['申請案件數'];?></td>
								<td><?=$value['核准件'];?></td>
								<td><?=(round($value['核准件']/$value['申請案件數'],2)*100)."%";?></td>
								<td><?=$userApply[(($value['aauNo'] != "") ? $value['aauNo']:"樂分期")];?></td>
								<td><?=$value['已簽約家數'];?></td>
								<td><?=(round($value['申請案件數']/$userApply[(($value['aauNo'] != "") ? $value['aauNo']:"樂分期")],2));?></td>
								<td><?=$count[$value['aauNo']]['撥款件數'];?></td>
								<td><?=number_format($Usermoney[$value['aauNo']]['撥款總金額'])?></td>
								<td><?=round($Usermoney[$value['aauNo']]['撥款總金額']/$count[$value['aauNo']]['撥款件數'],2)?></td>
								<td><?=round($Usermoney[$value['aauNo']]['平均期數']/$count[$value['aauNo']]['總件數'],2)?></td>
     						</tr>
							
     					<?php 
     						}
     					}
     					?>
     					</tbody>
						<tfoot>
							<tr>
								<th>通訊行-總計</th>
								<th><?=$communication['申請案件數']?></th>
								<th><?=$communication['核准件']?></th>
								<th><?=(round($communication['核准件']/$communication['申請案件數'],2)*100)."%";?></th>
								<th><?=$communication['已送店家數']?></th>
								<th><?=$communication['已簽約家數']?></th>
								<th><?=(round($communication['已送店家數']/$communication['已簽約家數'],2)*100)?></th>
								<th><?=$communication['撥款件數']?></th>
								<th><?=number_format($communication['撥款總金額']);?></th>
								<th><?=round($communication['撥款總金額']/$communication['撥款件數'],2);?></th>
								<th><?=round($communication['平均期數']/$communication['總件數'],2);?></th>
     						</tr>
							<tr>
								<th>樂分期-總計</th>
								<th><?=$total_other['0']['申請案件數']?></th>
								<th><?=$total_other['0']['核准件']?></th>
								<th><?=(round($total_other['0']['核准件']/$total_other['0']['申請案件數'],2)*100)."%"?></th>
								<th><?=$total_sent?></th>
								<th><?=$total_asign?></th>
								<th><?=(round($total_sent/$total_asign,2)*100)?></th>
								<th><?=$total_other['0']['撥款件數']?></th>
								<th><?=number_format($total_other['0']['撥款總金額'])?></th>
								<th><?=round($total_other['0']['撥款總金額']/$total_other['0']['撥款件數'],2)?></th>
								<th><?=round($Usermoney['company']['平均期數']/$count['company']['總件數'],2);?></th>
							</tr>
							<tr>
								<th>樂分期-機車</th>
								<th><?=$total_other['2']['申請案件數']?></th>
								<th><?=$total_other['2']['核准件']?></th>
								<th><?=(round($total_other['2']['核准件']/$total_other['2']['申請案件數'],2)*100)."%"?></th>
								<th>-</th>
								<th>-</th>
								<th>-</th>
								<th><?=$total_other['2']['撥款件數']?></th>
								<th><?=number_format($total_other['2']['撥款總金額'])?></th>
								<th><?=round($total_other['2']['撥款總金額']/$total_other['2']['撥款件數'],2)?></th>
								<th><?=round($total_other['2']['平均期數'],2)?></th>
							</tr>
							<tr>
								<th>樂分期-手機</th>
								<th><?=$total_other['1']['申請案件數']?></th>
								<th><?=$total_other['1']['核准件']?></th>
								<th><?=(round($total_other['1']['核准件']/$total_other['1']['申請案件數'],2)*100)."%"?></th>
								<th>-</th>
								<th>-</th>
								<th>-</th>
								<th><?=$total_other['1']['撥款件數']?></th>
								<th><?=number_format($total_other['1']['撥款總金額'])?></th>
								<th><?=round($total_other['1']['撥款總金額']/$total_other['1']['撥款件數'],2)?></th>
								<th><?=round($total_other['1']['平均期數'],2)?></th>
							</tr>
							<tr>
								<th>全部總計</th>
								<th><?=$total_other['2']['申請案件數']+$total_other['1']['申請案件數']+$total_other['0']['申請案件數']?></th>
								<th><?=$total_other['2']['核准件']+$total_other['1']['核准件']+$total_other['0']['核准件']?></th>
								<th><?=(round(($total_other['2']['核准件']+$total_other['1']['核准件']+$total_other['0']['核准件'])/($total_other['2']['申請案件數']+$total_other['1']['申請案件數']+$total_other['0']['申請案件數']),2)*100)."%"?></th>
								<th>-</th>
								<th>-</th>
								<th>-</th>
								<th><?=$total_other['2']['撥款件數']+$total_other['1']['撥款件數']+$total_other['0']['撥款件數']?></th>
								<th><?=number_format($total_other['2']['撥款總金額']+$total_other['1']['撥款總金額']+$total_other['0']['撥款總金額'])?></th>
								<th><?=round(($total_other['2']['撥款總金額']+$total_other['1']['撥款總金額']+$total_other['0']['撥款總金額'])/($total_other['2']['撥款件數']+$total_other['1']['撥款件數']+$total_other['0']['撥款件數']),2)?></th>
								<th><?=round(($total_other['2']['平均期數']+$total_other['1']['平均期數']+$total_other['0']['平均期數'])/3,2)?></th>
							</tr>
							
							
						</tfoot>
					</table>
				</div>
			</div>
			