<?php

    //
    session_start();
    // 关闭错误报告
    error_reporting(0);
	// 允許看到我要抽獎的會員清單
	$memArray = array('1000001','1140333','1145319','1145356','1149599','1149600','1002870');
	// 預設定
    include_once('portal/cfg/cfg.inc.php');
    include_once('portal/Controllers/lib/lib.php');

    //
    function __autoload($ClassName){
        require_once('portal/model/cls/'.$ClassName.".cls.php");
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
            $_SESSION['user']['fb_token'] = $mem_data['memFBtoken'];
        } else {
            echo "<script>alert('連結已經失效'); location.href='index.php?item=login';</script>";
        }
    }

    // Router of Url
    $itemVal = isset($_GET['item'])? $_GET['item'] : '' ;
    $actionVal = isset($_GET['action'])? $_GET['action'] : '' ;

    //會員資訊編輯頁面
    // if( ($_SESSION['user']['memClass']=="" || $_SESSION['user']['memClass']==null) && $_SESSION['user']['memNo'] != "" ) {
    //     $itemVal = "information_edit";
    // }


    /************************* layout *******************************/
    $_SESSION['vTitle'] = 'Nowait';
    include_once('portal/views/_header.php');

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
                case "register2":
                    include_once('portal/views/register/register-2.php');
                    break;
                case "register3":
                    include_once('portal/views/register/register-3.php');
                    break;
                case "register4":               
                    include_once('portal/views/register/register-4.php');
                    break;
				case "loading":
					include('portal/views/page_loading.html');
				break;
				case "award":
					include('portal/views/page_award.html');
				break;

                //免責聲明、 服務條款、 隱私權聲明等條款
                case "fmFreeRespons":
                    echo '<p align="center"> 此功能還未開放</p>';
                    include_once('portal/views/other/fmFreeRespons.php');
                break;
                case "fmServiceRules":
                    echo '<p align="center"> 此功能還未開放</p>';
                    include_once('portal/views/other/fmServiceRules.php');
                    break;
                case "fmPrivacy":
                    echo '<p align="center"> 此功能還未開放</p>';
                    include_once('portal/views/other/fmPrivacy.php');
                    break;

				//會員中心
                case "member_center":
                    switch($actionVal){

                        //什麼是推薦碼？
                        case "whcode":
                            include('portal/views/page_member_whcode.html');
    						break;
                        //推薦人清單查詢, 累積推薦人數
                        case "recomm_list":
                            include('portal/views/page_member_recomm_list.html');
    						break;

                        //會員基本資料修改
                        case "member_edit":
                            include('portal/views/page_member_edit.html');
    						break;
                            case "member_idnum_edit":
                            include('portal/views/member/page_member_idnum_edit.html');
                            break;
                        case "fb_edit":
                            include_once('portal/views/member/page_member_fbedit.html');
    						break;


                        // 變更密碼
                        case "password_edit":
                            include_once('portal/views/member/member-change.php');
    						break;

                        //分期訂單查詢
                        case "purchase":
                            if($_GET['orno'] != ""){
                                if($_GET['query'] != ""){
                                    include('portal/views/member/page_member_order_detail_query.php');
                                }else{
                                    include('portal/views/member/page_member_order_detail.php');
                                }
                            }else{
                                include('portal/views/member/page_member_order.php');
                            }
    						break;

                        //手機、機車借款訂單查詢
                        case "mco_purchase": // jimmy
                            if ($_GET['mcono'] != "") {
                                    include('portal/views/page_member_loan_detail.html');
                            } else {
                                include('portal/views/page_member_loan_order.html');
                            }
                            break;

                        // 我要繳款
                        case "pay":
                            if($_GET['orno'] != ""){
                                include('portal/views/member/member-pay-detail.php');
                            }else{
                                include('portal/views/member/member-pay.php');
                            }
                            break;

                        // 訂單查詢
                        case "order":
                            if($_GET['orno'] != ""){
                                include('portal/views/member/member-order-detail.php');
                            }else{
                                include('portal/views/member/member-order.php');
                            }
    						break;

                        //
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
                                    include('portal/views/page_member_rba_apply.html');
                                }
                            }else{
                                include('portal/views/page_member_query_code.html');
                            }
    						break;

                        //已領推薦金查詢明細
                        case "history":
                            if($_GET['d'] != ""){
                                include('portal/views/page_member_query_code_success_detail.html');
                            }else{
                                include('portal/views/page_member_query_code_apply.html');
                            }
    						break;

                        //
                        case "cancel":
                            $or = new Orders();
                            $or_data = $or->getOneOrderByNo($_GET['orno']);
                            $or->changeToReadable($or_data[0],1);
                            if(in_array($or_data[0]["orStatus"],$CanselstatusArr)){
                                include('portal/php/cancel_order.php');
                            }else{
                                echo "<script>alert('拒絕存取'); history.go(-1);</script>";
                            }
    						break;

                        //分期購買 STAGING
                        case "order_period":
                            switch($_GET['method']){
                                case "2":
                                    $or = new Orders();
                                    $or_data = $or->getTwinOrByMemberAndMethod($_SESSION['user']['memNo'],'1');
                                    if($or_data[1]['orNo'] != ''){
//                                        include('portal/views/staging/page_default_order_period_2.php');
//                                    }else{
                                        include('portal/views/staging/page_order_period_2.php');
                                    }
    								break;
                                case "3":
                                    include('portal/views/staging/page_order_period_3.php');
    								break;
                                case "4":
                                    include('portal/views/staging/page_order_period_4.php');
    								break;
                                default:
                                    $or = new Orders();
                                    $or_data = $or->getOnlyOrByMemberAndMethod($_SESSION['user']['memNo'],'1');
//                                    if($_SESSION['ord_code'] != ''){
//                                        echo "<script>location.href='index.php?item=member_center&action=order_edit&orno=".$_SESSION['ord_code']."'</script>";
//                                    }else{
                                        /*if($or_data[0]['orNo'] != ''){
                                            include('portal/views/staging/page_default_order_period.php');
                                        }else{*/
                                            include('portal/views/staging/page_order_period.php');
                                        //}
//                                    }
    								break;
                            }
    						break;

                        //修改訂單
                        case "order_edit":
                            $or = new Orders();
                            $or_data = $or->getOneOrderByNo($_GET['orno']);
                            if($or_data[0]['orIfEditable'] == 0){
                                switch($_GET['method']){
                                    case "2":
                                        include('portal/views/staging/page_edit_order_period_2.php');
    									break;
                                    case "3":
                                        include('portal/views/staging/page_edit_order_period_3.php');
    									break;
                                    case "4":
                                        include('portal/views/staging/page_edit_order_period_4.php');
    									break;
                                    default:
                                        if($_GET['front_mange'] != ''){
                                            $or->updateorIfProcessInCurrentStatus('1',$_GET['orno']);
                                        }
                                        include('portal/views/staging/page_edit_order_period.php');
    									break;
                                }
                            }
    						break;

                        //貸款...
                        case "loan_order_period": // jimmy
                            switch($_GET['method']) {
                                case "2":
                                    $mco = new Motorbike_Cellphone_Orders();
                                    $mco_data = $mco->getTwinOrByMemberAndMethod($_SESSION['user']['memNo']);
                                    include('portal/views/page_loan_order_period_2.html');
                                    break;
                                case "3":
                                    include('portal/views/page_loan_order_period_3.html');
                                    break;
                                case "4":
                                    include('portal/views/page_loan_order_period_4.html');
                                    break;
                                default:
                                    include('portal/views/page_loan_order_period.html');
                                    break;
                            }
                            break;

                        //貸款...
                        case "loan_order_edit": // jimmy
                            $mco = new Motorbike_Cellphone_Orders();
                            $mco_data = $mco->getOneOrderByNo($_GET['mcono']);
                            if ($mco_data[0]['mcoIfEditable'] == 0) {
                                switch ($_GET['method']) {
                                    case "2":
                                        include('portal/views/page_edit_loan_period_2.html');
                                        break;
                                    case "3":
                                        include('portal/views/page_loan_order_period_3.html');
                                        break;
                                    case "4":
                                        include('portal/views/page_loan_order_period_4.html');
                                        break;
                                    default:
                                        if ($_GET['front_mange'] != '') {
                                            $mco->updateorMcoIfProcessInCurrentStatus('1', $_GET['mcoNo']);
                                        }
                                        include('portal/views/page_edit_loan_period.html');
                                        break;
                                }
                            }
                            break;

                        // 直購購買流程
                        case "order_direct":
                            switch($_GET['method']){
                                case "2":
                                    include('portal/views/page_order_direct_2.html');
    								break;
                                case "3":
                                    include('portal/views/page_order_direct_3.html');
    								break;
                                default:
                                    if($_SESSION['ord_code'] == ""){
                                        include('portal/views/page_order_direct.html');
                                    }else{
                                        include('portal/views/page_order_direct_edit.html');
                                    }
    								break;
                            }
    						break;

                        //會員資料
                        default:                            
                            include('portal/views/member/member-info.php');
    						break;
                    }
    				break;

                case "logout":
                case "login":
                case "login_register":
                    include_once('portal/views/login/login.php');
    				break;

                //
                case "contact":
                    include_once('portal/views/page_contact_service.html');
    				break;

                //廠商專區
                case "sup_center":
                    //include_once('portal/views/page_sup_center.html');
                    include_once('portal/views/supplier/page_sup_center_new.php'); //encore
    				break;

                //商城
                case "category":
                    include_once('portal/views/product/page_category.php');
    				break;
                case "product":
                    include_once('portal/views/product/page_detail.php');
                    break;
                case "product_sup":
                    include_once('portal/views/product/page_detail_sup.php');
                    break;
                case "search":
                    include_once('portal/views/product/page_search.php');
    				break;


                //幫助中心
                case "help":
                    include_once('portal/views/help/helping.php');
                    break;
                //常見問題
                case "faq":
                    include_once('portal/views/help/helping-faq.php');
    				break;
                //聯絡客服
                case "co_company":
                    include_once('portal/views/help/helping-contact.php');
    				break;
                //關於我們
                case "aboutme":
                    include_once('portal/views/help/helping-about.php');
                    break;
                //購物流程
                case "help_process":
                    include_once('portal/views/help/helping-process.php');
                    break;


                //貸款VIP服務
                case "loan_menu":
                    include_once('portal/views/page_loan_menu.html');
    				break;
                case "loan_vip":
                    include_once('portal/views/page_loanVIP.html');
    				break;
                case "loan_Moto":
                    include_once('portal/views/page_loanMoto.html');
    				break;
                case "loan_Cell":
                    include_once('portal/views/page_loanCell.html');
    				break;
                case "loan_moto":
                    include_once('portal/views/page_loan_moto.html'); // add jimmy
                    break;
                case "loan_cell":
                    include_once('portal/views/page_loan_cell.html'); // add jimmy
                    break;
                case "information_edit":
                    include_once('portal/views/member/page_member_information.html');
                    break;                    

                

                default:
                    $Front_Manage = new Front_Manage();
                    $Front_Manage2 = new Front_Manage2();
                    if(array_key_exists($itemVal,$page_other )){
                        $page_data = $Front_Manage->getAllFM($itemVal);
                    }else if(array_key_exists($itemVal,$page_other2 )){
                        $page_data2 = $Front_Manage2->getAllFM($itemVal);
                    }
                    include_once('portal/views/page_other.html');
    				break;
            }
        }else{
            //首頁(沒有目標頁面itemVal)
//            include_once('portal/views/slider.php');
//            include_once('portal/views/page_top.html');
//            include_once('portal/views/page_content.html');
            include_once('portal/views/_index.php');
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
                            include_once('portal/views/page_other.html');
    						break;
                    }
    				break;

                default:
                    //首頁(沒有目標頁面itemVal)
