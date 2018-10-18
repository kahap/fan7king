<?php
session_start();
include('../model/php_model.php');

$mco = new Motorbike_Cellphone_Orders();
$member = new Member();
$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);

$mcoNo = $_SESSION['mco_code'];

if (!isset($_SESSION['mco_code']) || empty($mcoNo)) {
    echo "不正確的進入方式，請從會員中心的手機、機車借款訂單的訂單編號點選進入!";
    exit;
}

if ($_SESSION['shopping_user'][0]['memNo'] != "") {
    
    $data = $mco->getOneOrderByNo($mcoNo);
    $mco_data = $mco->getTwinOrByMemberAndMethod($_SESSION['user']['memNo']);
    $mco_dataDealFinish = $mco->getDealFinish($_SESSION['user']['memNo']);
    
    $array['mcoIdImgTop'] = (@$data[0]['mcoIdImgTop'] == '') ? (($mco_data[1]['mcoIdImgTop'] == '') ? $mco_dataDealFinish[0]['mcoIdImgTop'] : $mco_data[1]['mcoIdImgTop']) : $data[0]['mcoIdImgTop'];
    if (empty($array['mcoIdImgTop'])) {
        $errg[] = '請上傳身分證正面照片';
    } else {
        //身份證正面
        $mco->updateMcoIdImgTop($array['mcoIdImgTop'], $mcoNo);
    }
    
    $array['mcoIdImgBot'] = (@$data[0]['mcoIdImgBot'] == '') ? (($mco_data[1]['mcoIdImgBot'] == '') ? $mco_dataDealFinish[0]['mcoIdImgBot'] : $mco_data[1]['mcoIdImgBot']) : $data[0]['mcoIdImgBot'];
    if (empty($array['mcoIdImgBot'])) {
        $errg[] = '請上傳身分證反面照片';
    } else {
        //身份證背面
        $mco->updateMcoIdImgBot($array['mcoIdImgBot'], $mcoNo);
    }

    if ($memberData[0]['memClass'] == '0') {
        $array['mcoStudentIdImgTop'] = (@$data[0]['mcoStudentIdImgTop'] == '') ? (($mco_data[1]['mcoStudentIdImgTop'] == '') ? $mco_dataDealFinish[0]['mcoStudentIdImgTop'] : $mco_data[1]['mcoStudentIdImgTop']) : $data[0]['mcoStudentIdImgTop'];
        $array['mcoStudentIdImgBot'] = (@$data[0]['mcoStudentIdImgBot'] == '') ? (($mco_data[1]['mcoStudentIdImgBot'] == '') ? $mco_dataDealFinish[0]['mcoStudentIdImgBot'] : $mco_data[1]['mcoStudentIdImgBot']) : $data[0]['mcoStudentIdImgBot'];
        if (empty($array['mcoStudentIdImgTop'])) {
            $errg[] ='請上傳學生證正面照片';    
        } else {
            //學生證正面
            $mco->updateMcoStudentIdImgTop($array['mcoStudentIdImgTop'], $mcoNo);
        }
        if (empty($array['mcoStudentIdImgBot'])) {
            $errg[] = '請上傳學生證反面照片';
        } else {
            //學生證背面
            $mco->updateMcoStudentIdImgBot($array['mcoStudentIdImgBot'], $mcoNo);
        }
    }
    
    $array['mcoSubIdImgTop'] = (@$data[0]['mcoSubIdImgTop'] == '') ? (($mco_data[1]['mcoSubIdImgTop'] == '') ? $mco_dataDealFinish[0]['mcoSubIdImgTop'] : $mco_data[1]['mcoSubIdImgTop']) : $data[0]['mcoSubIdImgTop'];
    if (empty($array['mcoSubIdImgTop'])) {
        $errg[] = '請上傳健保卡/駕照正面照片';
    } else {
        //健保卡或駕照正面
        $mco->updateMcoSubIdImgTop($array['mcoSubIdImgTop'], $mcoNo);
    }
    

    if ($data[0]['mcoType'] == '2') { // 機車
        $array['mcoCarIdImgTop'] = (@$data[0]['mcoCarIdImgTop'] == '') ? (($mco_data[1]['mcoCarIdImgTop'] == '') ? $mco_dataDealFinish[0]['mcoCarIdImgTop'] : $mco_data[1]['mcoCarIdImgTop']) : $data[0]['mcoCarIdImgTop'];
        if (empty($array['mcoCarIdImgTop'])) {
            $errg[] = '請上傳行照正面照片';
        } else {
            //行照正面
            $mco->updateMcoCarIdImgTop($array['mcoCarIdImgTop'], $mcoNo);
        }
    }

    $array['mcoBankBookImgTop'] = (@$data[0]['mcoBankBookImgTop'] == '') ? (($mco_data[1]['mcoBankBookImgTop'] == '') ? $mco_dataDealFinish[0]['mcoBankBookImgTop'] : $mco_data[1]['mcoBankBookImgTop']) : $data[0]['mcoBankBookImgTop'];
    if (empty($array['mcoBankBookImgTop'])) {
        $errg[] = '請上傳存摺封面照片';
    } else {
        //存摺正面
        $mco->updateMcoBankBookImgTop($array['mcoBankBookImgTop'], $mcoNo);
    }
    
    if ($data[0]['mcoCompanyStatus'] == '1') {
        $array['mcoRecentTransactionImgTop'] = (@$data[0]['mcoRecentTransactionImgTop'] == '') ? (($mco_data[1]['mcoRecentTransactionImgTop'] == '') ? $mco_dataDealFinish[0]['mcoRecentTransactionImgTop'] : $mco_data[1]['mcoRecentTransactionImgTop']) : $data[0]['mcoRecentTransactionImgTop'];
        if (empty($array['mcoRecentTransactionImgTop'])) {
            $errg[] = '請上傳近六個月往來照片';
        } else {
            //近6個月薪資往來
            $mco->updateMcoCarIdImgTop($array['mcoRecentTransactionImgTop'], $mcoNo);
        }
    }
    
    if ($_POST['mcoIdIssueYear'] == "" || $_POST['mcoIdIssueMonth'] == "" || $_POST['mcoIdIssueDay'] == "") {
        $errg[] = '請請填寫身分證發證日期'; 
    }  
    if ($_POST['mcoIdIssuePlace'] == "") {
        $errg[] = '請請填寫身分證發證地點';
    }  
    if ($_POST['mcoIdIssueType'] == "") {
        $errg[] = '請請填寫身分證發證類別';
    }

    if (!empty($errg)) {
        $msg = implode(',', $errg);
        echo $msg;
    } else {
        $inputData['mcoIdIssueYear'] = $_POST['mcoIdIssueYear'];
        $inputData['mcoIdIssueMonth'] = $_POST['mcoIdIssueMonth'];
        $inputData['mcoIdIssueDay'] = $_POST['mcoIdIssueDay'];
        $inputData['mcoIdIssuePlace'] = $_POST['mcoIdIssuePlace'];
        $inputData['mcoIdIssueType'] = $_POST['mcoIdIssueType'];
        $mco->updateForId($inputData, $_SESSION['mco_code']);
        echo 1;
    }

} else {
    echo "登入過期，請重新登入再重新編輯!";
}
    
?>