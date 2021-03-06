<style type="text/css">
    .creditcard {
        margin: 0;
        padding:0 2px;
    }
    .creditcard input {
        margin:0;
        padding:0 2px;
        text-align: center;
    }
</style>
<script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="portal/assets/js/select/chosen.css">
<script src="portal/assets/js/aj-address.js" type="text/javascript"></script>
<script type="text/javascript">
    $(function () {
        $('.address-zone').ajaddress({ city: "請選擇", county: "請選擇" });
        if($(".memClass").val() == '0'){
            $(".memSchool").attr('disabled',false);
            $(".SchoolEmail").attr('disabled',false);
            $(".department").attr('disabled',false);
            // $(".chosen-container").show();
            $(".memAccount").show();
        }else{
            $(".memSchool").attr('disabled',true);
            $(".SchoolEmail").attr('disabled',true);
            $(".department").attr('disabled',true);
            // $(".chosen-container").hide();
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
        <form action="portal/Controllers/php/order_check.php" method="POST" id="order_add" enctype="multipart/form-data">
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
//                $imgArr = getAllImgs();
//                $imgArr = isset($imgArr) ?$imgArr: [];

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
                                    <input type="text" class="form-control memName" id="CName" name="memName" value="<?php echo $memberData[0]["memName"]; ?>" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="IdentKind" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身分別</label>
                                <div class="col-sm-9">
                                    <select class="form-control memClass" id="IdentKind" name="memClass" >
                                        <option value="0" <?php echo ($memberData[0]['memClass'] == 0) ? "selected":""; ?> >學生</option>
                                        <option value="4" <?php echo ($memberData[0]['memClass'] == 4) ? "selected":""; ?> >非學生</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="memSchool" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學籍資訊</label>
                                <div class="col-sm-9">
                                    <div class="form-group row">
                                        <p class="col-3 ">學校</p>
                                        <div class="col-9 mb-3">
                                            <select class="input form-control school memSchool" id="memSchool" name="school" required>
                                                <option value="">請選擇</option>
                                                <?php foreach($school_data as $keye => $valuee){ ?>
                                                    <option value="<?php echo $valuee['schNo'];?>"><?php echo $valuee['schName'];?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <p class="col-3 ">系所</p>
                                        <div class="col-9 mb-3">
                                            <select class="input form-control department memSchool" name="department[]" id="school_dept" required>
                                                <option value="">請選擇</option>
                                                <?php foreach($major_combine as $key => $value){ ?>
                                                    <optgroup class="departmentList" label="科系列表" data-id="school<?php echo $key; ?>">
                                                    <?php foreach($value as $k => $v){ ?>
                                                        <option value="<?php echo $v ?>"><?php echo $v; ?></option>
                                                    <?php } ?>
                                                    </optgroup>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <p class="col-3 ">年級</p>
                                        <div class="col-9 mb-3">
                                            <select class="form-control memSchool" name="classyear" required>
                                                <option value="">請選擇</option>
                                                <option value="一年級" <?php echo ($School[2]=="一年級") ? "selected":""; ?>>一年級</option>
                                                <option value="二年級" <?php echo ($School[2]=="二年級") ? "selected":""; ?>>二年級</option>
                                                <option value="三年級" <?php echo ($School[2]=="三年級") ? "selected":""; ?>>三年級</option>
                                                <option value="四年級" <?php echo ($School[2]=="四年級") ? "selected":""; ?>>四年級</option>
                                                <option value="五年級" <?php echo ($School[2]=="五年級") ? "selected":""; ?>>五年級</option>
                                                <option value="六年級" <?php echo ($School[2]=="六年級") ? "selected":""; ?>>六年級</option>
                                                <option value="七年級" <?php echo ($School[2]=="七年級") ? "selected":""; ?>>七年級</option>
                                                <option value="碩一" <?php echo ($School[2]=="碩一") ? "selected":""; ?>>碩一</option>
                                                <option value="碩二" <?php echo ($School[2]=="碩二") ? "selected":""; ?>>碩二</option>
                                                <option value="碩三" <?php echo ($School[2]=="碩三") ? "selected":""; ?>>碩三</option>
                                                <option value="博一" <?php echo ($School[2]=="博一") ? "selected":""; ?>>博一</option>
                                                <option value="博二" <?php echo ($School[2]=="博二") ? "selected":""; ?>>博二</option>
                                                <option value="博三" <?php echo ($School[2]=="博三") ? "selected":""; ?>>博三</option>
                                                <option value="博四" <?php echo ($School[2]=="博四") ? "selected":""; ?>>博四</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="SchoolEmail" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學校Email</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control SchoolEmail" id="SchoolEmail" name="memAccount" value="<?php echo $memberData[0]["memAccount"]; ?>" disabled />
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="EmailAddress" class="col-sm-3 col-form-label"><span class="text-orange">*</span>常用Email</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control memSubEmail" id="EmailAddress" name="memSubEmail" value="<?php
                                    if($memberData[0]['memClass'] != '0' && $memberData[0]['memFBtoken'] == ""){
                                        echo $memberData[0]["memAccount"];
                                    }else{
                                        echo $memberData[0]["memSubEmail"];
                                    }
                                    ?>" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="NowTelephone" class="col-sm-3 col-form-label"><span class="text-orange">*</span>現住電話</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control memPhone" id="NowTelephone" name="memPhone" value="<?php echo $memberData[0]['memPhone'] ?>" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="HomeTelephone" class="col-sm-3 col-form-label"><span class="text-orange">*</span>戶籍電話</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control orAppApplierBirthPhone" id="HomeTelephone" name="orAppApplierBirthPhone" >
                                    <div class="float-right m-1">
                                        <input class="form-check-input" type="checkbox" id="SameForNowTelephone" name="SameForNowTelephone">
                                        <label class="form-check-label" for="SameForNowTelephone">同現住電話</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Mobile" class="col-sm-3 col-form-label"><span class="text-orange">*</span>行動電話</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control memCell" id="Mobile" name="memCell" value="<?php echo $memberData[0]['memCell'] ?>" >
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
                                    <input id="fileupload" type="file" name="mypic" required >
                                </div>
                                <!-- <div class="progress">
                                    <span class="bar"></span><span class="percent">0%</span >
                                </div> -->
                                <div class="files"></div>
                                <div id="showimg">
                                    <?php
                                    if ($or_data[0]['orAppAuthenIdImgTop'] != ""){
                                    ?>
                                        <script type="text/javascript">
                                            $("[name='mypic']").removeAttr("required");
                                        </script>
                                        <img src='<?php echo $or_data[0]['orAppAuthenIdImgTop']; ?>' style='width: 400px' />
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人身分證反面</label>
                                <div class="col-sm-9">
                                    <input id="fileupload_1" type="file" name="mypic_1" required >
                                </div>
                                <!-- <div class="progress_1">
                                    <span class="bar_1"></span><span class="percent_1">0%</span >
                                </div> -->
                                <div class="files_1"></div>
                                <div id="showimg_1">
                                    <?php
                                    if ($or_data[0]['orAppAuthenIdImgBot'] != ""){
                                    ?>
                                        <script type="text/javascript">
                                            $("[name='mypic_1']").removeAttr("required");
                                        </script>
                                        <img src='<?php echo $or_data[0]['orAppAuthenIdImgBot']; ?>' style='width: 400px' />
                                    <?php
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="IdentNumber" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身份證字號</label>
                                <div class="col-sm-9">
                                    <input type="text"  class="form-control memIdNum" id="IdentNumber" name="memIdNum" value="<?php echo $memberData[0]["memIdNum"]; ?>">
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
                                            $year = date('Y')-1911;
                                            for($i=$year-70;$i<=$year;$i++){ ?>
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
                                        <select class="custom-select mb-3" name="orIdIssueYear" id="orIdIssueYear">
                                            <?php
                                            $year = date('Y')-1911;
                                            for($i=$year-70;$i<=$year;$i++){ ?>
                                                <option value="<?=$i ?>"><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>100</option>-->
<!--                                            <option value="">20</option>-->
<!--                                            <option value="">21</option>-->
                                        </select>
                                        年
                                        <select class="custom-select mb-3" name="orIdIssueMonth" id="orIdIssueMonth">
                                            <?php
                                            for($i=1;$i<=12;$i++){ ?>
                                                <option value="<?=$i ?>"><?=$i?></option>
                                            <?php } ?>
<!--                                            <option selected>01</option>-->
<!--                                            <option value="">02</option>-->
<!--                                            <option value=2">03</option>-->
                                        </select>
                                        月
                                        <select class="custom-select mb-3" name="orIdIssueDay" id="orIdIssueDay">
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
                                <label for="orIdIssuePlace" class="col-sm-3 col-form-label"><span class="text-orange">*</span>發證地點</label>
                                <div class="col-sm-9">
                                    <select class="input form-control" id="orIdIssuePlace" name="orIdIssuePlace" required >
                                        <option value="">請選擇</option>
                                        <?php
                                        foreach($IdPlace as $key => $value){
                                            ?>
                                            <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orIdIssueType" class="col-sm-3 col-form-label"><span class="text-orange">*</span>換補發類別</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="orIdIssueType" name="orIdIssueType" required >
                                        <option value="初發" selected>初發</option>
                                        <option value="補發">補發</option>
                                        <option value="換發">換發</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orAppApplierBirthAddrPostCode" class="col-sm-3 col-form-label"><span class="text-orange">*</span>戶籍地址</label>
                                <div class="col-sm-9">
                                    <div class="row address-zone">
                                        <div class="col-4 mb-3">
                                            <input type="text" class="form-control " id="orAppApplierBirthAddrPostCode" name="orAppApplierBirthAddrPostCode" value="">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control city" name="orAppApplierBirthCity" required >
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control county" name="orAppApplierBirthTown" required >
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" required class="form-control orAppApplierBirthAddr" id="orAppApplierBirthAddr" name="orAppApplierBirthAddr" value="" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="memAddr" class="col-sm-3 col-form-label"><span class="text-orange">*</span>現住地址</label>
                                <div class="col-sm-9">
                                    <div class="row address-zone">
                                        <div class="col-4 mb-3">
                                            <input type="text" name="memPostCode" class="form-control memPostCode" value="<?php echo $memberData['0']['memPostCode'];?>"  />
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control city" name="memCity" required >
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <select class="form-control county" name="memTown" required >
                                                <option value="">請選擇</option>
                                            </select>
                                        </div>
                                    </div>
                                    <input type="text" required class="form-control memAddr" id="memAddr" name="memAddr" value="<?php echo $memberData[0]['memAddr'] ?>" >
                                    <div class="float-right m-1">
                                        <input class="form-check-input" type="checkbox" id="SameForNowAddr" name="SameForNowAddr">
                                        <label class="form-check-label" for="SameForNowAddr" >同戶籍地址</label>
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
                                        <input class="form-check-input" type="radio" name="orAppApplierCompanystatus" id="JobStatus1" value="1">
                                        <label class="form-check-label" for="JobStatus1">有</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="orAppApplierCompanystatus" id="JobStatus2" value="0" checked>
                                        <label class="form-check-label" for="JobStatus2">無</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row  orAppApplierCompany">
                                <label for="CompanyName" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司名稱</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CompanyName" name="orAppApplierCompanyName" value="">
                                </div>
                            </div>
                            <div class="form-group row  orAppApplierCompany">
                                <label for="JobYear" class="col-sm-3 col-form-label"><span class="text-orange"></span>年資</label>
                                <div class="col-sm-9">
                                    <select name="orAppApplierYearExperience" class="input form-control" id="JobYear" required>
                                        <option value="">請選擇</option>
                                        <option value="半年以下">半年以下</option>
                                        <option value="半年到一年">半年到一年</option>
                                        <option value="一年">一年</option>
                                        <option value="兩年">兩年</option>
                                        <option value="三年">三年</option>
                                        <option value="三年以上">三年以上</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row  orAppApplierCompany">
                                <label for="Salary" class="col-sm-3 col-form-label "><span class="text-orange"></span>月薪</label>
                                <div class="col-sm-9">
                                    <select name="orAppApplierMonthSalary" class="input form-control" id="Salary" required>
                                        <option value="">請選擇</option>
                                        <option value="0-5000">0-5000</option>
                                        <option value="5000-10000">5000-10000</option>
                                        <option value="10000-20000">10000-20000</option>
                                        <option value="20000-30000">20000-30000</option>
                                        <option value="30000-40000">30000-40000</option>
                                        <option value="50000以上">50000以上</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row  orAppApplierCompany">
                                <label for="ComPhone" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="ComPhone" name="orAppApplierCompanyPhone">
                                </div>
                            </div>
                            <div class="form-group row  orAppApplierCompany">
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
                                <div class="col-sm-9" id="orAppApplierCreditstatus">
                                    <div class="row">
                                        <div class="col-3 creditcard"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_1" value=""></div>
                                        <div class="col-3 creditcard"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_2" value=""></div>
                                        <div class="col-3 creditcard"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_3" value=""></div>
                                        <div class="col-3 creditcard"><input type="text" class="form-control" maxlength='4' name="orAppApplierCreditNum_4" value=""></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="orAppApplierCreditIssueBank" class="col-sm-3 col-form-label"><span class="text-orange"></span>發卡銀行</label>
                                <div class="col-sm-9" >
                                    <input type="text" class="form-control" id="orAppApplierCreditIssueBank" name="orAppApplierCreditIssueBank" value="">
                                </div>
                            </div>
<!--                            <div class="form-group row">-->
<!--                                <label for="ComPhone" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司市話</label>-->
<!--                                <div class="col-sm-9">-->
<!--                                    <input type="text" class="form-control" id="ComPhone" name="orAppApplierCompanyPhone" value="">-->
<!--                                </div>-->
<!--                            </div>-->
                            <div class="form-group row">
                                <label for="ComExtension" class="col-sm-3 col-form-label"><span class="text-orange"></span>信用卡有效期限</label>
                                <div class="col-sm-9 ComExtension">
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
                                            <?php
                                            for($i=date('Y');$i<=date('Y')+32;$i++){ ?>
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
                                <label for="orReceiveName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" required class="form-control" id="orReceiveName" name="orReceiveName" value="" >
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
                            <div class="form-group row address-zone">
                                <label for="orReceiveAddr" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人地址</label>
                                <div class="col-sm-9">
<!--                                    <div class="row address-zone">-->
<!--                                        <div class="col-4 mb-3">-->
<!--                                            <input type="text" class="form-control" id="orReceiveAddrCode" name="orReceiveAddrCode">-->
<!--                                        </div>-->
<!--                                        <div class="col-4 mb-3">-->
<!--                                            <select class="form-control city" id="city" name="orReceiveCity">-->
<!--                                                <option value="">請選擇</option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                        <div class="col-4 mb-3">-->
<!--                                            <select class="form-control county" id="county" name="orReceiveTown">-->
<!--                                                <option value="">請選擇</option>-->
<!--                                            </select>-->
<!--                                        </div>-->
<!--                                    </div>-->
                                    <input type="text" required class="form-control orReceiveAddr" id="orReceiveAddr" name="orReceiveAddr" value="" >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="orReceivePhone" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="orReceivePhone" name="orReceivePhone" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orReceivePhone") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>" required >
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="orReceiveCell" class="col-sm-3 col-form-label"><span class="text-orange">*</span>收貨人手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="orReceiveCell" name="orReceiveCell" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orReceiveCell") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>" required >
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="exampleFormControlTextarea1" class="col-sm-3 col-form-label"><span class="text-orange"></span>收貨備註</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="orReceiveComment"><?php
                                        foreach($columnName as $key=>$value){
                                            //只顯示
                                            if($value["COLUMN_NAME"] == "orReceiveComment") {
                                                echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                            }
                                        }
                                        ?></textarea>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="BusinessNum" class="col-sm-3 col-form-label"><span class="text-orange"></span>是否需要統一編號</label>
                                <div class="col-sm-9" id="BusinessNum">
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
                                <label for="orBusinessNumNumber" class="col-sm-3 col-form-label"><span class="text-orange"></span>統一編號</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="orBusinessNumNumber" name="orBusinessNumNumber" value=
                                    "<?php
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
                                <label for="a10" class="col-sm-3 col-form-label"><span class="text-orange"></span>公司抬頭</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="orBusinessNumTitle" name="orBusinessNumTitle" value=
                                    "<?php
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
                                <label for="a9" class="col-sm-3 col-form-label"><span class="text-orange">*</span>親屬姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a9" name="orAppContactRelaName" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactRelaName") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a8" class="col-sm-3 col-form-label"><span class="text-orange">*</span>親屬關係</label>
                                <div class="col-sm-9">
                                    <select name="orAppContactRelaRelation" class="input form-control" id="a8" required >
                                        <?php
                                        foreach($orAppContactRelaRelation as $key => $value){
                                            $select = ($or_data[0]['orAppContactRelaRelation'] == $key) ? 'selected':'';
                                            echo "<option value='".$key."'".$select.">".$value."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a7" class="col-sm-3 col-form-label"><span class="text-orange"></span>親屬市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a7" name="orAppContactRelaPhone" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactRelaPhone") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a6-1" class="col-sm-3 col-form-label"><span class="text-orange">*</span>親屬手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a6-1" name="orAppContactRelaCell" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactRelaCell") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>" required >
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="a6" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a6" name="orAppContactFrdName" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactFrdName") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>" required >
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a5" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友關係</label>
                                <div class="col-sm-9">
                                    <select name="orAppContactFrdRelation" class="input form-control" id="a5" required >
                                        <?php
                                        foreach($orAppContactFrdRelation as $key => $value){
                                            $select = ($or_data[0]['orAppContactRelaRelation'] == $key) ? 'selected':'';
                                            echo "<option value='".$key."'".$select.">".$value."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="a4" class="col-sm-3 col-form-label"><span class="text-orange"></span>朋友市話</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a4" name="orAppContactFrdPhone" value=
                                    "<?php
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
                                <label for="a3" class="col-sm-3 col-form-label"><span class="text-orange">*</span>朋友手機</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a3" name="orAppContactFrdCell" value=
                                    "<?php
                                    foreach($columnName as $key=>$value){
                                        //只顯示
                                        if($value["COLUMN_NAME"] == "orAppContactFrdCell") {
                                            echo ($orData[0][$value["COLUMN_NAME"]]);   break;
                                        }
                                    }
                                    ?>" required >
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
                                <label for="a2" class="col-sm-3 col-form-label"><span class="text-orange"></span>可照會時間</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="a2" name="orAppExtraAvailTime" value=
                                    "<?php
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
                                <label for="a1" class="col-sm-3 col-form-label"><span class="text-orange"></span>注意事項</label>
                                <div class="col-sm-9 CreditBank" >
                                    <input type="text" class="form-control" id="a1" name="orAppExtraInfo" value=
                                    "<?php
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
                    <input class="form-check-input" type="checkbox" name="agree" id="ddd" value="1">
                    <label class="form-check-label" for="ddd">
                        申請案件如需保密請打勾（照會親友聯絡人時，不告知購買事由）
<!--                        <a class="text-orange secure" title="甚麼是保密照會？" href="#">甚麼是保密照會？</a>-->
                    </label>
                </div>
                <div class="section-staging">
                    <div class="form-group form-btn text-center">
                        <a class="btn btn-next bg-yellow next-btn">下一步</a>
                        <input class="btn btn-next bg-yellow next-btn2" type="submit" value="下一步" style="display: none"/>
                    </div>
                </div>
            </div>
        </form>
    </section>
</main>


<script src="portal/assets/js/select/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">

    //目前還沒看到這有甚麼用
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    };
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }

    //解釋甚麼是保密照會？
    $(".secure").click(function(){
        alert("若你不希望親友知道可以勾選第一步驟申請書姓名前面的保密，勾選之後還是會打電話但不會告知有購物，她們只會接到類似行銷電話確認身份而已。");
    });

    $(".department").attr('disabled',true);     //系別選擇
    $("#memSchool").on("change",function(){
        if(this.value!=""){
            $("#school_dept").val("").removeAttr("disabled");
            $(".departmentList").hide();
            $(".departmentList[data-id='school"+this.value+"']").show();
        }else{
            $("#school_dept").val("").attr("disabled","disabled");
            $(".departmentList").hide();
        }
    });
    $(".departmentList").hide();

    // $(".school").change(function(){
    //     var school = $(this).val();
    //     $(".department").attr('disabled',true);
    //     // $("#default").hide();
    //     $("#shool_"+school).attr('disabled',false);
    // });


    //工作狀態
    $('input[name=orAppApplierCompanyName]').attr('disabled',true);
    $('select[name=orAppApplierYearExperience]').attr('disabled',true);
    $('select[name=orAppApplierMonthSalary]').attr('disabled',true);
    $('input[name=orAppApplierCompanyPhone]').attr('disabled',true);
    $('input[name=orAppApplierCompanyPhoneExt]').attr('disabled',true);


    $("#orAppApplierCreditstatus input").attr('disabled',true);    //持有信用卡
    $("#orAppApplierCreditIssueBank").attr('disabled',true);      //信用卡銀行
    $("#orBusinessNumNumber").attr("disabled","disabled");     //統一編號
    $("#orBusinessNumTitle").attr("disabled","disabled");     //公司抬頭
    $(".ComExtension select").attr("disabled","disabled");     //信用卡有效期限
    // $("#orBusinessNumTitle").hide();     //
    // $(".memother").hide();
    //身分別
    $(".memClass").change(function(){
        var memClass_val = $(this).val();

        // if(memClass_val == '3'){
        //     $(".memother").show();
        // }else{
        //     $(".memother").hide();
        // }

        // memClass=0 是學生
        if(memClass_val == '0'){
            $(".memSchool").attr('disabled',false);
            $("#SchoolEmail").attr('disabled',false);
            // $(".chosen-container").show();
            $(".memAccount").attr('disabled',false);
            $(".memAccount").attr('','');
        }else{
            $(".memSchool").attr('disabled',true);
            $("#SchoolEmail").attr('disabled',true);
            // $(".chosen-container").hide();
            // $("#default").hide();
            $(".memAccount").attr('disabled',true);
            $(".memAccount").attr('',false);
        }
    });

    //
    function OCR(api,fileData){
        var reader=new FileReader();
        reader.onloadend=function() {
            if(api=="front"){
                $("#showimg").html("<img style='width: 400px'>").find("img").attr("src",reader.result);
            }else if(api=="back") {
                $("#showimg_1").html("<img style='width: 400px'>").find("img").attr("src",reader.result);
            }
            var urlf="https://asia-northeast1-prod-nowait-shop.cloudfunctions.net/"+api+"-ocr?";
            $.ajax({
                method:"post",
                url:urlf+$.param({
                    "access_token":"8Mvxx_n3QKPrHX=D1254416AE954D390E153635C745A2947262A7B4F20C04E9CE2708070B6635EC@QEv!BkutgUSG*v%D"
                }),
                data:{
                    img:reader.result.replace("data:image/jpeg;base64,","")
                },
                dataType:"json",
                success:function(response){
                    if(api=="front"){
                        $("#IdentNumber").val(response.id);
                        $("[name='year']").val(response.birth_date.year);
                        $("[name='month']").val(response.birth_date.month);
                        $("[name='date']").val(response.birth_date.day);
                        $("[name='orIdIssuePlace']").val(response.apply_site);
                        $("#orIdIssueYear").val(response.apply_date.year);
                        $("#orIdIssueMonth").val(response.apply_date.month);
                        $("#orIdIssueDay").val(response.apply_date.day);
                        $("#orIdIssueType").val(response.apply_style);
                        // $("#CName").val(response.name);
                    }else if(api=="back"){
                        $("[name='orAppApplierBirthCity']").val(response.residential_parsed_addr.city);
                        $("[name='orAppApplierBirthTown']").val(response.residential_parsed_addr.district);
                        $("[name='orAppApplierBirthAddr']").val(
                            response.residential_parsed_addr.neighbor+
                            response.residential_parsed_addr.near+
                            response.residential_parsed_addr.street+
                            response.residential_parsed_addr.section+
                            response.residential_parsed_addr.lane+
                            response.residential_parsed_addr.alley+
                            response.residential_parsed_addr.no+
                            response.residential_parsed_addr.floor
                        );
                    }
                }
            });
        };
        reader.readAsDataURL(fileData);
    }
    $("#fileupload").on("change",function(){
        if(this.value!=""){
            OCR("front",this.files[0])
        }
    });
    $("#fileupload_1").on("change",function(){
        if(this.value!=""){
            OCR("back",this.files[0])
        }
    });


    //下一步
    $(".next-btn").click(function(e){
        if( $("input[name=memClass]").val()==='0' && $("input[name=memAccount]").val().indexOf("edu") === -1){
            alert("請填寫學校Email做為認證 ");
            return false;
        }
        if(checkname($("input[name=memName]").val()) &&
            checkTwID($("input[name=memIdNum]").val()) &&
            checkPhone2($("input[name=memCell]").val()) &&
            checkDate($("select[name=year]").val(),$("select[name=month]").val(),$("select[name=date]").val()) /*&&
            checkAllContact()*/ )
        {
            $(".next-btn2").click();
            // $.ajax({
            //     url: 'portal/Controllers/php/order_check.php',
            //     data: $('#order_add').serialize(),
            //     type: "POST",
            //     dataType: 'text',
            //     success: function(msg){
            //         if(msg){
            //             e.preventDefault();
            //             if(msg == "1"){
            //                 alert("請記得到會員中心->會員基本資料做認證信");
            //                 location.href = "index.php?item=member_center&action=order_period&method=2";
            //             }else if(msg == "2"){
            //                 location.href = "index.php?item=member_center&action=order_period&method=2";
            //             }else{
            //                 alert(msg);
            //             }
            //         }else{
            //             alert(msg);
            //         }
            //         return false;
            //     },
            //     error:function(xhr, ajaxOptions, thrownError){
            //         alert(xhr.status);
            //         alert(thrownError);
            //         return false;
            //     }
            // });
        }
        return false;
    });

    //工作狀態
    $("input[name=orAppApplierCompanystatus]").change(function(){
        if($('input[name=orAppApplierCompanystatus]:checked').val() == 1){
            $('input[name=orAppApplierCompanyName]').attr('disabled',false);
            $('select[name=orAppApplierYearExperience]').attr('disabled',false);
            $('select[name=orAppApplierMonthSalary]').attr('disabled',false);
            $('input[name=orAppApplierCompanyPhone]').attr('disabled',false);
            $('input[name=orAppApplierCompanyPhoneExt]').attr('disabled',false);
        }else{
            $('input[name=orAppApplierCompanyName]').attr('disabled',true);
            $('select[name=orAppApplierYearExperience]').attr('disabled',true);
            $('select[name=orAppApplierMonthSalary]').attr('disabled',true);
            $('input[name=orAppApplierCompanyPhone]').attr('disabled',true);
            $('input[name=orAppApplierCompanyPhoneExt]').attr('disabled',true);
        }
    });
    //是否持有信用卡
    $("input[name=orAppApplierCreditstatus]").change(function(){
        if($('input[name=orAppApplierCreditstatus]:checked').val() == 1){
            $("#orAppApplierCreditstatus input").attr('disabled',false);
            $("#orAppApplierCreditIssueBank").attr('disabled',false);
            $(".ComExtension select").attr("disabled",false);     //信用卡有效期限
        }else{
            $("#orAppApplierCreditstatus input").attr('disabled',true);
            $("#orAppApplierCreditIssueBank").attr('disabled',true);
            $(".ComExtension select").attr("disabled",true);     //信用卡有效期限
        }
    });
    //是否需要統一編號
    $("input[name=orBusinessNumIfNeed]").change(function(){
        if($('input[name=orBusinessNumIfNeed]:checked').val() == 1){
            $("#orBusinessNumNumber").removeAttr("disabled");
            $("#orBusinessNumTitle").removeAttr("disabled");
        }else{
            $("#orBusinessNumNumber").attr("disabled","disabled");
            $("#orBusinessNumTitle").attr("disabled","disabled");
        }
    });
    // checkbox:同現住電話
    $("input[name=SameForNowTelephone]").change(function(){
        if($('input[name=SameForNowTelephone]:checked').val() == "on"){
            $("input[name=orAppApplierBirthPhone]").val($("input[name=memPhone]").val());
        }else{
            $("input[name=orAppApplierBirthPhone]").val('');
        }
    });
    // checkbox:同現住地址
    $("input[name=SameForNowAddr]").change(function(){
        if($('input[name=SameForNowAddr]:checked').val() == "on"){
            
            $("input[name=memPostCode]").val($("input[name=orAppApplierBirthAddrPostCode]").val());
            $("select[name=memCity]").val($("select[name=orAppApplierBirthCity]").val()).trigger("change");
            $("select[name=memTown]").val($("select[name=orAppApplierBirthTown]").val());
            $("input[name=memAddr]").val($("input[name=orAppApplierBirthAddr]").val());
        }else{
            $("input[name=memAddr]").val('');
            $("input[name=memPostCode]").val('');
            $("select[name=memCity]").val('');
            $("select[name=memTown]").val('');
        }
    });
    //同申請人現住資料
    $("input[name=sameofapplier_1]").change(function(){
        if($('input[name=sameofapplier_1]:checked').val() == "on"){
            $("input[name=sameofapplier_2]").attr("checked",false);
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
    });
    //同申請人戶籍資料
    $("input[name=sameofapplier_2]").change(function(){
        if($('input[name=sameofapplier_2]:checked').val() == "on"){
            $("input[name=sameofapplier_1]").attr("checked",false);
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
    });
    //
    // $("input[name=sameofapplier_3]").change(function(){
    //     if($('input[name=sameofapplier_3]:checked').val() == "on"){
    //         $("input[name=orAppApplierBirthAddr]").val($("input[name=memAddr]").val());
    //         $("input[name=orAppApplierBirthPhone]").val($("input[name=memPhone]").val());
    //         $(".orAppApplierBirthAddrPostCode").val($(".memPostCode").val());
    //     }else{
    //         $("input[name=orAppApplierBirthAddr]").val('');
    //         $("input[name=orAppApplierBirthPhone]").val('');
    //     }
    // });
    // 收貨人 - checkbox:同現住地址
    $("input[name=SameForNowAddr2]").change(function(){
        if($('input[name=SameForNowAddr2]:checked').val() == "on"){
            $("input[name=orReceiveAddr]").val($("input[name=memAddr]").val());
            $("input[name=orReceiveAddrCode]").val($("input[name=memPostCode]").val());
        }else{
            $("input[name=orReceiveAddr]").val('');
            $("input[name=orReceiveAddrCode]").val('');
        }
    });



    $("[name='orAppApplierBirthCity']").change(function(){
        if($(this).val() != ""){
            $("input[name=orAppApplierBirthAddr]").val($(this).val());
        }
    });
    $("[name='orAppApplierBirthTown']").change(function(){
        if($(this).val() != ""){
            var NewArray = new Array();
            var NewArray = $(this).val().split(" ");
            $("input[name=orAppApplierBirthAddr]").val(NewArray[1]+$("select[name=orAppApplierBirthCity]").val()+NewArray[0]);
            $("[name='orAppApplierBirthAddrPostCode']").val(NewArray[1]);
        }
    });
    $("[name='memCity']").change(function(){
        if($(this).val() != ""){
            $("input[name=memAddr]").val($(this).val());
        }
    });
    $("[name='memTown']").change(function(){
        if($(this).val() != ""){
            var NewArray = new Array();
            var NewArray = $(this).val().split(" ");
            $("input[name=memAddr]").val(NewArray[1]+$("select[name=memCity]").val()+NewArray[0]);
            $("[name='memPostCode']").val(NewArray[1]);
        }
    });
    // $("#city").change(function(){
    //     if($("#city").val() != ""){
    //         $("input[name=memAddr]").val($("#city").val());
    //     }
    // });
    // $("#county").change(function(){
    //     if($("#county").val() != ""){
    //         var NewArray = new Array();
    //         var NewArray = $("#county").val().split(" ");
    //         $("input[name=memAddr]").val($("#city").val()+NewArray[0]);
    //         $(".memPostCode").val(NewArray[1]);
    //     }
    // });


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

        if($("input[name=orAppContactRelaCell]").val() != $("input[name=orAppContactFrdCell]").val() &&
            $("input[name=memCell]").val() != $("input[name=orAppContactFrdCell]").val() &&
            $("input[name=memCell]").val() != $("input[name=orAppContactRelaCell]").val()
        ){
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
        if( strPhone && strPhone.length > 9  && strPhone.length <= 10) {
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
        if( strPhone &&  strPhone.length > 9  && strPhone.length <= 10) {
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