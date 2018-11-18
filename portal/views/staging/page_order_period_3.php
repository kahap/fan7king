

<main role="main">
    <h1><span>分期購買</span><small>staging</small></h1>
    <section id="staging-zone">
        <div class="container">
            <div class="row justify-content-md-center">
                <div class="col-lg-8">
                    <div class="row bs-wizard">
                        <div class="col-3 bs-wizard-step complete">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">1</a>
                            <div class="desc">填寫基本資料</div>
                        </div>
                        <div class="col-3 bs-wizard-step complete">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">2</a>
                            <div class="desc">簽名及附件上傳</div>
                        </div>
                        <div class="col-3 bs-wizard-step active">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">3</a>
                            <div class="desc">確認訂單資訊</div>
                        </div>
                        <div class="col-3 bs-wizard-step disabled">
                            <div class="progress"><div class="progress-bar"></div></div>
                            <a href="#" class="bs-wizard-dot">4</a>
                            <div class="desc">完成等候照會</div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- page heading-->
            <?php
            //print_r($_SESSION);

            //欄位名稱
            $or = new Orders();
            $columnName = $or->getAllColumnNames("orders");
            $or_data = $or->getOneOrderByNo($_SESSION['ord_code']);
            $member = new Member();
            $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
            //print_r($columnName);

            //聯絡人
            $rc = new API2("real_cases");
//
            $rc->setWhereArray(array("rcRelateDataNo"=>$or_data[0]['orNo']));
            $rc->getWithWhereAndJoinClause();
            $rcData = $rc->getData();
            $rcNo=0;
            if($rcData != null) {
                $rcNo = $rcData[0]["rcNo"];
            }
            $orderContact = new API2("orderContact");
            $orderContact->setWhereArray(array("rcNo"=>$rcNo));
            $orderContact->getWithWhereAndJoinClause();
            $ocData = $orderContact->getData();
