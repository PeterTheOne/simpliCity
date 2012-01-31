$(function(){
	$.post("aj_city_menu_openOverlay.aj.php", function(data){
		$("#citizenTable").html(data);
	});
	
	$("#addBtn").bind("touchend", function(){
		$("#addBtn").click();
	});
	$("#addBtn").click(function(){
		$.post("aj_city_menu_openOverlay.aj.php", function(data){
			$("#citizenTable").html(data);
			$("#overlay").show();
		});
	});
});
