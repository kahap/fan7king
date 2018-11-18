
<main role="main">
    <h1><span>登入</span><small>Login</small></h1>
    <section id="login-zone">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="section-inner bg-white text-center">
                        <h2><span>廠商登入</span></h2>

                        <div class="row justify-content-center">
                            <div class="col-lg-8">
                                <form action="#" class="login-two-bar">
                                    <div class="form-group row">
                                        <label for="form-supno" class="col-2 col-form-label text-hide label-cathead ">廠商代碼</label>
                                        <div class="col-10">
                                            <input type="text" class="form-control input-black" id="form-supno" placeholder="廠商代碼">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="form-phone" class="col-2 col-form-label text-hide label-cathead ">帳號</label>
                                        <div class="col-10">
                                            <input type="text" class="form-control input-black" id="form-phone" placeholder="帳號">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="form-password" class="col-2 col-form-label text-hide label-password">密碼</label>
                                        <div class="col-10">
                                            <input type="password" class="form-control input-black" id="form-password" placeholder="密碼">                                            
                                        </div>
                                    </div>
                                    <div class="form-group form-btn text-center">
                                        <button type="button" class="btn btn-login bg-yellow">登入</button>
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
    alert("會員本人同意以現場面交點收產品，非適用線上購物7天鑑賞期，產品維修保固與售後服務皆由選購的實體商店(蓋發票章)負責處理，本公司只提供分期購物與付款服務，不提供產品相關售後服務。");
    $('.btn-login').click(function(){
        var url ='portal/Controllers/php/login_sales.php';
        var supno=document.getElementById("form-supno").value;
        var account=document.getElementById("form-phone").value;
        var pwd=document.getElementById("form-password").value;
        var data ={
            "supNo":supno,
            "memAccount":account,
            "memPwd":pwd
        };
        $.ajax({
				url: url,
				data: data,
				type:"POST",
				dataType:'json',
				success: function(msg){
                    if (msg=="0") {
                        window.location = "?item=sup_center_page";
                    }else{
                        alert('error');
                    }
				},

				error:function(xhr, ajaxOptions, thrownError){ 
					alert(xhr.status); 
					alert(thrownError);
				}
		});
    });
</script>