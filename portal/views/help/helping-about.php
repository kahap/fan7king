<?php

$Front_Manage = new Front_Manage();
$Front_Manage2 = new Front_Manage2();
//if(array_key_exists($itemVal,$page_other )){
//    $page_data = $Front_Manage->getAllFM($itemVal);
//}else if(array_key_exists($itemVal,$page_other2 )){
    $page_data2 = $Front_Manage2->getAllFM('fmAboutUs,fmAboutUs2');
//}

?>

<main role="main">
    <h1><span>關於我們</span><small>about us</small></h1>
    <section id="helping-zone">
        <div class="container">
            <div class="box-about">
                <div class="row">
<!--                    <div class="col-lg-6">-->
                        <?php
							echo $page_data2['0']['fmAboutUs'];
                        ?>
<!--                        <p class="title">享受，不必等待</p>-->
<!--                        <p>NoWait 提供年輕世代更便捷、輕鬆的支付方式，實現投資自己、享受生活。<br>免卡分期、0元取貨，不需要信用卡也能輕鬆分期支付，全台最速 on-line 數位審核，審核僅需一杯咖啡的時間，讓您享有不必再虐心等待。</p>-->
<!--                    </div>-->
<!--                    <div class="col-lg-6">-->
<!--                        <img src="portal/images/about-nowait.png" alt="享受，不必等待" class="img-fluid">-->
<!--                    </div>-->
                </div>
<!--                <div class="row">-->
<!--                    <div class="col-lg-6 order-lg-2">-->
<!--                        <p class="title">全台服務體驗最佳的免卡分期電商購物網</p>-->
<!--                        <p>NoWait 力於打造全台服務體驗最佳的免卡分期電商購物網 !<br>簡潔的商城選物、數位e化的分期申請、業界最快的審核程序、多元彈性的繳款方式，創造讓消費者超順手的分期購物經驗，不斷豐富產品的品項類型，活用金融科技進化平台，持續追求成為客戶享購就GO 不用等待的第一選擇。</p>-->
<!--                    </div>-->
<!--                    <div class="col-lg-6 order-lg-1">-->
<!--                        <img src="portal/images/about-ec.png" alt="享受，不必等待" class="img-fluid">-->
<!--                    </div>-->
<!--                </div>-->
            </div>
        </div>
    </section>
</main>