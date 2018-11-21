<?php

$Front_Manage = new Front_Manage();
$Front_Manage2 = new Front_Manage2();
//if(array_key_exists($itemVal,$page_other )){
//    $page_data = $Front_Manage->getAllFM($itemVal);
//}else if(array_key_exists($itemVal,$page_other2 )){
$page_data2 = $Front_Manage2->getAllFM('co_company');
//}

?>

<main role="main">
    <h1><span>聯絡我們</span><small>contact us</small></h1>
    <section id="helping-zone">
        <div class="container">

            <?php
            echo $page_data2['0']['co_company'];
            ?>

<!--            <div class="box-contact set-vertical">-->
<!--                <div class="row">-->
<!--                    <div class="col-lg-3 set-vertical-item text-center">-->
<!--                        <img src="portal/images/contact-fb.png" alt="FACEBOOK官方粉絲團客服">-->
<!--                    </div>-->
<!--                    <div class="col set-vertical-item">-->
<!--                        <h2 class="text-yellow">FACEBOOK官方粉絲團客服</h2>-->
<!--                        <p><a href="https://www.facebook.com/nowait.shop/" title="FACEBOOK官方粉絲團客服" class="text-orange">https://www.facebook.com/685591428479643</a><br>有任何疑問，歡迎透過 FB客服進行線上詢問，服務時段：09:00 - 21:00，(國定例假日為公休日)。</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="box-contact set-vertical">-->
<!--                <div class="row">-->
<!--                    <div class="col-lg-3 set-vertical-item text-center">-->
<!--                        <img src="portal/images/contact-line.png" alt="LINE @ 客服">-->
<!--                    </div>-->
<!--                    <div class="col set-vertical-item">-->
<!--                        <h2 class="text-yellow">LINE @ 客服</h2>-->
<!--                        <p>客服 LINE ID：@kwh9566z<br>歡迎透過 line@ 小幫手，輸入訂單號或顧客身份，進行查詢，專人線上服務時段：09:00 - 21:00 (國定例假日為公休日)。</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="box-contact set-vertical">-->
<!--                <div class="row">-->
<!--                    <div class="col-lg-3 set-vertical-item text-center">-->
<!--                        <img src="portal/images/contact-nowait.png" alt="FACEBOOK官方粉絲團客服">-->
<!--                    </div>-->
<!--                    <div class="col set-vertical-item">-->
<!--                        <h2 class="text-yellow">NoWait</h2>-->
<!--                        <p>地址：台北市內湖區瑞光路188巷52號3樓<br>客服專線：02-2656-0619#9<br>傳真：02-2656-0638<br>線上服務時段：09:00 - 18:00(國定例假日為公休日 )<br>email：support@nowait.com.tw</p>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
        </div>
    </section>
</main>