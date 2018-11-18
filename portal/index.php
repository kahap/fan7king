<?php

    //
    session_start();
    // 关闭错误报告
    error_reporting(0);
	// 允許看到我要抽獎的會員清單
	$memArray = array('1000001','1140333','1145319','1145356','1149599','1149600','1002870');
	// 預設定
    include_once('Controllers/lib/lib.php');
    include_once('cfg/cfg.inc.php');

    //
    function __autoload($ClassName){
        require_once('model/cls/'.$ClassName.".cls.php");
    }

    //系統其他設定
    $fb = new Other_Setting();

    //計算瀏覽網站人數
    if(!isset($_COOKIE["viewed"])){
        setcookie("viewed","viewed",time()+86400);
        $os = new Other_Setting();
        $osData = $os->getAll();
        //總瀏覽數+1
        $os->updateViewCount($osData[0]["viewCount"]+1);
    }

    // 會員還存在登入
	if (isset($_GET['key'])) { // jimmy new add for password change
        $mem = new Member(); 
        $str = base64_decode($_GET['key']);
        $memIdNum = substr($str, 0, 10);
        $memPwd = substr($str, 11);
        $input_array = array('memIdNum' => $memIdNum, 'memPwd' => $memPwd);
        $mem_data = $mem->getMemberinformationNew($input_array);
        if ($mem_data != "" && $mem_data['memAllowLogin'] == 1) {
            $_SESSION['user']['memName'] = $mem_data['memName'];
            $_SESSION['user']['memNo'] = $mem_data['memNo'];
            $_SESSION['user']['memIdNum'] = $mem_data['memIdNum'];
            $_SESSION['user']['memClass'] = $mem_data['memClass'];
        } else {
            echo "<script>alert('連結已經失效'); location.href='index.php?item=login';</script>";
        }
    }

    // Router of Url
    $itemVal = isset($_GET['item'])? $_GET['item'] : '' ;
    $actionVal = isset($_GET['action'])? $_GET['action'] : '' ;

    //會員資訊編輯頁面
    if( ($_SESSION['user']['memClass']=="" || $_SESSION['user']['memClass']==null) && $_SESSION['user']['memNo'] != "" ) {
        $itemVal = "information_edit";
    }


    /************************* layout *******************************/
    $_SESSION['vTitle'] = 'Nowait';
    include_once('views/_header.php');


    // share code
    if( $_SESSION['user']['sharcode'] == "" )
    {
        if(isset($_GET['share']) && $_GET['share'] != ''){
            $_SESSION['user']['sharcode'] = $_GET['share'];
        }else{
            $_SESSION['user']['sharcode'] = '111';
        }
    }
    elseif (isset($_GET['share']) && $_GET['share'] != '')
    {
        $_SESSION['user']['sharcode'] = $_GET['share'];
    }


    //會員已登入
    if($_SESSION['user']['memName'] != "")
    {
        if($_SESSION['user']['memIdNum'] == "" || $_SESSION['user']['memIdNum']==null)
        {
            $itemVal = "member_center" ;
            $actionVal = "member_idnum" ;
        }

        if($itemVal != ""  ){
            switch($itemVal){
				case "loading":
					include('view/page_loading.html');
				break;
				case "award":
					include('view/page_award.html');
				break;

				//會員中心
                case "member_center":
                    switch($actionVal){
										
                        case "whcode":
                            include('view/page_member_whcode.html');
    						break;

                        case "recomm_list":
                            include('view/page_member_recomm_list.html');
    						break;

                        case "member_edit":
                            include('view/page_member_edit.html');
    						break;

                        case "member_idnum":
                            include('view/page_member_idnum_edit.html');
                            break;

                        case "fb_edit":
                            include_once('view/page_member_fbedit.html');
    						break;

                        // 變更密碼
                        case "password_edit":
                            include_once('views/member/member-change.php');
    						break;

                        case "purchase":
                            if($_GET['orno'] != ""){
                                if($_GET['query'] != ""){
                                    include('view/page_member_order_detail_query.html');
                                }else{
                                    include('view/page_member_order_detail.html');
                                }
                            }else{
                                include('view/page_member_order.html');
                            }
    						break;
                        case "mco_purchase": // jimmy
                            if ($_GET['mcono'] != "") {
                                    include('view/page_member_loan_detail.html');
                            } else {
                                include('view/page_member_loan_order.html');
                            }
                            break;

                        // 我要繳款
                        case "pay":
                            if($_GET['orno'] != ""){
                                include('views/member/member-pay-detail.php');
                            }else{
                                include('views/member/member-pay.php');
                            }
                            break;
    						break;

                        // 訂單查詢
                        case "order":
                            if($_GET['orno'] != ""){
                                include('views/member/member-order-detail.php');
                            }else{
                                include('views/member/member-order.php');
                            }
    						break;

                        case "query_code":


                            if($_SESSION['MackMoney'] != '' && $_GET['n'] == 'apply_ADS'){
                                $rbs = new Recomm_Bonus_Success();
                                $rba = new Recomm_Bonus_Apply();

                                foreach($_SESSION['MackMoney'] as $key => $value){
                                    $rba_data = $rba->getOneRBAByNo($value);
                                    if($rba_data[0]['rbaExtract'] == 1){
                                        $errg = false;
                                        echo "<script>alert('已申請過，不能再重複申請!!'); history.go(-1);</script>";
                                    }else{
                                        $errg = true;
                                    }
                                }
                                if($errg){
                                    $member = new Member();
                                    $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
                                    $array['rbaNo'] = json_encode($_SESSION['MackMoney']);
                                    $array['rbsTotal'] = $_SESSION['rbsTotal'];
                                    $array['memNo'] = $_SESSION['user']['memNo'];
                                    $array['rbsIdNum'] = $memberData[0]['memIdNum'];

                                    $_SESSION['memIdNum']  = $memberData[0]['memIdNum'];

                                    $array['memPhone'] = $memberData[0]['memPhone'];
                                    $array['memCell'] = $memberData[0]['memCell'];
                                    $allRbsData = $rba->getRBAByMemNo($_SESSION['user']['memNo']);
                                    $rbs_id = $rbs->insert($array,$allRbsData,$_SESSION['user']['memNo']);
                                    $_SESSION['rbs_id'] = $rbs_id;
                                    include('view/page_member_rba_apply.html');
                                }
                            }else{
                                include('view/page_member_query_code.html');
                            }

    						break;

                        case "history":
                            if($_GET['d'] != ""){
                                include('view/page_member_query_code_success_detail.html');
                            }else{
                                include('view/page_member_query_code_apply.html');
                            }

    						break;

                        case "cancel":
                            $or = new Orders();
                            $or_data = $or->getOneOrderByNo($_GET['orno']);
                            $or->changeToReadable($or_data[0],1);
                            if(in_array($or_data[0]["orStatus"],$CanselstatusArr)){
                                include('php/cancel_order.php');
                            }else{
                                echo "<script>alert('拒絕存取'); history.go(-1);</script>";
                            }
    						break;

                        case "order_period":
                            switch($_GET['method']){
                                case "2":
                                    $or = new Orders();
                                    $or_data = $or->getTwinOrByMemberAndMethod($_SESSION['user']['memNo'],'1');
                                    if($or_data[1]['orNo'] != ''){
                                        include('view/page_default_order_period_2.html');
                                    }else{
                                        include('view/page_order_period_2.html');
                                    }
    								break;
                                case "3":
                                    include('view/page_order_period_3.html');
    								break;
                                case "4":
                                    include('view/page_order_period_4.html');
    								break;

                                default:
                                    $or = new Orders();
                                    $or_data = $or->getOnlyOrByMemberAndMethod($_SESSION['user']['memNo'],'1');
                                    if($_SESSION['ord_code'] != ''){
                                        echo "<script>location.href='index.php?item=member_center&action=order_edit&orno=".$_SESSION['ord_code']."'</script>";
                                    }else{
                                        if($or_data[0]['orNo'] != ''){
                                            include('view/page_default_order_period.html');
                                        }else{
                                            include('view/page_order_period.html');
                                        }
                                    }
    								break;
                            }
    						break;

                        case "order_edit":
                            $or = new Orders();
                            $or_data = $or->getOneOrderByNo($_GET['orno']);
                            if($or_data[0]['orIfEditable'] == 0){
                                switch($_GET['method']){
                                    case "2":
                                        include('view/page_edit_order_period_2.html');
    									break;
                                    case "3":
                                        include('view/page_edit_order_period_3.html');
    									break;
                                    case "4":
                                        include('view/page_edit_order_period_4.html');
    									break;

                                    default:
                                        if($_GET['front_mange'] != ''){
                                            $or->updateorIfProcessInCurrentStatus('1',$_GET['orno']);
                                        }
                                        include('view/page_edit_order_period.html');
    									break;
                                }
                            }

    						break;

                        case "loan_order_period": // jimmy

                            switch($_GET['method']) {
                                case "2":
                                    $mco = new Motorbike_Cellphone_Orders();
                                    $mco_data = $mco->getTwinOrByMemberAndMethod($_SESSION['user']['memNo']);
                                    include('view/page_loan_order_period_2.html');
                                    break;
                                case "3":
                                    include('view/page_loan_order_period_3.html');
                                    break;
                                case "4":
                                    include('view/page_loan_order_period_4.html');
                                    break;

                                default:
                                    include('view/page_loan_order_period.html');
                                    break;
                            }
                            break;

                        case "loan_order_edit": // jimmy
                            $mco = new Motorbike_Cellphone_Orders();
                            $mco_data = $mco->getOneOrderByNo($_GET['mcono']);
                            if ($mco_data[0]['mcoIfEditable'] == 0) {
                                switch ($_GET['method']) {
                                    case "2":
                                        include('view/page_edit_loan_period_2.html');
                                        break;
                                    case "3":
                                        include('view/page_loan_order_period_3.html');
                                        break;
                                    case "4":
                                        include('view/page_loan_order_period_4.html');
                                        break;

                                    default:
                                        if ($_GET['front_mange'] != '') {
                                            $mco->updateorMcoIfProcessInCurrentStatus('1', $_GET['mcoNo']);
                                        }
                                        include('view/page_edit_loan_period.html');
                                        break;
                                }
                            }

                            break;

                        case "order_direct":
                            switch($_GET['method']){
                                case "2":
                                    include('view/page_order_direct_2.html');
    								break;
                                case "3":
                                    include('view/page_order_direct_3.html');
    								break;

                                default:
                                    if($_SESSION['ord_code'] == ""){
                                        include('view/page_order_direct.html');
                                    }else{
                                        include('view/page_order_direct_edit.html');
                                    }
    								break;
                            }

    						break;

                        //會員資料
                        default:
                            include('views/member/member-info.php');
    						break;
                    }
    				break;

                case "logout":
                case "login":
                case "login_register":
                    include_once('views/login/login.php');
    				break;

                case "contact":
                    include_once('view/page_contact_service.html');
    				break;
                 case "sup_center":
                    //include_once('view/page_sup_center.html');
                    include_once('view/page_sup_center_new.html'); //encore
    				break;


                case "category":
                    include_once('views/product/page_category.php');
    				break;
                case "product":
                    include_once('views/product/page_detail.php');
                    break;
                case "product_sup":
                    include_once('views/product/page_detail_sup.php');
                    break;
                case "search":
                    include_once('views/product/page_search.php');
    				break;


                case "faq":
                    include_once('view/page_faq.html');
    				break;

                case "co_company":
                    include_once('view/page_contact.html');
    				break;
                case "loan_menu":
                    include_once('view/page_loan_menu.html');
    				break;
                case "loan_vip":
                    include_once('view/page_loanVIP.html');
    				break;
                case "loan_Moto":
                    include_once('view/page_loanMoto.html');
    				break;
                case "loan_Cell":
                    include_once('view/page_loanCell.html');
    				break;
                case "loan_moto":
                    include_once('view/page_loan_moto.html'); // add jimmy
                    break;
                case "loan_cell":
                    include_once('view/page_loan_cell.html'); // add jimmy
                    break;
                case "information_edit":
                    include_once('view/page_member_information.html');
    				break;


                default:
                    $Front_Manage = new Front_Manage();
                    $Front_Manage2 = new Front_Manage2();
                    if(array_key_exists($itemVal,$page_other )){
                        $page_data = $Front_Manage->getAllFM($itemVal);
                    }else if(array_key_exists($itemVal,$page_other2 )){
                        $page_data2 = $Front_Manage2->getAllFM($itemVal);
                    }
                    include_once('view/page_other.html');
    				break;
            }
        }else{
            //首頁(沒有目標頁面itemVal)
//            include_once('view/slider.php');
//            include_once('view/page_top.html');
//            include_once('view/page_content.html');
            include_once('views/_index.php');
        }
    }

    //目標頁面(未登入)
    elseif($itemVal != ""){
        if(array_key_exists($itemVal,$page_other ) || array_key_exists($itemVal,$page_other2 )){
            switch($itemVal){
                case $itemVal:
                    switch($itemVal){


                        default:
                            $Front_Manage = new Front_Manage();
                            $Front_Manage2 = new Front_Manage2();
                            if(array_key_exists($itemVal,$page_other )){
                                $page_data = $Front_Manage->getAllFM($itemVal);
                            }else if(array_key_exists($itemVal,$page_other2 )){
                                $page_data2 = $Front_Manage2->getAllFM($itemVal);
                            }
                            include_once('view/page_other.html');
    						break;
                    }
    				break;

                default:
                    //首頁(沒有目標頁面itemVal)
//            include_once('view/slider.php');
//            include_once('view/page_top.html');
//            include_once('view/page_content.html');
                    include_once('views/_index.php');
    				break;
            }
        }

        elseif($itemVal=="member_center"){
            include_once('views/login/login.php');
        }

        // ---------------- register ------------------
        elseif($itemVal=="register"){
            include_once('views/register/register-1.php');
        }
        elseif($itemVal=="register2"){
            include_once('views/register/register-2.php');
        }
        elseif($itemVal=="register3"){
            include_once('views/register/register-3.php');
        }
        elseif($itemVal=="register4"){
            include_once('views/register/register-4.php');
        }

        // ---------------- login ------------------
        elseif($itemVal=="login" /*or $itemVal=="login_register"*/){
            include_once('views/login/login.php');
        }
        elseif($itemVal=="forgetpwd1"){
            include_once('views/login/forgetpwd-1.php');
        }
        elseif($itemVal=="forgetpwd2"){
            include_once('views/login/forgetpwd-2.php');
        }
        elseif($itemVal=="forgetpwd3"){
            include_once('views/login/forgetpwd-3.php');
        }


        // ---------------- other ------------------
        elseif($itemVal == "category"){
            include_once('views/product/page_category.php');
        }
        elseif($itemVal == 'product'){
            include_once('views/product/page_detail.php');
        }
        elseif($itemVal == 'product_sup'){
            include_once('views/product/page_detail_sup.php');
        }
        elseif($itemVal == "search"){
            include_once('views/product/page_search.php');
        }


        elseif($itemVal=="sup_center"){
            include_once('view/page_sup_center_new.html'); //encore
            //include_once('view/page_sup_center.html');
        }
        elseif($itemVal=="faq"){
            include_once('view/page_faq.html');
        }
        elseif($itemVal=="co_company"){
            include_once('view/page_contact.html');
        }
        elseif($itemVal=="loan_vip"){
            include_once('view/page_loanVIP.html');
        }
        elseif($itemVal=="loan_menu"){
            include_once('view/page_loan_menu.html');
        }
        elseif($itemVal=="information_edit"){
            include_once('view/page_member_information.html');
        }
        elseif($itemVal=="loan_Cell"){
            include_once('view/page_loanCell.html');
        }
        elseif($itemVal=="loan_Moto"){
            include_once('view/page_loanMoto.html');
        }
        elseif($itemVal=="loan_cell"){
            include_once('view/page_loan_cell.html'); // add jimmy
            echo "<script>alert('請先登入!!'); location.href='index.php?item=login';</script>";
        }
        elseif($itemVal=="loan_moto"){
            include_once('view/page_loan_moto.html'); // add jimmy
            echo "<script>alert('請先登入!!'); location.href='index.php?item=login';</script>";
        }
        else{
            //首頁(沒有目標頁面itemVal)
//            include_once('view/slider.php');
//            include_once('view/page_top.html');
//            include_once('view/page_content.html');
            include_once('views/_index.php');
        }
    }

    //首頁(沒有目標頁面itemVal)
    else{
        include_once('views/_index.php');
//        include_once('views/page_top.html');
//        include_once('views/page_content.html');
    }


    /************************* layout *******************************/
    include_once('views/_footer.php');

?>