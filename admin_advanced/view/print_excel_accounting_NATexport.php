<?php
function __autoload($ClassName){

	include_once('../cls/'.$ClassName.".cls.php");

}

require_once('../cfg/cfg.inc.php');

require_once('../lib/function.php');

$rc = new API("real_cases");
$tb = new API("transfer_bank");
$mem = new API("member");
$bar = new API("barcode");
$tpi = new API("tpi");
$sup = new API("supplier");

// FILE NAME
$file = "待開發票_" . date('Y-m-d_His') . ".xls";

$row = '';
$count = 0;
$transferTotal = 0;

function xlsWriteLabel($Row, $Col, $Value )
    {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
    }

foreach($_POST["rcNo"] as $key => $rcNo){
	$rcData = $rc->getOne($rcNo);
	$tbData = $tb->getOne($rcData[0]["tbNo"]);
	$memData = $mem->getOne($rcData[0]["memNo"]);
	$supData = $sup->getOne($rcData[0]["supNo"]);
	$transferTotal += $rcData[0]['rcBankTransferAmount'];
	
	$Y = (date('Y',strtotime($rcData[0]['rcApproDate']))-1911);
	$TodayY = ($rcData[0]["rcInvoiceNumber"] == '') ? "A".(date('Y',time())-1911).date('md',time()).str_pad($count+1,3,'0',STR_PAD_LEFT):$rcData[0]["rcInvoiceNumber"];
	//$TodayY = "A".(date('Y',time())-1911).date('md',time()).str_pad($count+1,3,'0',STR_PAD_LEFT);
	
	$row .=  '
				<tr>
				<td>'.$Y.'</td>
				<td>'.$TodayY.'</td>
				<td>'.$Y.date('md',strtotime($rcData[0]['rcApproDate'])).'</td>
	    		<td>20</td>
				<td></td>
				<td></td>
				<td>'.strtoupper($memData['0']['memIdNum']).'</td>
				<td></td>
				<td>'.pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0).'00</td>
				<td>'.pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0).'00</td>
				<td></td>
				<td></td>
				<td>1</td>
				<td></td>
				<td></td>
				<td></td>
				<td>2</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>'.pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0).'0001</td>
				<td>'.pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0).'001</td>
				<td></td>
				<td>1</td>
				<td>'.round($rcData[0]['rcPeriodTotal']/1.05).'</td>
				<td>'.round($rcData[0]['rcPeriodTotal']/1.05).'</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>1</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td>Y</td>
				<td>Y</td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>				
		  </tr>';
		if($rcData[0]["rcInvoiceNumber"] == ''){	  
			$array = array("rcInvoiceNumber"=>$TodayY,"rcInvoiceDate"=>date("Y-m-d H:i:s",time()));
			$rc->update($array,$rcNo);
		}
		

		$count++;
}

header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:filename=" . $file . "");

$html = '<HTML xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">'."\n";
$html .= '<head><meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8"><style>table{border-collapse:collapse;table-layout:auto;}td,th{border:1px solid #000;white-space: nowrap;}.text{mso-number-format:"\@";}</style></head>'."\n";
$html .=  '<body>';
$html .=  '<table><tbody>';
$html .=  '<tr>
				<th>年度</th>
				<th>進銷單號碼</th>
				<th>日期</th>
	    		<th>單別</th>
				<th>庫別(出)</th>
				<th>庫別(入)</th>
				<th>客戶供應商</th>
				<th>進銷特殊欄位</th>
				<th>序號(客戶供應商統編)</th>
				<th>序號(客戶供應商地址)</th>
				<th>業務員代號</th>
				<th>外幣代號</th>
				<th>匯率</th>
				<th>批號</th>
				<th>訂單單號</th>
				<th>採購單號</th>
				<th>稅別</th>
				<th>發票號碼</th>
				<th>製成品代號</th>
				<th>轉B帳註記</th>
				<th>類別課目代號</th>
				<th>立帳傳票號碼</th>
				<th>沖帳傳票號碼</th>
				<th>列印註記</th>
				<th>部門/工地編號</th>
				<th>專案/項目編號</th>
				<th>A|B帳唯一流水號</th>
				<th>產品組合代號</th>
				<th>帳款號碼</th>
				<th>報價單號</th>
				<th>內聯單號</th>
				<th>序號</th>
				<th>產品代號</th>
				<th>包裝別</th>
				<th>數量</th>
				<th>未稅單價</th>
				<th>未稅金額</th>
				<th>外幣未稅單價</th>
				<th>外幣未稅金額</th>
				<th>生產數量</th>
				<th>明細備註</th>
				<th>明細備註二</th>
				<th>單價含稅否欄位</th>
				<th>請款客戶</th>
				<th>主檔備註</th>
				<th>主檔自定義欄位一</th>
				<th>主檔自定義欄位二</th>
				<th>主檔自定義欄位三</th>
				<th>主檔自定義欄位四</th>
				<th>主檔自定義欄位五</th>
				<th>主檔自定義欄位六</th>
				<th>主檔自定義欄位七</th>
				<th>主檔自定義欄位八</th>
				<th>主檔自定義欄位九</th>
				<th>主檔自定義欄位十</th>
				<th>主檔自定義欄位十一</th>
				<th>主檔自定義欄位十二</th>
				<th>明細自定義欄位一</th>
				<th>明細自定義欄位二</th>
				<th>明細自定義欄位三</th>
				<th>明細自定義欄位四</th>
				<th>明細自定義欄位五</th>
				<th>明細自定義欄位六</th>
				<th>明細自定義欄位七</th>
				<th>明細自定義欄位八</th>
				<th>明細自定義欄位九</th>
				<th>明細自定義欄位十</th>
				<th>明細自定義欄位十一</th>
				<th>明細自定義欄位十二</th>
				<th>外銷方式</th>
				<th>付款方式代號</th>
				<th>送貨方式代號</th>
				<th>對方單號</th>
				<th>其他請款費用</th>
				<th>發票日期</th>
				<th>結帳日</th>
				<th>收款日</th>
				<th>對方品名/品名備註</th>
				<th>對方產品代號</th>
				<th>包裝單位</th>
				<th>包裝數量</th>
				<th>散裝數量</th>
				<th>線上數量換算比例</th>
				<th>包裝單價(登打)</th>
				<th>包裝單價(未稅)</th>
				<th>發票捐註記</th>
				<th>發票捐對象</th>
				<th>電子發票註記</th>
				<th>列印紙本電子發票註記</th>
				<th>載具類別號碼</th>
				<th>載具顯碼ID</th>
				<th>載具隠碼ID</th>
				<th>愛心碼</th>
				<th>對帳日</th>
			</tr>';
$html .=  '%s';
$html .=  "</tbody></table>";
$html .=  '</body></html>';
$txt = sprintf($html,$row);
echo $txt;

?>