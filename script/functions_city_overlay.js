$current = 0;

$(function(){
	
	$count = $(".jobentry").length;
	updateCitizenMenu();
	
	$("#goForward").css("cursor","pointer");
	$("#goBack").css("cursor","pointer");
	$("#goForward").bind("touchend", function(){
		//$("#goForward").click();
	});
	$("#goForward").click(function(){
		$current = ($current+1)%$count;
		$count = $(".jobentry").length;
		updateCitizenMenu();
	});
	$("#goBack").bind("touchend", function(){
		//$("#goBack").click();
	});
	$("#goBack").click(function(){
		$current = ($current-1+$count)%$count;
		$count = $(".jobentry").length;
		updateCitizenMenu();
	});
	
	$("#addCitizen").css("cursor","pointer");
	$("#removeCitizen").css("cursor","pointer");
	$("#addCitizen").bind("touchend", function(){
		//$("#addCitizen").click();
	});
	$("#addCitizen").click(function(){
		$.post("aj_city_overlay_addCitizen.aj.php", {id: $(".jobentry").eq($current).children("td").eq(0).html()}, function(data){
			$("#cityValues").remove();
			$.post("aj_city_overlay_printCityValues.aj.php", function(data){
				$("#landscape").append(data);
				$("#overlay").hide();
				$("#canvas").focus();
			});
			
		});
	});
	$("#removeCitizen").bind("touchend", function(){
		//$("#removeCitizen").click();
	});
	$("#removeCitizen").click(function(){
		$.post("aj_city_overlay_removeCitizen.aj.php", {id: $(".jobentry").eq($current).children("td").eq(0).html()}, function(data){
			$("#cityValues").remove();
			$.post("aj_city_overlay_printCityValues.aj.php", function(data){
				$("#landscape").append(data);
				$("#overlay").hide();
				$("#canvas").focus();
			});
		});
	});
	$("#addBtn").click(function(){
		$current = 0;
		$count = $(".jobentry").length;
		updateCitizenMenu();
	});
});

function updateCitizenMenu(){
	$("#currentTitle").html($(".jobentry").eq($current).children("td").eq(1).html()+" ("+$(".jobentry").eq($current).children("td").eq(3).html()+")");
	$("#citizenDescription").html($(".jobentry").eq($current).children("td").eq(2).html());
	
	if($(".jobentry").eq($current).children("td").eq(4).html() == 0){
		$("#removeCitizen").hide();
	} else {
		$("#removeCitizen").show();
	}
	
	if($(".jobentry").eq($current).children("td").eq(5).html() == 0){
		$("#addCitizen").hide();
	} else {
		$("#addCitizen").show();
	}
	
}