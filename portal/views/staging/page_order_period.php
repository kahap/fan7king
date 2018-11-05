<!-- page wapper-->
<style>
    h3{
        font-size: 16px;
        color:blue;
        margin-top:3px;
        margin-bottom:3px;
    }
</style>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="portal/assets/js/select/chosen.css">
<script src="portal/assets/js/aj-address.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.address-zone').ajaddress({ city: "請選擇", county: "請選擇" });
        if($(".memclass").val() == '0'){
            $(".memSchool").show();
            $(".chosen-container").show();
            $(".memAccount").show();
        }else{
            $(".memSchool").hide();
            $(".department").hide();
            $(".chosen-container").hide();
            $(".memAccount").hide();
        }
        if($("input[name=orBusinessNumIfNeed]").val() == '0'){
            $("#orBusinessNumNumber").hide();
        }
    });
</script>

<main role="main">
    <h1><span>分期購買</span><small>staging</small></h1>
    <section id="staging-zone">
        <form action="#">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-lg-8">
                        <div class="row bs-wizard">
                            <div class="col-3 bs-wizard-step active">
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#" class="bs-wizard-dot">1</a>
                                <div class="desc">填寫基本資料</div>
                            </div>
                            <div class="col-3 bs-wizard-step disabled">
                                <div class="progress"><div class="progress-bar"></div></div>
                                <a href="#" class="bs-wizard-dot">2</a>
                                <div class="desc">簽名及附件上傳</div>
                            </div>
                            <div class="col-3 bs-wizard-step disabled">
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
                $member = new Member();
                $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
                $columnName = $or->getAllColumnNames("orders");
                $iforder = $or->getOrderhistory($_SESSION['user']['memNo']);
                $disabled = ($iforder != '') ? "disabled":"";
                //欄位名稱
                //print_r($columnName);

                $school = new School();
                $school_data = $school->getAll();

                $major = new Major();
                $major_data = $major->getAll();

                foreach($major_data as $k => $v){
                    $major_combine[$v['schNo']][] = $v['majName'];
                }


                //相簿圖片
                $imgArr = getAllImgs();
                $imgArr = isset($imgArr) ?$imgArr: [];

                ?>
                <!-- ../page heading-->

                <p>申請人基本資料<span class="text-orange">*為必填欄位，請務必詳實填寫，如未滿20歲,需父母同意分期購買。</span></p>
                <div class="section-staging bg-white">
                    <div class="section-order-title">基本資料<span class="text-orange"> *基本資料請填寫完整，以增加審核速度與過案機會。</span></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control memName" id="CName" name="memName" value="<?php echo $memberData[0]["memName"]; ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="IdentKind" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身分別</label>
                                <div class="col-sm-9">
                                    <select class="form-control memclass" id="IdentKind" name="memclass">
                                        <option value="0" <?php echo ($memberData[0]['memClass'] == 0) ? "selected":""; ?> >學生</option>
                                        <option value="4" <?php echo ($memberData[0]['memClass'] == 4) ? "selected":""; ?> >非學生</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="SchoolEmail" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學校Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control memAccount" id="SchoolEmail" name="memAccount" value="<?php echo $memberData[0]["memAccount"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="EmailAddress" class="col-sm-3 col-form-label"><span class="text-orange">*</span>常用Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control memSubEmail" id="EmailAddress" name="memSubEmail" value=
                                    "<?php
                                        if($memberData[0]['memclass'] != '0' && $memberData[0]['memFBtoken'] == ""){
                                            echo $memberData[0]["memAccount"];
                                        }else{
                                            echo $memberData[0]["memSubEmail"];
                                        }
                                    ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="NowTelephone" class="col-sm-3 col-form-label"><span class="text-orange">*</span>現住電話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control memPhone" id="NowTelephone" name="memPhone" value="<?php echo $memberData[0]['memPhone'] ?>" placeholder="ex: 02-22898878">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="HomeTelephone" class="col-sm-3 col-form-label"><span class="text-orange">*</span>戶籍電話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control orAppApplierBirthPhone" id="HomeTelephone" name="orAppApplierBirthPhone">
                                    <div class="float-right m-1">
                                        <input class="form-check-input" type="checkbox" id="SameForNowTelephone">
                                        <label class="form-check-label" for="SameForNowTelephone">同現住電話</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Mobile" class="col-sm-3 col-form-label"><span class="text-orange">*</span>行動電話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control memCell" id="Mobile" name="memCell" value="<?php echo $memberData[0]['memCell'] ?>" placeholder="ex: 0911222333">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label"><span class="text-orange"></span>住房所有權</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input input" type="radio" name="orAppApplierLivingOwnership" id="HouseOwner1" value="自有/配偶">
                                        <label class="form-check-label" for="HouseOwner1">自有/配偶</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input input" type="radio" name="orAppApplierLivingOwnership" id="HouseOwner2" value="父母/子女">
                                        <label class="form-check-label" for="HouseOwner2">父母/子女</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input input" type="radio" name="orAppApplierLivingOwnership" id="HouseOwner3" value="親友">
                                        <label class="form-check-label" for="HouseOwner3">親友</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input input" type="radio" name="orAppApplierLivingOwnership" id="HouseOwner4" value="租賃">
                                        <label class="form-check-label" for="HouseOwner4">租賃</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input input" type="radio" name="orAppApplierLivingOwnership" id="HouseOwner5" value="宿舍">
                                        <label class="form-check-label" for="HouseOwner5">宿舍</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input input" type="radio" name="orAppApplierLivingOwnership" id="HouseOwner6" value="其他">
                                        <label class="form-check-label" for="HouseOwner6">其他</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">身分資料</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人身分證正面</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control memImage" id="customFile" name="memImage[]" >
<!--                                    <span id="stampImgErr" style="color:red;"></span><br>-->
<!--                                    <div id="preview-area-multiple" class="col-md-6 col-sm-6 col-xs-12">-->
<!--                                        --><?php
//                                        if($_GET["action"] == "edit" && !empty($proImageArray)){
//                                            foreach($proImageArray as $value){
//                                                if($value != ""){
//                                                    ?>
<!---->
<!--                                                    <div class="old" style="border:2px solid #AAA;padding:5px;margin:20px;display:inline-block;text-align:center;">-->
<!--                                                        <img style="max-width:300px;margin-bottom:10px;" src="--><?php //echo $value; ?><!--">-->
<!--                                                        <br>-->
<!--                                                        <button data-index="--><?php //echo $value; ?><!--" class="remove-img btn btn-danger">刪除</button>-->
<!--                                                    </div>-->
<!--                                                    --><?php
//                                                }
//                                            }
//                                        }
//                                        ?>
<!--                                    </div>-->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人身分證反面</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control memImage" id="customFile" name="memImage[]" >
                                    <!--                                    <span id="stampImgErr" style="color:red;"></span><br>-->
                                    <!--                                    <div id="preview-area-multiple" class="col-md-6 col-sm-6 col-xs-12">-->
                                    <!--                                        --><?php
                                    //                                        if($_GET["action"] == "edit" && !empty($proImageArray)){
                                    //                                            foreach($proImageArray as $value){
                                    //                                                if($value != ""){
                                    //                                                    ?>
                                    <!---->
                                    <!--                                                    <div class="old" style="border:2px solid #AAA;padding:5px;margin:20px;display:inline-block;text-align:center;">-->
                                    <!--                                                        <img style="max-width:300px;margin-bottom:10px;" src="--><?php //echo $value; ?><!--">-->
                                    <!--                                                        <br>-->
                                    <!--                                                        <button data-index="--><?php //echo $value; ?><!--" class="remove-img btn btn-danger">刪除</button>-->
                                    <!--                                                    </div>-->
                                    <!--                                                    --><?php
                                    //                                                }
                                    //                                            }
                                    //                                        }
                                    //                                        ?>
                                    <!--                                    </div>-->
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="IdentNumber" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身份證字號</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control memIdNum" id="IdentNumber" name="memIdNum" value="<?php echo $memberData[0]["memIdNum"]; ?>" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>出生年月日</label>
                                <div class="col-sm-9">
                                    <div class="form-inline">
                                        民國
                                        <select class="custom-select mb-3" name="year">
                                            <?php
                                            $year =  explode('-',$memberData[0]["memBday"]);
                                            for($i=50;$i<=105;$i++){ ?>
                                                <option value="<?=$i ?>" <?php echo ($year[0] == $i) ? 'selected':''; ?>><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>100</option>-->
<!--                                            <option value="">20</option>-->
<!--                                            <option value="">21</option>-->
                                        </select>
                                        年
                                        <select class="custom-select mb-3" name="month">
                                            <?php
                                            for($i=1;$i<=12;$i++){ ?>
                                                <option value="<?=$i ?>" <?php echo ($year[1] == $i) ? 'selected':''; ?>><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>01</option>-->
<!--                                            <option value="">02</option>-->
<!--                                            <option value=2">03</option>-->
                                        </select>
                                        月
                                        <select class="custom-select mb-3" name="date">
                                            <?php
                                            for($i=1;$i<=31;$i++){ ?>
                                                <option value="<?=$i ?>" <?php echo ($year[2] == $i) ? 'selected':''; ?>><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>01</option>-->
<!--                                            <option value="">02</option>-->
<!--                                            <option value=2">03</option>-->
                                        </select>
                                        日
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身分證發證日期</label>
                                <div class="col-sm-9">
                                    <div class="form-inline">
                                        民國
                                        <select class="custom-select mb-3" name="orIdIssueYear">
                                            <?php
                                            $year = date('Y',time())-1911;
                                            for($i=50;$i<=$year;$i++){ ?>
                                                <option value="<?=$i ?>"><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>100</option>-->
<!--                                            <option value="">20</option>-->
<!--                                            <option value="">21</option>-->
                                        </select>
                                        年
                                        <select class="custom-select mb-3" name="orIdIssueMonth">
                                            <?php
                                            for($i=1;$i<=12;$i++){ ?>
                                                <option value="<?=$i ?>"><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>01</option>-->
<!--                                            <option value="">02</option>-->
<!--                                            <option value=2">03</option>-->
                                        </select>
                                        月
                                        <select class="custom-select mb-3" name="orIdIssueDay">
                                            <?php
                                            for($i=1;$i<=31;$i++){ ?>
                                                <option value="<?=$i ?>"><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>01</option>-->
<!--                                            <option value="">02</option>-->
<!--                                            <option value=2">03</option>-->
                                        </select>
                                        日
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange"></span>發證類別</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="IdentKind" name="orIdIssueType">
                                        <option selected value="<?php echo $or_data[0]['orIdIssueType']; ?>"><?php echo $or_data[0]['orIdIssueType']; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange"></span>換補發類別</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="IdentKind" name="">
                                        <option selected>換補發類別</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>戶籍地址</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control" id="CreditBank" name="orAppApplierBirthAddrPostCode" value="<?php echo $or_data['0']['orAppApplierBirthAddrPostCode'];?>" readonly >
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control city">
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control county">
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control orAppApplierBirthAddr" id="CreditBank" name="orAppApplierBirthAddr" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>現住地址</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control" id="CreditBank" name="memPostCode" value="<?php echo $memberData['0']['memPostCode'];?>" readonly >
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control city" id="city">
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control county" id="county">
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control memAddr" id="CreditBank" name="memAddr" value="<?php echo $memberData[0]['memAddr'] ?>">
                                    <div class="float-right m-1">
                                        <input class="form-check-input" type="checkbox" id="SameForNowTelephone" name="sameofapplier">
                                        <label class="form-check-label" for="SameForNowTelephone" >同戶籍地址</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">工作</div>
                    <div class="row" id="orAppApplierCompanystatus">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="JobStatus" class="col-sm-3 col-form-label"><span class="text-orange"></span>工作狀態</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orAppApplierCompanystatus" id="JobStatus1" value="1">
                                        <label class="form-check-label" for="JobStatus1">有</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orAppApplierCompanystatus" id="JobStatus2" value="0" checked>
                                        <label class="form-check-label" for="JobStatus2">無</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CompanyName" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司名稱</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CompanyName" name="orAppApplierCompanyName" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="JobYear" class="col-sm-3 col-form-label"><span class="text-orange"></span>年資</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="JobYear" name="orAppApplierYearExperience" placeholder="一年">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="Salary" class="col-sm-3 col-form-label"><span class="text-orange"></span>月薪</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="Salary" name="orAppApplierMonthSalary" placeholder="100000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComPhone" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComPhone" name="orAppApplierCompanyPhone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComExtension" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話分機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComExtension" name="orAppApplierCompanyPhoneExt">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">信用卡</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="HaveCredit" class="col-sm-3 col-form-label"><span class="text-orange"></span>持有信用卡</label>
                                <div class="col-sm-9" id="HaveCredit">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orAppApplierCreditstatus" id="HaveCredit1" value="1">
                                        <label class="form-check-label" for="HaveCredit1">有</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orAppApplierCreditstatus" id="HaveCredit2" value="0" checked>
                                        <label class="form-check-label" for="HaveCredit2">無</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditNo" class="col-sm-3 col-form-label"><span class="text-orange"></span>信用卡號</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-3"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_1" value=""></div>
                                        <div class="col-3"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_2" value=""></div>
                                        <div class="col-3"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_3" value=""></div>
                                        <div class="col-3"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_4" value=""></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>發卡銀行</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppApplierCreditIssueBank" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComPhone" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComPhone" name="orAppApplierCompanyPhone" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComExtension" class="col-sm-3 col-form-label"><span class="text-orange"></span>信用卡有效期限</label>
                                <div class="col-sm-9">
                                    <div class="form-inline">
                                        <select class="custom-select mb-3" name="orAppApplierCreditDueDate_1">
                                            <?php for($i=1;$i<=12;$i++){ ?>
                                                <option value="<?=$i?>"><?=$i; ?></option>
                                            <?php } ?>
<!--                                            <option selected>01</option>-->
<!--                                            <option value="">02</option>-->
<!--                                            <option value=2">03</option>-->
                                        </select>
                                        月
                                        <select class="custom-select mb-3" name="orAppApplierCreditDueDate_2">
                                            <?php for($i=2016;$i<=2030;$i++){ ?>
                                                <option value="<?=$i?>"><?=$i; ?></option>
                                            <?php } ?>
<!--                                            <option selected>19</option>-->
<!--                                            <option value="">20</option>-->
<!--                                            <option value="">21</option>-->
                                        </select>
                                        年
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">收貨人資料</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orReceiveName" value="
                                        <?php
                                        foreach($columnName as $key=>$value){
                                            //只顯示
                                            if($value["COLUMN_NAME"] == "orReceiveName") {
                                                echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                            }
                                        }
                                        ?>">
                                    <div class="float-left m-1 mr-2">
                                        <input class="form-check-input" type="checkbox" id="SameForLive" name="sameofapplier_1">
                                        <label class="form-check-label" for="SameForLive">同申請人現住資料</label>
                                    </div>
                                    <div class="float-left m-1 ml-3">
                                        <input class="form-check-input" type="checkbox" id="SameForRegistration" name="sameofapplier_2">
                                        <label class="form-check-label" for="SameForRegistration">同申請人戶籍資料</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人地址</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control" id="CreditBank" name="">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="CreditBank" name="orReceiveAddr" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orReceiveAddr") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orReceivePhone" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orReceivePhone") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orReceiveCell" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orReceiveCell") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>收貨備註</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="orReceiveComment">
                                        <?php
                                        foreach($columnName as $key=>$value){
                                            //只顯示
                                            if($value["COLUMN_NAME"] == "orReceiveComment") {
                                                echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                            }
                                        }
                                        ?>
                                    </textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>是否需要統一編號</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orBusinessNumIfNeed" id="HaveCredit1" value="1">
                                        <label class="form-check-label" for="HaveCredit1">是</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orBusinessNumIfNeed" id="HaveCredit2" value="0" checked>
                                        <label class="form-check-label" for="HaveCredit2">否</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>統一編號</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orBusinessNumNumber" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orBusinessNumNumber") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司抬頭</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orBusinessNumTitle" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orBusinessNumTitle") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">聯絡人資訊<span class="text-orange">請填寫真實資料，造假會導致案件申請失敗</span></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>親屬姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactRelaName" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactRelaName") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>親屬關係</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactRelaRelation" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactRelaRelation") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>親屬市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactRelaPhone" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactRelaPhone") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactFrdName" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactFrdName") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友關係</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactFrdRelation" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactFrdRelation") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactFrdPhone" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactFrdPhone") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppContactFrdCell" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactFrdCell") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">備註</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>可照會時間</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppExtraAvailTime" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppExtraAvailTime") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>注意事項</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="orAppExtraInfo" value="
                                    <?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppExtraInfo") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-check form-check-inline mt-4">
                    <input class="form-check-input" type="checkbox" name="agree" id="HaveCredit2" value="1">
                    <label class="form-check-label" for="HaveCredit2">申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）<a class="text-orange secure" title="甚麼是保密照會？">甚麼是保密照會？</a></label>
                </div>
                <div class="section-staging">
                    <div class="form-group form-btn text-center">
                        <a class="btn btn-next bg-yellow">下一步</a>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>


<script src="assets/js/select/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function(){

        //
        $('.btn-next').click(function(e){
            // if($('input[name=period]').val() != ""){
            location.href='index.php?item=member_center&action=order_period&method=2';
            e.preventDefault();
            return false;

                if($('input[name=user]').val() != ""){
                    $.ajax({
                        url: 'portal/Controllers/php/order_period.php',
                        data: $('#shopping').serialize(),
                        type:"POST",
                        dataType:'text',
                        success: function(msg){
                            if(msg == "1"){
                                location.href='index.php?item=member_center&action=order_period';
                            }else{
                                alert(msg);
                                return false;
                            }
                        },
                        error:function(xhr, ajaxOptions, thrownError){
                            alert(xhr.status);
                            alert(thrownError);
                            return false;
                        }
                    });
                }else{
                    alert('請先登入帳號');
                    // location.href="index.php?item=login";
                    location.href="index.php?item=login&pro=<?=$proNo; ?>&share=<?php echo $_GET['share']? $_GET['share'] : ''; ?>";
                    e.preventDefault();
                    return false;
                }
            // }else{
            //     alert('請選擇期數');
            //     return false;
            //
            // }
        });
    });

    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    $(".secure").click(function(){
        alert("若你不希望親友知道可以勾選第一步驟申請書姓名前面的保密，勾選之後還是會打電話但不會告知有購物，她們只會接到類似行銷電話確認身份而已。");
    })

    $(".department").hide();
    $(".school").change(function(){
        var school = $(this).val();
        $(".department").hide();
        $("#default").hide();
        $("#shool_"+school).show();
    });

    $("#orAppApplierCompanystatus").hide();
    $("#orAppApplierCreditstatus").hide();
    $("#orBusinessNumNumber").hide();
    $(".memother").hide();
    $(".memclass").change(function(){
        var memclass_val = $(this).val();
        if(memclass_val == '3'){
            $(".memother").show();
        }else{
            $(".memother").hide();
        }
        if(memclass_val == '0'){
            $(".memSchool").show();
            $(".chosen-container").show();
            $(".memAccount").show();
        }else{
            $(".memSchool").hide();
            $(".chosen-container").hide();
            $("#default").hide();
            $(".memAccount").hide();
        }
    })
    $(".next-btn").click(function(){
        if(checkname($("input[name=memName]").val()) && checkTwID($("input[name=memIdNum]").val()) && checkPhone2($("input[name=memCell]").val()) && checkDate($("select[name=year]").val(),$("select[name=month]").val(),$("select[name=date]").val()) && checkAllContact()){
            $.ajax({
                url: 'php/order_check.php',
                data: $('#order_add').serialize(),
                type:"POST",
                dataType:'text',
                success: function(msg){
                    if(msg){
                        if(msg == "1"){
                            alert("請記得到會員中心->會員基本資料做認證信");
                            location.href = "index.php?item=member_center&action=order_period&method=2";
                        }else if(msg == "2"){
                            location.href = "index.php?item=member_center&action=order_period&method=2";
                        }else{
                            alert(msg);
                        }
                    }else{
                        alert(msg);
                    }
                },

                error:function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }
    })
    $("input[name=orAppApplierCompanystatus]").change(function(){
        if($('input[name=orAppApplierCompanystatus]:checked').val() == 1){
            $("#orAppApplierCompanystatus").show();
        }else{
            $("#orAppApplierCompanystatus").hide();
        }
    })
    $("input[name=orAppApplierCreditstatus]").change(function(){
        if($('input[name=orAppApplierCreditstatus]:checked').val() == 1){
            $("#orAppApplierCreditstatus").show();
        }else{
            $("#orAppApplierCreditstatus").hide();
        }
    })
    $("input[name=orBusinessNumIfNeed]").change(function(){
        if($('input[name=orBusinessNumIfNeed]:checked').val() == 1){
            $("#orBusinessNumNumber").show();
        }else{
            $("#orBusinessNumNumber").hide();
        }
    })


    $("input[name=sameofapplier]").change(function(){
        if($('input[name=sameofapplier]:checked').val() == "on"){
            $("input[name=memAddr]").val($("input[name=orAppApplierBirthAddr]").val());
            $("input[name=memPhone]").val($("input[name=orAppApplierBirthPhone]").val());
            $(".memPostCode").val($(".orAppApplierBirthAddrPostCode").val());
        }else{
            $("input[name=memAddr]").val('');
            $("input[name=memPhone]").val('');
        }
    })
    $("input[name=sameofapplier_3]").change(function(){
        if($('input[name=sameofapplier_3]:checked').val() == "on"){
            $("input[name=orAppApplierBirthAddr]").val($("input[name=memAddr]").val());
            $("input[name=orAppApplierBirthPhone]").val($("input[name=memPhone]").val());
            $(".orAppApplierBirthAddrPostCode").val($(".memPostCode").val());
        }else{
            $("input[name=orAppApplierBirthAddr]").val('');
            $("input[name=orAppApplierBirthPhone]").val('');
        }
    })

    $("input[name=sameofapplier_1]").change(function(){
        if($('input[name=sameofapplier_1]:checked').val() == "on"){
            $("input[name=orReceiveName]").val($("input[name=memName]").val());
            $("input[name=orReceiveAddr]").val($("input[name=memAddr]").val());
            $("input[name=orReceivePhone]").val($("input[name=memPhone]").val());
            $("input[name=orReceiveCell]").val($("input[name=memCell]").val());
        }else{
            $("input[name=orReceiveName]").val('');
            $("input[name=orReceiveAddr]").val('');
            $("input[name=orReceivePhone]").val('');
            $("input[name=orReceiveCell]").val('');
        }
    })
    $("input[name=sameofapplier_2]").change(function(){
        if($('input[name=sameofapplier_2]:checked').val() == "on"){
            $("input[name=orReceiveName]").val($("input[name=memName]").val());
            $("input[name=orReceiveAddr]").val($("input[name=orAppApplierBirthAddr]").val());
            $("input[name=orReceivePhone]").val($("input[name=orAppApplierBirthPhone]").val());
            $("input[name=orReceiveCell]").val($("input[name=memCell]").val());
        }else{
            $("input[name=orReceiveName]").val('');
            $("input[name=orReceiveAddr]").val('');
            $("input[name=orReceivePhone]").val('');
            $("input[name=orReceiveCell]").val('');
        }
    })


    $(".city").change(function(){
        if($(".city").val() != ""){
            $("input[name=orAppApplierBirthAddr]").val($(".city").val());
        }
    })
    $(".county").change(function(){
        if($(".county").val() != ""){
            var NewArray = new Array();
            var NewArray = $(".county").val().split(" ");
            $("input[name=orAppApplierBirthAddr]").val($(".city").val()+NewArray[0]);
            $(".orAppApplierBirthAddrPostCode").val(NewArray[1]);
        }
    })
    $("#city").change(function(){
        if($("#city").val() != ""){
            $("input[name=memAddr]").val($("#city").val());
        }
    })
    $("#county").change(function(){
        if($("#county").val() != ""){
            var NewArray = new Array();
            var NewArray = $("#county").val().split(" ");
            $("input[name=memAddr]").val($("#city").val()+NewArray[0]);
            $(".memPostCode").val(NewArray[1]);
        }
    })
    function checkAllContact(){
        var errg = 0;
        if(checkname($("input[name=orAppContactRelaName]").val()) && checkname($("input[name=orAppContactFrdName]").val())){
            errg +=0;
        }else{
            alert('您輸入的親屬或朋友中文姓名不正確');
            errg +=1;
        }

        if(checkPhone4($("input[name=orAppContactRelaCell]").val())  && checkPhone4($("input[name=orAppContactFrdCell]").val())){
            errg +=0;
        }else{
            errg +=1;
        }

        if($("input[name=orAppContactRelaCell]").val() != $("input[name=orAppContactFrdCell]").val() && $("input[name=memCell]").val() != $("input[name=orAppContactFrdCell]").val() && $("input[name=memCell]").val() != $("input[name=orAppContactRelaCell]").val() ){
            errg +=0;
        }else{
            alert('申請人、親屬、朋友不可為同一手機號碼');
            errg +=1;
        }

        if(errg >=1){
            return false
        }else{
            return true;
        }
    }
    function checkTwID(id){
        //建立字母分數陣列(A~Z)
        var city = new Array(
            1,10,19,28,37,46,55,64,39,73,82, 2,11,
            20,48,29,38,47,56,65,74,83,21, 3,12,30
        )
        id = id.toUpperCase();
        // 使用「正規表達式」檢驗格式
        if (id.search(/^[A-Z](1|2)\d{8}$/i) == -1) {
            alert('身分證字號錯誤錯誤');
            return false;
        } else {
            //將字串分割為陣列(IE必需這麼做才不會出錯)
            id = id.split('');
            //計算總分
            var total = city[id[0].charCodeAt(0)-65];
            for(var i=1; i<=8; i++){
                total += eval(id[i]) * (9 - i);
            }
            //補上檢查碼(最後一碼)
            total += eval(id[9]);
            //檢查比對碼(餘數應為0);
            if(total%10 == 0 ){
                return true;
            }else{
                alert('身分證字號錯誤');
            }
        }
    }
    function checkPhone(strPhone) {
        var phoneRegNoArea = /^(0\d{1,2})-(\d{6,8})$/;
        var prompt = "您輸入的戶籍市話號碼不正確!"
        if(phoneRegNoArea.test(strPhone) ){
            return true;
        }else{
            alert( prompt );
            return false;
        }
    }
    function checkPhone1(strPhone) {
        var phoneRegNoArea = /^(0\d{1,2})-(\d{6,8})$/;
        var prompt = "您輸入的現住市話號碼不正確!"
        if(phoneRegNoArea.test(strPhone) ){
            return true;
        }else{
            alert( prompt );
            return false;
        }
    }
    function checkPhone2(strPhone) {
        var phoneRegWithArea = /^09[0-9]{8}$/;
        var phoneRegNoArea = /^(0\d+)-(\d{8})$/;
        var prompt = "您輸入的手機號碼不正確!";
        if( strPhone.length > 9  && strPhone.length <= 10) {
            if( phoneRegWithArea.test(strPhone) ){
                return true;
            }else{
                alert( prompt );
                return false;
            }
        }else{
            alert( prompt );
            return false;

        }
    }
    function checkPhone4(strPhone) {
        var phoneRegWithArea = /^09[0-9]{8}$/;
        var prompt = "您輸入的親屬或朋友手機號碼不正確";
        if( strPhone.length > 9  && strPhone.length <= 10) {
            if( phoneRegWithArea.test(strPhone) ){
                return true;
            }else{
                alert( prompt );
                return false;
            }
        }else{
            alert( prompt );
            return false;

        }

    }
    function checkname(strname) {
        var check_name = /[^\u3447-\uFA29]/ig;
        if(strname.match(/[^\u3447-\uFA29]/ig)){
            alert('請輸入中文姓名');
            return false;
        }else{
            return true;
        }
    }
    function checkDate(year,month,day){
        y = parseInt(year) + 1911;
        var dt1 = new Date(y, month, day);
        var dt2 = new Date();
        diff = (((dt2-dt1)/(1000*60*60*24))/365);
        if(diff >= 17.931){
            return true;
        }else{
            alert('未滿18歲不能申請');
            return false;
        }
    }

</script>

<!-- 2 -->
<script>
    $("#next").click(function(){
        if($("#check2").prop("checked")){
            if($("#check4").is(":checked")){
                $.ajax({
                    url: 'php/order_check_file.php',
                    data: $('#order_add').serialize(),
                    type:"POST",
                    dataType:'text',
                    success: function(msg){
                        if(msg == 1){
                            location.href='index.php?item=member_center&action=order_period&method=3';
                        }else{
                            alert(msg);
                        }
                    },

                    error:function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status);
                        alert(thrownError);
                    }
                });
            }else{
                alert("請勾選同意條款");
            }
        }else{
            alert("請勾選同意條款");
        }
    });
    $("#next_1").click(function(){
        if($("input[name='check']:checked").length == 1 && $("input[name='check3']:checked").length == 1 && $("input[name='check4']:checked").length == 1){
            $.ajax({
                url: 'php/order_check_file.php',
                data: $('#order_add').serialize(),
                type:"POST",
                dataType:'text',
                success: function(msg){
                    if(msg == 1){
                        location.href='index.php?item=member_center&action=order_period&method=3';
                    }else{
                        alert(msg);
                    }
                },

                error:function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }else{
            alert("請勾選同意條款及父母同意確認購買此商品");
        }
    });
    $(function () {
        var bar = $('.bar');
        var percent = $('.percent');
        var showimg = $('#showimg');
        var progress = $(".progress");
        var files = $(".files");
        var btn = $(".btn span");
        $("#fileupload").wrap("<form id='myupload' action='php/file.php' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload").change(function(){
            $("#myupload").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    showimg.empty();
                    progress.show();
                    var percentVal = '0%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                    btn.html("上傳中...");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar.width(percentVal);
                    percent.html(percentVal);
                },
                success: function(data) {
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg.html("<img src='"+img+"'>");
                        btn.html("上傳檔案");
                    }else{
                        btn.html("上傳失敗");
                        bar.width('0')
                        files.html(xhr.responseText);
                    }
                },
                error:function(xhr){
                    btn.html("上傳失敗");
                    bar.width('0')
                    files.html(xhr.responseText);
                }
            });
        });

        var bar_1 = $('.bar_1');
        var percent_1 = $('.percent_1');
        var showimg_1 = $('#showimg_1');
        var progress_1 = $(".progress_1");
        var files_1 = $(".files_1");
        var btn_1 = $(".btn_1 span");
        $("#fileupload_1").wrap("<form id='myupload_1' action='php/file_1.php' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload_1").change(function(){
            $("#myupload_1").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    showimg_1.empty();
                    progress_1.show();
                    var percentVal = '0%';
                    bar_1.width(percentVal);
                    percent_1.html(percentVal);
                    btn_1.html("上傳中...");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar_1.width(percentVal);
                    percent_1.html(percentVal);
                },
                success: function(data) {
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg_1.html("<img src='"+img+"'>");
                        btn_1.html("上傳檔案");
                    }else{
                        btn_1.html("上傳失敗");
                        bar_1.width('0')
                        files_1.html(xhr.responseText);
                    }
                },
                error:function(xhr){
                    btn_1.html("上傳失敗");
                    bar_1.width('0')
                    files_1.html(xhr.responseText);
                }
            });
        });
        var bar_2 = $('.bar_2');
        var percent_2 = $('.percent_2');
        var showimg_2 = $('#showimg_2');
        var progress_2 = $(".progress_2");
        var files_2 = $(".files_2");
        var btn_2 = $(".btn_2 span");
        $("#fileupload_2").wrap("<form id='myupload_2' action='php/file_2.php' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload_2").change(function(){
            $("#myupload_2").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    showimg_2.empty();
                    progress_2.show();
                    var percentVal = '0%';
                    bar_2.width(percentVal);
                    percent_2.html(percentVal);
                    btn_2.html("上傳中...");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar_2.width(percentVal);
                    percent_2.html(percentVal);
                },
                success: function(data) {
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg_2.html("<img src='"+img+"'>");
                        btn_2.html("上傳檔案");
                    }else{
                        btn_2.html("上傳失敗");
                        bar_2.width('0')
                        files_2.html(xhr.responseText);
                    }
                },
                error:function(xhr){
                    btn_2.html("上傳失敗");
                    bar_2.width('0')
                    files_2.html(xhr.responseText);
                }
            });
        });
        var bar_3 = $('.bar_3');
        var percent_3 = $('.percent_3');
        var showimg_3 = $('#showimg_3');
        var progress_3 = $(".progress_3");
        var files_3 = $(".files_3");
        var btn_3 = $(".btn_3 span");
        $("#fileupload_3").wrap("<form id='myupload_3' action='php/file_3.php' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload_3").change(function(){
            $("#myupload_3").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    showimg_3.empty();
                    progress_3.show();
                    var percentVal = '0%';
                    bar_3.width(percentVal);
                    percent_3.html(percentVal);
                    btn_3.html("上傳中...");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar_3.width(percentVal);
                    percent_3.html(percentVal);
                },
                success: function(data) {
                    if(data.pic != ''){
                        var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                        showimg_3.html("<img src='"+img+"'>");
                        btn_3.html("上傳檔案");
                    }else{
                        btn_3.html("上傳失敗");
                        bar_3.width('0')
                        files_3.html(xhr.responseText);
                    }
                },
                error:function(xhr){
                    btn_3.html("上傳失敗");
                    bar_3.width('0')
                    files_3.html(xhr.responseText);
                }
            });
        });
        var bar_4 = $('.bar_4');
        var percent_4 = $('.percent_4');
        var showimg_4 = $('#showimg_4');
        var progress_4 = $(".progress_4");
        var files_4 = $(".files_4");
        var btn_4 = $(".btn_4 span");
        $("#fileupload_4").wrap("<form id='myupload_4' action='php/file_4.php' method='post' enctype='multipart/form-data'></form>");
        $("#fileupload_4").change(function(){
            $("#myupload_4").ajaxSubmit({
                dataType:  'json',
                beforeSend: function() {
                    showimg_4.empty();
                    progress_4.show();
                    var percentVal = '0%';
                    bar_4.width(percentVal);
                    percent_4.html(percentVal);
                    btn_4.html("上傳中...");
                },
                uploadProgress: function(event, position, total, percentComplete) {
                    var percentVal = percentComplete + '%';
                    bar_4.width(percentVal);
                    percent_4.html(percentVal);
                },
                success: function(data) {
                    var img = "https://happyfan7.com/admin/file/<?php echo $memberData[0]['memNo'];?>/"+data.pic;
                    showimg_4.html("<img src='"+img+"'>");
                    btn_4.html("上傳檔案");
                },
                error:function(xhr){
                    btn_4.html("上傳失敗");
                    bar_4.width('0')
                    files_4.html(xhr.responseText);
                }
            });
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $('#upload').click(function(){
            $("#colors_sketch").data("jqScribble").save(function(imageData){

                $.post('php/file_5.php', {imagedata: imageData}, function(response){
                    $('#upload button').html('簽名完成');
                    $('#upload button').prop("disabled", true);
                    $('#orAppAuthenProvement').hide();
                });
            });
        });

        $('#upload_1').click(function(){
            $("#colors_sketch_1").data("jqScribble").save(function(imageData){

                $.post('php/file_6.php', {imagedata: imageData}, function(response){
                    $('#upload_1 button').html('簽名完成');
                    $('#upload_1 button').prop("disabled", true);
                    $('#orAppAuthenPromiseLetter').hide();

                });
            });
        });
        $('#upload_2').click(function(){
            var canvasData_2 = colors_sketch_2.toDataURL("image/png");
            var ajax = new XMLHttpRequest();
            ajax.open("POST",'php/file_7.php',false);
            ajax.setRequestHeader('Content-Type', 'application/upload');
            ajax.send(canvasData_2);
            $('#upload_2 button').html('上傳成功');
        });
    });
</script>

<!--3-->
<script>
    $(".next-btn").click(function(){
        $(".next-btn").hide();
        $.ajax({
            url: 'php/order_finish.php',
            data: "member_data=11",
            type:"POST",
            dataType:'text',
            success: function(msg){
                if(msg){
                    alert('購買完成，請等候電話照會');
                    location.href='index.php?item=member_center&action=order_period&method=4';
                    thankButton();
                }else{
                    alert('系統操作錯誤');
                    $(".next-btn").show();
                }
            },

            error:function(xhr, ajaxOptions, thrownError){
                alert(xhr.status);
                alert(thrownError);
                $(".next-btn").show();
            }
        });
    })

    $(".next").click(function(){
        location.href='index.php?item=member_center&action=order_period&method=auto';
    })
</script>