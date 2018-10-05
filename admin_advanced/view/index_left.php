			<aside id="slide-out" class="side-nav white fixed">
                <div class="side-nav-wrapper">
                    <div class="sidebar-profile">
                        <div class="sidebar-profile-image">
                            <img src="assets/images/profile-image.png" class="circle" alt="">
                        </div>
                        <div class="sidebar-profile-info">
                            <a href="javascript:void(0);" class="account-settings-link">
                                <p><?php echo $_SESSION["adminUserData"]["aauName"]; ?><i class="material-icons right">arrow_drop_down</i></p>
                            </a>
                        </div>
                    </div>
                    <div class="sidebar-account-settings">
                        <ul>
                            <li class="no-padding">
                                <a id="logout" class="waves-effect waves-grey"><i class="material-icons">exit_to_app</i>登出</a>
                            </li>
                        </ul>
                    </div>
                <ul class="sidebar-menu collapsible collapsible-accordion" data-collapsible="accordion">
                    <?php if(in_array(10,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "member" ? "active" : ""; ?>">
	                    <!--<a class="waves-effect waves-grey active" href="?page=member">
	                    	<i class="material-icons">perm_identity</i>會員資訊
	                    </a>-->
						<a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "member" ? "active" : ""; ?>" href="?page=member">
							<i class="material-icons">perm_identity</i>會員資訊
						</a>
						<div class="collapsible-body">
							<ul>
							<?php if(in_array(1001,$rightsOwned)){ ?><li><a href="?page=member&type=dataedit" <?php echo isset($page,$type) && $page == "member" && $type == "dataedit" ? 'class="active-page"' : ""; ?>>會員資料編輯</a></li><?php } ?>
							<?php if(in_array(1001,$rightsOwned)){ ?><li><a href="?page=member&type=fbchange" <?php echo isset($page,$type) && $page == "member" && $type == "fbchange" ? 'class="active-page"' : ""; ?>>會員FB交換</a></li><?php } ?>
							</ur>
						</div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(11,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "orders_view" ? "active" : ""; ?>">
	                    <a class="waves-effect waves-grey active" href="?page=orders_view">
	                    	<i class="material-icons">library_books</i>總案件查詢-Allan專用
	                    </a>
                    </li>
                    <?php } ?>
                    <?php if(in_array(12,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "orders_view_general" ? "active" : ""; ?>">
	                    <a class="waves-effect waves-grey active" href="?page=orders_view_general">
	                    	<i class="material-icons">library_books</i>總案件查詢-一般 
	                    </a>
                    </li>
                    <?php } ?>
                    <?php if(in_array(100,$rightsOwned)){ ?>
                	<li class="no-padding <?php echo isset($page) && $page == "case" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "case" ? "active" : ""; ?>"><i class="material-icons">apps</i>進件作業<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
<?php if(in_array(101,$rightsOwned)){ ?><li><a href="?page=case&type=EmailNotIdentify" <?php echo isset($page,$type) && $page == "case" && $type == "EmailNotIdentify" ? 'class="active-page"' : ""; ?>>已下單-Email未認證</a></li><?php } ?>
<?php if(in_array(102,$rightsOwned)){ ?><li><a href="?page=case" <?php echo isset($page) && !isset($type) && $page == "case" ? 'class="active-page"' : ""; ?>>未進件列表</a></li> <?php } ?>
<?php if(in_array(103,$rightsOwned)){ ?><li><a href="?page=case&type=unfixed" <?php echo isset($page,$type) && $page == "case" && $type == "unfixed" ? 'class="active-page"' : ""; ?>>未進件列表-待補</a></li><?php } ?>
<?php if(in_array(104,$rightsOwned)){ ?><li><a href="?page=case&type=fixed" <?php echo isset($page,$type) && $page == "case" && $type == "fixed" ? 'class="active-page"' : ""; ?>>未進件列表-客戶自行補件</a></li><?php } ?>
<?php if(in_array(105,$rightsOwned)){ ?><li><a href="?page=case&type=after_unfixed" <?php echo isset($page,$type) && $page == "case" && $type == "after_unfixed" ? 'class="active-page"' : ""; ?>>已授信列表-待補</a></li><?php } ?>
<?php if(in_array(106,$rightsOwned)){ ?><li><a href="?page=case&type=after_fixed" <?php echo isset($page,$type) && $page == "case" && $type == "after_fixed" ? 'class="active-page"' : ""; ?>>已授信列表-客戶自行補件</a></li><?php } ?>
<?php if(in_array(107,$rightsOwned)){ ?><li><a href="?page=case&type=mco" <?php echo isset($page,$type) && $page == "case" && $type == "mco" ? 'class="active-page"' : ""; ?>>未進件列表(機車/手機)</a></li><?php } ?>
<?php if(in_array(108,$rightsOwned)){ ?><li><a href="?page=case&type=query" <?php echo isset($page,$type) && $page == "case" && $type == "query" ? 'class="active-page"' : ""; ?>>進件查核表</a></li><?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(200,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "credit" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "credit" ? "active" : ""; ?>"><i class="material-icons">apps</i>徵信作業<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                            	<?php if(in_array(201,$rightsOwned)){ ?><li><a href="?page=credit" <?php echo isset($page) && !isset($type) && $page == "credit" ? 'class="active-page"' : ""; ?>>徵信派件</a></li>
                   				<?php } ?>
                   				<?php if(in_array(202,$rightsOwned)){ ?><li><a href="?page=credit&type=view" <?php echo isset($page,$type) && $page == "credit" ? 'class="active-page"' : ""; ?>>徵信作業</a></li>
                            	<?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(300,$rightsOwned) ){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "authen" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "authen" ? "active" : ""; ?>"><i class="material-icons">apps</i>授信作業<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                            	<?php if(in_array(301,$rightsOwned)){ ?><li><a href="?page=authen" <?php echo isset($page) && !isset($type) && $page == "authen" ? 'class="active-page"' : ""; ?>>授信派件</a></li>
                                <?php } ?>
                   				<?php if(in_array(302,$rightsOwned)){ ?><li><a href="?page=authen&type=view" <?php echo isset($page,$type) && !isset($already) && $page == "authen" && $type == "view" ? 'class="active-page"' : ""; ?>>授信作業</a></li>
                                <?php } ?>
                    			<?php if(in_array(303,$rightsOwned)){ ?><li><a href="?page=authen&type=view&already=true" <?php echo isset($page,$type,$already) && $page == "authen" ? 'class="active-page"' : ""; ?>>已授信案件</a></li>
                            	<?php } ?>
                    			<?php if(in_array(304,$rightsOwned)){ ?>								<li><a href="?page=authen&type=statistics" <?php echo isset($page,$type,$already) && $page == "authen" && $page == "statistics" ? 'class="active-page"' : ""; ?>>授信案件統計</a></li>
                            	<?php } ?>
                    			<?php if(in_array(305,$rightsOwned)){ ?>								<li><a href="?page=authen&type=statisticsForDate" <?php echo isset($page,$type) && $page == "authen" && $type == "statisticsForDate" ? 'class="active-page"' : ""; ?>>每日授信案件統計</a></li>
                            	<?php } ?>
                            	
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(400,$rightsOwned) ){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "appropriation" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "appropriation" ? "active" : ""; ?>"><i class="material-icons">apps</i>買帳作業<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                            	<?php if(in_array(401,$rightsOwned)){ ?><li><a href="?page=appropriation" <?php echo isset($page) && !isset($type) && $page == "appropriation" ? 'class="active-page"' : ""; ?>>待撥款案件列表</a></li>
                                <?php } ?>
                                <?php if(in_array(402,$rightsOwned)){ ?><li><a href="?page=appropriation&type=ready" <?php echo isset($page,$type) && $page == "appropriation" && $type == "ready" ? 'class="active-page"' : ""; ?>>撥款作業</a></li>
                                <?php } ?>
                                <?php if(in_array(403,$rightsOwned)){ ?><li><a href="?page=appropriation&type=finished" <?php echo isset($page,$type) && $page == "appropriation" && $type == "finished" ? 'class="active-page"' : ""; ?>>撥款完成案件列表</a></li>
                                <?php } ?>
                                <?php if(in_array(404,$rightsOwned)){ ?><li><a href="?page=appropriation&type=upload&which=cmc_success_list" <?php echo isset($page,$type,$which) && $page == "appropriation" && $type == "upload" && $which == "cmc_success_list" ? 'class="active-page"' : ""; ?>>CMC撥款檔下載</a></li>
                                <?php } ?>
                                <?php if(in_array(405,$rightsOwned)){ ?><li><a href="?page=appropriation&type=upload&which=cmc_success_get" <?php echo isset($page,$type,$which) && $page == "appropriation" && $type == "upload" && $which == "cmc_success_get" ? 'class="active-page"' : ""; ?>>CMC撥款成功檔</a></li>
                                <?php } ?>
                                <?php if(in_array(406,$rightsOwned)){ ?><li><a href="?page=appropriation&type=upload&which=pay" <?php echo isset($page,$type,$which) && $page == "appropriation" && $type == "upload" && $which == "pay" ? 'class="active-page"' : ""; ?>>還款檔</a></li>
                                <?php } ?>
                                <?php if(in_array(407,$rightsOwned)){ ?><li><a href="?page=appropriation&type=upload&which=history" <?php echo isset($page,$type,$which) && $page == "appropriation" && $type == "upload" && $which == "history" ? 'class="active-page"' : ""; ?>>還款檔上傳紀錄</a></li>
                            	<?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(500,$rightsOwned) ){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "accounting" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "accounting" ? "active" : ""; ?>"><i class="material-icons">apps</i>會計作業<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <?php if(in_array(501,$rightsOwned)){ ?><li><a href="?page=accounting&type=edit_records" <?php echo isset($page,$type) && $page == "accounting" && $type == "edit_records" ? 'class="active-page"' : ""; ?>>調帳作業</a></li>
                                <?php } ?>
                                <?php if(in_array(502,$rightsOwned)){ ?><li><a href="?page=accounting&type=extra_pay" <?php echo isset($page,$type) && $page == "accounting" && $type == "extra_pay" ? 'class="active-page"' : ""; ?>>溢收款及結清狀態</a></li>
                                <?php } ?>
                                <?php if(in_array(503,$rightsOwned)){ ?><li><a href="?page=accounting&type=finished_appro" <?php echo isset($page,$type) && $page == "accounting" && $type == "finished_appro" ? 'class="active-page"' : ""; ?>>會計欲撥款案件</a></li>
                            	<?php } ?>
								<?php if(in_array(504,$rightsOwned)){ ?><li><a href="?page=accounting&type=export_file" <?php echo isset($page,$type) && $page == "accounting" && $type == "export_file" ? 'class="active-page"' : ""; ?>>會計匯出檔案</a></li>
								<?php } ?>
								<?php if(in_array(505,$rightsOwned)){ ?>								<li><a href="?page=accounting&type=export_file_cussup" <?php echo isset($page,$type) && $page == "accounting" && $type == "export_file_cussup" ? 'class="active-page"' : ""; ?>>匯出客供應報表</a></li>
								<?php } ?>
								<?php if(in_array(506,$rightsOwned)){ ?>								<li><a href="?page=accounting&type=upload_file_cussup" <?php echo isset($page,$type) && $page == "accounting" && $type == "upload_file_cussup" ? 'class="active-page"' : ""; ?>>匯入文中發票檔</a></li>
								<?php } ?>
								<?php if(in_array(507,$rightsOwned)){ ?>								<li><a href="?page=accounting&type=queryPay" <?php echo isset($page,$type) && $page == "accounting" && $type == "queryPay" ? 'class="active-page"' : ""; ?>>入帳日查詢</a></li>
								<?php } ?>
								<?php if(in_array(508,$rightsOwned)){ ?>								<li><a href="?page=accounting&type=90buy" <?php echo isset($page,$type) && $page == "accounting" && $type == "90buy" ? 'class="active-page"' : ""; ?>>90日買回查詢</a></li>
								<?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(600,$rightsOwned) ){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "urge" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "urge" ? "active" : ""; ?>"><i class="material-icons">apps</i>催收作業<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
<?php if(in_array(601,$rightsOwned)){ ?><li><a href="?page=urge&type=my" <?php echo isset($page,$type) && $page == "urge" && $type =="my" ? 'class="active-page"' : ""; ?>>我的催收案件</a></li><?php } ?>
<?php if(in_array(602,$rightsOwned)){ ?><li><a href="?page=urge&type=all" <?php echo isset($page,$type)  && $page == "urge" && $type =="all" ? 'class="active-page"' : ""; ?>>全部催收案件</a></li><?php } ?>
<?php if(in_array(603,$rightsOwned)){ ?><li><a href="?page=urge&type=txtmsg" <?php echo isset($page,$type) && $page == "urge" && $type =="txtmsg" ? 'class="active-page"' : ""; ?>>簡訊紀錄</a></li><?php } ?>
<?php if(in_array(605,$rightsOwned)){ ?><li><a href="?page=urge&type=edit_case" <?php echo isset($page,$type) && $page == "urge" && $type =="edit_case" ? 'class="active-page"' : ""; ?>>正常催記紀錄</a></li><?php } ?>
                                
                                <?php if(in_array(604,$rightsOwned)){ ?><li><a href="?page=urge&type=user" <?php echo isset($page,$type) && $page == "urge" && $type =="user" ? 'class="active-page"' : ""; ?>>催收人員管理</a></li>
                            	<?php } ?>
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    <?php if(in_array(700,$rightsOwned)){ ?><li class="no-padding <?php echo isset($page) && $page == "supplier" ? "active" : ""; ?>"><a class="waves-effect waves-grey active" href="?page=supplier"><i class="material-icons">apps</i>供應商管理
	                    </a>
                    </li>
                    <?php } ?>
                    <?php if(in_array(800,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "param" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "param" ? "active" : ""; ?>"><i class="material-icons">apps</i>參數設定<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
<?php if(in_array(801,$rightsOwned)){ ?><li><a href="?page=param&type=account" <?php echo isset($page,$type) && $page == "param" && $type == "account" ? 'class="active-page"' : ""; ?>>帳密管理</a></li><?php } ?>
<?php if(in_array(802,$rightsOwned)){ ?><li><a href="?page=param&type=roles" <?php echo isset($page,$type) && $page == "param" && $type == "roles" ? 'class="active-page"' : ""; ?>>腳色管理</a></li><?php } ?>
<?php if(in_array(803,$rightsOwned)){ ?><li><a href="?page=param&type=delay" <?php echo isset($page,$type) && $page == "param" && $type == "delay" ? 'class="active-page"' : ""; ?>>逾期設定</a></li><?php } ?>
                                <!-- <li><a href="?page=param&type=note_person" <?php echo isset($page,$type) && $page == "param" && $type == "note_person" ? 'class="active-page"' : ""; ?>>照會對象管理</a></li> -->
                            </ul>
                        </div>
                    </li>
                    <?php } ?>
					<?php if(in_array(900,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "param" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "param" ? "active" : ""; ?>"><i class="material-icons">apps</i>Allan專用<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
<?php if(in_array(901,$rightsOwned)){ ?><li><a href="?page=statistics&type=regist" <?php echo isset($page,$type) && $page == "statistics" && $type == "regist" ? 'class="active-page"' : ""; ?>>本月每日註冊人數</a></li><?php } ?>
<?php if(in_array(902,$rightsOwned)){ ?><li><a href="?page=statistics&type=pay" <?php echo isset($page,$type) && $page == "statistics" && $type == "pay" ? 'class="active-page"' : ""; ?>>本月撥款金額</a></li><?php } ?>
<?php /*if(in_array(903,$rightsOwned)){ ?><li><a href="?page=statistics&type=paydetail" <?php echo isset($page,$type) && $page == "statistics" && $type == "paydetail" ? 'class="active-page"' : ""; ?>>每月撥款明細</a></li><?php }*/ ?>
<?php if(in_array(905,$rightsOwned)){ ?><li><a href="?page=statistics&type=tpiPenalty" <?php echo isset($page,$type) && $page == "statistics" && $type == "tpiPenalty" ? 'class="active-page"' : ""; ?>>滯納金查詢</a></li><?php } ?>
<?php if(in_array(904,$rightsOwned)){ ?><li><a href="?page=statistics&type=monthtpiPenalty" <?php echo isset($page,$type) && $page == "statistics" && $type == "monthtpiPenalty" ? 'class="active-page"' : ""; ?>>每月滯納金查詢</a></li><?php } ?>
<?php if(in_array(906,$rightsOwned)){ ?><li><a href="?page=statistics&type=claims" <?php echo isset($page,$type) && $page == "statistics" && $type == "claims" ? 'class="active-page"' : ""; ?>>債權餘額表</a></li><?php } ?>




                            </ul>
                        </div>
                    </li>
                    <?php } ?>
					
					<?php if(in_array(1000,$rightsOwned)){ ?>
                    <li class="no-padding <?php echo isset($page) && $page == "sales" ? "active" : ""; ?>">
                        <a class="collapsible-header waves-effect waves-grey <?php echo isset($page) && $page == "sales" ? "active" : ""; ?>"><i class="material-icons">apps</i>業務人員報表<i class="nav-drop-icon material-icons">keyboard_arrow_right</i></a>
                        <div class="collapsible-body">
                            <ul>
<?php if(in_array(1100,$rightsOwned)){ ?><li><a href="?page=sales&type=period" <?php echo isset($page,$type) && $page == "sales" && $type == "period" ? 'class="active-page"' : ""; ?>>業務人員區間報表</a></li><?php } ?>
<?php if(in_array(1200,$rightsOwned)){ ?><li><a href="?page=sales&type=3month" <?php echo isset($page,$type) && $page == "sales" && $type == "3month" ? 'class="active-page"' : ""; ?>>業務人員前三個月報表</a></li><?php } ?>
<?php /*if(in_array(903,$rightsOwned)){ ?><li><a href="?page=statistics&type=paydetail" <?php echo isset($page,$type) && $page == "statistics" && $type == "paydetail" ? 'class="active-page"' : ""; ?>>每月撥款明細</a></li><?php }*/ ?>
<?php if(in_array(1300,$rightsOwned)){ ?><li><a href="?page=sales&type=periodid" <?php echo isset($page,$type) && $page == "sales" && $type == "periodid" ? 'class="active-page"' : ""; ?>>業務人員區間報表[含身分]</a></li><?php } ?>
<?php if(in_array(1400,$rightsOwned)){ ?><li><a href="?page=sales&type=3mothid" <?php echo isset($page,$type) && $page == "sales" && $type == "3mothid" ? 'class="active-page"' : ""; ?>>業務人員前三個月報表[含身分]</a></li><?php } ?>
<?php if(in_array(1500,$rightsOwned)){ ?><li><a href="?page=sales&type=rank" <?php echo isset($page,$type) && $page == "sales" && $type == "rank" ? 'class="active-page"' : ""; ?>>業務人員案件最多排名</a></li><?php } ?>

 


                            </ul>
                        </div>
                    </li>
                    <?php } ?>
                    

                </ul>
                <div class="footer">
                    <p class="copyright">Steelcoders ©</p>
                    <a href="#!">Privacy</a> &amp; <a href="#!">Terms</a>
                </div>
                </div>
            </aside>
            <script>
			$(function(){
				$("#logout").click(function(e){
					e.preventDefault();
					$.ajax({
						url:"ajax/admin_user/logout.php",
						type:"post",
						success:function(result){
							alert(result);
							location.href="index.php";
						}
					});
				});
			});
            </script>