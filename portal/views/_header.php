<?php

	$src = (isset($_GET['pro']) && $_GET['pro'] != "") ?
        'https://'.DOMAIN.'/index.php?item=product&pro='.$_GET['pro'].'&share='.$_SESSION['user']['memNo']:
        'https://'.DOMAIN.'/index.php';


	$pm = new Product_Manage();
    $pm_detail = null;

	if(isset($_GET['pro']) && $_GET['pro'] != "")
	{
		$pm_detail = $pm->getAllByProName($_GET['pro']); //商品內容
		$img = json_decode($pm_detail[0]['proImage']);
		$img[0] = ($img[0] != "")? $img[0] : "admin/images/indexImg/20160607062650.jpg";

		//設定商品期數與行銷字眼
		$ps = new Period_Setting();
		$month = $ps->getAllPS();
		if ($month) {
            foreach ($month as $k => $v) {
                $price[ $v['psMonthNum'] ] = ceil($v['psRatio'] * $pm_detail[0]['pmPeriodAmnt'] / $v['psMonthNum']);
            }
            $str_price = "月付: NT $".number_format(min($price))."*". array_search(min($price),$price)."期";
        }else{
            $price = array();
            $str_price = '沒有分期基數設定!';
        }
		
	}else{
		$str_price = '服務體驗最佳的免卡分期電商購物網，免卡分期、0元取貨，不需信用卡也能輕鬆分期支付。';
	}


	$category = new Category();
	$cate_head = $category->getAllCatDisplayAndOrder();

?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1" />
    <meta name="rating" content="general" />

    <title><?php echo $_SESSION['vTitle'];?></title>


<!--	<title>樂分期-HappyFan7</title>-->
	<meta property="og:url"           content="<?php echo $src; ?>" />
	<meta property="og:type"          content="website" />
	<meta property="og:title"         content="<?php echo ($pm_detail && $pm_detail[0]['proName'] != '')? $pm_detail[0]['proName'] : 'Nowait 商城'; ?>" />
	<meta property="og:description"   content="<?php echo $str_price; ?>" />
	<meta property="og:image"         content="https://<?php echo DOMAIN; ?>/<?php echo ($img[0]!="")? "admin/".$img[0] : "admin/images/indexImg/20160607062650.jpg"; ?>" />

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!--	<meta name="viewport" content="width=device-width, initial-scale=1">-->
	<link rel="icon" href="assets/data/page_icon.png">
    <script type='text/javascript' src='portal/assets/lib/jquery/jquery-1.11.2.min.js'></script>
