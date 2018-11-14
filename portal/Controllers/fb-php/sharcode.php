<?php

session_start();
require_once 'src/Facebook/autoload.php';

$helper = $fb->getRedirectLoginHelper();
$permissions = ['email', 'user_friends','public_profile', 'user_birthday','user_location','user_link'];
$loginUrl = $helper->getLoginUrl('http://nowait.kahap.com/fan7king_dev2/fb-php/fbconfig.php', $permissions);
	$_SESSION['user']['sharcode'] = $_GET['sharcode'];
	header('Location: '.$loginUrl);

?>
