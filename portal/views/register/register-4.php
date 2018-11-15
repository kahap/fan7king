
    <main role="main">
        <h1><span>註冊</span><small>rigester</small></h1>
        <section id="login-zone">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="section-inner bg-white text-center">
                            <h2><span>設定密碼</span></h2>
                            <div class="row bs-wizard">
                                <div class="col-3 bs-wizard-step complete">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">1</a>
                                </div>
                                <div class="col-3 bs-wizard-step complete">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">2</a>
                                </div>
                                <div class="col-3 bs-wizard-step complete">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">3</a>
                                </div>
                                <div class="col-3 bs-wizard-step active">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">4</a>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <form action="#" class="form">
                                        <div class="form-group row">
                                            <label for="form-pwd" class="col-1 col-form-label text-hide label-password">密碼</label>
                                            <div class="col-11">
                                                <input type="password" class="form-control input-black" id="form-pwd" placeholder="密碼">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="form-checkpwd" class="col-1 col-form-label text-hide label-password">確認密碼</label>
                                            <div class="col-11">
                                                <input type="password" class="form-control input-black" id="form-checkpwd" placeholder="確認密碼">
                                                <div class="form-check text-left m-3">
                                                    <input class="form-check-input" type="checkbox" id="FieldsetCheck">
                                                    <label class="form-check-label" for="FieldsetCheck">我是學生(勾選此選項可快速申請)</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-btn text-center">
                                            <button type="button" class="btn btn-login bg-yellow">完成</button>
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
    
    <script>
    $(".btn-login").click(function(){
        var password = $('input[id=form-pwd]').val();
        var checkPassword = $('input[id=form-checkpwd]').val();
        var isStudent = (document.getElementById("FieldsetCheck").checked)?"0":"4";
        var token ='<?php echo $_SESSION['user']["fb_access_token"];?>';
        if (password!="" && password == checkPassword) {
            var url = "API/set_password";
            var cell = '<?php echo $_POST['phoneNumber'];?>';
            var form ={
                "memCell":cell,
                "password":password,
                "token":token,
                "memClass":isStudent,
                "type":"WebRegist"
            }
            $.ajax({
                url:url,
                type:"POST",
                data:form,
                datatype:"json",
                success:function(result){
                    var J = JSON.parse(result);
                    if (J.data) {
                        location.href="?item=member_center&action=member_idnum_edit";
                    }else{
                        alert(J.message);
                    }
                    
                },
                error:function(){
                    
                }
            });
        }else{
            alert("新設密碼和再次確認密碼請設定一樣");
        }
        
        
        
    })
    
    </script>