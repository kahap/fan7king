function retrieveDataBase(dataSend,url,tableId,orderWhich,descOrAsc,ifInit=true){
	$.ajax({
		type: "POST",
		data: dataSend,
		url: url,
		success: function(result){
			$(".loading-tr").remove();
			if(result != "empty"){
				var results = JSON.parse(result);
				$.each(results, function(k,v){
					$(tableId+" tbody").append("<tr></tr>");
					$.each(v, function(name,value){
						$(tableId+" tbody tr:last-child").append("<td>"+value+"</td>");
					});
				});
			}else{
				var tdLength = $(tableId+" th").length;
				$(tableId+" tbody").append("<tr><td style='text-align:center;' colspan='"+tdLength+"'>尚無任何資料</td></tr>");
			}
		},
		complete: function (result) {
			if(ifInit){
				initPage(tableId,orderWhich,descOrAsc);
			}
		}
	});
}

function printOnTable(tableId,data){
	if(data.length != 0){
		$.each(data, function(k,v){
			$(tableId+" tbody").append("<tr></tr>");
			$.each(v, function(name,value){
				$(tableId+" tbody tr:last-child").append("<td>"+value+"</td>");
			});
		});
	}

}

function initPage(tableId,orderWhich,descOrAsc){
	$(tableId).DataTable({
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
	    "order": [[ orderWhich , descOrAsc ]],
	    "iDisplayLength": 100
    	
    });
    $('.dataTables_length select').addClass('browser-default');
}

function createTable(parent,tableId,thHeaders){
	var html = '<table id="'+tableId+'" class="display responsive-table datatable-example">'+
				'<thead>'+
				'<tr>';
	for(var i=0; i<thHeaders.length; i++){
		html += "<th>"+thHeaders[i]+"</th>";
	}
	html += '</tr></thead><tbody></tbody></table>';
	parent.append(html);

}

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#show-img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}
