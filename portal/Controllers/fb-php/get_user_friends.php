<?php
session_start();
// added in v4.0.0
require_once 'autoload.php';
	include('../cls/Member.cls.php');
	include_once('../lib/lib.php');
	include_once('../cfg/cfg.inc.php');
	include_once('../cls/WADB.cls.php');	
$member = new Member();

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

/*
$fb = new Facebook([
  'app_id' => '1557221921249508',
  'app_secret' => 'aaf94a1bd83321900f71695a99f458b5',
  'default_graph_version' => 'v2.4',
]);

$helper = $fb->getCanvasHelper();
$permissions = ['user_friends', 'user_likes'];

try {
	if(!isset($_SESSION['user']["fb_access_token"])){
		$accessToken = $helper->getAccessToken();
		$_SESSION['user']["fb_access_token"] = (string) $accessToken;
	}
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}

$fb->setDefaultAccessToken($_SESSION['user']["fb_access_token"]);

try {
	$response = $fb->get('/me/taggable_friends');
	$frds = $response->getGraphEdge();
	
	print_r($frds);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
	// When Graph returns an error
	echo 'Graph returned an error: ' . $e->getMessage();
	exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
	// When validation fails or other local issues
	echo 'Facebook SDK returned an error: ' . $e->getMessage();
	exit;
}
*/



// init app with app id and secret
FacebookSession::setDefaultApplication( '358306487860510','bbff3360de628c59397f27261daceb02' );

// If you already have a valid access token:
$session = new FacebookSession($_SESSION['user']["fb_access_token"]);

// To validate the session:
try {
  $session->validate();
} catch (FacebookRequestException $ex) {
  // Session not valid, Graph API returned an exception with the reason.
  echo $ex->getMessage();
} catch (\Exception $ex) {
  // Graph API returned info, but it may mismatch the current app or have expired.
  echo $ex->getMessage();
}

// see if we have a session
if ( isset( $session ) ) {
	$request = new FacebookRequest(
	  $session,
	  'GET',
	  '/me?fields=name,gender,email'
	);
	$response = $request->execute();
	$graphObject = $response->getGraphObject();
	$fbid = $graphObject->getProperty('id');             
	$fbfullname = $graphObject->getProperty('name'); 
	$femail = $graphObject->getProperty('email');    
	$gender = $graphObject->getProperty('gender');
	echo $femail;
}else{
	echo "no seesion";
}


?>