<?php
session_start();
require_once 'src/Facebook/autoload.php';
$fb = new Facebook\Facebook([
  'app_id' => '101663476857298',
  'app_secret' => '1f419adaeae978bf5395ce69503816df',
  'default_graph_version' => 'v2.2',
  ]);

$helper = $fb->getRedirectLoginHelper();

$permissions = ['email']; // Optional permissions
$loginUrl = $helper->getLoginUrl('http://guide4me.com/fb-php/fb-callback.php', $permissions);

echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
?>