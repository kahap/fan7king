<?php
    $member = new Member();
    $memberData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $memOrigData = $member->getOneMemberByNo($_SESSION['user']['memNo']);
    $member->changeToReadable($memberData[0]);


    $page = isset($_GET["paginate"])? $_GET["paginate"] : 1;
    $amount = 10;
    $page_url = "?item=member_center&action=order";

    $or = new Orders();
    $orMemData = $or->getOrByMemberAndMethod($_SESSION['user']['memNo'],1, ($page-1)*$amount, $amount );     // p=0 , a=30
    $totalData = $or->getOrByMemberAndMethodCount($_SESSION['user']['memNo'],1);
    $lastPage = ceil($totalData/$amount);

    $pm = new Product_Manage();
    $pro = new Product();
?>
<style>
    td{
        text-align:center;
    }
</style>

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
                        <div class="section-inner-table bg-white">
                            <table class="table table-borderless table-rwd">
                                <thead>
                                    <tr class="bg-pale">
                                        <th nowrap="nowrap">訂單編號</th>
                                        <th nowrap="nowrap">訂單日期</th>
                                        <th nowrap="nowrap">商品名稱</th>
<!--                                        <th nowrap="nowrap">商品規格</th>-->
<!--                                        <th nowrap="nowrap">商品型號</th>-->
                                        <th nowrap="nowrap">訂單狀態</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                $order_status = array('未完成下單','取消訂單');
                                if($orMemData != null){
                                    //系統其他設定
                                    $os = new Other_Setting();
                                    $osData = $os->getAll();

                                    foreach($orMemData as $key=>$value) {
                                        $curTIme = strtotime($value["orDate"]) + $osData[0]["orderLimitDays"] * 86400;
                                        if (($curTIme >= time() && in_array($value["orStatus"], $order_status)) || (!in_array($value["orStatus"], $order_status))) {
                                            $orig = $value;
                                            $or->changeToReadable($value, $value["orMethod"]);
                                            $pmData = $pm->getOnePMByNo($value["pmNo"]);
                                            $proData = $pro->getOneProByNo($pmData[0]["proNo"]);
                                            ?>
                                            <tr>
                                                <td data-th="訂單編號" nowrap="nowrap">
                                                    <a style="text-decoration:underline;color:blue;"
                                                       href="?item=member_center&action=order&orno=<?php echo $value["orNo"]; ?>">
                                                        <?php echo $value["orCaseNo"]; ?>
                                                    </a>
                                                </td>
                                                <td data-th="訂單日期" nowrap="nowrap"><?php echo $value["orDate"]; ?></td>
                                                <td data-th="商品名稱"><?php echo $proData[0]["proName"]; ?></td>
<!--                                                <td data-th="商品規格" nowrap="nowrap">--><?php //echo $value["orProSpec"]; ?><!--</td>-->
<!--                                                <td data-th="商品型號" nowrap="nowrap"></td>-->
                                                <td data-th="狀態" nowrap="nowrap">
                                                    <?php
                                                    if ($value["orStatus"] == '出貨中') {
                                                        echo $value["orHandleTransportSerialNum"] != "" ?
                                                            '<a style="text-decoration:underline;color:blue;" href="?item=member_center&action=purchase&orno=' . $value["orNo"] . '">出貨中</a>' :
                                                            "備貨中";
                                                    } else {
                                                        if ($value["orStatus"] == '資料不全需補件') {
                                                            echo "<a style='text-decoration:underline;color:blue;' href='?item=member_center&action=order_edit&method=1&orno=" . $value["orNo"] . "&front_mange=1'>" . $value["orStatus"] . "</a>";
                                                        } else {
                                                            echo ($value["orStatus"] == '未進件') ? '審查中' : $value["orStatus"];
                                                        }
                                                    }
                                                    ?>
                                                    <br>
                                                    <?php 
                                                    if($value["orIfEditable"] == '0' or $value["orStatus"] == '待補'){
                                                        if($value["orStatus"] != '取消訂單' && $value["orStatus"] != '已完成' && $value["orStatus"] != '審查中'){
                                                            echo "<a class='text-orange' href='?item=member_center&action=order_period&method=2&orno=".$value["orNo"]."&front_mange=1'>編輯</a>";
                                                        }
                                                    } 
                                                    ?>
                                                    <?php if($value["orStatus"] == '我要繳款'){ ?><a class='text-orange' href="?item=member_center&action=purchase&orno=<?php echo $value["orNo"]; ?>&query=p">前往</a><?Php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="6">沒有任何資料</td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                        <ul class="pagination justify-content-center flex-wrap">
                            <?php if ($page>1){ ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_url;?>&paginate=<?php echo 1;?>">&lt;</a></li>
                            <?php }
                            $num=3;
                            for ($i=1; $i<=$lastPage; $i++) {
                                if ($i>$page+$num || $i<$page-$num)continue;
                                ?>
                                <li class="page-item <?php if ($page==$i)echo 'active';?>">
                                    <a class="page-link" href="<?php echo $page_url;?>&paginate=<?php echo $i;?>">
                                        <?php echo $i;?>
                                    </a>
                                </li>
                                <?php
                            }
                            if ($page<$lastPage){ ?>
                                <li class="page-item"><a class="page-link" href="<?php echo $page_url;?>&paginate=<?php echo $lastPage;?>">&gt;</a></li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    </main>

<script type="text/javascript">

    $("#copyButton").click(function() {
        copyToClipboard(document.getElementById("copyTarget"));
        alert('複製成功');
    });

    function copyToClipboard(elem) {
        // create hidden text element, if it doesn't already exist
        var targetId = "_hiddenCopyText_";
        var isInput = elem.tagName === "INPUT" || elem.tagName === "TEXTAREA";
        var origSelectionStart, origSelectionEnd;
        if (isInput) {
            // can just use the original source element for the selection and copy
            target = elem;
            origSelectionStart = elem.selectionStart;
            origSelectionEnd = elem.selectionEnd;
        } else {
            // must use a temporary form element for the selection and copy
            target = document.getElementById(targetId);
            if (!target) {
                var target = document.createElement("textarea");
                target.style.position = "absolute";
                target.style.left = "-9999px";
                target.style.top = "0";
                target.id = targetId;
                document.body.appendChild(target);
            }
            target.textContent = elem.textContent;
        }
        // select the content
        var currentFocus = document.activeElement;
        target.focus();
        target.setSelectionRange(0, target.value.length);

        // copy the selection
        var succeed;
        try {
            succeed = document.execCommand("copy");
        } catch(e) {
            succeed = false;
        }
        // restore original focus
        if (currentFocus && typeof currentFocus.focus === "function") {
            currentFocus.focus();
        }

        if (isInput) {
            // restore prior selection
            elem.setSelectionRange(origSelectionStart, origSelectionEnd);
        } else {
            // clear temporary content
            target.textContent = "";
        }
        return succeed;
    }

</script>