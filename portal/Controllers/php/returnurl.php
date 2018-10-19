<?
include('../model/php_model.php');

$allpay = new Allpay(MerchantID,HashKey,HashIV);
$sql = 'init';
print_r($allpay->CheckConnent());
if($allpay->CheckConnent()){
	$sql = '';
	if(isset($_GET['type'])){

	}
}

$log_path = $_SERVER['DOCUMENT_ROOT'] . '/log_allpay/' . $_POST['MerchantTradeNo'] . '-' . $_GET['type'] . '-' . time() . '.txt';

if(isset($_GET['type'])) $fp = fopen($log_path, 'w');

ob_start();
	echo "_SERVER\n";
	print_r($_SERVER);
	echo "GET\n";
	print_r($_GET);
	echo "POST\n";
	print_r($_POST);

	echo '-' . $sql . '-';

$txt = ob_get_clean();
fwrite($fp,$txt);
fclose($fp);

if($allpay->CheckConnent()){
	echo '1|OK';
}
?>