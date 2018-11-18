<?php
    session_start();
    include('../model/php_model.php');

    $errg = "";
    if ($_SESSION['shopping_user'][0]['memNo'] == "") {
        $errg = "Session已過期";
    }

    if ($errg == "") {

        $mco = new Motorbike_Cellphone_Orders();
        $mco_data = $mco->getOneOrderByNo($_SESSION['mco_code']);
        $member = new Member();
        $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
        $email = new Email();

        $mcoIfEditable = '1';
        if ($mco_data[0]['mcoStatus'] == '5') { // 原本資料不全需補件
            $mco->updateStatus('6', $_SESSION['ord_code']); // 審查中
            //$mco->updateStatusTime('6',$_SESSION['ord_code']);   todo ?
            $str_title = '補件';
        } else {
            /*fb進件模式*/
            if ($memberData[0]['memClass'] == '0' && $memberData[0]['memFBtoken'] != '') {
                if ($memberData[0]['memEmailAuthen'] == '0' || $memberData[0]['memEmailAuthen'] == '') {
                    $mco->updateStatus('0', $_SESSION['mco_code']);
                    //$or->updateStatusTime('0',$_SESSION['ord_code']); todo ?
                } else if ($memberData[0]['memEmailAuthen'] == '1') {
                    $mco->updateStatus('1', $_SESSION['mco_code']);
                    //$or->updateStatusTime('1',$_SESSION['ord_code']);
                }
            } else if ($memberData['0']['memFBtoken'] != '') {
                $mco->updateStatus('1', $_SESSION['mco_code']);
                //$or->updateStatusTime('1',$_SESSION['ord_code']);
            }
            
            /*一般帳號*/
            if ($memberData[0]['memEmailAuthen'] == '1' && $memberData[0]['memFBtoken'] == '') {
                $mco->updateStatus('1', $_SESSION['mco_code']);
                //$or->updateStatusTime('1',$_SESSION['ord_code']);
            }
            
            if ($memberData[0]['memEmailAuthen'] == '0' && $memberData[0]['memClass'] == '0') {
                $str_title = '已下單，Email未驗證';
            } else {
                $str_title = '未進件';
            }
            
        }
        
        $mco->updateMcoIfEditable($mcoIfEditable, $_SESSION['mco_code']);
        /*
        $Class = ($memberData[0]['memClass'] == '0') ? '學生' : '非學生';

        if ($memberData[0]['memEmailAuthen'] == '0' && $memberData[0]['memClass'] == '0') {
            $receiverNameAndEmails = array('service@nowait.shop' => 'EC部');
        } else if ($mco_data[0]['mcoStatus'] == '5') {
            $receiverNameAndEmails = array(
                'service@nowait.shop' => 'EC部',
                'happyfan7@21-finance.com' => '客服部',
                'sinlenlin@gmail.com' => '林青嵐',
                'andy_kuo@21-finance.com' => '郭原彰',
                'dan_chang@21-finance.com' => '客服1',
                'aa22760676@gmail.com' => '客服人員D'
            );
        } else {
            $receiverNameAndEmails = array(
                'service@nowait.shop' => 'EC部',
                'happyfan7@21-finance.com' => '客服部',
                'sinlenlin@gmail.com' => '林青嵐',
                'achappyfan7@gmail.com' => 'Allan',
                'andy_kuo@21-finance.com' => '郭原彰',
                'dan_chang@21-finance.com' => '客服1',
                'aa22760676@gmail.com' => '客服人員D'
            );
        }
            
        $titles = "【NoWait-" . $str_title . "】" . $mco_data[0]['mcoDate'] . ",流水號:" . $_SESSION['mco_code'] . "," . $memberData[0]['memName'] . "先生/小姐,訂單編號:" . $mco_data[0]['mcoCaseNo'];
        $contents = content1($memberData[0]['memName'], $memberData[0]['memIdNum'], $Class, $str_title $mco_data[0]['mcoCaseNo'], $mco_data[0]['mcoDate'], $mco_data[0]['mcoPeriodAmount'], $mco_data[0]['mcoPeriodTotal']);

        $send = $email->SendBCCEmail_smtp($receiverNameAndEmails, "service@nowait.shop", "NoWait", $titles, $contents);

        if (in_array("Allan", $receiverNameAndEmails)) {
            $ch = curl_init("http://nowait.shop/php/index.php?inst=happyfan7&msg=" . str_replace(" ", "_", $titles));
            curl_setopt($ch, CURLOPT_HTTPHEADER, false);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        
        if ($memberData[0]['memEmailAuthen'] == 0 && $memberData[0]['memClass'] == '0') {
            
            $receiverNameAndEmail = array(
                $memberData[0]['memAccount'] => $memberData[0]['memName'],
                'sinlenlin@gmail.com' => '客服人員A',
                'biglee2275@gmail.com' => '客服人員B',
                'min_yeon@kimo.com' => '客服人員C',
                'aa22760676@gmail.com' => '客服人員D'
            );

            $titles = "【NoWait購物網】學校Email認證信件";

            $contents = content($_SESSION['shopping_user'][0]['memName'], $memberData[0]['pass_number'], $_SESSION['user']['memNo']);

            $send = $email->SendBCCEmail_smtp($receiverNameAndEmail, "service@nowait.shop", "NoWait", $titles, $contents);

        } else {

            $receiverNameAndEmail = array($memberData[0]['memAccount'] => $memberData[0]['memName']);

            $titles = "【NoWait購物網】您訂購的商品審核中(訂單編號: " . $mco_data[0]['mcoCaseNo'] . ")";
            
            $contents = content2($_SESSION['shopping_user'][0]['memName'], $mco_data[0]['mcoCaseNo'], $mco_data[0]['mcoDate'], $mco_data[0]['mcoPeriodAmount'], $mco_data[0]['mcoPeriodTotal']);

            $send = $email->SendBCCEmail_smtp($receiverNameAndEmail, "service@nowait.shop", "NoWait", $titles, $contents);
        }
        */
        unset($mco);
        unset($email);
        unset($member);
        unset($_SESSION['mco_code']);
        unset($_SESSION['shopping_user']['0']);
        unset($_SESSION['shopping_product']['0']);  
        echo true;
    } else {
        echo false;
    }


