$(function(){
	$("button.dataRefresh").on("click", function(){
		$('h2.status').text('資料載入中...');
		$('tbody tr').remove();
		
		$.ajax({
            type : "POST",
            url : "crawlerGetData.php",
            data : {
				reGetData : true
        	},
        	dataType : 'html'
  		}).done(function(data) {  
            if(data !== 'fail') {
				$('tbody').html(data);
            } else {
				alert("Database connection failed");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert("There is an error, please confirm console log");
            console.log(jqXHR.responseText);
        });
	});
});
