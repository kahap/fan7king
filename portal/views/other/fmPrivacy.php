<?php

$Front_Manage = new Front_Manage();
$Front_Manage2 = new Front_Manage2();
//if(array_key_exists($itemVal,$page_other )){
//    $page_data = $Front_Manage->getAllFM($itemVal);
//}else if(array_key_exists($itemVal,$page_other2 )){
$page_data2 = $Front_Manage->getAllFM('fmPrivacy');
//}

?>

<div class="columns-container">
    <div class="columns-container" style="padding: 60PX;margin-top:-20px;">
        <?php
        echo $page_data2['0']['fmPrivacy'];
        ?>
    </div>
</div>