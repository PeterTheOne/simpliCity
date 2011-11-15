$(function(){
	$("addBtn").bind("touchend", function(){
		$("addBtn").click();
	});
	$("addBtn").click(function(){
		$.post("addCitizen.aj.php", function(data){
			$("#landscape").html(data);
		}
	});
	
});