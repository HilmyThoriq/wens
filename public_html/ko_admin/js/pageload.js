$(function(){ 
	$(".pageload").click(function(e){
		e.preventDefault();
		$("#main_cont").load($(this).attr("href"));
		var class_find = $(this).attr("class");
		$(".pageload").removeClass("on");
		if(class_find.indexOf("on") == -1){
			$(this).addClass("on");
		}
	});
});
