$(function(){
	$.post("citizenMenu.aj.php", function(data){
		$("#citizenTable").html(data);
	});
	
	$("#addBtn").bind("touchend", function(){
		//$("#addBtn").click();
	});
	$("#addBtn").click(function(){
		$.post("citizenMenu.aj.php", function(data){
			$("#citizenTable").html(data);
			$("#overlay").show();
		});
	});
});
