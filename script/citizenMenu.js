$(function(){
	$("#addCitizen").bind("touchend", function(){
		$("#addCitizen").click();
	});
	$("#addCitizen").click(function(){
		$.post("addCitizen.aj.php", function(data){
			$("#landscape").html(data);
		});
	});
	$("#removeCitizen").bind("touchend", function(){
		$("#removeCitizen").click();
	});
	$("#removeCitizen").click(function(){
		$.post("removeCitizen.aj.php", function(data){
			$("#landscape").html(data);
		});
	});
});
