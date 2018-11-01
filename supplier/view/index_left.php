<body class="nav-md">
	<?php 
	require_once('model/require_general.php');
	//時間
	date_default_timezone_set('Asia/Taipei');
	$date = date('Y-m-d', time());
	$year = explode("-", $date)[0];
	
	$or = new Orders();
	
	?>
  <div class="container body">


    <div class="main_container">

      <div class="col-md-3 left_col">
        <div class="left_col scroll-view">

          <div class="navbar nav_title" style="border: 0;">
            <a href="admin.php" class="site_title"><i class="fa fa-circle"></i> <span >NoWait 夥伴商城</span></a>
          </div>
          <div class="clearfix"></div>

          <!-- menu prile quick info -->
          <div class="profile">
            <div class="profile_pic">
              <img src="images/img.jpg" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
              <span>你好,</span>
              <h2><?php echo $_SESSION['supplieruserdata']['supName']; ?></h2>
            </div>
          </div>
          <!-- /menu prile quick info -->

          <br />

          <!-- sidebar menu -->
          <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">

            
            <div class="menu_section">
            <h3>供應商</h3>
              <ul class="nav side-menu">
                <li><a><i class="fa fa-briefcase"></i> 商品管理 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" >  
                    <li><a href="?page=product&type=productManage">商品上架</a></li>                  
                  </ul>
                </li>
	<!-- new add -->
                <li><a><i class="fa fa-tag"></i> 人員登錄 <span class="fa fa-chevron-down"></span></a>
                  <ul class="nav child_menu" style="display: none">
                    <li><a href="?page=supplier">業務人員</a></li>
                  </ul>
                </li> 
    <!-- new add -->      
                <li><a><i class="fa fa-file-text-o"></i> 案件進度 <span class="fa fa-chevron-down"></span></a>
                  <ul id="order-submenu" class="nav child_menu" >
                  	<li><a href="?page=order&action=query&method=1"> 分期案件查詢</a></li>
                    <?php 
                   
                    foreach($or->statusArr as $key=>$value){	
			
					?>
						<li>
	                    	<a href="?page=order&method=1&status=<?php echo $key; ?>&orDateFrom=2016-04-01&orDateTo=<?php echo $date;?>">
	                    		<?php echo $value;?>
	                    		<?php 
	                    		switch($key){	
                    			case 0:

	                    		?>
	                    			<br>(待客戶驗證：<?php echo count($or->getOneOrderByOrStatusAndMethod(0,1)); ?>)
								<?php
	                    			break;
									case 110:
	                    		?>
	                    			<br>(待完成：<?php echo count($or->getOneOrderByOrStatusAndMethod(110,1)); ?>)
	                    		<?php
	                    			break;
									case 1:
	                    		?>
	                    			<br>(待處理：<?php echo count($or->getOneOrderByOrStatusAndMethod(1,1)); ?> / 未列印：<?php echo count($or->getOneOrderByOrStatusAndMethodAndIfProcess(1,1,0)); ?>)
	                    		<?php
	                    			break;
									case 2:
	                    		?>
	                    			<br>(待審核：<?php echo count($or->getOneOrderByOrStatusAndMethod(2,1)); ?>)
	                    		<?php
	                    			break;
									case 3:
	                    		?>
	                    			<br>(待處理：<?php echo count($or->getOneOrderByOrStatusAndMethod(3,1)); ?>)
	                    		<?php
	                    			break;
									case 4:
	                    		?>
	                    			<br>(總共：<?php echo count($or->getOneOrderByOrStatusAndMethod(4,1)); ?>)
	                    		<?php
	                    			break;
									case 5:
	                    		?>
	                    			<br>(待處理：<?php echo count($or->getOneOrderByOrStatusAndMethod(5,1)); ?> / 顧客尚未查看：<?php echo count($or->getOneOrderByOrStatusAndMethodAndIfProcess(5,1,0)); ?>)
	                    		<?php
	                    			break;
									case 6:
	                    		?>
	                    			<br>(待審核：<?php echo count($or->getOneOrderByOrStatusAndMethod(6,1)); ?> / 未列印：<?php echo count($or->getOneOrderByOrStatusAndMethodAndIfProcess(6,1,0)); ?>)
	                    		<?php
	                    			break;
									case 7:
	                    		?>
	                    			<br>(總共：<?php echo count($or->getOneOrderByOrStatusAndMethod(7,1)); ?>)
	                    		<?php
	                    			break;
									case 8:
	                    		?>
	                    			<br>(等待中：<?php echo count($or->getOneOrderByOrStatusAndMethod(8,1)); ?>)
	                    		<?php
	                    			break;
									case 9:
	                    		?>
	                    			<br>(待處理：<?php echo count($or->getOneOrderByOrStatusAndMethod(9,1)); ?>)
	                    		<?php
	                    			break;
									case 10:
	                    		?>
	                    			<br>(總共：<?php echo count($or->getOneOrderByOrStatusAndMethod(10,1)); ?> / 未列印：<?php echo count($or->getOneOrderByOrStatusAndMethodAndIfProcess(10,1,0)); ?>)
	                    		<?php
	                    			break;
									case 11:
	                    		?>
	                    			<br>(等待中：<?php echo count($or->getOneOrderByOrStatusAndMethod(11,1)); ?>)
	                    		<?php
	                    			break;
									case 12:
	                    		?>
	                    			<br>(待處理：<?php echo count($or->getOneOrderByOrStatusAndMethod(12,1)); ?>)
	                    		<?php
	                    			break;
									case 13:
	                    		?>
	                    			<br>(總共：<?php echo count($or->getOneOrderByOrStatusAndMethod(13,1)); ?> / 未處理：<?php echo count($or->getOneOrderByOrStatusAndMethodAndIfProcess(13,1,0)); ?>)
	                    		<?php 
	                    			break;
	                    		}
	                    		?>
	                    	</a>
                    	</li>
					<?php		
						}                   
                    ?>
                  </ul>
                </li>
              
              
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
