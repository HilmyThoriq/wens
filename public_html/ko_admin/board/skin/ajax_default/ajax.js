/*
function list_ajax(board, keyword, search_key, start, cate){
	var board = board;
	var keyword = keyword;
	var search_key = search_key;
	var page = start;
	var cate = cate;
	if( !page ) page = 0;

	$.ajax({
		type : "POST",
		url : "/ko_admin/board/skin/ajax_default/ajax_list.php",
		data : {
			board_id : board,
			keyword : keyword,
			search_key : search_key,
			start : page,
		},
		success: function (data) {
			$("#list_ajax_area").html(data);

		},
		error : function( jqXHR, textStatus, errorThrown ) {
			alert( textStatus );
			alert( errorThrown );
		}
	});
}

function view_ajax(board, keyword, search_key, start, cate, no){
	var board = board;
	var keyword = keyword;
	var search_key = search_key;
	var page = start;
	var cate = cate;
	var no = no;
	if( !page ) page = 0;

	$.ajax({
		type : "POST",
		url : "/ko_admin/board/skin/ajax_default/ajax_view.php",
		data : {
			board_id : board,
			keyword : keyword,
			search_key : search_key,
			start : page,
			cate : cate,
			no : no,
		},
		success: function (data) {
			$("#list_ajax_area").html("");
			$("#list_ajax_area").html(data);

		},
		error : function( jqXHR, textStatus, errorThrown ) {
			alert( textStatus );
			alert( errorThrown );
		}
	});
}
*/

//FILE ID...
 function uploadFile(FILE){
	
}


function do_auth(mode, board, keyword, search_key, start, cate, no, passwd, return_mode){
	var mode = mode;
	var board = board;
	var keyword = keyword;
	var search_key = search_key;
	var page = start;
	var cate = cate;
	var no = no;
	var passwd = passwd;
	var return_mode = return_mode;
	var url_ = "/ko_admin/board/skin/ajax_default/ajax_auth_check.php";
	
	$.ajax({
		type : "POST",
		url : url_,
		data : {
			mode : mode,
			board_id : board,
			keyword : keyword,
			search_key : search_key,
			start : page,
			cate : cate,
			no : no,
			password : passwd,
			return_mode : return_mode
		},
		success: function (data) {

			$("#list_ajax_area").html(data);

		},
		error : function( jqXHR, textStatus, errorThrown ) {
			alert( textStatus );
			alert( errorThrown );
		}
	});
}

function do_Process(mode, board, keyword, search_key, start, cate, no, passwd, ref_no){

	var mode = mode;
	var board = board;
	var keyword = keyword;
	var search_key = search_key;
	var page = start;
	var cate = cate;
	var no = no;
	var passwd = passwd;
	var ref_no = ref_no;

	if( !page ) page = 0;

	if(mode == "write"){
	
		var url_ = "/ko_admin/board/skin/ajax_default/ajax_form.php";

	} else if(mode == "modify"){
	
		var url_ = "/ko_admin/board/skin/ajax_default/ajax_form.php";

	} else if(mode == "reply"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_form.php";

	} else if(mode == "view"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_view.php";

	} else if(mode == "delete"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} else if(mode == "write_proc"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} else if(mode == "modify_proc"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} else if(mode == "check"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_check.php";

	} else if(mode == "comment_proc"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} else {
		var url_ = "/ko_admin/board/skin/ajax_default/ajax_list.php";

	} 

	$.ajax({
		type : "POST",
		url : url_,
		data : {
			mode : mode,
			board_id : board,
			keyword : keyword,
			search_key : search_key,
			start : page,
			cate : cate,
			no : no,
			password : passwd,
			ref_no : ref_no
		},
		success: function (data) {
			$("#list_ajax_area").html(data);

		},
		error : function( jqXHR, textStatus, errorThrown ) {
			alert( textStatus );
			alert( errorThrown );
		}
	});
}





function do_Proc(mode, board, keyword, search_key, start, cate, no){

	var params = jQuery("#default_ajax_form").serialize();
	var mode = mode;
	var board = board;
	var keyword = keyword;
	var search_key = search_key;
	var page = start;
	var cate = cate;
	var no = no;
	var passwd = passwd;
	var ref_no = ref_no;
	var params = params;

	if( !page ) page = 0;

	if(mode == "write"){

		if(!$("#title").val()){
			alert("제목을 입력하세요.");
			return false;
		}

		if(!$("#name").val()){
			alert("작성자를 입력하세요.");
			return false;
		}

			if(!$("#pin").val()){
			alert("비밀번호를 입력하세요.");
			return false;
		}

			if(!$("input[name='rand_auth_']").val()){
			alert("스팸방지번호를 입력하세요.");
			return false;
		}

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} else if(mode == "modify"){

		if(!$("#title").val()){
			alert("제목을 입력하세요.");
			return false;
		}

		if(!$("#name").val()){
			alert("작성자를 입력하세요.");
			return false;
		}

			if(!$("#pin").val()){
			alert("비밀번호를 입력하세요.");
			return false;
		}

			if(!$("input[name='rand_auth_']").val()){
			alert("스팸방지번호를 입력하세요.");
			return false;
		}

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} else if(mode == "check"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_check.php";

	} else if(mode == "comment_proc"){

		var url_ = "/ko_admin/board/skin/ajax_default/ajax_proc.php";

	} 

	$.ajax({
		type : "POST",
		url : url_,
		data : {
			params : params,
		},
		success: function (data) {
			$("#list_ajax_area").html(data);

		},
		error : function( jqXHR, textStatus, errorThrown ) {
			alert( textStatus );
			alert( errorThrown );
		}
	});
}




