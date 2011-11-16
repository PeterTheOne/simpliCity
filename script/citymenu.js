$(function(){
	$("#addBtn").bind("touchend", function(){
		$("#addBtn").click();
	});
	$("#addBtn").click(function(){
		$.post("citizenMenu.aj.php", function(data){
			$("#landscape").html(data);
		});
	});
});
