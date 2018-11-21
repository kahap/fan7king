<?php
$member = new Member();
$memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
$memOrigData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
$member->changeToReadable($memberData[0]);

$or = new Orders();
$orMemData = $or->getOrByMemberAndMethod($_SESSION['user']['memNo'],1, 0,9999);

$pm = new Product_Manage();
$pro = new Product();

//訂單
$orNo = $_GET["orno"];
$orOrigData = $or->getOneOrderByNo($orNo);
$orData = $or->getOneOrderByNo($orNo);
$or->changeToReadable($orData[0],$method);

//欄位名稱
$columnName = $or->getAllColumnNames("orders");

//商品上架
$pmData = $pm->getOnePMByNo($orData[0]["pmNo"]);

//商品
$proData = $pro->getOneProByNo($pmData[0]["proNo"]);
?>

<main role="main">
    <h1><span>會員中心</span><small>member center</small></h1>
    <section id="member-zone">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <?php
                    $active = 3;
                    require_once 'portal/views/member/_left.php';
                    ?>
                </div>
                <div class="col-lg-9">
                    <div class="section-inner-table bg-white">
                        <table class="table table-borderless table-rwd">
                            <thead>
                            <tr class="bg-pale">
                                <th nowrap="nowrap">　期數</th>
                                <th nowrap="nowrap">應繳款日</th>
                                <th nowrap="nowrap">實際繳款日</th>
                                <th nowrap="nowrap">應繳金額</th>
                                <th nowrap="nowrap">還款金額</th>
                                <th nowrap="nowrap">繳款方式</th>
                            </tr>
                            </thead>
                            <?php
                            if($orMemData != null){
                                foreach($orMemData as $key=>$value){

                                    $orig = $value;
                                    $or->changeToReadable($value,$value["orMethod"]);
                                    $pmData = $pm->getOnePMByNo($value["pmNo"]);
                                    $proData = $pro->getOneProByNo($pmData[0]["proNo"]);
                                    if($value["orStatus"] == '我要繳款'){
                                        ?>
                                        <tbody>
                                        <tr>
                                            <td data-th="期數" nowrap="nowrap" class="text-center"><?php echo $value["orPeriodAmnt"];?></td>
                                            <td data-th="應繳款日" nowrap="nowrap"><?php echo $value["orHandlePaySupDate"];?></td>
                                            <td data-th="實際繳款日"><?php echo $value["orHandlePaySupDate"];?></td>
                                            <td data-th="應繳金額" nowrap="nowrap"><?php echo $value["orPeriodTotal"]/$value['orPeriodAmnt'];?></td>
                                            <td data-th="還款金額" nowrap="nowrap"></td>
                                            <td data-th="繳款方式" class="payment" nowrap="nowrap">
                                            <?php
                                            if ($value["prPayBy"]==0){
                                                ?>
                                                <a href="#" title="網銀/ATM匯入繳款" data-toggle="modal" data-target="#exampleModal">
                                                    <img src="images/icon-atm.png" alt="網銀/ATM匯入繳款">
                                                </a>
                                                <?php
                                            }elseif ($value["prPayBy"]==1) {
                                                ?>
                                                <a href="#" title="全家、萊爾富超商掃描電子條碼付款">
                                                    <img src="images/icon-barcode.png" alt="全家、萊爾富超商掃描電子條碼付款">
                                                </a>
                                                <?php
                                            }elseif ($value["prPayBy"]==2){
                                                ?>
                                                <a href="#" title="列印紙本四大超商付款">
                                                    <img src="images/icon-printer.png" alt="列印紙本四大超商付款">
                                                </a>
                                                <?php
                                            }
                                            ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                }
                            }else{
                                ?>
                                <td colspan="5">沒有任何資料</td>
                                <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


<div class="modal fade modal-center" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <h2><span>ATM匯款資訊</span></h2>
                <div class="pay-modal">
                    <div class="pay-modal-title">
                        <p class="text-center">本期繳款金額：<span class="text-orange">1000元</span></p>
                    </div>
                    <div class="pay-modal-content">
                        <p class="text-center text-gray">請使用網路銀行或實體ATM將繳款金額轉入以下帳戶</p>
                        <div class="bank-info">
                            <p class="text-center">板信商業銀行</p>
                            <p>銀行帳號：0000000000<br>銀行代碼：118</p>
                        </div>
                    </div>
                </div>
                <p class="text-center">轉帳完成後請保留轉帳收據直至帳款入帳。若轉帳後４８小時仍未入帳，<br>
                    請與客服聯繫查詢，可至<a href="member-order.htm" title="訂單查詢" class="text-orange">訂單查詢</a>內確認是否完成匯款。</p>
            </div>
        </div>
    </div>
</div>