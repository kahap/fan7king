
<style>
    /*
    info, .success, .warning, .error, .validation {
        border: 1px solid;
        margin: 10px 0px;
        padding:15px 10px 15px 50px;
        background-repeat: no-repeat;
        background-position: 10px center;
    }
    .success {
        color: #4F8A10;
        background-color: #DFF2BF;
        background-image:url('assets/images/success.png');
    }
    .warning {
        color: #9F6000;
        background-color: #FEEFB3;
        background-image: url('assets/images/warning.png');
    }
    .error {
        color: #D8000C;
        background-color: #FFBABA;
        background-image: url('assets/images/error.png');
    }
    label {
        display: inline-block;
        cursor: pointer;
        position: relative;
        padding-left: 25px;
        margin-right: 15px;
        font-size: 14px;
    }
    label:before {
        content: "";
        display: inline-block;
        width: 16px;
        height: 16px;
        margin-right: 10px;
        position: absolute;
        left: 0;
        bottom: 1px;
        background-color: #aaa;
        box-shadow: inset 0px 2px 3px 0px rgba(0, 0, 0, .3), 0px 1px 0px 0px rgba(255, 255, 255, .8);
    }
    input[type=checkbox] {
        display: none;
    }
    .checkbox label {
        margin-bottom: 10px;
    }
    .checkbox label:before {
        border-radius: 3px;
    }
    input[type=checkbox]:checked + label:before {
        content: "\2713";
        text-shadow: 1px 1px 1px rgba(0, 0, 0, .2);
        font-size: 15px;
        color: #f3f3f3;
        text-align: center;
        line-height: 15px;
    }
*/
</style><!-- page wapper-->

<?php

    require_once 'portal/Controllers/fb-php/src/Facebook/autoload.php';

    $helper = $fb->getRedirectLoginHelper();
    $permissions = ['email'];
    $loginUrl = $helper->getLoginUrl('https://'.DOMAIN.'/portal/Controllers/fb-php/fbconfig.php', $permissions);
?>
    <main role="main">
        <h1><span>登入</span><small>login</small></h1>
        <section id="login-zone">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="section-inner bg-white">
                            <h2><span>我有帳號</span></h2>
                            <?php
                            if($_SESSION['user']['status'] == "success"){
                                ?>
                                <p class="text-center text-orange text-errmsg success">
                                    驗證成功,請立即登入!!
                                </p>
                                <?php
                            }elseif($_SESSION['user']['status'] == "already"){
                                ?>
                                <p class="text-center text-orange text-errmsg warning">
                                    該Email已經驗證成功!!
                                </p>
                                <?php
                            }elseif($_SESSION['user']['status'] == "error"){
                                ?>
                                <p class="text-center text-orange text-errmsg">
                                    不存在的Email!!
                                </p>
                                <?php
                            }elseif($_SESSION['user']['status'] == "errorpw"){
                                ?>
                                <p class="text-center text-orange text-errmsg">
                                    帳號或密碼錯誤
                                </p>
                                <?php
                            }else{
                                ?>
                                <p class="text-center text-orange text-errmsg">
                                    &nbsp;
                                </p>
                                <?php
                            }
                            ?>
                            <form action="#" class="form">
                                <div class="form-group row">
                                    <label for="form-phone" class="col-2 col-form-label text-hide label-phone">手機號碼</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control input-black" id="form-phone" placeholder="手機號碼">
<!--                                        <input id="emmail_login" type="text" class="input form-control">-->
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="form-password" class="col-2 col-form-label text-hide label-password">密碼</label>
                                    <div class="col-10">
                                        <input type="password" class="form-control input-black password_login" id="form-password" placeholder="密碼">
                                        <p class="form-text text-right text-orange"><a href="?item=forgetpwd1" title="忘記密碼？">忘記密碼？</a></p>
                                    </div>
                                </div>
                                <div class="form-group form-btn text-center">
                                    <button class="btn btn-login bg-yellow" id="login">登入</button>
                                    <a class="btn btn-facebook" href="<?php echo $loginUrl; ?>">Facebook帳號登入
                                        <?php if($_GET['pro'] == '10190'){ ?>
                                        <img src="https://farm-tw.plista.com/activity2;domainid:718601;campaignid:717271;event:30" style="width:1px;height:1px;" alt="" />
                                        <?PHP } ?>
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-inner bg-white">
                            <h2><span>還沒有Nowait帳號？</span></h2>
                            <div class="register-now text-center text-orange">
                                <img src="portal/images/reg-logo.png" width="176" height="203" alt="nowait icon" class="m-60">
                                <p>立即註冊享受購物快感</p>
                                <a href="?item=register" class="btn btn-register bg-orange">立即註冊</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script>

    $('#login').click(function(){
        // var emmail_login = $('#emmail_login').val();
        var cell_login = $('#form-phone').val();
        var password_login = $('.password_login').val();

        // if(emmail_login != '' && password_login != ''){
        if(cell_login != '' && password_login != ''){
            $.ajax({
                url: 'portal/Controllers/php/login.php',
                // data: "emmail_login="+emmail_login+"&password_login="+password_login,
                data: "cell_login="+cell_login+"&password_login="+password_login,
                type: "POST",
                dataType: 'text',
                success: function(msg){
                    if(msg == 0){
                        alert('登入成功');
                        location.href='index.php?item=member_center&action=member_center';
                    }
                    else if(msg == 1){
                        alert('登入成功');
                        // location.href='index.php?item=member_center&action=member_center';
                        location.href='index.php<?php echo (isset($_GET['pro'])&& $_GET['pro'] != "") ? '?item=product&pro='.$_GET['pro']:''; ?>';
                    }else if(msg == 2){
                        alert('您的帳號已經設定停權，如有任何問題請洽客服人員，謝謝');
                    }else{
                        alert('帳號密碼錯誤');
                    }
                },

                error:function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }else{
            alert('帳號密碼必須填寫');
        }
    });

</script>