//            var_dump($ocData);
//            echo "<script>alert('rh:'+".count($ocData).");</script>";
            ?>
            <!-- ../page heading-->
            <div class="section-staging bg-white">
                <div class="section-order-title">購買商品</div>
                <div class="form-group row">
                    <div class="col-sm-3">商品名稱</div>
                    <div class="col-sm-9"><?php echo $_SESSION['shopping_product'][0]['proName']?></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">商品規格</div>
                    <div class="col-sm-9"><?php echo $or_data[0]['orProSpec']?></div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">商品總額</div>
                    <div class="col-sm-9"><?php echo number_format(ceil($or_data[0]['orPeriodTotal']))?> 元</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">月付</div>
                    <div class="col-sm-9"><?php echo number_format(ceil($or_data[0]['orPeriodTotal']/$or_data[0]['orPeriodAmnt']))?> 元</div>
                </div>
                <div class="form-group row">
                    <div class="col-sm-3">期數</div>
                    <div class="col-sm-9"><?php echo $or_data[0]['orPeriodAmnt']?> 期</div>
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">申請人資料</div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col">申請人中文姓名</div>
                            <div class="col"><?php echo $memberData['0']["memName"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">身分別</div>
                            <div class="col"><?php echo $memclass[$memberData[0]['memClass']]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">學校Email</div>
                            <div class="col"><?php echo $memberData[0]["memAccount"]; ?></div>
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
                            <div class="col"><?php echo $or_data[0]["orAppApplierBirthPhone"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">行動電話</div>
                            <div class="col"><?php echo $memberData[0]["memCell"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">住房所有權</div>
                            <div class="col"><?php echo $or_data[0]["orAppApplierLivingOwnership"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">戶籍地址</div>
                            <div class="col"><?php echo $or_data[0]["orAppApplierBirthAddr"]; ?></div>
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
                                <img src="<?php echo  str_replace('../','',$or_data[0]['orAppAuthenIdImgTop']); ?>" alt="" style="width: 150px">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">申請人身份證反面</div>
                            <div class="col-12">
                                <img src="<?php echo  str_replace('../','',$or_data[0]['orAppAuthenIdImgBot']); ?>" alt="" style="width: 150px">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col">身份證字號</div>
                            <div class="col"><?php echo $memberData['0']["memIdNum"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">出生年月日</div>
                            <?php
                                $birthday=explode("-",$memberData['0']["memBday"]);
                            ?>
                            <div class="col">民國 <?php echo $birthday[0];?> 年 <?php echo $birthday[1];?> 月 <?php echo $birthday[2];?> 日</div>
                        </div>
                        <div class="form-group row">
                            <div class="col">申請人身份證發證日期</div>
                            <div class="col"><?php echo $or_data[0]['orIdIssueYear']."-".$or_data[0]['orIdIssueMonth']."-".$or_data[0]['orIdIssueDay']; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">申請人身份證發證地點</div>
                            <div class="col"><?php echo $or_data[0]['orIdIssuePlace'];?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">申請人身份證發證類別</div>
                            <div class="col"><?php echo $or_data[0]['orIdIssueType']; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">收貨人資料</div>
                <div class="row">
                    <div class="col">
                        <?php
                        foreach($columnName as $key=>$value){
                            if(strrpos($value["COLUMN_NAME"], "orReceive") !== false){
                            ?>
                        <div class="form-group row">
                            <div class="col"><?php echo $value["COLUMN_COMMENT"]; ?></div>
                            <div class="col"><?php echo $or_data[0][$value['COLUMN_NAME']]; ?></div>
                        </div>
                            <?php
                            }
                        }
                        ?>
<!--                        <div class="form-group row">-->
<!--                            <div class="col">收貨人地址</div>-->
<!--                            <div class="col">台北市中正區基隆路</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">收貨人市話</div>-->
<!--                            <div class="col">0226739991</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">收貨人手機</div>-->
<!--                            <div class="col">0900000000</div>-->
<!--                        </div>-->
                    </div>
<!--                    <div class="col">-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">收貨備註</div>-->
<!--                            <div class="col-12">-->
<!--                                --><?php
//                                foreach($columnName as $key=>$value){
//                                    //只顯示
//                                    if($value["COLUMN_NAME"] == "orReceiveComment") {
//                                        echo ($orData[0][$value["COLUMN_NAME"]]);   break;
//                                    }
//                                }
//                                ?>
<!--                            </div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">工作</div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col">公司名稱</div>
                            <div class="col"><?php echo $or_data[0]["orAppApplierCompanyName"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">年資</div>
                            <div class="col"><?php echo ($or_data[0]["orAppApplierYearExperience"] == '0') ? '':$or_data[0]["orAppApplierYearExperience"]; ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <div class="col">月薪</div>
                            <div class="col"><?php echo ($or_data[0]["orAppApplierMonthSalary"] == '0') ? '':$or_data[0]["orAppApplierMonthSalary"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">公司市話</div>
                            <div class="col"><?php echo $or_data[0]["orAppApplierCompanyPhone"]; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">信用卡</div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col">信用卡號(僅供參考)</div>
                            <div class="col"><?php echo ($or_data[0]["orAppApplierCreditNum"] == '---') ? '':$or_data[0]["orAppApplierCreditNum"]; ?></div>
                        </div>
                        <div class="form-group row">
                            <div class="col">發卡銀行</div>
                            <div class="col"><?php echo $or_data[0]["orAppApplierCreditIssueBank"]; ?></div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group row">
                            <div class="col">信用卡有效期限</div>
                            <div class="col"><?php echo $or_data[0]["orAppApplierCreditDueDate"]; ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">統一編號</div>
                <div class="row">
                    <div class="col">
                        <div class="form-group row">
                            <div class="col">是否需要統一編號</div>
                            <div class="col">
                                <?php
                                foreach($columnName as $key=>$value){
                                    //只顯示
                                    if($value["COLUMN_NAME"] == "orBusinessNumIfNeed") {
                                        echo ($orData[0][$value["COLUMN_NAME"]]);   break;
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
                                    //只顯示
                                    if($value["COLUMN_NAME"] == "orBusinessNumNumber") {
                                        echo ($orData[0][$value["COLUMN_NAME"]]);   break;
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
                                    //只顯示
                                    if($value["COLUMN_NAME"] == "orBusinessNumTitle") {
                                        echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">聯絡人資料</div>
                <div class="row">
                    <?php
                    foreach ($ocData as $val) {
                        ?>
                        <div class="col">
                            <div class="form-group row">
                                <div class="col">聯絡人姓名</div>
                                <div class="col"><?php echo $val['rcContactName'];?></div>
                            </div>
                            <div class="form-group row">
                                <div class="col">聯絡人關係</div>
                                <div class="col"><?php echo $val['rcContactRelation'];?></div>
                            </div>
                            <div class="form-group row">
                                <div class="col">聯絡人市話</div>
                                <div class="col"><?php echo $val['rcContactPhone'];?></div>
                            </div>
                            <div class="form-group row">
                                <div class="col">聯絡人手機</div>
                                <div class="col"><?php echo $val['rcContactCell'];?></div>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
<!--                    <div class="col">-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">親屬姓名</div>-->
<!--                            <div class="col">--><?php //echo $val['rcContactName'];?><!--</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">親屬關係</div>-->
<!--                            <div class="col">--><?php //echo $val['rcContactRelation'];?><!--</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">親屬市話</div>-->
<!--                            <div class="col">--><?php //echo $val['rcContactPhone'];?><!--</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">親屬手機</div>-->
<!--                            <div class="col">--><?php //echo $val['rcContactCell'];?><!--</div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">朋友姓名</div>-->
<!--                            <div class="col">魯凱</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">朋友關係</div>-->
<!--                            <div class="col">戀人</div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">朋友市話</div>-->
<!--                            <div class="col"></div>-->
<!--                        </div>-->
<!--                        <div class="form-group row">-->
<!--                            <div class="col">朋友手機</div>-->
<!--                            <div class="col">0957192738</div>-->
<!--                        </div>-->
<!--                    </div>-->
                </div>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">備註</div>
                <div class="row">
                <?php
                foreach($columnName as $key=>$value){
                    if(strrpos($value["COLUMN_NAME"], "orAppExtra") !== false){
                        ?>
                        <div class="col">
                            <div class="form-group row">
                                <div class="col"><?php echo $value["COLUMN_COMMENT"]; ?></div>
                                <div class="col"><?php echo $or_data[0][$value['COLUMN_NAME']]; ?></div>
                            </div>
                        </div>
<!--                        <div class="col">-->
<!--                            <div class="form-group row">-->
<!--                                <div class="col">注意事項</div>-->
<!--                                <div class="col"></div>-->
<!--                            </div>-->
<!--                        </div>-->
                        <?php
                    }
                }
                ?>
                </div>
            </div>
            <div class="form-check form-check-inline mt-4">
                <input class="form-check-input" type="checkbox" name="agree" id="HaveCredit2" value="1" <?php echo ($or_data['0']["orIfSecret"] == '1') ? 'checked disabled':'disabled'; ?>>
                <label class="form-check-label" for="HaveCredit2">申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）</label>
            </div>
            <div class="section-staging bg-white">
                <div class="section-order-title">證件資料</div>
                <div class="form-group row">
                    <div class="col">申請人學生證正面</div>
                    <div class="col-12"><img src="<?php echo str_replace('../','',$or_data[0]['orAppAuthenStudentIdImgTop']); ?>" class="img-fluid" alt=""></div>
                </div>
                <div class="form-group row">
                    <div class="col">申請人學生證反面</div>
                    <div class="col-12"><img src="<?php echo str_replace('../','',$or_data[0]['orAppAuthenStudentIdImgBot']); ?>" class="img-fluid" alt=""></div>
                </div>
                <div class="form-group row">
                    <div class="col">申請人自拍照</div>
                    <div class="col-12"><img src="<?php echo str_replace('../','',$or_data[0]['orAppAuthenSelfImgTop']); ?>" class="img-fluid" alt=""></div>
                </div>
                <div class="section-order-title">請在下方兩處，以滑鼠或手寫功能簽上正楷簽名</div>
                <p>本票： 憑票於中華民國 年 月 日無條件支付 大方藝彩行銷顧問股份有限公司或指定人
                    <br>新台幣　
                    <span style=""><?php
                        $ex = preg_split("//", $or_data[0]['orPeriodTotal']);
                        $ln = strlen($or_data[0]['orPeriodTotal']);
                        $count = count($ex);
                        echo ($ln > 5) ? $coin[$ex[1]]:'零';
                        ?>
                    </span>
                    &nbsp;拾&nbsp;
                    <span style=""><?php echo ($ln = 5) ? $coin[$ex[1]]:'零'; ?></span>
                    &nbsp;萬&nbsp;
                    <span style=""><?php echo $coin[$ex[2]]; ?></span>
                    &nbsp;仟&nbsp;
                    <span style=""><?php echo $coin[$ex[3]]; ?></span>
                    &nbsp;佰&nbsp;
                    <span style=""><?php echo $coin[$ex[4]]; ?></span>
                    &nbsp;拾&nbsp;
                    <span style=""><?php echo $coin[$ex[5]]; ?></span>
                    &nbsp;&nbsp;  　元整
                    <br>此總額為您選擇之月付金X期數
                    <br>此本票免除作成拒絕證書及票據法第八十九條之通知義務，
                    <br>利息自到期日迄清償日止按年利率百分之二十計付，
                    <br>發票人授權持票人得填載到期日。
                    <br>付款地：桃園市桃園區文中路493號4樓
                    <br>此據
                    <br>中華民國 <?php echo date('Y',strtotime($or_data[0]['orDate']))-1911; ?> 年 <?php echo date('m',strtotime($or_data[0]['orDate'])); ?> 月 <?php echo date('d',strtotime($or_data[0]['orDate'])); ?> 日
                    <br>約定說明：「此本票係供為分期付款買賣之分期款項總額憑證，俟分期付款完全清償完畢時，此本票自動失效，但如有一期未付，發票人願意就全部本票債務負責清償。」本人同意依法令規定應以書面為之者,得以電子文件為之.依法令規定應簽名或蓋章者，得以電子簽章為之。 </p>
                <p>發票人中文正楷簽名</p>
                <div class="sign-zone" style="height: 100%;">
                    <?php
                    if ($or_data[0]['orAppAuthenProvement'] != "") echo "<img src='". str_replace('../','',$or_data[0]['orAppAuthenProvement']) ."' id='orAppAuthenProvement' />";
                    ?>
                </div>
                <div class="section-order-title"></div>
                <p>★分期付款期間未繳清以前禁止出售或典當，以免觸法<br>分期付款約定事項： 一、 申請人(即買方)及其連帶保證人向商品經銷商(即賣方)以分期付款方式購買消費性商品，並簽約本「分期付款申請書暨約定書」，業經申請人及其連帶保證人對本條約所有條款均已經合理天數詳細審閱，且已充份理解契約內容，同意與商品經銷商共同遵守「分期付款約定書(點文字可連結閱讀詳文)」之各項約定條款。<br>二、申請人及其連帶保證人於簽約時同意商品經銷商不另書面通知得將支付分期金額之權利及依本約定書約定所有之其他一切權利及利益轉讓與廿一世紀數位科技有限公司及其帳款收買人，受讓人對於分期付款買賣案件擁有核准與否同意權，並茲授權帳款收買人將分期付款總額或核准金額，逕行扣除手續費及相關費用，撥付與商品經銷商指定銀行帳戶，相關手續費金額之約定則按商品經銷商與 大方藝彩行銷顧問股份有限公司所簽訂相關之合約約定之，申請人及其連帶保證人絕無異議。<br>三、申請人（即買方）及其連帶保證人聲明確實填寫及簽訂本「分期付款申請書暨約定書」內容，且交付商品經銷商之任何文件中並無不實之陳述或說明之情事。 </p>

                <form id="order_add">
                <div class="form-check text-left m-2">
                    <input class="form-check-input" type="checkbox" id="check2" name="check" value="" checked disabled>
                    <label class="form-check-label agree" for="check2">
                        我已詳細閱讀並同意以上條款及
                        <a href="?item=fmPeriodDeclare" class="text-orange" target="_blank" style="">「分期付款約定書(點文字可連結閱讀詳文)」</a>
                        之內容及所有條款
                    </label>
                </div>
                <div class="form-check text-left m-2">
                    <input class="form-check-input" id="check4" type="checkbox" name="check4" value="" disabled checked style="">
                    <label class="form-check-label agree" for="check4">
                        我已詳細閱讀並同意
                        <a href="?item=fmFreeRespons" class="text-orange">免責聲明</a>、
                        <a href="?item=fmServiceRules" class="text-orange">服務條款</a>、
                        <a href="?item=fmPrivacy" class="text-orange">隱私權聲明</a>
                        等條款
                    </label>
                </div>
                </form>
                <p>申請人中文正楷簽名</p>
                <div class="sign-zone" style="height: 100%;">
                    <?php
                    if ($or_data[0]['orAppAuthenPromiseLetter'] != "") echo "<img src='". str_replace('../','',$or_data[0]['orAppAuthenPromiseLetter']) ."' id='orAppAuthenPromiseLetter' />";
                    ?>
                </div>
            </div>
            <div class="section-order">
                <div class="form-group form-btn text-center">
                    <a href="index.php?item=member_center&action=order_period&method=2" class="btn btn-prev bg-gray prev-btn">上一步</a>
                    <?php
//                    if(in_array($memberData['0']['memFBtoken'],$fb_token)){
//                        echo "<a class='next' style='float:right;background:#ff3366;color: #fff; border: 1px solid #ff3366;'><button>下一步</button></a>";
//                    }elseif(is_file($or_data[0]['orAppAuthenIdImgTop'])){
//                        ?>
                        <a class="btn btn-next bg-yellow next-btn">
                            完成
                            <?php //if($_GET['pro'] == '10190'){ ?>
                                <!-- <img src="https://farm-tw.plista.com/activity2;domainid:718601;campaignid:717271;event:31" style="width:1px;height:1px;" alt="" /> -->
                            <?PHP //} ?>
                            
                        </a>
<!--                        --><?php
//                    }else{
                        ?>
<!--                        <a style="float: right;border: 1px solid #ff3366;" href="http://happyfan7.com/?item=fmContactService" target="_blank"><button >證件未上傳成功，請洽詢客服人員!!</button></a>-->
                        <?php
//                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
</main>

<script>
    $(".next-btn").click(function(e){
        $(".next-btn").hide();
        $.ajax({
            url: 'portal/Controllers/php/order_finish.php',
            data: "member_data=11",
            type:"POST",
            dataType:'text',
            success: function(msg){
                if(msg){
                    alert('購買完成，請等候電話照會');
                    location.href='index.php?item=member_center&action=order_period&method=4';
                    //thankButton();  //<!-- 華維廣告 -->
                }else{
                    alert('系統操作錯誤');
                    $(".next-btn").show();
                }
                e.preventDefault();
            },
            error:function(xhr, ajaxOptions, thrownError){
                alert(xhr.status);
                alert(thrownError);
                $(".next-btn").show();
            }
        });
    });

    $(".next").click(function(){
        location.href='index.php?item=member_center&action=order_period&method=auto';
    })
</script>