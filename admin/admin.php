<?php
	session_start();
	require_once('model/require_general.php');
	foreach($_GET as $key => $value){
		$$key = $value;
	}
	if(isset($_SESSION['userdata'])){
		$ag = new Admin_Group();
		$agData = $ag->getOneAGByNo($_SESSION['userdata']['agNo']);
		$curRrightArr = json_decode($agData[0]["agRight"]);
		include('view/index_header.html');
		include('view/index_left.php');
		include('view/index_top.html');
		
		if(isset($page)){
			switch($page){
				case "briefInfo":
					if(in_array(0, $curRrightArr)){
						if(isset($type)){
							switch($type){
								case "general":
									include('view/page_content.php');
									break;
								case "monthly":
									include('view/page_content_monthly.php');
									break;
							}
						}else{
							include('view/page_content.php');
						}
					}else{
						include "view/page_block.php";
					}
					break;
				case "edit_front":
					if(in_array(90, $curRrightArr)){
						switch($type)
                        {
							//免責聲明編輯
							case "fmLoanVIP":
								include('view/page_fm.php');
							break;
							
							
							//購物流程編輯
							case "fmBuyStep":
								include('view/page_fm.php');
							break;
							
							
							//免責聲明編輯
							case "fmFreeRespons":
								include('view/page_fm.php');
							break;
							
							
							//服務條款編輯
							case "fmServiceRules":
								include('view/page_fm.php');
							break;
							
							//隱私權聲明編輯
							case "fmPrivacy":
								include('view/page_fm.php');
							break;
							
							//推薦獎金說明編輯
							case "fmRecBonus":
								include('view/page_fm.php');
							break;
							
							//直購流程
							case "fmDirectBuyRules":
								include('view/page_fm.php');
							break;
							
							//聯絡客服
							case "fmContactService":
								include('view/page_fm.php');
							break;
							
							//合作提案
							case "fmCoopDetail":
								include('view/page_fm.php');
							break;
							
							//購物須知
							case "fmBuyMustKnow":
								include('view/page_fm.php');
							break;
							
							//分期付款約定書
							case "fmPeriodDeclare":
								include('view/page_fm.php');
							break;
							
							//關於我們
							case "fmAboutUs":
								include('view/page_fm.php');
                                break;

                            //聯絡我們
                            case "co_company":
                                include('view/page_fm.php');
                                break;
								
							//分期付款約定事項
							case "fmInstallPromise":
								include('view/page_fm.php');
							break;
						}
					}else{
						include "view/page_block.php";
					}
				break;
				//會員頁
				case "member":
					if(isset($type)){
						switch($type)
                        {
							//一般會員
							case "member":
								if(in_array(10, $curRrightArr)){
									if(isset($action)){
										switch($action){
											//瀏覽
											case "view":
												include "view/page_member_view.php";
												break;
											//編輯
											case "edit":
												if(in_array(14, $curRrightArr)){
													include "view/page_member_edit.php";
												}else{
													include "view/page_block.php";
												}
												break;
										}
									}else{
										include('view/page_member.php');
									}
								}else{
									include "view/page_block.php";
								}
								break;
							//推薦人清單
							case "recomm_list":
								if(in_array(15, $curRrightArr)){
									if(isset($action)){
										switch($action){
											//瀏覽
											case "view":
												include "view/page_recomm_list_view.php";
												break;
										}
									}else{
										include('view/page_recomm_list.php');
									}
								}else{
									include "view/page_block.php";
								}
								break;
							//老客戶
							case "loyalGuest":
								if(in_array(11, $curRrightArr)){
									include "view/page_loyal_guest.php";
								}else{
									include "view/page_block.php";
								}
								break;
						}
					}else{
						include('view/page_member.php');
					}
				break;
				
				//Q&A
				case "qanda":
					if(in_array(20, $curRrightArr)){
						if(isset($action)){
							switch($action){
								case "view":
									include "view/page_qanda_view.php";
									break;
								case "edit":
									include "view/page_qanda_edit.php";
									break;
								case "insert":
									include "view/page_qanda_edit.php";
									break;
							}
						}else{
							include "view/page_qanda.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				//Q&A APP
				case "qaapp":
					if(in_array(20, $curRrightArr)){
						if(isset($action)){
							switch($action){
								case "view":
									include "view/page_qanda_app_view.php";
									break;
								case "edit":
									include "view/page_qanda_app_edit.php";
									break;
								case "insert":
									include "view/page_qanda_app_edit.php";
									break;
							}
						}else{
							include "view/page_qanda_app.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//供應商管理
				case "supplier":
					if(in_array(30, $curRrightArr)){
						if(isset($action)){
							switch($action){
								//瀏覽
								case "view":
									include "view/page_supplier_view.php";
									break;
								//修改
								case "edit":
									include "view/page_supplier_edit.php";
									break;
								//新增
								case "insert":
									include "view/page_supplier_edit.php";
									break;
								//分期
								case "1":
									include "view/page_supplier_order.php";
									break;
								//直購
								case "0":
									include "view/page_supplier_order_direct.php";
									break;
								//供貨商品清單
								case "productList":
									include "view/page_supplier_product_list.php";
									break;
								case "branch":
									include "view/page_supplier_branch.php";
                                	break;
								case "branchEdit":
									include "view/page_supplier_branch_edit.php";
									break;
								case "branchInsert":
									include "view/page_supplier_branch_edit.php";
									break;        
							}
						}else{
							include('view/page_supplier.php');
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//客服管理
				case "customer":
					if(in_array(12, $curRrightArr)){
						if(isset($type)){
							switch($type){
								//簡訊通知
								case "textmsg":
									include "view/page_service_textmsg.php";
									break;
								//email通知
								case "email":
									include "view/page_service_email.php";
									break;
							}
						}else{
							include('view/page_recomm_bonus_apply.php');
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//商品管理
				case "product":
					if(in_array(40, $curRrightArr)){
						if(isset($type)){
							switch($type){
								//分類管理
								case "general":
									if(isset($which)){
										switch($which){
											case "category":
												if(isset($action)){
													switch($action){
														case "view":
															include "view/page_category_view.php";
															break;
														case "edit":
														case "insert":
															include "view/page_category_edit.php";
															break;
													}
												}else{
													include "view/page_category.php";
												}
												break;
                                            //品牌管理
											case "brand":
												include "view/page_brand.php";
												break;
                                            //品項管理
                                            case "items":
                                                include "view/page_items.php";
                                                break;
										}
									}else{
										include "view/page_category.php";
										break;
									}
									break;
								//商品上架管理
								case "productManage":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_productManage_view.php";
												break;
											//insert
											case "insert":
												include "view/page_productManage_edit.php";
												break;
											//edit
											case "edit":
												include "view/page_productManage_edit.php";
												break;
										}
									}else if(isset($special)){
										include "view/page_productManage_order.php";
									}else{
										include "view/page_productManage.php";
									}
									break;
								//商品管理
								case "product":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_product_view.php";
												break;
											//insert
											case "insert":
												include "view/page_product_edit.php";
												break;
											//edit
											case "edit":
												include "view/page_product_edit.php";
												break;
										}
									}else{
										include "view/page_product.php";
									}
									break;
							}
						}else{
							include "view/page_product.php";
						}
					}else{
						include "view/page_block.php";
					}
					break;
								
				//推薦獎金
				case "recommBonus":
					if(in_array(13, $curRrightArr)){
						if(isset($type)){
							switch($type){
								//發放
								case "confirm":
									if(isset($action)){
										switch($action){
											case "view":
												include "view/page_recomm_bonus_success_view.php";
												break;
										}
									}else{
										include "view/page_recomm_bonus_success.php";
									}
									break;
							}
						}else{
							include('view/page_recomm_bonus_apply.php');
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//訂單+進件
				case "order":
					if(in_array(60, $curRrightArr) || in_array(61, $curRrightArr) || in_array(70, $curRrightArr)){
						if(isset($action)){
							switch($action){
								case "view":
									if(in_array(60, $curRrightArr) || in_array(61, $curRrightArr) || in_array(70, $curRrightArr)){
										include "view/page_order_view.php";
									}else{
										include "view/page_block.php";
									}
									break;
								case "query":
									if(isset($method)){
										switch($method){
											case 0:
												if(in_array(70, $curRrightArr)){
													include "view/page_order_query_direct.php";
												}else{
													include "view/page_block.php";
												}
												break;
											case 1:
												if(in_array(60, $curRrightArr) || in_array(61, $curRrightArr) ){
													include "view/page_order_query.php";
												}else{
													include "view/page_block.php";
												}
												break;
										}
									}else{
										include "view/page_order_query.php";
									}
									break;
							}
						}else if(isset($method)){
							switch($method){
								case "1":
									if(in_array(60, $curRrightArr) || in_array(61, $curRrightArr)){
										if(isset($status)){
											if(in_array(60, $curRrightArr)){
												include "view/page_order_period.php";
											}else if(in_array(61, $curRrightArr)){
												if($status == 1 || $status == 110 || $status == 2 || $status == 5 || $status == 6 || $status == 0 || $status == 3 || $status == 4 || $status == 7){
													include "view/page_order_period.php";
												}else{
													include "view/page_block.php";
												}
											}else{
												include "view/page_block.php";
											}
										}
									}else{
										include "view/page_block.php";
									}
									break;
								case "0":
									if(in_array(70, $curRrightArr)){
										include "view/page_order_direct.php";
									}else{
										include "view/page_block.php";
									}
									break;
							}
						}else{
							include "view/page_order_period.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//審查時間報表
				case "report":
					if(in_array(50, $curRrightArr)){
						if(isset($type)){
							switch($type){
								case "statusReport":
									include "view/page_status_report.php";
									break;
							}
						}else{
							include "view/page_status_report.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//權限管理
				case "right":
					if(in_array(80, $curRrightArr)){
						if(isset($type)){
							switch($type){
								case "group":
									if(isset($action)){
										switch($action){
											case "view":
												include "view/page_admin_group_view.php";
												break;
											case "edit":
											case "insert":
												include "view/page_admin_group_edit.php";
												break;
										}
									}else{
										include "view/page_admin_group.php";
									}
									break;
								case "account":
									if(isset($action)){
										switch($action){
											case "view":
												include "view/page_admin_account_view.php";
												break;
											case "edit":
											case "insert":
												include "view/page_admin_account_edit.php";
												break;
										}
									}else{
										include "view/page_admin_account.php";
									}
									break;
							}
						}else{
							include "view/page_admin_group.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//發票串接管理
				case "receipt":
					if(in_array(100, $curRrightArr)){
						if(isset($type)){
							switch($type){
								case "setting":
									include "view/page_receipt_setting.php";
									break;
								case "view":
									include "view/page_receipt_view.php";
									break;
								case "import":
									include "view/page_receipt_import.php";
									break;
							}
						}else{
							include "view/page_admin_group.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//其餘功能
				case "other":
					if(in_array(110, $curRrightArr)){
						if(isset($type)){
							switch($type){
								//版本控管
								case "version":
									include "view/page_other_version_control.php";
									break;
								//訂單過N天隱藏
								case "orderDays":
									include "view/page_other_orders_limits.php";
									break;
								//簡訊開關
								case "txtmsgSwitch":
									include "view/page_other_txtmsg_switch.php";
									break;
								//廣告管理
								case "advertise":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_other_advertise_view.php";
												break;
												//insert
											case "insert":
											case "edit":
												include "view/page_other_advertise_edit.php";
												break;
										}
									}else{
										include "view/page_other_advertise.php";
									}
									break;
								//廣告管理
								case "companyCoop":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_other_company_coop_view.php";
												break;
										}
									}else{
										include "view/page_other_company_coop.php";
									}
									break;
									
								//vip貸款
								case "loan_vip":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_other_loan_vip_view.php";
												break;
										}
									}else{
										include "view/page_other_loan_vip.php";
									}
									break;	
								//簡訊開關
								case "recommSetting":
									include "view/page_recomm_setting.php";
									break;
								//FB粉絲團
								case "fbLink":
									include "view/page_other_fb_link.php";
									break;
								//廣告管理
								case "periodSetting":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_other_period_setting_view.php";
												break;
												//insert
											case "insert":
											case "edit":
												include "view/page_other_period_setting_edit.php";
												break;
										}
									}else{
										include "view/page_other_period_setting.php";
									}
									break;
								//廣告管理
								case "periodSetting2":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_other_period_setting2_view.php";
												break;
												//insert
											case "insert":
											case "edit":
												include "view/page_other_period_setting2_edit.php";
												break;
										}
									}else{
										include "view/page_other_period_setting2.php";
									}
									break;
								//最新消息
								case "news":
									if(isset($action)){
										switch($action){
											//view
											case "view":
												include "view/page_news_view.php";
												break;
											//insert
											case "insert":
												include "view/page_news_edit.php";
												break;
											//edit
											case "edit":
												include "view/page_news_edit.php";
												break;
										}
									}else{
										include "view/page_news.php";
									}
									break;
								//系統參數設定
								case "hotkeys":
									include "view/page_hotkeys.php";
									break;
							}
						}else{
							include "view/page_product.php";
						}
					}else{
						include "view/page_block.php";
					}
				break;
				
				//版本控管
				case "version":
					if(in_array(111, $curRrightArr)){
						if(isset($type)){
							switch($type){
								//版本控管
								case "edit":
									include "view/page_other_version_control.php";
									break;
							}
						}
					}
				break;
			}
		}else{
			include('view/plain_page.html');
		}
		include('view/index_footer.html');
	}else{
		echo "<script>location.href='index.php';</script>";
	}
?>