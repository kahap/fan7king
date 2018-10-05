$(document).ready(function(){
    
var rendered = false;
	
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 15, // Creates a dropdown of 15 years to control year
    format: 'yyyy-mm-dd',
    monthsFull: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
    monthsShort: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'],
//    monthsFull: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
//    monthsShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
//    weekdaysFull: ['日', '一', '二', '三', '四', '五', '六'],
//    weekdaysShort: ['日', '一', '二', '三', '四', '五', '六'],
//    today: '今天',
//    clear: '清除',
//    close: '關閉',
//    labelMonthNext: '下一月',
//    labelMonthPrev: '上一月',
//    labelMonthSelect: '選擇月',
//    labelYearSelect: '選擇年'
	onSet: function( arg ){
        if ( 'select' in arg ){ //prevent closing on selecting month/year
            this.close();
        }
    }
});

function formatYear(rendered){
	if(!rendered){
		$(".picker__select--year").find("option").each(function(){
			var curYr = $(this).val() - 1911;
			$(this).text(curYr);
			$(this).val(curYr);
		});
		rendered = true;
	}
}


$('input.autocomplete').autocomplete({
    data: {
      "Apple": null,
      "Microsoft": null,
      "Google": 'assets/images/google.png'
    }
  });
  
  
});

