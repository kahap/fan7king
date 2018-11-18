<?php
session_start();
// added in v4.0.0
require_once 'src/Facebook/autoload.php';
	include('../cls/Member.cls.php');
	include_once('../lib/lib.php');
	include_once('../cfg/cfg.inc.php');
	include_once('../cls/WADB.cls.php');	
$member = new Member();



$fb = new Facebook\Facebook([
	'app_id' => AutoloadAPPId,
	'app_secret' => AutoloadAPPSecret,
	'default_graph_version' => FbADVersion,
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
	$response = $fb->get('/me/taggable_friends?fields=name&limit=100');
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


/*
// init app with app id and secret
FacebookSession::setDefaultApplication( '1557221921249508','aaf94a1bd83321900f71695a99f458b5' );

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
	  '/me/'
	);
	$response = $request->execute();
	$graphObject = $response->getGraphObject();
	$frds = $graphObject->asArray();
	print_r($frds);
}else{
	echo "no seesion";
}
*/

?>