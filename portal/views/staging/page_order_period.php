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
                                    <input type="text" class="form-control" id="NowTelephone" name="NowTelephone">
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
                                    <input type="text" class="form-control" id="Mobile" name="Mobile">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-sm-3 col-form-label"><span class="text-orange"></span>住房所有權</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HouseOwner" id="HouseOwner1" value="1">
                                        <label class="form-check-label" for="HouseOwner1">自有/配偶</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HouseOwner" id="HouseOwner2" value="2">
                                        <label class="form-check-label" for="HouseOwner2">父母/子女</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HouseOwner" id="HouseOwner3" value="3">
                                        <label class="form-check-label" for="HouseOwner3">親友</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HouseOwner" id="HouseOwner4" value="4">
                                        <label class="form-check-label" for="HouseOwner4">租賃</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HouseOwner" id="HouseOwner5" value="5">
                                        <label class="form-check-label" for="HouseOwner5">宿舍</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HouseOwner" id="HouseOwner6" value="6">
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
                                    <input type="file" class="form-control" id="customFile">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人身分證反面</label>
                                <div class="col-sm-9">
                                    <input type="file" class="form-control" id="customFile">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="IdentNumber" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身份證字號</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="IdentNumber" name="IdentNumber" value="F123456789" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>出生年月日</label>
                                <div class="col-sm-9">
                                    <div class="form-inline">
                                        民國
                                        <select class="custom-select mb-3">
                                            <option selected>100</option>
                                            <option value="">20</option>
                                            <option value="">21</option>
                                        </select>
                                        年
                                        <select class="custom-select mb-3">
                                            <option selected>01</option>
                                            <option value="">02</option>
                                            <option value=2">03</option>
                                        </select>
                                        月
                                        <select class="custom-select mb-3">
                                            <option selected>01</option>
                                            <option value="">02</option>
                                            <option value=2">03</option>
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
                                        <select class="custom-select mb-3">
                                            <option selected>100</option>
                                            <option value="">20</option>
                                            <option value="">21</option>
                                        </select>
                                        年
                                        <select class="custom-select mb-3">
                                            <option selected>01</option>
                                            <option value="">02</option>
                                            <option value=2">03</option>
                                        </select>
                                        月
                                        <select class="custom-select mb-3">
                                            <option selected>01</option>
                                            <option value="">02</option>
                                            <option value=2">03</option>
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
                                    <select class="form-control" id="IdentKind" name="IdentKind">
                                        <option selected>發證類別</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange"></span>換補發類別</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="IdentKind" name="IdentKind">
                                        <option selected>換補發類別</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>戶籍地址</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option selected>xxxx</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option selected>xxxx</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>現住地址</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option selected>xxxx</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option selected>xxxx</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                    <div class="float-right m-1">
                                        <input class="form-check-input" type="checkbox" id="SameForNowTelephone">
                                        <label class="form-check-label" for="SameForNowTelephone">同戶籍地址</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="section-staging bg-white">
                    <div class="section-order-title">工作</div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="JobStatus" class="col-sm-3 col-form-label"><span class="text-orange"></span>工作狀態</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="JobStatus" id="JobStatus1" value="1">
                                        <label class="form-check-label" for="JobStatus1">有</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="JobStatus" id="JobStatus2" value="2">
                                        <label class="form-check-label" for="JobStatus2">無</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CompanyName" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司名稱</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CompanyName" name="CompanyName">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="JobYear" class="col-sm-3 col-form-label"><span class="text-orange"></span>年資</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="JobYear" name="JobYear" placeholder="一年">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="Salary" class="col-sm-3 col-form-label"><span class="text-orange"></span>月薪</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="Salary" name="Salary" placeholder="100000">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComPhone" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComPhone" name="ComPhone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComExtension" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話分機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComExtension" name="ComExtension">
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
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HaveCredit" id="HaveCredit1" value="1">
                                        <label class="form-check-label" for="HaveCredit1">有</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HaveCredit" id="HaveCredit2" value="2">
                                        <label class="form-check-label" for="HaveCredit2">無</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditNo" class="col-sm-3 col-form-label"><span class="text-orange"></span>信用卡號</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-3"><input type="text" class="form-control"></div>
                                        <div class="col-3"><input type="text" class="form-control"></div>
                                        <div class="col-3"><input type="text" class="form-control"></div>
                                        <div class="col-3"><input type="text" class="form-control"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>發卡銀行</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComPhone" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComPhone" name="ComPhone">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="ComExtension" class="col-sm-3 col-form-label"><span class="text-orange"></span>信用卡有效期限</label>
                                <div class="col-sm-9">
                                    <div class="form-inline">
                                        <select class="custom-select mb-3">
                                            <option selected>01</option>
                                            <option value="">02</option>
                                            <option value=2">03</option>
                                        </select>
                                        月
                                        <select class="custom-select mb-3">
                                            <option selected>19</option>
                                            <option value="">20</option>
                                            <option value="">21</option>
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
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                    <div class="float-left m-1 mr-2">
                                        <input class="form-check-input" type="checkbox" id="SameForLive">
                                        <label class="form-check-label" for="SameForLive">同申請人現住資料</label>
                                    </div>
                                    <div class="float-left m-1 ml-3">
                                        <input class="form-check-input" type="checkbox" id="SameForRegistration">
                                        <label class="form-check-label" for="SameForRegistration">同申請人戶籍資料</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人地址</label>
                                <div class="col-sm-9">
                                    <div class="row">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option selected>xxxx</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control">
                                                <option selected>xxxx</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>收貨備註</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>是否需要統一編號</label>
                                <div class="col-sm-9">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HaveCredit" id="HaveCredit1" value="1">
                                        <label class="form-check-label" for="HaveCredit1">是</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="HaveCredit" id="HaveCredit2" value="2">
                                        <label class="form-check-label" for="HaveCredit2">否</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>統一編號</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司抬頭</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
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
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>親屬關係</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>親屬市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>親屬關係</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友關係</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
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
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CreditBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>注意事項</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CreditBank" name="CreditBank">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-check form-check-inline mt-4">
                    <input class="form-check-input" type="checkbox" name="HaveCredit" id="HaveCredit2" value="2">
                    <label class="form-check-label" for="HaveCredit2">申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）<a class="text-orange" href="#" title="甚麼是保密照會？">甚麼是保密照會？</a></label>
                </div>
                <div class="section-staging">
                    <div class="form-group form-btn text-center">
                        <a href="staging-2.htm" class="btn btn-next bg-yellow">下一步</a>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>


<script src="assets/js/select/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
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
</script>

<script>
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