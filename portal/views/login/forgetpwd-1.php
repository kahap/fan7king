
    <main role="main">
        <h1><span>忘記密碼</span><small>forgot password</small></h1>
        <section id="login-zone">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="section-inner bg-white">
                            <h2><span>輸入手機號碼以傳送簡訊驗證碼</span></h2>
                            <p class="text-center text-orange text-errmsg">輸入手機號碼以傳送簡訊驗證碼 / 手機號碼格式錯誤</p>
                            <form action="#" class="form">
                                <div class="form-group row">
                                    <label for="form-phone" class="col-2 col-form-label text-hide label-phone">手機號碼</label>
                                    <div class="col-10">
                                        <input type="text" class="form-control input-orange" id="form-phone" placeholder="輸入手機號碼">
                                    </div>
                                </div>
                                <div class="form-group form-btn text-center">
                                    <a href="?item=forgetpwd2" class="btn btn-sendsms bg-yellow">傳送簡訊驗碼</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        $(".reset_email").click(function(){
            var memAccount = $('.memAccount').val();
            if(memAccount != ''){
                $.ajax({
                    url: 'php/member_forget.php',
                    data: "memAccount="+memAccount,
                    type:"POST",
                    dataType:'text',
                    success: function(msg){
                        if(msg){
                            alert('已寄送到'+memAccount);
                        }else{
                            alert('不存在的EMAIL');
                        }
                    },

                    error:function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }else{
                alert('不准為空值');
            }

        })
    </script>