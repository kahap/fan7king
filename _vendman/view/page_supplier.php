<?php 
require_once('model/require_general.php');

$sup = new Supplier_sales();
$allSupData = $sup->getOneSupplier_salesByNo($_SESSION['supplieruserdata']['supNo']);
?>
<!-- page content -->
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
                <div class="title_left" style="margin-left: 400px">
                    <h3>銷售人員列表</h3>
                    <a style="text-decoration:none;" href="?page=supplier&action=insert">
                        <button class="btn btn-success" style="margin-left: 450px;">新增銷售員</button>
                    </a>
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_content">
                            <table id="example" class="table table-striped responsive-utilities jambo_table">
                                <thead>
                                    <tr class="headings">
                                        <th>購物站編號 </th>
                                        <th>銷售員編號 </th>
                                        <th>銷售員姓名 </th>
                                        <th>銷售員帳號 </th>
                                        <th>銷售員密碼 </th>
                                        <th class=" no-link last">
                                            <span class="nobr">資料編輯</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 
                                    if ($allSupData != null) {
                                        foreach ($allSupData as $key => $value) {
                                ?>
                                    <tr class="pointer">
                                        <td class=" "><?php echo $value["supNo"]; ?></td>
                                        <td class=" "><?php echo $value["ssNo"]; ?></td>
                                        <td class=" "><?php echo $value["ssName"]; ?></td>
                                        <td class=" "><?php echo $value["ssLogId"]; ?></td>
                                        <td class=" "><?php echo $value["ssPwd"]; ?></td>
                                        <td class=" last">
                                            <a href="?page=supplier&action=view&supno=<?php echo $value["ssNo"]; ?>">
                                                <button style="background-color:#FFF;border:1px solid #CCC;" class="btn btn-defult view-details">
                                                詳細資訊/編輯
                                                </button>
                                            </a>
                                        </td>
                                        <!--  若欄位少
                                        <td class=" last">
                                            <a class="content-edit" style="text-decoration: none;" href="#">
                                                <span style="margin-right:10px;" class="glyphicon glyphicon-pencil"></span>
                                            </a>
                                            <a class="content-remove" style="text-decoration: none;" href="#">
                                                <span class="glyphicon glyphicon-remove"></span>
                                            </a>
                                        </td>
                                        -->
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
                <br />
                <br />
                <br />
            </div>
        </div>
    </div>
        
  <!-- Datatables -->
    <script src="js/datatables/js/jquery.dataTables.js"></script>
    <script src="js/datatables/tools/js/dataTables.tableTools.js"></script>

  <!-- pace -->
    <script src="js/pace/pace.min.js"></script>
    <script>
    
    $(document).ready(function() {
        $('input.tableflat').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });
    });

    var asInitVals = new Array();
    $(document).ready( function() {
        var oTable = $('#example').dataTable({
            "oLanguage": {
              "sSearch": "搜尋: "
            },
            'iDisplayLength': 100,
            "sPaginationType": "full_numbers"
        })<?php if (isset($_GET["pageIndex"]) && $_GET["pageIndex"]=='last') echo ".fnPageChange( 'last' );$(window).scrollTop($(document).height())";?>;
        $("tfoot input").keyup(function() {
            /* Filter on the column based on the index of this element's parent <th> */
            oTable.fnFilter(this.value, $("tfoot th").index($(this).parent()));
        });
        $("tfoot input").each(function(i) {
            asInitVals[i] = this.value;
        });
        $("tfoot input").focus(function() {
            if (this.className == "search_init") {
                this.className = "";
                this.value = "";
            }
        });
        $("tfoot input").blur(function(i) {
            if (this.value == "") {
                this.className = "search_init";
            this.value = asInitVals[$("tfoot input").index(this)];
            }
        });
    });
    </script>