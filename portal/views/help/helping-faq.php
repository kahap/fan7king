<?php
//require_once('model/require_general.php');

$qa = new Que_And_Ans();
$allQAData = $qa->getAllQA();

?>
    <main role="main">
        <h1><span>常見問題</span><small>FAQ</small></h1>
        <section id="helping-zone">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="list-group list-menu list-spy">
                            <?php foreach ($qaTypeArr as $key =>$value){ ?>
                            <a href="#z<?php echo $key;?>" class="list-group-item list-group-item-action "><?php echo $value;?></a>
                            <?php } ?>
<!--                            <a href="#z2" class="list-group-item list-group-item-action">訂購申請</a>-->
<!--                            <a href="#z3" class="list-group-item list-group-item-action">配送物流</a>-->
<!--                            <a href="#z4" class="list-group-item list-group-item-action">商品退換</a>-->
<!--                            <a href="#z5" class="list-group-item list-group-item-action">付款方式</a>-->
<!--                            <a href="#z6" class="list-group-item list-group-item-action">其他相關</a>-->
                        </div>
                    </div>
                    <div class="col-lg-9">
                    <?php
                    if($qaTypeArr != null){
                        foreach($qaTypeArr as $key => $value) {
                            ?>
                            <div id="z<?php echo $key;?>" class="box-faq">
                                <p class="title"><?php echo $value; ?></p>
                                <div class="accordion accordion-faq" id="accordion1">
                                <?php
                                if($allQAData != null) {
                                    foreach ($allQAData as $key2 => $value2) {
                                        if ($value2['qaType'] != $key) continue;
                                        if ($value2['qaIfShow'] != 1) continue;
                                        ?>
                                        <div class="card">
                                            <div class="card-header">
                                                <a class="btn btn-link collapsed" data-toggle="collapse"
                                                   data-target="#collapse1-1"><?php echo $value2['qaQues']; ?></a>
                                            </div>
                                            <div id="collapse1-1" class="collapse" data-parent="#accordion1">
                                                <div class="card-body">
                                                    <?php echo $value2['qaAnsw']; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php
                                    }
                                }
                                ?>
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-2">請問審核的時間大約多久呢?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-2" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                學生身份審核時間約30分鐘，其餘身份為當天審核，可以上NoWait網站上查詢。 【會員中心】-點選-->
<!--                                                【分期訂單查詢-】【查看訂單狀態】。若平日超過下午5點後/假日，案件可能隔天上班日進行審核。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-3">我是否可以保留個人隱私不讓親友知道?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-3" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                可以的，我們會保留您的個人隱私。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-4">外籍人士能否申辦呢?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-4" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                很抱歉，目前尚未開放外籍人士，必須要持有【中華民國身分證】才能申辦。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-5">我是學生，但學生証遺失怎麼辦？還能申辦免卡分期嗎？</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-5" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                可以的，只要您向學校申請『在校證明書』提供給NoWait，就可以申請免卡分期。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-6">如何更新我的個人資料 ? </a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-6" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                您可以至【會員中心】，點選【會員資料修改】，就可以更改個人資料，建議您及時更新您的最新聯絡資訊，以確保溝通的正確性與及時性。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-7">我的帳號無法登入 ? </a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-7" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                無法登入原因可能如下：<br>-->
<!--                                                1.您申請帳號當時，尚未完成輸入手機認證碼的動作，導致註冊手續未完成，所以無法開通會員。<br>-->
<!--                                                2.您的密碼或使用者名稱，需注意英文的大小寫都必須與之前的設定一致。<br>-->
<!--                                                3.如果忘記密碼，請點選【登入】的忘記密碼，重新輸入綁定的手機號碼以傳送簡訊驗證碼<br>-->
<!--                                                如仍無法登入或其他狀況，請您再與NoWait購物網客服中心聯繫，謝謝。<br>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse1-8">要如何查詢以往的訂購紀錄呢 ?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse1-8" class="collapse" data-parent="#accordion1">-->
<!--                                            <div class="card-body">-->
<!--                                                當每次交易完成後，電腦螢幕/手機APP上會顯示出交易紀錄頁面，如果想查詢您之前的交易紀錄。請到【會員中心】點選【訂單查詢】，就可以查看您在NoWait購物網站的所有交易紀錄了。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                </div>
                            </div>
<!--                            <div id="z2" class="box-faq">-->
<!--                                <p class="title">訂購申請</p>-->
<!--                                <div class="accordion accordion-faq" id="accordion2">-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse2-1">如果網站上沒有我想要的商品，可以客訂嗎?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse2-1" class="collapse" data-parent="#accordion2">-->
<!--                                            <div class="card-body">-->
<!--                                                可以的~您若找不到東西，可以與我們聯繫唷。<br>-->
<!--                                                *此外，依您要求之客訂訂商品一旦下訂就無法再享有7天鑑賞期及退換貨！(請確定想要買的商品，再跟客服人員說)<br>-->
<!--                                                *客訂商品僅限台灣地區平台上有出貨之現貨商品，請有客訂需求者，麻煩提供網址與客服人員確定。<br>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div id="z3" class="box-faq">-->
<!--                                <p class="title">配送物流</p>-->
<!--                                <div class="accordion accordion-faq" id="accordion3">-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse3-1">請問何時會到貨呢?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse3-1" class="collapse" data-parent="#accordion3">-->
<!--                                            <div class="card-body">-->
<!--                                                1. NoWait目前配送方式統一為宅配到府。<br>-->
<!--                                                2.現貨商品在【訂單狀態：核准】後，約3至7個工作天送達(不含國定例假日)。<br>-->
<!--                                                3. 預購商品約7至14個工作天送達(不含國定例假日，請依商品頁標示實際時間及方式為主)。<br>-->
<!--                                                4. 運送範圍為臺灣本島，其他地區客戶建議您使用臺灣本島親友地址收件。<br>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="card">-->
<!--                                            <div class="card-header">-->
<!--                                                <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                                   data-target="#collapse3-2">如何查詢目前出貨狀態？</a>-->
<!--                                            </div>-->
<!--                                            <div id="collapse3-2" class="collapse" data-parent="#accordion3">-->
<!--                                                <div class="card-body">-->
<!--                                                    1.-->
<!--                                                    您可以上會員中心【訂單查詢】中查詢出貨狀態，當您的訂單狀態從【核准】到【備貨中】後，約1-3天會變成【出貨中】，當變成【出貨中】時，您就可以點進入訂單查詢，會顯示目前配送的【宅配公司】和【宅配單號】，您就可以上物流公司網站進行查詢目前商品運送狀況！<br>-->
<!--                                                    *備貨中：商品準備中、*出貨中：顯示物流公司名稱及單號，大約2-4個工作天商品會送到您的收貨地址。<br>-->
<!--                                                    2.您可以於填寫收地址時，備註您希望的到貨時間，我們將盡量優先依您所選擇的時間配送。-->
<!--                                                    3.離島地區地區暫不配送。<br>-->
<!--                                                    4.若因交易條件有誤或有其他本公司無法接受訂單之情形，本公司將以專人電話通知您訂單不成立/取消訂單，並且不會請您至銀行或ATM自動提款機做任何操作。-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                        <div class="card">-->
<!--                                            <div class="card-header">-->
<!--                                                <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                                   data-target="#collapse3-2">完成訂單後還可以更改收貨地址嗎?</a>-->
<!--                                            </div>-->
<!--                                            <div id="collapse3-2" class="collapse" data-parent="#accordion3">-->
<!--                                                <div class="card-body">-->
<!--                                                    很抱歉，因訂單作業流程非常快速，故當您完成訂單後無法為您修改出貨資料，建議您手機及聯絡電話保持開通，如配送中有問題貨運公司將與您聯絡，不便之處，敬請見諒，謝謝。-->
<!--                                                </div>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div id="z4" class="box-faq">-->
<!--                                <p class="title">商品退換</p>-->
<!--                                <div class="accordion accordion-faq" id="accordion4">-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse4-1">我購買的商品有七天猶豫期嗎?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse4-1" class="collapse" data-parent="#accordion4">-->
<!--                                            <div class="card-body">-->
<!--                                                依據消費者保護法的規定，您享有商品貨到日起七天猶豫期之權益的保障服務-->
<!--                                                (猶豫期非鑑賞期，除新品瑕疵外，商品如經拆封或使用，即不能退換貨)<br>-->
<!--                                                貼心提醒：<br>-->
<!--                                                凡購買一經拆封即無法退換貨。<br>-->
<!--                                                如遇新品不良本公司均依據原廠保固條約/維修方式處理。<br>-->
<!--                                                保固內（非人為）商品有硬體故障狀況時（經過軟體重置後）功能依然無法正常使用，得依據原廠公司標準維修流程進行檢測維修，不提供整盒換新服務，並採更換零件或（一對一）整機交換方式處理。<br>-->
<!--                                                Apple產品不保固不影響功能使用的問題。（如：外觀、亮點、暗點，故此不提供新品更換服務）-->
<!--                                                購買前請謹慎考慮。<br>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse4-2">商品是正品嗎?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse4-2" class="collapse" data-parent="#accordion4">-->
<!--                                            <div class="card-body">-->
<!--                                                NoWait購物網的商品皆由知名廠商所提供，所有商品均為正品，可以安心選購。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse4-3">商品有保固期嗎?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse4-3" class="collapse" data-parent="#accordion4">-->
<!--                                            <div class="card-body">-->
<!--                                                因每項商品屬性不同，保固內容請詳見商品頁面標示。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div id="z5" class="box-faq">-->
<!--                                <p class="title">付款方式</p>-->
<!--                                <div class="accordion accordion-faq" id="accordion5">-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse5-1">購物後何時開始繳款呢?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse5-1" class="collapse" data-parent="#accordion5">-->
<!--                                            <div class="card-body">-->
<!--                                                第一期的繳款時間會在【確認收貨】之後14天，收到貨後約2-7天內即可上NoWait網站上查詢【實際繳款時間】並可以開始繳款。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse5-2">如何繳交分期款呢?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse5-2" class="collapse" data-parent="#accordion5">-->
<!--                                            <div class="card-body">-->
<!--                                                1.可以每月固定至超商透過手機APP線上查詢，顯示條碼掃描付款 。<br>-->
<!--                                                2.透過網路銀行/ATM轉帳繳款<br>-->
<!--                                                3.可自行至NoWait網站上查詢及列印繳款單<br>-->
<!--                                                ★註：請準時繳款以維護自身信用、超過繳款期限將依「分期付款約定書」之約定條款，可能會導致遲繳違約金及滯納金的產生。<br>-->
<!--                                                請妥善保存繳款收據建議以掃描或拍照留存，繳款當下無法立即更新【實際繳款日】，至少需3個工作天後才能查詢。<br>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse5-2">我可以提前結清款項嗎?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse5-2" class="collapse" data-parent="#accordion5">-->
<!--                                            <div class="card-body">-->
<!--                                                可以的，提前結清就是把剩餘的期數*月付金額一次繳清，如要提前結清請與客服人員聯絡。<br>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                            <div id="z6" class="box-faq">-->
<!--                                <p class="title">其他相關</p>-->
<!--                                <div class="accordion accordion-faq" id="accordion6">-->
<!--                                    <div class="card">-->
<!--                                        <div class="card-header">-->
<!--                                            <a class="btn btn-link collapsed" data-toggle="collapse"-->
<!--                                               data-target="#collapse6-1">何時會收到發票呢?</a>-->
<!--                                        </div>-->
<!--                                        <div id="collapse6-1" class="collapse" data-parent="#accordion6">-->
<!--                                            <div class="card-body">-->
<!--                                                為配合財政部訂定之電子發票實施作業，於NoWait消費開立之電子發票，會在您收到貨品後約10-15工作天，本公司將以Email寄送發票資訊給您。-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            </div>-->
                            <?php
                        }
                    }
                    ?>
                    </div>
                </div>
            </div>
        </section>
    </main>