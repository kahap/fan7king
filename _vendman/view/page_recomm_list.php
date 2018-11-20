<?php 
require_once('model/require_general.php');

$member = new Member();
$allMemberData = $member->getRecommMemberList();


?>
<!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>推薦人清單</h3>
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
                        <th>會員編號 </th>
                        <th>姓名 </th>
						<th>學校系級</th>
                        <th>推薦人填寫該會員人數 </th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
	                    if($allMemberData != null){
	                    	foreach($allMemberData as $key=>$value){
								$memData = $member->getOneMemberByNo($value["memRecommCode"]);
                    ?>
                      <tr class="pointer">
                        <td class=" ">
	                        <a style="text-decoration:underline;color:blue;" href="?page=member&type=recomm_list&action=view&memno=<?php echo $value["memRecommCode"]; ?>"><?php echo $value["memRecommCode"]; ?></a>
                        </td>
						<td class=" "><?php echo $memData['0']["memName"]; ?></td>
                        <td class=" "><?php echo $memData['0']["memSchool"]; ?></td>
                        <td class=" "><?php echo $value["total"]; ?></td>
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
    $(document).ready(function() {
      var oTable = $('#example').dataTable({
        "oLanguage": {
          "sSearch": "搜尋: "
        },
        'iDisplayLength': 100,
        "order": [[ 0, "asc" ]],
        "sPaginationType": "full_numbers"
      });
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