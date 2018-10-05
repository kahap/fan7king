<?php

include_once('cfg/cfg.inc.php');	
include_once('cls/WADB.cls.php');

function Member() {
	$db = new WADB(SYSTEM_DBHOST, SYSTEM_DBNAME, SYSTEM_DBUSER, SYSTEM_DBPWD);
	return $db;
}

function getMemberData($db) {
	$sql = "SELECT `memNo`, `memSubEmail`, `memPwd`, `memIdNum`
			FROM `member` 
			WHERE (`memIdNum` IS NOT NULL
			OR `memSubEmail` IS NOT NULL 
			AND `memAllowLogin` = 1)
            and memNo BETWEEN '1000001' and '1025000'";
	$data = $db->selectRecords($sql);
	return $data;
}

function updateMemberPw($db, $data) {

	if (!empty($data) && !is_null($data)) {

		$sql1 = " UPDATE `member` SET `memPwd` = CASE `memNo` ";
		
		$whereStr = $data[0]['memNo'];
		foreach ($data as $key => $value) {
			$sql2 .= " WHEN " . $value['memNo'] . " THEN '" . $value['newPwd'] . "'";
			if ($key > 0) {
				$whereStr .= "," . $value['memNo'];
			}
		}

		$sql = $sql1 . $sql2 . " END WHERE `memNo` IN (" . $whereStr . ")";
		writeLog($sql);

		$update = $db->updateRecords($sql);
		return $update;
	} else {
		return false;
	}
}

function generatePw() {

	$units = array();
	for ($i = 0; $i < 1000000; $i++) {
		$units[] = md5(uniqid(md5(microtime(true)), true));
	}

	return $units;
}

function writeLog($jsonArray) {
	if (gettype($jsonArray) == "array") { 
		$jsonString = json_encode($jsonArray);
	} else if (gettype($jsonArray) == "string") {
		$jsonString = $jsonArray;
	}
	$fp = fopen(__DIR__ . "/passwordLog.txt", "w+");
	fwrite($fp, $jsonString);
	fclose($fp);
}

function writeOldPwd($data) {

	$stringValue = "";
	$result = array();

	$sql1 = " UPDATE `member` SET `memPwd` = CASE `memNo` ";
	$whereStr = $data[0]['memNo'];

	foreach ($data as $key => $value) {
		$result[]['memNo'] = $value['memNo'];
		$result[]['memPwd'] = $value['memPwd'];
		$sql2 .= " WHEN " . $value['memNo'] . " THEN '" . $value['memPwd'] . "'";
		if ($key > 0) {
			$whereStr .= "," . $value['memNo'];
		}
	}

	$sql = $sql1 . $sql2 . " END WHERE `memNo` IN (" . $whereStr . ")";
	$stringValue = json_encode($result);

	$fp = fopen(__DIR__ . "/oldPwd.txt", "a+");
	fwrite($fp, $stringValue . "\r\n" . $sql);
	fclose($fp);
}

//收件人地址 Address ，多個MAIL用逗號分隔
//主旨  Subject
//內容  Content 
//ApiKey ApiKey，"說好的瑪莎拉蒂勒
function curl_email($query) {

    $url = "http://api.21-finance.com/api/mail";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($query));
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
    $output = curl_exec($ch);
    curl_close($ch);
}

function mailContent($encodeString, $mailAddr) {

	$title = "【樂分期購物網】重要通知!請重新變更密碼";

	$content = '
	<head>
		<meta charset="UTF-8">
	</head>
	<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
		<tbody>
			<tr>
				<td style="color:#FF3333;font-weight:bold;text-align:center;">
					此為系統自動通知信，請勿直接回信！<br>
					若您有任何問題，請透過網站<a href="https://develop.happyfan7.com/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a>人員查詢。
				</td>
			</tr>
			<tr>
				<td style="color:black;font-weight:bold;background-color:#F5F3F1;">
					<p>親愛的會員您好，請點選以下連結進行密碼變更作業。</p>
					<a href="https://develop.happyfan7.com/index.php?item=member_center&action=password_edit&key='
					. $encodeString. '">http://develop.happyfan7.com/index.php?item=member_center&action=password_edit&key=' . $encodeString . '</a>
				</td>
			</tr>
		</tbody>
	</table>';

	$query = array(
		"Address" => $mailAddr,
		"Subject" => $title,
		"Content" => $content,
		"ApiKey" => "說好的瑪莎拉蒂勒"
	);

	curl_email($query);
}

// db connect
$member = Member();

// gen pwd
echo "=======> gen pwd \n";
$units = generatePw();

// get member data
echo "=======> get member data \n";
$data = getMemberData($member);

// write log for old pwd
echo "=======> write log for old pwd \n";
writeOldPwd($data);

// add newPwd to member data
echo "=======> add newPwd to member data \n";
$length = count($data);
for ($i = 0; $i < $length; $i++) {
	$data[$i]['newPwd'] = substr($units[$i], 0, 10);
}

// update newPwd to member table and write log
echo "=======> update newPwd to member table and write log \n";
updateMemberPw($member, $data);

// send email

echo "=======> send email \n";
// gen base64
foreach($data as $key => $value) {
	$encodeString = $value['memIdNum'] . "@" . $value['newPwd'];
	$encodeString = base64_encode($encodeString);
	mailContent($encodeString, $value['memSubEmail']);
}

unset($member);
?>