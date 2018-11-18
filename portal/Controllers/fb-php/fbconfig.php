<?php
session_start();
header("Content-Type:text/html; charset=utf-8");
// added in v4.0.0
include_once('../cfg/cfg.inc.php');
require_once 'src/Facebook/autoload.php';
	include('../cls/Member.cls.php');
	include('../cls/Orders.cls.php');
	include_once('../lib/lib.php');
	include_once('../cls/WADB.cls.php');
$member = new Member();
$order = new Orders();
$str = ($_SESSION['pro'] != "") ? "?item=product&pro=".$_SESSION['pro']:"";
$str = ($_SESSION['item'] != "") ? "?item=product_sup&pro=".$_SESSION['pro']:"";

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email'];
$loginUrl = $helper->getLoginUrl('https://'.DONAME.'/fb-php/fbconfig.php', $permissions);

try {
	$accessToken = $helper->getAccessToken();
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}
if ( isset( $accessToken ) ) {
	$fb->setDefaultAccessToken($accessToken);
		$response = $fb->get('/me?fields=name,gender,email');
		$graphObject = $response->getGraphNode();
     	$fbid = $graphObject->getProperty('id');
 	    $fbfullname = $graphObject->getProperty('name');
	    $femail = $graphObject->getProperty('email');
		$gender = $graphObject->getProperty('gender') == "male" ? 1 : 0;
	$member_data = $member->check_FBtoken($fbid);
	if($member_data != ""){
		$Iforder = $order->getOrByMember($member_data['memNo']);
		if($member_data['memAllowLogin'] == 1){
            $_SESSION['user']['memName'] = $member_data['memName'];
            $_SESSION['user']['memNo'] = $member_data['memNo'];
            $_SESSION['user']['memClass'] = $member_data['memClass'];
            if($member_data['memIdNum'] == null || $member_data['memIdNum']=='' ){
                header("Location: ../index.php?item=member_center&action=member_idnum");
            }
            else
            {
                $_SESSION['user']['memIdNum'] = $member_data['memIdNum'];
                if($member_data['edit'] == '0' && $Iforder == '' && empty($_SESSION['user']['memClass'])){
                    header("Location: ../index.php?item=information_edit");
                }else{
					header("Location: ../index.php$str");
                }
            }
		}else{
			echo "<script>alert('您的帳號已經設定停權，如有任何問題請洽客服人員，謝謝')</script>";
			echo "<script>location.href='../index.php?item=login'</script>";
		}
		if(empty($member_data['memSubEmail']) || $member_data['memSubEmail'] == "無"){
			if($femail != ""){
				$member->updateMemberEmail($femail,$member_data['memNo']);
			}
		}
		if(empty($member_data['memGender']) || $member_data['memGender'] == "2"){
			$member->updateMemberGender($gender,$member_data['memNo']);
		}
	}else{
		$array['memFBtoken'] = $fbid;
        $array['memName'] = $fbfullname;
		//$array['memName'] = '新會員';
	    $array['memSubEmail'] =  $femail;
		$array['memGender'] = $gender;
		$array['memRecommCode'] = $_SESSION['user']['sharcode'];
		$array['pass_number'] = Pass();

		$id = $member->insert_FBtoken($array);
		$_SESSION['user']['memName'] = $fbfullname;
		$_SESSION['user']['memNo'] = $id;
		//print_r($array);
		//header("Location: ../index.php$str");
		header("Location: ../index.php?item=information_edit");
	}
	$accessToken = @$session->getToken();
	$_SESSION['user']["fb_access_token"] = (string) $accessToken;
} else {
	$scope = array('email', 'user_friends','public_profile', 'user_birthday','user_location','user_link');
	$loginUrl = $helper->getLoginUrl($scope);
	header("Location: ".$loginUrl);
}

	function Pass($i=8) {
	    srand((double)microtime()*1000000);
	    return strtoupper(substr(md5(uniqid(rand())),rand(0,32-$i),$i));
	}
?>