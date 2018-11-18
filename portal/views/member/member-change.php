
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
                        require_once 'portal/views/member/_left.php';
                        ?>
                    </div>
                    <div class="col-lg-9">
                        <div class="section-inner bg-white">


                            <form action="" id="member_edit">
                                <div class="form-group row">
                                    <label for="staticSource" class="col-sm-3 col-form-label"><span class="text-orange">*</span> 原始密碼</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control input-black-all memPwd" name="memPwd" id="staticSource" value="<?php echo $memPwd;?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticNewpw" class="col-sm-3 col-form-label"><span class="text-orange">*</span> 新密碼</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control input-black-all NewmemPwd" name="NewmemPwd" id="staticNewpw" value="">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="staticCheckpw" class="col-sm-3 col-form-label"><span class="text-orange">*</span> 再次確認新密碼</label>
                                    <div class="col-sm-9">
                                        <input type="password" class="form-control input-black-all reNewmemPwd" name="reNewmemPwd" id="staticCheckpw" value="">
                                    </div>
                                </div>
                                <div class="form-group text-right mt-50">
                                    <button class="btn bg-yellow" id="update">確認送出</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script>

    $(".memother").hide();

    $(".memclass").change(function(){
        var memclass_val = $(this).val();
        if(memclass_val == '3'){
            $(".memother").show();
        }else{
            $(".memother").hide();
        }
    });

    $("#update").click(function(ev){
        var NewmemPwd = $(".NewmemPwd").val();
        var reNewmemPwd = $(".reNewmemPwd").val();
        if( NewmemPwd == reNewmemPwd){
            $.ajax({
                url: 'portal/Controllers/php/member_password_edit.php',
                data: $('#member_edit').serialize(),
                type:"POST",
                dataType:'text',
                success: function(msg){
                    if(msg){
                        alert('更新成功');
                        location.href='index.php?item=member_center&action=password_edit';
                    }else{
                        alert('原始密碼錯誤');
                    }
                },

                error:function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }else{
            alert("新設密碼和再次確認密碼請設定一樣");
        }
        ev.preventDefault();
        return false;
    });

</script>