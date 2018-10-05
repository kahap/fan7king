<body class="nav-md">
	<?php 
	require_once('model/require_general.php');
	//時間
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d', time());
	$year = explode("-", $date)[0];
	
	$or = new Orders();
	$ag = new Admin_Group();
	$agData = $ag->getOneAGByNo($_SESSION['userdata']['agNo']);
	$curRrightArr = json_decode($agData[0]["agRight"]);
	?>
  <div class="container body">


    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
            <a href="admin.php" class="site_title"><i class="fa fa-paw"></i> <span>樂分期管理後台</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>你好,</span>
              <h2><?php echo $_SESSION['userdata']['smName']; ?></h2>
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            
            <div class="menu_section">
              <h3>
	              <?php 
	              	$ag = new Admin_Group();
	              	$agData = $ag->getOneAGByNo($_SESSION['userdata']['agNo']);
	              	echo $agData[0]['agName'];
	              ?>
              </h3>
              <ul class="nav side-menu">
                <?php if(in_array(0, $curRrightArr)){ ?>
              	<li><a><i class="fa fa-home"></i> 摘要資訊 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=briefInfo&type=general">摘要資訊</a></li>
                    <li><a href="?page=briefInfo&type=monthly&year=<?php echo $year; ?>">每月統計</a></li>
                  </ul>
                </li>
                <?php } ?>
				<?php if(in_array(10, $curRrightArr) || in_array(11, $curRrightArr) || in_array(12, $curRrightArr) || in_array(13, $curRrightArr)){ ?>
                <li><a><i class="fa fa-user"></i> 會員管理 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <?php if(in_array(10, $curRrightArr)){ ?>
                    <li><a href="?page=member&type=member">會員資訊</a></li>
                    <?php } ?>
                    <?php if(in_array(15, $curRrightArr)){ ?>
                    <li><a href="?page=member&type=recomm_list">推薦人清單</a></li>
                    <?php } ?>
                    <?php if(in_array(11, $curRrightArr)){ ?>
                    <li><a href="?page=member&type=loyalGuest">老客戶查詢</a></li>
                    <?php } ?>
                    <?php if(in_array(13, $curRrightArr)){ ?>
                    <li><a href="?page=recommBonus&type=confirm&status=0">推薦碼獎金撥款</a></li>
                	<?php } ?>
                    <?php if(in_array(12, $curRrightArr)){ ?>
                    <li><a href="?page=customer&type=textmsg">簡訊通知</a></li>
                    <li><a href="?page=customer&type=email">Email通知</a></li>
                    <?php } ?>
                  </ul>
                </li>
				<?php } ?>
                <?php if(in_array(20, $curRrightArr)){ ?>
                <li><a><i class="fa fa-question"></i> 常見問題 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=qanda">常見問題管理</a></li>
                  </ul>
                </li>
                <li><a><i class="fa fa-question"></i> 常見問題-APP <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=qaapp">APP常見問題管理</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(30, $curRrightArr)){ ?>
                <li><a><i class="fa fa-tag"></i> 供應商管理 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=supplier">供應商瀏覽</a></li>
                    <li><a href="?page=supplier&action=productList">供貨商品清單</a></li>
                    <li><a href="?page=supplier&action=1&supno=all&orDateFrom=2016-01-01&orDateTo=<?php echo $date; ?>&status=3">分期訂貨(核准)</a></li>
                    <li><a href="?page=supplier&action=0&supno=all&orDateFrom=2016-01-01&orDateTo=<?php echo $date; ?>&status=0&orPaySuccess=1">直購訂貨(已付處理)</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(40, $curRrightArr)){ ?>
                <li><a><i class="fa fa-briefcase"></i> 商品管理 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=product&type=general&which=category">分類管理</a></li>
                    <li><a href="?page=product&type=general&which=brand">品牌管理</a></li>
                    <li><a href="?page=product&type=product">商品總覽</a></li>
                    <li><a href="?page=product&type=productManage">商品上架管理</a></li>
                    <li><a href="?page=product&type=productManage&special=new">最新排序</a></li>
                    <li><a href="?page=product&type=productManage&special=special">精選排序</a></li>
                    <li><a href="?page=product&type=productManage&special=hot">限時排序</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(50, $curRrightArr)){ ?>
                <li><a><i class="fa fa-briefcase"></i> 案件審查時間報表 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=report&type=statusReport&orDateFrom=<?php echo $date; ?>&orDateTo=<?php echo $date; ?>"> 案件審查時間報表</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(60, $curRrightArr) || in_array(61, $curRrightArr)){ ?>
                <li><a><i class="fa fa-file-text-o"></i> 分期進件 <span class="fa fa-chevron-down"></span></a>
                  <ul id="order-submenu" class="nav child_menu" style="display: none">
                  	<li><a href="?page=order&action=query&method=1"> 分期案件查詢</a></li>
                    <?php 
                    foreach($or->statusArr as $key=>$value){
						if(in_array(61, $curRrightArr) && !in_array(60, $curRrightArr)){
							if($key == 1 || $key == 110 || $key == 2 || $key == 5 || $key == 6 || $key == 0 || $key == 3 || $key == 4 || $key == 7){
                    ?>
                    	<li>
	                    	<a href="?page=order&method=1&status=<?php echo $key; ?>&orDateFrom=2016-04-01&orDateTo=<?php echo $date;?>">
	                    		<?php echo $value; ?>
	                    		<?php 
	                    		switch($key){
	                    			/*case 0:
	                    		?>
	                    			<br>(待客戶驗證：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(0,1)); ?>)
								<?php
	                    			break;*/
									/*case 110:
	                    		?>
	                    			<br>(待完成：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(110,1)); ?>)
	                    		<?php
	                    			break;*/
									/*case 1:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(1,1)); ?> / 未列印：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(1,1,0)); ?>)
	                    		<?php
	                    			break;
									case 2:
	                    		?>
	                    			<br>(待審核：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(2,1)); ?>)
	                    		<?php
	                    			break;*/
									case 3:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(3,1)); ?>)
	                    		<?php
	                    			break;
									/*case 4:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(4,1)); ?>)
	                    		<?php
	                    			break;
									case 5:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(5,1)); ?> / 顧客尚未查看：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(5,1,0)); ?>)
	                    		<?php
	                    			break;
									case 6:
	                    		?>
	                    			<br>(待審核：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(6,1)); ?> / 未列印：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(6,1,0)); ?>)
	                    		<?php
	                    			break;
									case 7:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(7,1)); ?>)
	                    		<?php
	                    			break;*/
									case 8:
	                    		?>
	                    			<br>(等待中：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(8,1)); ?>)
	                    		<?php
	                    			break;
									case 9:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(9,1)); ?>)
	                    		<?php
	                    			break;
									/*case 10:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(10,1)); ?> / 未列印：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(10,1,0)); ?>)
	                    		<?php
	                    			break;
									case 11:
	                    		?>
	                    			<br>(等待中：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(11,1)); ?>)
	                    		<?php
	                    			break;
									case 12:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(12,1)); ?>)
	                    		<?php
	                    			break;
									case 13:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(13,1)); ?> / 未處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(13,1,0)); ?>)
	                    		<?php 
	                    			break;*/
	                    		}
	                    		?>
	                    	</a>
                    	</li>
                    <?php 
							}
						}else if(in_array(60, $curRrightArr)){
					?>
						<li>
	                    	<a href="?page=order&method=1&status=<?php echo $key; ?>&orDateFrom=2016-04-01&orDateTo=<?php echo $date;?>">
	                    		<?php echo $value; ?>
	                    		<?php 
	                    		switch($key){
	                    			/*case 0:
	                    		?>
	                    			<br>(待客戶驗證：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(0,1)); ?>)
								<?php
	                    			break;
									case 110:
	                    		?>
	                    			<br>(待完成：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(110,1)); ?>)
	                    		<?php
	                    			break;
									case 1:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(1,1)); ?> / 未列印：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(1,1,0)); ?>)
	                    		<?php
	                    			break;
									case 2:
	                    		?>
	                    			<br>(待審核：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(2,1)); ?>)
	                    		<?php
	                    			break;*/
									case 3:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(3,1)); ?>)
	                    		<?php
	                    			break;
									/*case 4:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(4,1)); ?>)
	                    		<?php
	                    			break;
									case 5:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(5,1)); ?> / 顧客尚未查看：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(5,1,0)); ?>)
	                    		<?php
	                    			break;
									case 6:
	                    		?>
	                    			<br>(待審核：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(6,1)); ?> / 未列印：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(6,1,0)); ?>)
	                    		<?php
	                    			break;
									case 7:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(7,1)); ?>)
	                    		<?php
	                    			break;*/
									case 8:
	                    		?>
	                    			<br>(等待中：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(8,1)); ?>)
	                    		<?php
	                    			break;
									case 9:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(9,1)); ?>)
	                    		<?php
	                    			break;
									/*case 10:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(10,1)); ?> / 未列印：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(10,1,0)); ?>)
	                    		<?php
	                    			break;
									case 11:
	                    		?>
	                    			<br>(等待中：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(11,1)); ?>)
	                    		<?php
	                    			break;
									case 12:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(12,1)); ?>)
	                    		<?php
	                    			break;
									case 13:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(13,1)); ?> / 未處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(13,1,0)); ?>)
	                    		<?php 
	                    			break;*/
	                    		}
	                    		?>
	                    	</a>
                    	</li>
					<?php		
						}
                    } 
                    ?>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(70, $curRrightArr)){ ?>
                <li><a><i class="fa fa-file-text-o"></i> 直購進件 <span class="fa fa-chevron-down"></span></a>
                  <ul id="order-submenu" class="nav child_menu" style="display: none">
                  	<li><a href="?page=order&action=query&method=0"> 直購案件查詢</a></li>
                    <?php foreach($or->statusDirectArr as $key=>$value){?>
                    	<li>
	                    	<a href="?page=order&method=0&status=<?php echo $key; ?>&orDateFrom=2016-04-01&orDateTo=<?php echo $date;?>">
	                    		<?php echo $value; ?>
	                    		<?php 
	                    		switch($key){
	                    			case 0:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(0,0)); ?>)
	                    		<?php
	                    			break;
									case 1:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(1,0)); ?>)
	                    		<?php
	                    			break;
									case 2:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(2,0)); ?>)
	                    		<?php
	                    			break;
									case 3:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(3,0)); ?>)
	                    		<?php
	                    			break;
									case 4:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(4,0)); ?> / 未處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(4,0,0)); ?>)
	                    		<?php
	                    			break;
									case 5:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(5,0)); ?>)
	                    		<?php
	                    			break;
									case 6:
	                    		?>
	                    			<br>(待處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(6,0)); ?>)
	                    		<?php
	                    			break;
									case 7:
	                    		?>
	                    			<br>(總共：<?php echo sizeof($or->getOneOrderByOrStatusAndMethod(7,0)); ?> / 未處理：<?php echo sizeof($or->getOneOrderByOrStatusAndMethodAndIfProcess(7,0,0)); ?>)
	                    		<?php 
	                    			break;
	                    		}
	                    		?>
	                    	</a>
                    	</li>
                    <?php } ?>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(80, $curRrightArr)){ ?>
                <li><a><i class="fa fa-key"></i> 權限管理 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=right&type=group">群組管理</a></li>
                    <li><a href="?page=right&type=account">後台帳號管理</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(90, $curRrightArr)){ ?>
                <li><a><i class="fa fa-pencil"></i> 前台編輯頁面 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=edit_front&type=fmBuyStep">購物流程</a></li>
                    <li><a href="?page=edit_front&type=fmFreeRespons">免責聲明</a></li>
                    <li><a href="?page=edit_front&type=fmServiceRules">服務條款</a></li>
                    <li><a href="?page=edit_front&type=fmPrivacy">隱私權聲明</a></li>
					<li><a href="?page=edit_front&type=fmLoanVIP">貸款VIP服務</a></li>
                    <li><a href="?page=edit_front&type=fmRecBonus">什麼是推薦碼</a></li>
					<li><a href="?page=edit_front&type=fmDirectBuyRules">直購流程</a></li>
					<li><a href="?page=edit_front&type=fmContactService">聯絡客服</a></li>
					<li><a href="?page=edit_front&type=fmCoopDetail">合作提案</a></li>
					<li><a href="?page=edit_front&type=fmBuyMustKnow">購物須知</a></li>
					<li><a href="?page=edit_front&type=fmPeriodDeclare">分期付款約定書</a></li>
					<li><a href="?page=edit_front&type=fmInstallPromise">分期付款約定事項</a></li>
					<li><a href="?page=edit_front&type=fmAboutUs">關於我們</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(100, $curRrightArr)){ ?>
                <li><a><i class="fa fa-dollar"></i> 發票號碼串接 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=receipt&type=setting">鑑賞期天數設定</a></li>
                    <li><a href="?page=receipt&type=view">開立發票訂單查詢</a></li>
                    <li><a href="?page=receipt&type=import">匯入發票明細</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(110, $curRrightArr)){ ?>
                <li><a><i class="fa fa-windows"></i> 其餘功能 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=other&type=orderDays">訂單過N天隱藏</a></li>
                    <li><a href="?page=other&type=txtmsgSwitch">簡訊通知開關</a></li>
                    <li><a href="?page=other&type=advertise">廣告管理</a></li>
                    <li><a href="?page=other&type=news">最新消息</a></li>
                    <li><a href="?page=other&type=recommSetting">推薦獎金設定</a></li>
                    <li><a href="?page=other&type=fbLink">FB粉絲團連結</a></li>
                    <li><a href="?page=other&type=hotkeys">商品熱門字設定</a></li>
                    <li><a href="?page=other&type=periodSetting">分期月付計算設定-1</a></li>
                    <li><a href="?page=other&type=periodSetting2">分期月付計算設定-2</a></li>
                    <li><a href="?page=other&type=companyCoop">廠商合作提案</a></li>
                    <li><a href="?page=other&type=loan_vip">貸款VIP服務</a></li>
                  </ul>
                </li>
                <?php } ?>
                <?php if(in_array(111, $curRrightArr)){ ?>
                <li><a><i class="fa fa-windows"></i> 版本控款 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=version&type=edit">更改版本號</a></li>
                  </ul>
                </li>
                <?php } ?>
              </ul>
            </div>

          </div>
          <!-- /sidebar menu -->

          <!-- /menu footer buttons -->
          <div class="sidebar-footer hidden-small">
            <a data-toggle="tooltip" data-placement="top" title="Settings">
              <span class="glyphicon glyphicon-cog" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="FullScreen">
              <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Lock">
              <span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span>
            </a>
            <a data-toggle="tooltip" data-placement="top" title="Logout">
              <span class="glyphicon glyphicon-off" aria-hidden="true"></span>
            </a>
          </div>
          <!-- /menu footer buttons -->
        </div>
      </div>
<script>
	$(function(){
		//即時更新推薦獎金是否可領取
		$.ajax({
			url:"ajax/rba/edit_status_all.php",
			type:"post",
			success:function(result){
				
			}
		});
	});
</script>