<!--    <link rel="stylesheet" type="text/css" href="assets/lib/bootstrap/css/bootstrap.min.css" />-->
    <link rel="stylesheet" type="text/css" href="portal/assets/lib/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/lib/select2/css/select2.min.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/lib/jquery.bxslider/jquery.bxslider.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/lib/owl.carousel/owl.carousel.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/lib/fancyBox/jquery.fancybox.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/lib/jquery-ui/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/css/animate.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/css/reset.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/css/style.css" />
    <link rel="stylesheet" type="text/css" href="portal/assets/css/responsive.css" />


    <link rel="stylesheet" href="portal/assets/bootstrap/bootstrap.min.css" />
    <link rel="stylesheet" href="portal/assets/slick/slick.css" />
    <link rel="stylesheet" href="portal/assets/theme/css/theme.css" />
    <style>
        /*.card_1 {border:none;box-shadow:0 5px 6px 0 rgba(0,0,0,.16);border-radius:0;height:97%;margin:0 .5rem;padding-bottom:18px}*/
        .card_1{border:none;height:200px;text-align:center;background-image:linear-gradient(to left,transparent,transparent 50%,#f8f8f8 50%,#f8f8f8);background-position:100% 0;background-size:200% 100%;transition:all .25s ease-in}
        .card_1:hover{background-position:0 0}
        .btn-group-toggle label.btn {
            margin-top: 10px;
        }
    </style>

    <link type="text/css" rel="stylesheet" href="portal/assets/css/waitMe.css">
    <script src="portal/assets/js/waitMe.js"></script>

</head>
<body class="home">
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <div class="navbar-box">
                <div class="row">
                    <div class="col-lg-3 col-md-12">
                        <a href="index.php" title="Nowait" class="navbar-brand">
                            <img class="img-fluid" src="portal/assets/images/svg/logo.svg" data-src-base="portal/images/svg/" data-src="<991:logo-mobile.svg,>991:logo.svg" width="284" height="85" alt="Nowait">
                        </a>
                    </div>
                    <div class="col-4 d-flex align-items-center">
                        <img src="portal/assets/images/slogan.png" class="img-fluid" alt="免卡分期 享購現在">
                    </div>
                    <div class="col-5 d-flex align-items-center">
                        <form class="form form-search" action="index.php" method="GET" >

                            <input type="hidden" name="item" value="search" />

                            <div class="input-group input-serach">
                                <input type="text" class="form-control" placeholder="熱門關鍵字：<?php
                                    $string = new Hotkeys();
                                    $string_key = $string->getAllHK();
                                    if($string_key[0]['hkEnable'] != '0'){
                                        echo $string_key[0]['hkKey'];
                                        $string_key2 = explode(',' , $string_key[0]['hkKey']) ;
                                    }else{
                                        echo 'Apple, Sony, Phone';
                                    }
                                ?>" name="search" value=""/>
                                <div class="input-group-append">
                                    <span class="input-group-btn">
                                        <button class="btn btn-dark btn-searc" type="submit">
                                            <img src="portal/assets/images/icon-search.png" alt="搜尋">
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <p class="text-orange">
                                <a href="?item=search&search=<?php echo trim($string_key2[0]);?>" title="">
                                    <?php  echo ($string_key2)? $string_key2[0] : '任天堂Switch'; ?>
                                </a>
                                <a href="?item=search&search=<?php echo trim($string_key2[1]);?>" title="">
                                    <?php  echo ($string_key2)? $string_key2[1] : 'PS4 pro'; ?>
                                </a>
<!--                                <a href="#" title="小米手環">小米手環</a>-->
                            </p>
                        </form>
                    </div>
                </div>
            </div>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navigation">
                <div class="row">
                    <div class="col-lg-3 col-md-12 order-md-12">
                        <ul class="list-inline float-right">
                        <?php
                        if($_SESSION['user']['memName'] != ""){
                            ?>
                            <li class="list-inline-item item-register">
                                <a href="?item=member_center"> <?php echo $_SESSION['user']['memName']?>，您好</a>
                                <a href="portal/Controllers/php/logout.php"  title="登出" class="xs-none">登出</a>
                            </li>
                            <?php
                        }else{
                            ?>
                            <li class="list-inline-item item-register">
                                <a href="?item=register<?php echo ($_GET['share'] != "") ? '&share='.$_GET['share']:'';?>" title="註冊">註冊</a>
                                <a href="?item=login<?php echo ($_GET['share'] != "") ? '&share='.$_GET['share']:'';?>" title="登入">登入</a>
                            </li>
                            <?php
                        }
                        ?>
                        </ul>
                    </div>
                    <div class="col-lg-4 col-md-12 order-md-6">
                        <ul class="list-inline">
                            <li class="list-inline-item <?php echo ($_GET['item'] == 'sup_center') ? 'active':''; ?>">
                                <a href="?item=sup_center" title="廠商專區">廠商專區</a>
                            </li>
                            <li class="list-inline-item <?php echo ($_GET['item'] == 'faq') ? 'active':''; ?>">
                                <a href="?item=help" title="幫助中心">幫助中心</a>
                            </li>
                            <li class="list-inline-item <?php echo ($_GET['item'] == 'member_center') ? 'active':''; ?>">
                                <a href="?item=member_center" title="會員中心">會員中心</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-5 col-md-12 order-md-1">
                        <ul class="list-inline">
                            <li class="list-inline-item list-download">
                                <a href="#" title="下載APP">下載APP</a>
                                <div class="app d-none">
                                    <dl>
                                        <dt>下載NoWait</dt>
                                        <dd>
                                            <div class="row">
                                                <div class="col">
                                                    <img class="img-fluid" src="portal/assets/images/icon-applestore.png" alt="Apple Store">
                                                    <a href="#" title="下載NoWait">
                                                        <img class="img-fluid" src="portal/assets/images/Tmp/qrcode.png" alt="Apple Store">
                                                    </a>
                                                </div>
                                                <div class="col">
                                                    <img class="img-fluid" src="portal/assets/images/icon-googleplay.png" alt="Google Play">
                                                    <a href="#" title="下載NoWait">
                                                        <img class="img-fluid" src="portal/assets/images/Tmp/qrcode.png" alt="Android">
                                                    </a>
                                                </div>
                                            </div>
                                        </dd>
                                    </dl>
                                </div>
                            </li>
                            <li class="list-inline-item xs-none">
                                <a class="facebook" href="http://www.facebook.com/sharer.php?u=http://<?=DOMAIN?>/index.php%3Fshare%3D<?php echo $_SESSION['user']['memNo'] ?>" target="_blank">
                                    <img src="portal/assets/images/fb_share.png"/>
                                </a>
<!--                                <a class="facebook" href="#" title="追蹤Nowait">追蹤Nowait</a>-->
                            </li>
                            <li class="list-inline-item xs-show">
<!--                                <a class="logout" href="#" title="登　　出">登　　出</a>-->
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
    <!-- end header -->
