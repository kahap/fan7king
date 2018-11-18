
<main role="main">
    <h1><span>忘記密碼</span><small>forgot password</small></h1>
    <section id="login-zone">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section-inner bg-white">
                        <h2><span>輸入手機號碼以傳送簡訊驗證碼</span></h2>
                        <p class="text-center text-orange text-errmsg">輸入手機號碼以傳送簡訊驗證碼</p>
                        <div class="form">
                            <div class="row justify-content-center">
                                <div class="col-lg-8">
                                    <div class="form-group row">
                                        <label for="form-phone" class="col-1 col-form-label text-hide label-phone">手機號碼</label>
                                        <div class="col-11">
                                            <input type="text" class="form-control input-black" id="form-phone" placeholder="輸入手機號碼">
                                        </div>
                                    </div>
                                    <div class="form-group form-btn text-center">
                                        <a class="btn-next btn btn-sendsms bg-yellow">傳送簡訊驗碼</a>
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

<script>
    $(".btn-next").click(function(){
        // var str = "&share="+$('input[name=memRecommCode]').val();
        var str = "&cell="+$('input[id=form-phone]').val();
        var d = new Date();
        d = new Date(d.getTime() - 3000000);
        var date_format_str = d.getFullYear().toString()+"-"+((d.getMonth()+1).toString().length==2?(d.getMonth()+1).toString():"0"+(d.getMonth()+1).toString())+"-"+(d.getDate().toString().length==2?d.getDate().toString():"0"+d.getDate().toString())+" "+(d.getHours().toString().length==2?d.getHours().toString():"0"+d.getHours().toString())+":"+((parseInt(d.getMinutes()/5)*5).toString().length==2?(parseInt(d.getMinutes()/5)*5).toString():"0"+(parseInt(d.getMinutes()/5)*5).toString())+":00";

        var url = "API/regist_phone2"
        var cell = $('input[id=form-phone]').val();
        var form ={
            "phoneNumber":cell,
            "token":"",
            "time":date_format_str,
            "type":"regist"                    
        }
        $.ajax({
            url:url,
            type:"POST",
            data:form,
            datatype:"json",
            success:function(result){
                var J = JSON.parse(result);
                if (J.data) {
                    location.href="?item=forgetpwd2"+str;
                }else{
                    alert(J.message);
                }
            }                    
        });
    })
</script>