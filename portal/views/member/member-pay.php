
    <main role="main">
        <h1><span>會員中心</span><small>member center</small></h1>
        <section id="member-zone">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <?php
                        $active = 4;
                        require_once 'views/member/_left.php';
                        ?>
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
                                        <td data-th="訂單編號" nowrap="nowrap">A201809210004</td>
                                        <td data-th="訂單日期" nowrap="nowrap">2018-09-21</td>
                                        <td data-th="商品名稱">Apple MacBook Air 13.3/1.8/8G/128G Flash*（MQD32TA/A）6期0利率</td>
                                        <td data-th="商品規格" nowrap="nowrap">銀色</td>
                                        <td data-th="商品型號" nowrap="nowrap">銀色</td>
                                        <td data-th="狀態" nowrap="nowrap">審查完成<br><a class="text-orange" href="member-pay-detail.php">我要繳款</a></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>