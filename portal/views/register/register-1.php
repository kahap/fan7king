
<script>
    function FacebookPixel() {
        fbq('track', "Lead");
        fbq('track', 'CompleteRegistration');
        setTimeout(function () {
        }, 500);
        var MTBADVS = window.MTBADVS = window.MTBADVS || {}; MTBADVS.ConvContext = MTBADVS.ConvContext || {}; MTBADVS.ConvContext.queue = MTBADVS.ConvContext.queue || [];
        MTBADVS.ConvContext.queue.push({
            "advertiser_id": 7935,
            "price": 0,
            "convtype": 1,
            "dat": ""
        });
        var el = document.createElement('script'); el.type = 'text/javascript'; el.async = true;
        el.src = (('https:' == document.location.protocol) ? 'https://' : 'http://') + 'js.mtburn.com/advs-conversion.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(el, s);
    }
</script>

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
                            <h2><span>Facebook快速連結</span></h2>
                            <div class="row bs-wizard">
                                <div class="col-3 bs-wizard-step active">
                                    <div class="progress"><div class="progress-bar"></div></div>
                                    <a href="#" class="bs-wizard-dot">1</a>
                                </div>
                                <div class="col-3 bs-wizard-step disabled">
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
                            <p class="text-black">使用Facebook帳號快速成為會員！</p>
                            <img src="portal/images/reg-fb.png" width="112" height="112" alt="facebook" class="m-60">
                            <p>
                                <a class="btn btn-facebook" onclick="FacebookPixel();return false;">
                                    Facebook帳號登入
                                    <?php if($_GET['pro'] == '10190'){ ?>
                                        <img src="https://farm-tw.plista.com/activity2;domainid:718601;campaignid:717271;event:30" style="width:1px;height:1px;" alt="" />
                                    <?PHP } ?>
                                </a>
                            </p>
                            <p class="text-orange">實名註冊，請使用自己的Facebook帳號註冊</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script>
    //使用facebook帳號註冊
    $(".btn-facebook").click(function(){
        // if($("input[name='check']:checked").length == 1){
            var sharcode = $("input[name='memRecommCode']").val();
            if (!sharcode)sharcode='';
            <?php $_SESSION['pro'] = isset($_GET['pro'])? $_GET['pro'] : null; ?>
            location.href="portal/Controllers/fb-php/sharcode.php?sharcode="+sharcode;
        // }else{
        //     alert("請勾選同意條款");
        //     $("input[name='check']").val('');
        // }
    });

    // 建立一般帳號
    $(".normal").click(function(){
        var str = "&share="+$('input[name=memRecommCode]').val();
        if($("input[name='check']:checked").length == 1){
            location.href="?item=register"+str;
        }else{
            alert("請勾選同意條款");
            $("input[name='check']").val('');
        }
    });
</script>