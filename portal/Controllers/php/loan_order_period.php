<?php
    session_start();
    include('../model/php_model.php');
    $member = new Member();
    $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    if (isset($_SESSION['mco_code'])) {
        unset($_SESSION['mco_code']);
    }
    if ($_SESSION['user']['memNo'] != "") {
        
        if ($memberData[0]['memFBtoken'] == '' && $memberData[0]['memEmailAuthen'] == '0') {
            echo "您尚未完成Email驗證,請您先至註冊時填寫之帳號(Email)收信並點選驗證網址,才能進行後續借款,如您仍有問題,請聯絡客服人員,樂分期將為您服務,謝謝!!";
        } else {
            $_SESSION['shopping_user'] = $memberData;
            $_SESSION['mco_data'] = $_POST;
            echo "success";
        }
        
    } else {
        echo "請先登入後再進行借款!!";
    }
    
?>