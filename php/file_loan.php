<?php
session_start();
include('../model/php_model.php');


if (isset($_SESSION['mco_code']) && $_SESSION['mco_code'] != '' && $_SESSION['shopping_user'][0]['memNo'] != '') {
    $mco = new Motorbike_Cellphone_Orders();
    //申請人身份證正面
    if (isset($_FILES['idPositive']) && !empty($_FILES['idPositive'])) {
        $picName = $_FILES['idPositive']['name'];
        $picSize = $_FILES['idPositive']['size'];
        $picTmpName = $_FILES['idPositive']['tmp_name'];
        $picType = $_FILES['idPositive']['type'];
        $picError = $_FILES['idPositive']['error'];
        $updateType = 1;
    }

    //申請人身份證反面
    if (isset($_FILES['idNegative']) && !empty($_FILES['idNegative'])) {
        $picName = $_FILES['idNegative']['name'];
        $picSize = $_FILES['idNegative']['size'];
        $picTmpName = $_FILES['idNegative']['tmp_name'];
        $picType = $_FILES['idNegative']['type'];
        $picError = $_FILES['idNegative']['error'];
        $updateType = 2;
    }

    //申請人健保卡或駕照正面
    if (isset($_FILES['licensePositive']) && !empty($_FILES['licensePositive'])) {
        $picName = $_FILES['licensePositive']['name'];
        $picSize = $_FILES['licensePositive']['size'];
        $picTmpName = $_FILES['licensePositive']['tmp_name'];
        $picType = $_FILES['licensePositive']['type'];
        $picError = $_FILES['licensePositive']['error'];
        $updateType = 3;
    }

    //申請人行照正面
    if (isset($_FILES['vehiclePositive']) && !empty($_FILES['vehiclePositive'])) {
        $picName = $_FILES['vehiclePositive']['name'];
        $picSize = $_FILES['vehiclePositive']['size'];
        $picTmpName = $_FILES['vehiclePositive']['tmp_name'];
        $picType = $_FILES['vehiclePositive']['type'];
        $picError = $_FILES['vehiclePositive']['error'];
        $updateType = 4;
    }

    //申請人學生證正面
    if (isset($_FILES['studentPositive']) && !empty($_FILES['studentPositive'])) {
        $picName = $_FILES['studentPositive']['name'];
        $picSize = $_FILES['studentPositive']['size'];
        $picTmpName = $_FILES['studentPositive']['tmp_name'];
        $picType = $_FILES['studentPositive']['type'];
        $picError = $_FILES['studentPositive']['error'];
        $updateType = 5;
    }

    //申請人學生證反面
    if (isset($_FILES['studentNegtive']) && !empty($_FILES['studentNegtive'])) {
        $picName = $_FILES['studentNegtive']['name'];
        $picSize = $_FILES['studentNegtive']['size'];
        $picTmpName = $_FILES['studentNegtive']['tmp_name'];
        $picType = $_FILES['studentNegtive']['type'];
        $picError = $_FILES['studentNegtive']['error'];
        $updateType = 6;
    }

    //存摺封面
    if (isset($_FILES['bankbook']) && !empty($_FILES['bankbook'])) {
        $picName = $_FILES['bankbook']['name'];
        $picSize = $_FILES['bankbook']['size'];
        $picTmpName = $_FILES['bankbook']['tmp_name'];
        $picType = $_FILES['bankbook']['type'];
        $picError = $_FILES['bankbook']['error'];
        $updateType = 7;
    }

    //近六個月往來
    if (isset($_FILES['bankContent']) && !empty($_FILES['bankContent'])) {
        $picName = $_FILES['bankContent']['name'];
        $picSize = $_FILES['bankContent']['size'];
        $picTmpName = $_FILES['bankContent']['tmp_name'];
        $picType = $_FILES['bankContent']['type'];
        $picError = $_FILES['bankContent']['error'];
        $updateType = 8;
    }

    //其他附件資料
    if (isset($_FILES['otherData']) && !empty($_FILES['otherData'])) {
        
        $count = 0;
        foreach ($_FILES['otherData']['name'] as $key => $value) {
            $picName[] = $value;
            $count += 1;
        }
        foreach ($_FILES['otherData']['size'] as $key => $value) {
            $picSize[] = $value;
        }
        foreach ($_FILES['otherData']['tmp_name'] as $key => $value) {
            $picTmpName[] = $value;
        }
        foreach ($_FILES['otherData']['type'] as $key => $value) {
            $picType[] = $value;
        }
        foreach ($_FILES['otherData']['error'] as $key => $value) {
            $picError[] = $value;
        }

        $updateType = 9; 
    }

    $path = "../admin/file/" . $_SESSION['shopping_user'][0]['memNo'];
    //檢查是否有會員資料夾
    if (!is_dir($path)) {
        mkdir($path);
        chmod($path, 0777);
    }
    
    $file = new File();
    $systemDirPath = $path . "/";
    $rand = rand(100, 999); 
    $defaultFileName = date("YmdHis");

    if ($updateType != 9) {
        if ($file->FileCheck($picTmpName, $picType, $picSize, $picError, $systemDirPath, $picName)) {            
            $type = strstr($picName, '.');
            $fileName = $defaultFileName . $type;
            $thumbNail = $file->SaveImageThumbnail($picType, $picTmpName, $systemDirPath, $fileName, '900', '600');
            $finalFileName = $systemDirPath . $fileName;

            if (!is_file($finalFileName)) {
                $finalFileName = '';
                $arr = array( 
                    'name' => $picName, 
                    'msg' => '檢查上傳後圖片路徑錯誤',
                    'status' => 'FAIL'
                );
            } else {

                switch ($updateType) {
                    case 1: //申請人身份證正面
                        $mco->updateMcoIdImgTop(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 2: //申請人身份證反面
                        $mco->updateMcoIdImgBot(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 3: //申請人健保卡或駕照正面
                        $mco->updateMcoSubIdImgTop(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 4: //申請人行照正面
                        $mco->updateMcoCarIdImgTop(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 5: //申請人學生證正面
                        $mco->updateMcoStudentIdImgTop(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 6: //申請人學生證反面
                        $mco->updateMcoStudentIdImgBot(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 7: //存摺封面
                        $mco->updateMcoBankBookImgTop(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    case 8: //近六個月往來
                        $mco->updateMcoRecentTransactionImgTop(substr($finalFileName, 3), $_SESSION['mco_code']);
                        break;
                    default:
                        break;
                }

                $size = round($picSize / 1024, 2); //轉成kb 
                $arr = array( 
                    'name' => $picName, 
                    'pic' => $fileName, 
                    'size' => $size,
                    'status' => 'OK'
                );

            }

        } else {
            $arr = array(
                'status' => 'FAIL',
                'msg' => $file->Geterrorstring()
            );
        }
    } else { //其他附件資料
        $imgUpdatePath = array();
        for ($i = 0; $i < $count; $i++) {

            if ($file->FileCheck($picTmpName[$i], $picType[$i], $picSize[$i], $picError[$i], $systemDirPath, $picName[$i])) {
                $defaultFileName = date("YmdHis") . "_" . $i;
                $type = strstr($picName[$i], '.');
                $fileName = $defaultFileName . $type;
                $thumbNail = $file->SaveImageThumbnail($picType[$i], $picTmpName[$i], $systemDirPath, $fileName, '900', '600');
                $finalFileName = $systemDirPath . $fileName;

                if (!is_file($finalFileName)) {
                    $finalFileName = '';
                    $detail = array( 
                        'name' => $picName[$i], 
                        'msg' => '檢查上傳後圖片路徑錯誤'
                    );
                    $arr['data'][] = $detail;
                    $arr['status'] = 'FAIL';

                } else {
                    $imgUpdatePath[] = substr($finalFileName, 3);
                    $size = round($picSize[$i] / 1024, 2); //轉成kb 
                    $detail = array( 
                        'name' => $picName[$i], 
                        'pic' => $fileName, 
                        'size' => $size
                    );
                    $arr['data'][] = $detail;
                    $arr['status'] = 'OK';
                }

            } else {

                $detail = array(
                    'name' => $picName[$i],
                    'msg' => $file->Geterrorstring()
                );
                $arr['data'][] = $detail;
                $arr['status'] = 'FAIL';
            }
        }

        $mco->updateMcoExtraInfoUpload(json_encode($imgUpdatePath, JSON_UNESCAPED_UNICODE), $_SESSION['mco_code']);
        $arr['count'] = $count;
    }

    unset($mco);
    unset($file);
} else {
    $arr = array(
        'status' => 'FAIL',
        'msg' => '非正確填寫步驟流程或請檢查是否有登入!'
    );
}
 
echo json_encode($arr);
exit;
?>