<?php
header('Content-Type: text/html; charset=utf8');
require_once('../../model/require_ajax.php');

session_start();
		if($_SESSION['adminUserData']['aauName'] != ""){
			$apiMem = new API("member");
			if($_POST['memNo_A'] != "" && $_POST['memNo_B'] != ""){
				$a = "select memFBtoken from member where memNo = '".$_POST['memNo_A']."'";
				$atoken = $apiMem->customSql($a);
				
				$b = "select memFBtoken from member where memNo = '".$_POST['memNo_B']."'";
				$btoken = $apiMem->customSql($b);
				
				$system  = "UPDATE `member` SET `memFBtoken`='".$btoken['0']['memFBtoken']."' where memNo = '".$_POST['memNo_A']."' ";
				$apiMem->customSql($system);
				
				$system  = "UPDATE `member` SET `memFBtoken`='".$atoken['0']['memFBtoken']."' where memNo = '".$_POST['memNo_B']."' ";
				$apiMem->customSql($system);
				
					
				$sql = "INSERT INTO `log`(`aauName`,`aauNo`,`memNo`) VALUES ('".$_SESSION['adminUserData']['aauName']."','".$_SESSION['adminUserData']['aauNo']."','".$_POST['memNo_A']."_".$_POST['memNo_B']."')";
				$apiMem->customSql($sql);
				echo "OK";
			}else{
				$system  = "UPDATE `member` SET `memName`='".trim($_POST['memName'])."',`memClass`='".trim($_POST['memClass'])."', memIdnum='".trim($_POST['memIdnum'])."',memSubEmail='".trim($_POST['memSubEmail'])."', memCell='".trim($_POST['memCell'])."' where memNo = '".$_POST['memNo']."' ";
				$apiMem->customSql($system);
				
				
				$sql = "INSERT INTO `log`(`aauName`,`aauNo`,`memNo`) VALUES ('".$_SESSION['adminUserData']['aauName']."','".$_SESSION['adminUserData']['aauNo']."','".$_POST['memNo']."')";
				$apiMem->customSql($sql);
				echo "OK";
			}
		}else{
			echo "請重新登入後做更新!!";
		}
			
			
			

?>