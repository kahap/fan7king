<?php
session_start();
header("Content-Type:text/html; charset=utf-8");
// added in v4.0.0
require_once 'autoload.php';
	include('../cls/Member.cls.php');
	include_once('../lib/lib.php');
	include_once('../cfg/cfg.inc.php');
	include_once('../cls/WADB.cls.php');	
$member = new Member();
$str = ($_SESSION['pro'] != "") ? "?item=product&pro=".$_SESSION['pro']:"";

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication( AutoloadAPPId , AutoloadAPPSecret );
// login helper with redirect_uri
    $helper = new FacebookRedirectLoginHelper('https://happyfan7.com/fb-php/fbconfig.php');
try {
  $session = $helper->getSessionFromRedirect();
} catch( FacebookRequestException $ex ) {
  // When Facebook returns an error
} catch( Exception $ex ) {
  // When validation fails or other local issues
}
// see if we have a session
  
if ( isset( $session ) ) {
  $request = new FacebookRequest( $session, 'GET', '/me?fields=name,gender,email' );
  $helper->getLoginUrl(array('scope' => 'email'));
  $response = $request->execute();
  $graphObject = $response->getGraphObject();
     	$fbid = $graphObject->getProperty('id');             
 	    $fbfullname = $graphObject->getProperty('name'); 
	    $femail = $graphObject->getProperty('email');    
		$gender = $graphObject->getProperty('gender') == "male" ? 1 : 0;
	$member_data = $member->check_FBtoken($fbid);
	if($member_data != ""){
		if($member_data['memAllowLogin'] == 1){
			$_SESSION['user']['memName'] = $member_data['memName'];
			$_SESSION['user']['memNo'] = $member_data['memNo'];
			header("Location: ../index.php$str");
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
	    $array['memSubEmail'] =  $femail;
		$array['gender'] = $gender;
		$array['memRecommCode'] = $_SESSION['user']['sharcode'];
		$array['pass_number'] = Pass();
		
		$id = $member->insert_FBtoken($array);
		$_SESSION['user']['memName'] = $fbfullname;
		$_SESSION['user']['memNo'] = $id;
		header("Location: ../index.php$str");
	}	
	$accessToken = $session->getToken();
	$_SESSION['user']["fb_access_token"] = (string) $accessToken;
} else {
$loginUrl = $helper->getLoginUrl();
 header("Location: ".$loginUrl);
}

	function Pass($i=8) { 
	    srand((double)microtime()*1000000); 
	    return strtoupper(substr(md5(uniqid(rand())),rand(0,32-$i),$i)); 
	}
?>