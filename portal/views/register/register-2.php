
    <main role="main">
        <h1>
            <span>註冊</span>
            <small>rigester</small>
        </h1>
        <section id="login-zone">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="section-inner bg-white text-center">
                            <h2><span>加入專屬會員</span></h2>
                            <div class="row bs-wizard">
                                <div class="col-3 bs-wizard-step complete">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">1</a>
                                </div>
                                <div class="col-3 bs-wizard-step active">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">2</a>
                                </div>
                                <div class="col-3 bs-wizard-step disabled">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">3</a>
                                </div>
                                <div class="col-3 bs-wizard-step disabled">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">4</a>
                                </div>
                            </div>
                            <p class="text-black">系統將發送簡訊驗證碼至您的手機</p>
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <form action="#" class="form">
                                        <div class="form-group row">
                                            <label for="form-phone" class="col-1 col-form-label text-hide label-phone">手機號碼</label>
                                            <div class="col-11">
                                                <input type="text" class="form-control input-black" id="form-phone" placeholder="請輸入您的手機號碼">
                                                <div class="form-check text-left m-2">
                                                    <input class="form-check-input" type="checkbox" id="FieldsetCheck" name="check">
                                                    <label class="form-check-label sz-12" for="FieldsetCheck">我已詳細閱讀並同意
                                                        <a href="?item=fmFreeRespons" class="text-orange">免責聲明</a>、
                                                        <a href="?item=fmServiceRules" class="text-orange">服務條款</a>、
                                                        <a href="?item=fmPrivacy" class="text-orange">隱私權聲明</a>等條款
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-btn text-center">
                                            <a class="btn btn-next bg-yellow">下一步</a>
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
        $(".btn-next").click(function(){
            // var str = "&share="+$('input[name=memRecommCode]').val();
            var str = "&cell="+$('input[id=form-phone]').val();
            if($("input[name='check']:checked").length == 1){
                var d = new Date();
                d = new Date(d.getTime() - 3000000);
                var date_format_str = d.getFullYear().toString()+"-"+((d.getMonth()+1).toString().length==2?(d.getMonth()+1).toString():"0"+(d.getMonth()+1).toString())+"-"+(d.getDate().toString().length==2?d.getDate().toString():"0"+d.getDate().toString())+" "+(d.getHours().toString().length==2?d.getHours().toString():"0"+d.getHours().toString())+":"+((parseInt(d.getMinutes()/5)*5).toString().length==2?(parseInt(d.getMinutes()/5)*5).toString():"0"+(parseInt(d.getMinutes()/5)*5).toString())+":00";

                var url = "API/regist_phone"
                var cell = $('input[id=form-phone]').val();
                var form ={
                    "phoneNumber":cell,
                    "token":"",
                    "time":date_format_str
                }
                $.ajax({
                    url:url,
                    type:"POST",
                    data:form,
                    datatype:"json",
                    success:function(result){
                    }                    
                });
                location.href="?item=register3"+str;
            }else{
                alert("請勾選同意條款");
                $("input[name='check']").val('');
            }
        })
    </script>