<html>
	<meta charset="utf8">
	<form id="happyfan" action="https://happyfan7.com" method="POST" target="_blank">
	訂單編號<input type="text" name="orCaseNo"><br>
	會員編號<input type="text" name="memNo"><br>
	產品名稱<input type="text" name="orProductName"><br>
	產品規格<input type="text" name="orProductSpect"><br>
	數量<input type="text" name="orProductNumber"><br>
	總金額<input type="text" name="orPeriodTotal"><br>
	分期數<input type="text" name="orPeriodAmnt"><br>
	分期利率<input type="text" name="orPeriodRate"><br>
	<input type="hidden" name="Firm_Number" value="00000">
		<input type="submit" value="送出">
	</form>
</html>
<script>
$('.btn').click(function(){
	$.ajax({
		'url' : 'https://happyfan7.com',
		'type' : 'POST',
		'data' : $("#happyfan").serialize(),
		dataType : 'json',
		success: function (data) {
			if (data.err != '') {
	    		alert('Error');
	    	} else {	    		
                window.open(url, '_blank');
	    	}
	    }
	});
})
	
</script>