//            include_once('portal/views/slider.php');
//            include_once('portal/views/page_top.html');
//            include_once('portal/views/page_content.html');
                    include_once('portal/views/_index.php');
    				break;
            }
        }

        elseif($itemVal=="member_center"){
            include_once('portal/views/login/login.php');
        }

        //免責聲明、 服務條款、 隱私權聲明等條款
        elseif($itemVal=="fmFreeRespons"){
            echo '<p align="center"> 此功能還未開放</p>';
            include_once('portal/views/other/fmFreeRespons.php');
        }elseif($itemVal=="fmServiceRules"){
            echo '<p align="center"> 此功能還未開放</p>';
            include_once('portal/views/other/fmServiceRules.php');
        }elseif($itemVal=="fmPrivacy"){
            echo '<p align="center"> 此功能還未開放</p>';
            include_once('portal/views/other/fmPrivacy.php');
        }

        // ---------------- register ------------------
        elseif($itemVal=="register"){
            include_once('portal/views/register/register-1.php');
        }
        elseif($itemVal=="register2"){
            include_once('portal/views/register/register-2.php');
        }
        elseif($itemVal=="register3"){
            include_once('portal/views/register/register-3.php');
        }
        elseif($itemVal=="register4"){
            include_once('portal/views/register/register-4.php');
        }

        // ---------------- login ------------------
        elseif($itemVal=="login" /*or $itemVal=="login_register"*/){
            include_once('portal/views/login/login.php');
        }
        elseif($itemVal=="forgetpwd1"){
            include_once('portal/views/login/forgetpwd-1.php');
        }
        elseif($itemVal=="forgetpwd2"){
            include_once('portal/views/login/forgetpwd-2.php');
        }
        elseif($itemVal=="forgetpwd3"){
            include_once('portal/views/login/forgetpwd-3.php');
        }


        // ---------------- other ------------------
        elseif($itemVal == "category"){
            include_once('portal/views/product/page_category.php');
        }
        elseif($itemVal == 'product'){
            include_once('portal/views/product/page_detail.php');
        }
        elseif($itemVal == 'product_sup'){
            include_once('portal/views/product/page_detail_sup.php');
        }
        elseif($itemVal == "search"){
            include_once('portal/views/product/page_search.php');
        }

        //廠商專區
        elseif($itemVal=="sup_center"){
            include_once('portal/views/supplier/page_sup_center_new.php'); //encore
            //include_once('portal/views/page_sup_center.html');
        }


        //幫助中心
        elseif($itemVal=="faq"){
            include_once('portal/views/help/helping-faq.php');
        }
        //聯絡客服
        elseif($itemVal=="co_company"){
            include_once('portal/views/help/helping-contact.php');
        }
        //關於我們
        elseif($itemVal=="aboutme"){
            include_once('portal/views/help/helping-about.php');
        }


        //貸款VIP服務
        elseif($itemVal=="loan_vip"){
            include_once('portal/views/page_loanVIP.html');
        }
        //我要借款
        elseif($itemVal=="loan_menu"){
            include_once('portal/views/page_loan_menu.html');
        }
        //
        elseif($itemVal=="information_edit"){
            include_once('portal/views/page_member_information.html');
        }
        //手機貸款服務
        elseif($itemVal=="loan_Cell"){
            include_once('portal/views/page_loanCell.html');
        }
        //機車貸款服務
        elseif($itemVal=="loan_Moto"){
            include_once('portal/views/page_loanMoto.html');
        }
        //
        elseif($itemVal=="loan_cell"){
            include_once('portal/views/page_loan_cell.html'); // add jimmy
            echo "<script>alert('請先登入!!'); location.href='index.php?item=login';</script>";
        }
        //
        elseif($itemVal=="loan_moto"){
            include_once('portal/views/page_loan_moto.html'); // add jimmy
            echo "<script>alert('請先登入!!'); location.href='index.php?item=login';</script>";
        }
        else{
            //首頁(沒有目標頁面itemVal)
//            include_once('portal/views/slider.php');
//            include_once('portal/views/page_top.html');
//            include_once('portal/views/page_content.html');
            include_once('portal/views/_index.php');
        }
    }

    //首頁(沒有目標頁面itemVal)
    else{
        include_once('portal/views/_index.php');
//        include_once('portal/views/page_top.html');
//        include_once('portal/views/page_content.html');
    }


    /************************* layout *******************************/
    include_once('portal/views/_footer.php');

?>