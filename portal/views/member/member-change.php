
    <main role="main">
        <h1><span>會員中心</span><small>member center</small></h1>
        <section id="member-zone">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <div class="list-group">
                            <a href="?item=member_center" class="list-group-item list-group-item-action active">基本資料</a>
                            <a href="?item=member_center&action=password_edit" class="list-group-item list-group-item-action">變更密碼</a>
                            <a href="?item=member_center&action=order" class="list-group-item list-group-item-action">訂單查詢</a>
                            <a href="?item=member_center&action=pay" class="list-group-item list-group-item-action">我要繳款</a>
                        </div>
                        <div class="sell xs-none" style="height: auto;background-image: linear-gradient(151deg, #ff7f00,#fff0c9);">
                            <?php
                            $ad = new Advertise();
                            $adData = $ad->getAllOrderBy(3,false);
                            if($adData != ""){
                                foreach($adData as $key => $value){
                                    ?>
                                    <li>
                                        <a href="<?php echo $value["adLink"]; ?>">
                                            <img src="../admin/<?php echo $value["adImage"]; ?>" alt="slide-left" style="width: 100%">
                                        </a>
                                    </li>
                                    <?php
                                }
                            }else{
                                ?>
                                <li><a href="#"><img alt="" src="assets/images/Not-found.png" title=""  alt="slide-left"></a></li>
                                <li><a href="#"><img alt="" src="assets/images/Not-found.png" title=""  alt="slide-left"></a></li>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="section-inner bg-white">
                            <form action="">
                                <div class="form-group row">
                                    <label for="staticSource" class="col-sm-3 col-form-label"><span class="text-orange">*</span> 原始密碼</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control input-black-all" id="staticSource" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticNewpw" class="col-sm-3 col-form-label"><span class="text-orange">*</span> 新密碼</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control input-black-all" id="staticNewpw" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticCheckpw" class="col-sm-3 col-form-label"><span class="text-orange">*</span> 再次確認新密碼</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control input-black-all" id="staticCheckpw" value="">
                                    </div>
                                </div>
                                <div class="form-group text-right mt-50">
                                    <button class="btn bg-yellow">確認送出</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>