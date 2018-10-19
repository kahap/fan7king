
<?php
    $member = new Member();
    $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $memOrigData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $origMemberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    if (isset($_GET['key'])) {
        $memPwd = $memberData[0]['memPwd'];
    }
    $member->changeToReadable($memberData[0]);

    $lg = new Loyal_Guest();
    $allLgData = $lg->getAllLoyalGuest();
    $ifLoyal = "否";
    foreach($allLgData as $keyIn=>$valueIn){
        if($valueIn["lgIdNum"] == $memOrigData[0]["memIdNum"]){
            $ifLoyal = "是";
        }
    }
?>

    <main role="main">
        <h1><span>會員中心</span><small>member center</small></h1>
        <section id="member-zone">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3">
                        <?php
                        $active = 2;
                        require_once 'views/member/_left.php';
                        ?>
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