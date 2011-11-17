$(function(){
	$(".addCitizen").css("cursor","pointer");
	$(".removeCitizen").css("cursor","pointer");
	$(".addCitizen").bind("touchend", function(){
		$(".addCitizen").click();
	});
	$(".addCitizen").click(function(){
		$.post("addCitizen.aj.php", {id: $(this).parent().children(".id").html()}, function(data){
			$("#landscape").html(data);
		});
	});
	$(".removeCitizen").bind("touchend", function(){
		$(".removeCitizen").click();
	});
	$(".removeCitizen").click(function(){
		$.post("removeCitizen.aj.php", {id: $(this).parent().children(".id").html()}, function(data){
			$("#landscape").html(data);
		});
	});
});