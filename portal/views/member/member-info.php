
<?php
    $member = new Member();
    $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $memOrigData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
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
                            <div class="form-group row">
                                <label for="staticIDs" class="col-sm-3 col-form-label">會員編號</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticIDs" value="<?php echo $_SESSION['user']['memNo']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticName" class="col-sm-3 col-form-label">姓名</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticName" value="<?php echo $memberData[0]["memName"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticNickname" class="col-sm-3 col-form-label">暱稱</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticNickname" value="">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticSex" class="col-sm-3 col-form-label">性別</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticSex" value="<?php echo $memberData[0]["memGender"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticBirth" class="col-sm-3 col-form-label">生日</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticBirth" value="<?php echo $memberData[0]["memBday"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticKind" class="col-sm-3 col-form-label">身分別</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticKind" value="<?php echo $memberData[0]["memClass"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticID" class="col-sm-3 col-form-label">身分證號</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticID" value="<?php echo $memberData[0]["memIdNum"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticAddress" class="col-sm-3 col-form-label">現住地址</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticAddress" value="<?php echo $memberData[0]["memAddr"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticTel" class="col-sm-3 col-form-label">現住電話</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticTel" value="<?php echo $memberData[0]["memPhone"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticPhone" class="col-sm-3 col-form-label">手機</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticPhone" value="<?php echo $memberData[0]["memCell"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticLine" class="col-sm-3 col-form-label">LineID</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticLine" value="<?php echo $memberData[0]["memLineId"]; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="staticAddTime" class="col-sm-3 col-form-label">帳號申請時間</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext" id="staticAddTime" value="<?php echo $memberData[0]["memRegistDate"]; ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script>
    $(".reset_email").click(function(){
        var memAccount = "<?php echo $memberData[0]['memAccount']?>";
        var memName = "<?php echo $memberData[0]["memName"]; ?>";
        var memno = "<?php echo $_SESSION['user']['memNo']; ?>";
        var pass_number = "<?php echo $memberData[0]["pass_number"]; ?>";
        $.ajax({
            url: 'Controllers/php/member_resetmail.php',
            data: 'memAccount='+memAccount+"&memName="+memName+"&memno="+memno+"&pass_number="+pass_number,
            type: "POST",
            dataType: 'text',
            success: function(msg){
                if(msg){
                    alert('已寄送到'+memAccount);
                }else{
                    alert('寄送錯誤 or 瀏覽器未支援');
                }
            },

            error:function(xhr, ajaxOptions, thrownError){
                alert(xhr.status);
                alert(thrownError);
            }
        });
    })
</script>