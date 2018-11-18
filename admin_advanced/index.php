<?php
session_start ();
require_once 'model/require_general.php';

date_default_timezone_set ( 'Asia/Taipei' );

foreach ( $_GET as $key => $value ) {
	$$key = $value;
}

if (isset ( $_SESSION ["adminUserData"] )) {
	// if(1 == 1){
	// 判斷權限
	$aar = new API ( "admin_advanced_roles" );
	// / for multiple value
	$json_aNum = $_SESSION ["adminUserData"] ["aarNo"];
	if ($json_aNum == "[]") {
		$newURL = "ajax/admin_user/logout_back.php";
		header ( 'Location: ' . $newURL );
	}
	$array_aNum = json_decode ( $json_aNum );
	$rightsOwned = array ();
	if (is_array ( $array_aNum )) {
		$hash_tmp = array ();
		foreach ( $array_aNum as $item ) {
			$aarData = $aar->getOne ( $item );
			$tmp = array_merge ( $rightsOwned, json_decode ( $aarData [0] ["aafID"] ) );
			foreach ( array_values ( $tmp ) as $tmpi ) {
				$hash_tmp [$tmpi] = 1;
			}
			$rightsOwned = array_keys ( $hash_tmp );
		}
	} else {
		$aarData = $aar->getOne ( $_SESSION ["adminUserData"] ["aarNo"] );
		$rightsOwned = json_decode ( $aarData [0] ["aafID"] );
	}
	// print_r($rightsOwned);
	// /
	
	include "view/index_header.html";
	include "view/index_top.html";
	include "view/index_left.php";
	if (isset ( $page )) {
		switch ($page) {
			// 會員查詢
			case "member" :
				if (isset ( $type )) {
					switch ($type) {
						case "list" :
							include "view/page_member_list.php";
						break;
						case "view" :
							include "view/page_member_view.php";
						break;
						case "dataedit":
							include "view/page_member_edit.php";
						break;
						case "fbchange":
							include "view/page_member_fbchange.php";
						break;
					}
				} else {
					include "view/page_member.php";
				}
				break;
			// 催收作業
			case "urge" :
				if (in_array ( 600, $rightsOwned ) || in_array ( 601, $rightsOwned ) || in_array ( 602, $rightsOwned ) || in_array ( 603, $rightsOwned ) || in_array ( 604, $rightsOwned ) || in_array ( 605, $rightsOwned )) {
					if (isset ( $type )) {
						switch ($type) {
							case "my" :
							case "all" :
								include "view/page_urge_list.php";
								break;
							case "edit" :
								include "view/page_urge_edit.php";
								break;	
							case "edit_case" :
								if (isset ( $action )) {
									switch ($action) {
										case "list" :
											include "view/page_orders_view_list.php";
											break;
										
										case "edit" :
											include "view/page_urge_edit.php";
											break;
									}
								} else {
									include "view/page_orders_view.php";
								}
								
								break;
							
							case "txtmsg" :
								include "view/page_urge_txtmsg.php";
								break;
							case "user" :
								if (in_array ( 600, $rightsOwned )) {
									if (isset ( $action )) {
										switch($action){
											case "edit":
												include "view/page_urge_user_edit.php";
											break;
											
											case "insert":
												include "view/page_urge_user_edit.php";
											break;
											
											case "del" :
												$apiMem = new API("member");
												$sql = "delete  FROM `urge_user` where aauNo = '".$_GET["uuNo"]."' && upNo = '".$_GET['upNo']."'";
												$apiMem->customSql($sql);
												
												$sql1 = "delete FROM `urge_request_records` where uuNo = '".$_GET["uuNo"]."' && urrCurMValue = 'M".($_GET['upNo']-1)."' && urrStatus = '0'";
												$apiMem->customSql($sql1);
												
												$ch = curl_init("http://nowait.shop/admin_advanced/ajax/urge/auto_calc_urge_user.php");
												curl_setopt($ch, CURLOPT_HTTPHEADER, false);
												$result = curl_exec($ch);
												curl_close($ch);
												echo "<script>alert('刪除成功!')</script>";
												echo "<script>location.href='index.php?page=urge&type=user';</script>";
											break;
										}
										
									} else {
										include "view/page_urge_user.php";
									}
								} else {
									include "view/page_forbidden.html";
								}
								break;
						}
					} else {
						include "view/plain_page.html";
					}
				} else {
					include "view/page_forbidden.html";
				}
				break;
			// 案件查詢
			case "orders_view" :
				if (isset ( $type )) {
					switch ($type) {
						case "list" :
							include "view/page_orders_view_list.php";
							break;
						case "view" :
							include "view/page_orders_view_view.php";
							break;
					}
				} else {
					include "view/page_orders_view.php";
				}
				break;
			// 案件查詢
			case "orders_view_general" :
				if (isset ( $type )) {
					switch ($type) {
						case "list" :
							include "view/page_orders_view_list_general.php";
							break;
						case "view" :
							include "view/page_orders_view_view.php";
							break;
					}
				} else {
					include "view/page_orders_view.php";
				}
				break;
			// 進件
			case "case" :
				if (in_array ( 100, $rightsOwned )) {
					if (isset ( $type )) {
						switch ($type) {
							case "edit" :
								include "view/page_case_create_edit.php";
								break;
							case "fixed" :
							case "unfixed" :
							case "after_fixed" :
							case "after_unfixed" :
							case "EmailNotIdentify" :
								include "view/page_case_create_list.php";
								break;
							case "mco" :
								include "view/page_case_create_list_mco.php";
								break;
							case "query" :
								include "view/page_case_create_query.php";
								break;
						}
					} else {
						include "view/page_case_create_list.php";
					}
				} else {
					include "view/page_forbidden.html";
				}
				break;
			// 徵信
			case "credit" :
				if (isset ( $type )) {
					if (in_array ( 200, $rightsOwned )) {
						switch ($type) {
							case "view" :
								include "view/page_credit_view.php";
								break;
							case "insert" :
								include "view/page_credit_insert.php";
								break;
						}
					} else {
						include "view/page_forbidden.html";
					}
				} else {
					if (in_array ( 200, $rightsOwned )) {
						include "view/page_credit_list.php";
					} else {
						include "view/page_forbidden.html";
					}
				}
				break;
			// 授信
			case "authen" :
				if (isset ( $type )) {
					if (in_array ( 300, $rightsOwned )) {
						switch ($type) {
							case "view" :
								include "view/page_authen_view.php";
								break;
							case "view_fixed" :
								include "view/page_authen_view_fixed.php";
								break;
							case "insert" :
								include "view/page_authen_insert.php";
								break;
							case "statistics" :
								include "view/page_authen_statistics.php";
								break;
							
							case "statisticsForDate" :
								include "view/page_authen_statisticsForDate.php";
								break;
						}
					} else {
						include "view/page_forbidden.html";
					}
				} else {
					if (in_array ( 300, $rightsOwned )) {
						include "view/page_authen_list.php";
					} else {
						include "view/page_forbidden.html";
					}
				}
				break;
			// 撥款作業
			case "appropriation" :
				if (isset ( $type )) {
					switch ($type) {
						case "ready" :
							if (in_array ( 400, $rightsOwned )) {
								include "view/page_appropriation_ready.php";
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "finished" :
							if (in_array ( 400, $rightsOwned )) {
								include "view/page_appropriation_finished.php";
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "view" :
							if (in_array ( 400, $rightsOwned )) {
								include "view/page_appropriation_view.php";
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "upload" :
							if (isset ( $which )) {
								switch ($which) {
									case "cmc_success_list" :
										if (in_array ( 400, $rightsOwned )) {
											include "view/page_appropriation_upload_cmc_success_list.php";
										} else {
											include "view/page_forbidden.html";
										}
										break;
									case "cmc_success_get" :
										if (in_array ( 400, $rightsOwned )) {
											include "view/page_appropriation_upload_cmc_success_get.php";
										} else {
											include "view/page_forbidden.html";
										}
										break;
									case "pay" :
										if (in_array ( 400, $rightsOwned )) {
											include "view/page_appropriation_upload_pay.php";
										} else {
											include "view/page_forbidden.html";
										}
										break;
									case "history" :
										if (in_array ( 400, $rightsOwned )) {
											include "view/page_appropriation_upload_history.php";
										} else {
											include "view/page_forbidden.html";
										}
										break;
								}
							} else {
								include "view/page_forbidden.html";
							}
							break;
					}
				} else {
					if (in_array ( 400, $rightsOwned )) {
						include "view/page_appropriation_list.php";
					} else {
						include "view/page_forbidden.html";
					}
				}
				break;
			// 會計
			case "accounting" :
				if (isset ( $type )) {
					switch ($type) {
						case "edit_records" :
							if (in_array ( 500, $rightsOwned )) {
								if (isset ( $action )) {
									switch ($action) {
										case "list" :
											include "view/page_appropriation_edit_records_list.php";
											break;
										case "edit" :
											include "view/page_appropriation_edit_records_edit.php";
											break;
									}
								} else {
									include "view/page_appropriation_edit_records.php";
								}
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "extra_pay" :
							if (in_array ( 500, $rightsOwned )) {
								if (isset ( $action )) {
									switch ($action) {
										case "list" :
											include "view/page_accounting_extra_pay_list.php";
											break;
										case "edit" :
											include "view/page_accounting_extra_pay_edit.php";
											break;
									}
								} else {
									include "view/page_accounting_extra_pay.php";
								}
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "finished_appro" :
							if (in_array ( 500, $rightsOwned )) {
								if (isset ( $action )) {
									switch ($action) {
										case "list" :
											include "view/page_accounting_finished_appro_list.php";
											break;
									}
								} else {
									include "view/page_accounting_finished_appro.php";
								}
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "export_file" :
							if (isset ( $action )) {
								switch ($action) {
									case "list" :
										include "view/page_accounting_export_file_list.php";
										break;
								}
							} else {
								include "view/page_accounting_export_file.php";
							}
							break;
						case "export_file_cussup" :
							if (isset ( $action )) {
								switch ($action) {
									case "list" :
										include "view/page_accounting_export_file_cussup_list.php";
										break;
								}
							} else {
								include "view/page_accounting_export_file.php";
							}
							break;
						
						case "upload_file_cussup" :
							if (in_array ( 502, $rightsOwned )) {
								include "view/page_accounting_upload_file_cussup.php";
							} else {
								include "view/page_forbidden.html";
							}
							break;
						
						case "queryPay" :
							if (in_array ( 507, $rightsOwned )) {
								include "view/page_accounting_queryPay.php";
							} else {
								include "view/page_forbidden.html";
							}
							break;
						case "90buy" :
							if (in_array ( 508, $rightsOwned )) {
								include "view/page_accounting_90buy.php";
							} else {
								include "view/page_forbidden.html";
							}
							break;				
					}
				} else {
					include "view/page_forbidden.html";
				}
				break;
			// 供應商
			case "supplier" :
				if (in_array ( 700, $rightsOwned )) {
					if (isset ( $action )) {
						include "view/page_supplier_insert_edit.php";
					} else {
						include "view/page_supplier.php";
					}
				} else {
					include "view/page_forbidden.html";
				}
				break;
			// 參數設定
			case "param" :
				if (in_array ( 800, $rightsOwned )) {
					switch ($type) {
						case "roles" :
							if ($_GET ['action'] == 'insert') {
								include "view/page_insert_edit_general.php";
							} elseif ($_GET ['action'] == 'edit') {
								include "view/page_insert_edit_general.php";
							} else {
								include "view/page_insert_list.php";
							}
							break;
						
						case "account" :
							if ($action == "edit") {
								include "view/page_insert_edit_general.php";
							} elseif ($action == "insert") {
								include "view/page_insert_edit_general.php";
							} else {
								include "view/page_param_setting.php";
							}
							break;
						
						case "delay" :
							include "view/page_param_delay.php";
							break;
					}
				}
				break;
			// Allan專區
			case "statistics" :
				if (in_array ( 900, $rightsOwned )) {
					if (isset ( $type )) {
						switch ($type) {
							case "regist" :
								include ("view/page_statistics_regist.php");
								break;
							
							case "pay" :
								include ("view/page_statistics_pay.php");
								break;
							
							case "paydetail" :
								break;
							
							case "tpiPenalty" :
								include ("view/page_statistics_tpiPenalty.php");
								break;
							
							case "monthtpiPenalty" :
								include ("view/page_statistics_monthtpiPenalty.php");
								break;
							
							case "claims" :
								include ("view/page_statistics_claims.php");
								break;
						}
					} else {
						include "view/page_forbidden.html";
					}
				} else {
					include "view/page_forbidden.html";
				}
				break;
			
			case "sales" :
				if (in_array ( 1000, $rightsOwned )) {
					if (isset ( $type )) {
						switch ($type) {
							case "period" :
								include ("view/page_sales_period.php");
								break;
							
							case "periodid" :
								include ("view/page_sales_periodid.php");
								break;							
							
							case "rank" :
								include ("view/page_sales_rank.php");
								break;
							
							case "paydetail" :
								break;
							
							case "tpiPenalty" :
								include ("view/page_statistics_tpiPenalty.php");
								break;
							
							case "monthtpiPenalty" :
								include ("view/page_statistics_monthtpiPenalty.php");
								break;
							
							case "claims" :
								include ("view/page_statistics_claims.php");
								break;
						}
					} else {
						include "view/page_forbidden.html";
					}
				} else {
					include "view/page_forbidden.html";
				}
				break;			
				
		}
	} else {
		include "view/plain_page.html";
	}
	include "view/index_footer.html";
} else {
	include "view/page_login.html";
}