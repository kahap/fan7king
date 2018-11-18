
<main role="main">
    <h1><span>忘記密碼</span><small>forgot password</small></h1>
    <section id="login-zone">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section-inner bg-white">
                        <h2><span>輸入簡訊驗證碼</span></h2>
                        <p class="text-center text-orange text-errmsg">驗證碼錯誤(可留空白)</p>
                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <div class="form">
                                    <div class="form-group row">
                                        <label for="form-phone" class="col-1 col-form-label text-hide label-phone">簡訊驗證碼</label>
                                        <div class="col-11">
                                            <input type="text" class="form-control input-black" id="form-phone" placeholder="輸入簡訊驗證碼">
                                        </div>
                                    </div>
                                    <div class="form-group form-btn text-center">
                                        <a class="btn-next1 btn btn-login bg-yellow">確認輸入</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script type="text/javascript">
    $(".btn-next1").click(function(){
        var passNumber=$('input[id=form-phone]').val();
        
        var url = "API/regist_phone_keyCheck";
        var cell = '<?php echo $_GET["cell"]; ?>';
        var form ={
            "phoneNumber":cell,
            "token":"",
            "passNumber":passNumber
        }
        $.ajax({
            url:url,
            type:"POST",
            data:form,
            datatype:"json",
            success:function(result){
                var J = JSON.parse(result);
                if (J.data) {
                    location.href="?item=forgetpwd3&cell="+cell;
                }else{
                    alert(J.message);
                }
                
            },
            error:function(){
                
            }
        });
    })
</script>