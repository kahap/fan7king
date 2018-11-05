
<?php

    $cell = isset($_GET['cell'])? $_GET['cell'] : '';
    $cell = $cell!='' ? $cell : '沒有電話號碼';

?>
    <main role="main">
        <h1><span>註冊</span><small>rigester</small></h1>
        <section id="login-zone">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="section-inner bg-white text-center">
                            <h2><span>輸入驗證碼</span></h2>
                            <div class="row bs-wizard">
                                <div class="col-3 bs-wizard-step complete">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">1</a>
                                </div>
                                <div class="col-3 bs-wizard-step complete">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">2</a>
                                </div>
                                <div class="col-3 bs-wizard-step active">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">3</a>
                                </div>
                                <div class="col-3 bs-wizard-step disabled">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">4</a>
                                </div>
                            </div>
                            <p class="text-black">手機號碼：<?php echo $cell;?></p>
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <form action="#" class="form">
                                        <div class="form-group row">
                                            <label for="form-phone" class="col-1 col-form-label text-hide label-phone">數字驗證碼</label>
                                            <div class="col-11">
                                                <input type="text" class="form-control input-black" id="form-phone" placeholder="請輸入4位數字驗證碼">
                                            </div>
                                        </div>
                                        <div class="form-group text-center">
                                            <button type="button" class="btn btn-resend text-yellow">重新發送驗證碼</button>
                                        </div>
                                        <div class="form-group form-btn text-center">
                                            <a href="?item=register2" class="btn btn-next bg-yellow">重新輸入手機號碼</a>
                                            <a href="?item=register4" class="btn btn-next bg-yellow">下一步</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>