
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
                                            <button type="button" class="btn btn-next1 bg-yellow">下一步</button>
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
    $(".btn-resend").click(function(){           
        var d = new Date();
        d = new Date(d.getTime() - 3000000);
        var date_format_str = d.getFullYear().toString()+"-"+((d.getMonth()+1).toString().length==2?(d.getMonth()+1).toString():"0"+(d.getMonth()+1).toString())+"-"+(d.getDate().toString().length==2?d.getDate().toString():"0"+d.getDate().toString())+" "+(d.getHours().toString().length==2?d.getHours().toString():"0"+d.getHours().toString())+":"+((parseInt(d.getMinutes()/5)*5).toString().length==2?(parseInt(d.getMinutes()/5)*5).toString():"0"+(parseInt(d.getMinutes()/5)*5).toString())+":00";

        var url = "API/regist_phone";
        var cell = '<?php echo $cell; ?>';
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
    })
    $(".btn-next1").click(function(){
        var passNumber=$('input[id=form-phone]').val();
        
        var url = "API/regist_phone_keyCheck";
        var cell = '<?php echo $cell;?>';
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
                    location.href="?item=register4";
                }else{
                    alert(J.message);
                }
                
            },
            error:function(){
                
            }
        });
    })
    </script>