function content1($memName, $memIdNum, $class, $str_title, $mcoCaseNo, $mcoDate, $mcoPeriodAmount, $mcoPeriodTotal) {
    $content = '    
<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
    <tbody>
        <tr>
            <td style="text-align:center;">
                <img src="https://nowait.shop/assets/images/logo_2.png" />
            </td>
        </tr>
        <tr>
            <td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
        </tr>
        <tr>
            <td style="color:black;font-weight:bold;">
                <p>顧客姓名：<span style="color:#FF9900;">' . $memName . ' </span> 先生/小姐</p>
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;background-color:#F5F3F1;">
                【NoWait-進件通知】<br>

                身份證字號：'. $memIdNum . '<br />

                身份：' . $class . '<br />
                
                訂單狀態：' . $str_title . '<br />

                商品明細如下：<br>

                訂單編號：' . $mcoCaseNo . '<br />

                訂購日期：' . $mcoDate . '<br /> 
                
                分期期數：' . $mcoPeriodAmount . ' 期<br />
                
                每期金額：' . number_format($mcoPeriodTotal / $mcoPeriodAmount) . ' 元<br />
            </td>
        </tr>
    </tbody>
</table>';
    return $content;
}

function content($memName, $passNumber, $memNo) {
    $content = '   
<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
    <tbody>
        <tr>
            <td style="text-align:center;">
                <img src="https://nowait.shop/assets/images/logo_2.png" />
            </td>
        </tr>
        <tr>
            <td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
        </tr>
        <tr>
            <td style="color:black;font-weight:bold;">
                <p>親愛的<span style="color:#FF9900;">' . $memName . ' </span> 先生/小姐，您好：</p>
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;background-color:#F5F3F1;">
                <p>這封認證信是由<span style="color:#0006FF;text-decoration:underline;">NoWait購物網</span>所發出，<span style="color:red">請點選下面鏈結</span>開通您的會員帳號，您將享受NoWait購物網提供的會員購物服務。</p>
                <p>
                    <a href=https://nowait.shop/php/member_id.php?pass_number=' . $passNumber . '&memNo=' . $memNo . '>https://nowait.shop/php/member_id.php?pass_number=' . $passNumber . '&memNo=' . $memNo . '</a>
                </p>
                <p>若此帳號並非您本人所申請，請您不須理會此會員確認信函。 感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，NoWait將會為您處理。 NoWait購物網祝福您 順心如意!!</p>
            </td>
        </tr>
    </tbody>
</table>';
    return $content;
}

function content2($memName, $mcoCaseNo, $mcoDate, $mcoPeriodAmount, $mcoPeriodTotal) {
    $content = '   
<table width="660" align="center" cellpadding="10" cellspacing="1" style="border:3px solid #999;">
    <tbody>
        <tr>
            <td style="text-align:center;">
                <img src="https://nowait.shop/assets/images/logo_2.png" />
            </td>
        </tr>
        <tr>
            <td style="color:#FF3333;font-weight:bold;text-align:center;">此為系統自動通知信，請勿直接回信！</td>
        </tr>
        <tr>
            <td style="color:black;font-weight:bold;">
                <p>親愛的<span style="color:#FF9900;">' . $memName . ' </span> 先生/小姐，您好：</p>
                <p style="color:red;">下單當日請注意手機！！平日若超過2天未接獲電話，麻煩請洽客服人員並告知申請人可接電話時間。
國定例假日下單案件較多，若無接到照會電話屬正常，若連假後仍無接獲電話亦可主動與客服聯絡，感謝您！</p>
            </td>
        </tr>
        <tr>
            <td style="font-weight:bold;background-color:#F5F3F1;">
                您所訂購的商品審核中，您此次訂購的商品明細如下：<br>

                訂單編號：' . $mcoCaseNo . '<br>

                訂購日期：' . $mcoDate . '<br> 
                
                分期期數：' . $mcoPeriodAmount . ' 期<br>
                
                每期金額：' . number_format($mcoPeriodTotal / $mcoPeriodAmount) . ' 元<br>
            </td>
        </tr>
        <tr>
            <td>
                <p>
                    感謝您的支持，如有疑問歡迎到 <a href="https://nowait.shop/index.php?item=fmContactService" target="_blank"><span style="#FF9900;text-decoration:underline;">聯絡客服</span></a> 反應，
                    NoWait將會為您處理。如需訂購其他商品請至 <a href="https://nowait.shop/index.php" target="_blank"><span style="color:blue;text-decoration:underline;">NoWait購物網</span></a> 選購。
                </p>
            </td>
        </tr>                       
    </tbody>
</table>';
    return $content;
}
?>