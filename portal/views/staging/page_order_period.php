
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
                <p>申請人基本資料<span class="text-orange">*為必填欄位，請務必詳實填寫，如未滿20歲,需父母同意分期購買。</span></p>
                <div class="section-staging bg-white">
                    <div class="section-order-title">基本資料<span class="text-orange"> *基本資料請填寫完整，以增加審核速度與過案機會。</span></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group row">
                                <label for="CName" class="col-sm-3 col-form-label"><span class="text-orange">*</span>申請人姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="CName" name="CName" value="大中天" disabled>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="IdentKind" class="col-sm-3 col-form-label"><span class="text-orange">*</span>身分別</label>
                                <div class="col-sm-9">
                                    <select class="form-control" id="IdentKind" name="IdentKind">
                                        <option selected>學生</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="SchoolEmail" class="col-sm-3 col-form-label"><span class="text-orange">*</span>學校Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="SchoolEmail" name="SchoolEmail">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="EmailAddress" class="col-sm-3 col-form-label"><span class="text-orange">*</span>常用Email</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="EmailAddress" name="EmailAddress">
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
                                    <input type="text" class="form-control" id="HomeTelephone" name="HomeTelephone">
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