
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
                        <div class="section-inner-table bg-white">
                            <table class="table table-borderless table-rwd">
                                <thead>
                                    <tr class="bg-pale">
                                        <th nowrap="nowrap">訂單編號</th>
                                        <th nowrap="nowrap">訂單日期</th>
                                        <th nowrap="nowrap">商品名稱</th>
                                        <th nowrap="nowrap">商品規格</th>
                                        <th nowrap="nowrap">商品型號</th>
                                        <th nowrap="nowrap">狀態</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td data-th="訂單編號" nowrap="nowrap"><a class="text-orange" href="member-order-detail.php">A201809210004</a></td>
                                        <td data-th="訂單日期" nowrap="nowrap">2018-09-21</td>
                                        <td data-th="商品名稱">Apple MacBook Air 13.3/1.8/8G/128G Flash*（MQD32TA/A）6期0利率</td>
                                        <td data-th="商品規格" nowrap="nowrap">銀色</td>
                                        <td data-th="商品型號" nowrap="nowrap">銀色</td>
                                        <td data-th="狀態" nowrap="nowrap">審查中<br></td>
                                    </tr>
                                    <tr>
                                        <td data-th="訂單編號" nowrap="nowrap"><a class="text-orange" href="member-order-detail.php">A201809210004</a></td>
                                        <td data-th="訂單日期" nowrap="nowrap">2018-09-21</td>
                                        <td data-th="商品名稱">Apple MacBook Air 13.3/1.8/8G/128G Flash*（MQD32TA/A）6期0利率</td>
                                        <td data-th="商品規格" nowrap="nowrap">銀色</td>
                                        <td data-th="商品型號" nowrap="nowrap">銀色</td>
                                        <td data-th="狀態" nowrap="nowrap">審查完成<br><a class="text-orange" href="member-pay-detail.php">我要繳款</a></td>
                                    </tr>
                                    <tr>
                                        <td data-th="訂單編號" nowrap="nowrap"><a class="text-orange" href="member-order-detail.php">A201809210004</a></td>
                                        <td data-th="訂單日期" nowrap="nowrap">2018-09-21</td>
                                        <td data-th="商品名稱">Apple MacBook Air 13.3/1.8/8G/128G Flash*（MQD32TA/A）6期0利率</td>
                                        <td data-th="商品規格" nowrap="nowrap">銀色</td>
                                        <td data-th="商品型號" nowrap="nowrap">銀色</td>
                                        <td data-th="狀態" nowrap="nowrap">未完成下單<br><a class="text-orange" href="#">修改</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>