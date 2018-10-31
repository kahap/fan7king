<?php
    $member = new Member();
    $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $memOrigData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $member->changeToReadable($memberData[0]);

    $pm = new Product_Manage();
    $pro = new Product();
    $or = new Orders();

    //訂單
    $orNo = isset($_GET["orno"])? $_GET["orno"] : '';
    $orOrigData = $or->getOneOrderByNo($orNo);
    $orData = $or->getOneOrderByNo($orNo);
    $or->changeToReadable($orData[0],1);

    //欄位名稱
    $columnName = $or->getAllColumnNames("orders");

    //商品上架
    $pmData = $pm->getOnePMByNo($orData[0]["pmNo"]);

    //商品
    $proData = $pro->getOneProByNo($pmData[0]["proNo"]);

?>
    <main role="main">
        <h1><span>會員中心</span><small>member center</small></h1>
        <section id="member-zone">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <?php
                        $active = 3;
                        require_once 'portal/views/member/_left.php';
                        ?>
                    </div>
                    <div class="col-lg-9">
                        <div class="section-order bg-pale">
                            訂單編號<span class="text-orange"><?php echo $orNo ?></span>
                            訂單狀態<span class="text-orange">
                            <?php
                                if($orData[0]["orStatus"] == '出貨中'){
                                    echo $orData[0]["orHandleTransportSerialNum"] != "" ? "出貨中" : "備貨中";
                                }else{
                                    echo ($orData[0]["orStatus"] == '未進件') ? '審查中':$orData[0]["orStatus"];
                                }
                            ?>
                            </span>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">購買商品</div>
                            <div class="form-group row">
                                <div class="col-sm-3">商品名稱</div>
                                <div class="col-sm-9"><?php echo $proData[0]["proName"]; ?></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">商品規格</div>
                                <div class="col-sm-9"><?php echo $orData[0]["orProSpec"]; ?></div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">月付</div>
                                <div class="col-sm-9"><?php echo number_format($orData[0]["orPeriodTotal"]/$orData[0]["orPeriodAmnt"]); ?> 元</div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-3">期數</div>
                                <div class="col-sm-9"><?php echo $orData[0]["orPeriodAmnt"]; ?> 期</div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">申請人資料</div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">申請人中文姓名</div>
                                        <div class="col"><?php echo $memberData[0]["memName"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">身分別</div>
                                        <div class="col"><?php echo $memberData[0]["memClass"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">學校Email</div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">常用聯絡Email</div>
                                        <div class="col"><?php echo $memberData[0]["memSubEmail"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">現住市話</div>
                                        <div class="col"><?php echo $memberData[0]["memPhone"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">戶籍電話</div>
                                        <div class="col">
                                        <?php
                                        foreach($columnName as $key=>$value){
                                            if($value["COLUMN_NAME"] == "orAppAssureBirthPhone") {
                                                echo ($orData[0][$value["COLUMN_NAME"]]);
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">行動電話</div>
                                        <div class="col"><?php echo $memberData[0]["memCell"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">住房所有權</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierLivingOwnership") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">戶籍地址</div>
                                        <div class="col">
                                        <?php
                                        foreach($columnName as $key=>$value){
                                            //只顯示戶籍地址
                                            if($value["COLUMN_NAME"] == "orAppApplierBirthAddr") {
                                                echo ($orData[0][$value["COLUMN_NAME"]]);
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">現住地址</div>
                                        <div class="col"><?php echo $memberData[0]["memAddr"]; ?></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">申請人身份證正面</div>
                                        <div class="col-12">
                                            <img id="showimg" src="<?php echo '../'.$orData[0]['orAppAuthenIdImgTop']; ?>" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">申請人身份證反面</div>
                                        <div class="col-12">
                                            <img id="showimg" src="../<?php echo '../'.$orData[0]['orAppAuthenIdImgBot']; ?>" alt="">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">身份證字號</div>
                                        <div class="col"><?php echo $memberData[0]["memIdNum"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">出生年月日</div>
                                        <div class="col"><?php echo $memberData[0]["memBday"]; ?></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">申請人身份證發證日期</div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">申請人身份證發證地點</div>
                                        <div class="col"></div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">申請人身份證發證類別</div>
                                        <div class="col"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">收貨人資料</div>
                            <div class="row">
                                <div class="col">
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        if(strrpos($value["COLUMN_NAME"], "orReceive") !== false){
                                            ?>
                                            <div class="form-group row">
                                                <div class="col"><?php echo $value["COLUMN_COMMENT"]; ?></div>
                                                <div class="col">
                                                    <?php
                                                    if(strrpos($value["COLUMN_NAME"], "Comment") !== false){
                                                        echo "</h4><p>".$orData[0][$value['COLUMN_NAME']]."</p>";
                                                    }else{
                                                        echo "".$orData[0][$value["COLUMN_NAME"]]."</h4>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div class="col">
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        if(strrpos($value["COLUMN_NAME"], "orAppExtra") !== false){
                                            ?>
                                            <div class="form-group row">
                                                <div class="col"><?php echo $value["COLUMN_COMMENT"];?></div>
                                                <div class="col-12"><?php echo $orData[0][$value["COLUMN_NAME"]];?></div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">工作</div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">公司名稱</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierCompanyName") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">年資</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierYearExperience") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">月薪</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierMonthSalary") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">公司市話</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierCompanyPhone") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">信用卡</div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">信用卡號(僅供參考)</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierCreditNum") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">發卡銀行</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierCreditIssueBank") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">信用卡有效期限</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orAppApplierCreditDueDate") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">統一編號</div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">是否需要統一編號</div>
                                        <div class="col">
                                        <?php
                                        foreach($columnName as $key=>$value){
                                            if(strrpos($value["COLUMN_NAME"], "orBusinessNum") !== false){
//                                                echo "<h4>".$value["COLUMN_COMMENT"]." : ";
                                                if(strrpos($value["COLUMN_NAME"], "If") !== false){
                                                    if($orData[0][$value["COLUMN_NAME"]] == 1){
                                                        echo '是</h4>';
                                                    }else{
                                                        echo '否</h4>';
                                                    }
                                                }else{
//                                                    echo $orData[0][$value["COLUMN_NAME"]]."</h4>";
                                                }
                                            }
                                        }
                                        ?>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col">統一編號</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orBusinessNumNumber") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group row">
                                        <div class="col">公司抬頭</div>
                                        <div class="col">
                                            <?php
                                            foreach($columnName as $key=>$value){
                                                if($value["COLUMN_NAME"] == "orBusinessNumTitle") {
                                                    echo ($orData[0][$value["COLUMN_NAME"]]);
                                                }
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">聯絡人資料</div>
                            <div class="row">
                                <div class="col">
                                <?php
                                foreach($columnName as $key=>$value){
                                    if(strrpos($value["COLUMN_NAME"], "orAppContact") !== false){
                                        $orContact = json_decode($orData[0][$value["COLUMN_NAME"]]);
                                        ?>
                                        <div class="form-group row">
                                            <div class="col"><?php echo $value["COLUMN_COMMENT"];?></div>
                                            <div class="col"><?php echo $orContact[0];?></div>
                                        </div>
                                        <?php
                                        if ($key==4){
                                        ?>
                                </div>
                                <div class="col">
                                        <?php
                                        }
                                    }
                                }
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">備註</div>
                            <div class="row">
                                <?php
                                foreach($columnName as $key=>$value){
                                    if(strrpos($value["COLUMN_NAME"], "orAppExtra") !== false){
                                        ?>
                                        <div class="col">
                                            <div class="form-group row">
                                                <div class="col"><?php echo $value["COLUMN_COMMENT"];?></div>
                                                <div class="col"><?php echo $orData[0][$value["COLUMN_NAME"]];?></div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                        <div class="section-order bg-white">
                            <div class="section-order-title">證件資料</div>
                            <div class="form-group row">
                                <div class="col">申請人學生證正面</div>
                                <div class="col-12"><img id="showimg" src="<?php echo $orData[0]['orAppAuthenStudentIdImgTop']; ?>" class="img-fluid" alt=""></div>
                            </div>
                            <div class="form-group row">
                                <div class="col">申請人學生證反面</div>
                                <div class="col-12"><img id="showimg" src="<?php echo $orData[0]['orAppAuthenStudentIdImgBot']; ?>" class="img-fluid" alt=""></div>
                            </div>
                            <div class="form-group row">
                                <div class="col">申請人自拍照</div>
                                <div class="col-12"><img src="https://via.placeholder.com/510x284" class="img-fluid" alt=""></div>
                            </div>
                            <div class="section-order-title">請在下方兩處，以滑鼠或手寫功能簽上正楷簽名</div>
                            <p>本票： 憑票於中華民國 年 月 日無條件支付 大方藝彩行銷顧問股份有限公司或指定人
                            <br>新台幣　 零  拾  柒  萬  肆  仟  陸  佰  陸  拾  肆   　元整
                            <br>此總額為您選擇之月付金X期數
                            <br>此本票免除作成拒絕證書及票據法第八十九條之通知義務，
                            <br>利息自到期日迄清償日止按年利率百分之二十計付，
                            <br>發票人授權持票人得填載到期日。
                            <br>付款地：桃園市桃園區文中路493號4樓
                            <br>此據
                            <br>中華民國 107 年 10 月 02 日
                            <br>約定說明：「此本票係供為分期付款買賣之分期款項總額憑證，俟分期付款完全清償完畢時，此本票自動失效，但如有一期未付，發票人願意就全部本票債務負責清償。」本人同意依法令規定應以書面為之者,得以電子文件為之.依法令規定應簽名或蓋章者，得以電子簽章為之。 </p>
                            <p>發票人中文正楷簽名</p>
                            <div class="sign-zone"></div>
                            <div class="section-order-title"></div>
                            <p>★分期付款期間未繳清以前禁止出售或典當，以免觸法<br>分期付款約定事項： 一、 申請人(即買方)及其連帶保證人向商品經銷商(即賣方)以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人對本條約所有條款均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守「分期付款約定書(點文字可連結閱讀詳文)」之各項約定條款。<br>二、申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期金額之權利及依本約定書約定所有之其他一切權利及利益轉讓與廿一世紀數位科技有限公司及其帳款收買人，受讓人對於分期付款買賣案件擁有核准與否同意權，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商指定銀行帳戶，相關手續費金額之約定則按商品經銷商與 大方藝彩行銷顧問股份有限公司所簽訂相關之合約約定之，申請人及其連帶保證人絕無異議。<br>三、申請人（即買方）及其連帶保證人聲明確實填寫及簽訂本「分期付款申請書暨約定書」內容，且交付商品經銷商之任何文件中並無不實之陳述或說明之情事。 </p>
                            <div class="form-check text-left m-2">
                                <input class="form-check-input" type="checkbox" id="FieldsetCheck">
                                <label class="form-check-label" for="FieldsetCheck">我已詳細閱讀並同意以上條款及<a href="#" class="text-orange">「分期付款約定書(點文字可連結閱讀詳文)」</a>之內容及所有條款</label>
                            </div>
                            <div class="form-check text-left m-2">
                                <input class="form-check-input" type="checkbox" id="FieldsetCheck">
                                <label class="form-check-label" for="FieldsetCheck">我已詳細閱讀並同意<a href="#" class="text-orange">免責聲明</a>、<a href="#" class="text-orange">服務條款</a>、<a href="#" class="text-orange">隱私權聲明</a>等條款</label>
                            </div>
                            <p>申請人中文正楷簽名</p>
                            <div class="sign-zone"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>