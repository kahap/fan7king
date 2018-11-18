
<main role="main">
    <h1><span>忘記密碼</span><small>forgot password</small></h1>
    <section id="login-zone">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section-inner bg-white">
                        <h2><span>設定新密碼</span></h2>
                        <p class="text-center text-orange text-errmsg">密碼輸入不相符(可留空白)</p>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <form action="portal/Controllers/php/member_password_edit2.php" class="form" method="post">
                                    <div class="form-group row">
                                        <label for="form-pwd" class="col-1 col-form-label text-hide label-password">密碼</label>
                                        <div class="col-11">
                                            <input type="password" class="form-control input-black" id="form-pwd" name="NewmemPwd" placeholder="密碼">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="form-checkpwd" class="col-1 col-form-label text-hide label-password">確認密碼</label>
                                        <div class="col-11">
                                            <input type="password" class="form-control input-black" id="form-checkpwd" placeholder="確認密碼">
                                        </div>
                                    </div>
                                    <div class="form-group form-btn text-center">
                                        <button type="submit" class="btn btn-login bg-yellow">完成設定</button>
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
<script type="text/javascript">
    $(".form").on("submit",function(ev){
        if($("#form-pwd").val()!=$("#form-checkpwd").val()){
            window.alert("密碼與確認密碼不一致！");
            ev.preventDefault();
            return false;
        }
    });
</script>