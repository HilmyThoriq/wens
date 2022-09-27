//컨텐츠 드래그앤드랍 순서변경
$(function(){

	$("[data-drag-sort]").sortable({

		axis: 'y',
		update: function (event, ui) {
			
			var database = $(this).data("drag-sort");
			var data = $(this).sortable("toArray", {attribute: "data-unique-no"});
			var positions = data.join('|');
			var type = $(this).data("drag-sort-type");
			var no = $(this).data("drag-sort-no");

			$.ajax({
				type: "POST",
				url: "/ko_admin/inc/sort_ajax.php",
				data : {
					sort_data: positions,
					database : database,
					type: type,
					no : no
				},
				success : function(args) {
					// console.log(args);

				},beforeSend: function () {
					//$("#div_ajax_load_image").show();
				},
				complete: function () {
					//$("#div_ajax_load_image").hide();
					 location.reload();
				}
			});
		}
	});
});
