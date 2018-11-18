<style>
    label{
        font-size: 16px;
        color:blue;
    }
</style>

<!-- page wapper-->
<main role="main">
    <h1><span>分期購買</span><small>staging</small></h1>
    <section id="staging-zone">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <div class="row bs-wizard">
                        <div class="col-3 bs-wizard-step complete">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">1</a>
                            <div class="desc">填寫基本資料</div>
                        </div>
                        <div class="col-3 bs-wizard-step complete">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">2</a>
                            <div class="desc">簽名及附件上傳</div>
                        </div>
                        <div class="col-3 bs-wizard-step complete">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">3</a>
                            <div class="desc">確認訂單資訊</div>
                        </div>
                        <div class="col-3 bs-wizard-step active">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">4</a>
                            <div class="desc">完成等候照會</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            $member = new Member();
            $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
            ?>
            <div class="section-staging bg-white">
                <h2><span>申請完成</span></h2>
                <div class="text-center text-box">
                    <p>
                        您已完成分期申請並等候照會，我們將於24小時內與您聯絡。<br>
                        我們的服務時間為週一至周五 9:30~12:30 & 13:30~18:00，國定例假日為公休日。
                    </p>
                    <p>
                        若您於非服務時間購買，將會於次一營業日與您聯絡。 <br>
                        繳款或查看填寫資訊請至
                        <a href="?item=member_center" class="text-orange">會員中心</a> >
                        <a href="?item=member_center&action=order" class="text-orange">訂單查詢</a>
                    </p>
                </div>
                <div class="form-group form-btn text-center">
                    <a href="?item=member_center&action=order" class="btn btn-next bg-yellow">訂單查詢</a>
                </div>
            </div>
            <div class="down-rightnow">
                <img src="portal/images/rightnow.png" data-src-base="portal/images/" data-src="<991:rightnow-m.png,>991:rightnow.png" class="img-fluid" alt="即刻下載NoWait APP">
            </div>
        </div>
    </section>
</main>