<?php

$page = isset($_GET['paginate'])? $_GET['paginate'] : '1';


$sup = new API("supplier");

$data = $sup->getAll( ($page-1)*30 , 30);
$totalProData = $sup->getAllCount();
$lastPage = ceil($totalProData/30);

?>
<main class="mn-inner">
	<div class="row">
		<?php if(!isset($errMsg)){ ?>
		<div class="col s12">
			<div class="page-title">供應商管理</div>
		</div>
		<div class="col s12 m12 l12">
			<div class="card">
				<div class="card-content">
					<span class="card-title">供應商列表</span>
					<div style="padding-bottom:20px;">
						<a href="?page=supplier&action=insert" class="waves-effect waves-light btn green m-b-xs">新增供應商</a>
					</div>


                    <div class="top dataTables_wrapper no-footer">
                        <div class="dataTables_info" id="example_info2" role="status" aria-live="polite">顯示 第 <?php echo ($page-1)*30+1;?> 筆 到 第 <?php echo ($page)*30;?> 筆，總共 <?php echo $totalProData;?> 筆</div>

                        <div class="dataTables_paginate paging_simple_numbers" id="example_paginate">
                            <a href="?page=supplier" class="paginate_button first disabled" aria-controls="example" data-dt-idx="0" tabindex="0" id="example_first">
                                第一頁
                            </a>
                            <?php if ($page>1){ ?>
                                <a href="?page=supplier&paginate=<?php echo $page-1;?>" class="paginate_button previous disabled" aria-controls="example" data-dt-idx="1" tabindex="0" id="example_previous">
                                    <i class="material-icons">chevron_left</i>
                                </a>
                            <?php } ?>

                            <?php for ($i=1;$i<=$lastPage;$i++){ ?>
                                <span>
                                <a href="?page=supplier&paginate=<?php echo $i;?>" class="paginate_button <?php if ($page==$i)echo 'current';?>" aria-controls="example" data-dt-idx="2" tabindex="0">
                                <?php echo $i; ?>
                                </a>
                            </span>
                            <?php } ?>
                            <!--                            <input class="paginate_button choosePage" value="--><?php //echo $page;?><!--" data-href="?page=orders_view_general&type=list">-->

                            <?php if ($page<$lastPage){ ?>
                                <a href="?page=supplier&paginate=<?php echo $page+1;?>" class="paginate_button next disabled" aria-controls="example" data-dt-idx="3" tabindex="0" id="example_next">
                                    <i class="material-icons">chevron_right</i>
                                </a>
                            <?php } ?>
                            <a href="?page=supplier&paginate=<?php echo $lastPage;?>" class="paginate_button last disabled" aria-controls="example" data-dt-idx="4" tabindex="0" id="example_last">
                                最後一頁
                            </a>
                        </div>
                    </div>
                    <br />

                    <div  style="overflow-x:scroll; ">
                    <table id="example" class="display responsive-table datatable-example">
						<thead>
     						<tr>
								<th>編號</th>
								<th>供應商名稱</th>
								<th>聯絡人</th>
								<th>電話</th>
								<th>傳真</th>
								<th>編輯</th>
     						</tr>
						</thead>
     					<tbody>
     					<?php 
     					if($data != null){
     						foreach($data as $key=>$value){
                                ?>
                                    <tr>
                                        <td><?php echo $value["supNo"];?></td>
                                        <td><?php echo $value["supName"];?></td>
                                        <td><?php echo $value["supContactName"];?></td>
                                        <td><?php echo $value["supPhone"];?></td>
                                        <td><?php echo $value["supFax"];?></td>
                                        <td>
                                            <a href="?page=supplier&action=edit&no=<?php echo $value["supNo"]; ?>" class="waves-effect waves-light btn orange m-b-xs">
                                                瀏覽/編輯
                                            </a>
                                        </td>
                                    </tr>
                                <?php
     						}
     					}
     					?>
     					</tbody>
					</table>
                    </div>

				</div>
			</div>
		</div>
		<?php }else{ ?>
		<div class="col s12">
			<div class="page-title"><?php echo $errMsg; ?></div>
		</div>
		<?php } ?>
	</div>
</main>

<script src="assets/plugins/datatables/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        language: {
            searchPlaceholder: '尋找關鍵字',
            sInfo: "從 _START_ 到 _END_ ，共 _TOTAL_ 筆",
            sSearch: '',
            sLengthMenu: '顯示數 _MENU_',
            sLength: 'dataTables_length',
            oPaginate: {
                sFirst: '<i class="material-icons">chevron_left</i>',
                sPrevious: '<i class="material-icons">chevron_left</i>',
                sNext: '<i class="material-icons">chevron_right</i>',
                sLast: '<i class="material-icons">chevron_right</i>' 
            }
        },
        "paging": false,
        "processing": true,
    });
    $('.dataTables_length select').addClass('browser-default');

    /*
    *
     */
    $('#example_info').hide();
    $('.bottom').next('.dataTables_info').hide();

    $('.choosePage').change(function () {
        location.href = $(this).data('href') + '&paginate=' + $(this).val();
    });
});
</script>