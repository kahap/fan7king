<?php
session_start();
require_once 'src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => AutoloadAPPId,
  'app_secret' => AutoloadAPPSecret,
  'default_graph_version' => FbADVersion,
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://guide4me.com/fb-php/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>