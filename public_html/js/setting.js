$(function() {
	console.log($( ".datepicker" ));
  $( ".datepicker" ).datepicker({
    dateFormat: 'yy-mm-dd',
    prevText: '이전 달',
    nextText: '다음 달',
    monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
    dayNames: ['일','월','화','수','목','금','토'],
    dayNamesShort: ['일','월','화','수','목','금','토'],
    dayNamesMin: ['일','월','화','수','목','금','토'],
    showMonthAfterYear: true,
    changeMonth: true,
    changeYear: true,
    showOn: "both",
	buttonText: "달력보기",
  });
});
function id_ajax() {

	if("<?=$manager_type?>" == "" || $("input[name='id']").val() == "") {
		alert("비정상적인 접근 또는 입력한 아이디가 올바르지 않습니다.");
		return false;
	}

	if("<?=$manager_type?>" == "board"){
		var check_ = "board_"+$("input[name='id']").val();
	} else {
		var check_ = $("input[name='id']").val();
	}

	$.ajax({
		type : "POST",
		url : "/ko_admin/inc/id_ajax.php",
		data : {
			variable : "koweb_" + "<?=$manager_type?>" + "_config",
			variable_data : check_
		},
		success : function(args) {
				alert(args);
		}
	});
}


//임시값 담아두는 배열
var _tempTypeOption = new Array();
function changeTypeOptionDisable(obj){
	//다음 Element (타입세부 필드)
	var nextEl = obj.parentElement.nextElementSibling;
	var nextInput = nextEl.firstElementChild;
	var objCnt = nextInput.name.split('_').pop();
	switch (obj.value) {
		case "radio":
		case "select":
		case "checkbox":
			//임시값을 되돌림
			if(_tempTypeOption[objCnt]) nextInput.value = _tempTypeOption[objCnt];
			nextInput.disabled = false;

			break;
		default:
			//임시값 배열에 없다면 임시값에 입력
			if(!_tempTypeOption[objCnt]) _tempTypeOption[objCnt] = nextInput.value;
			nextInput.disabled = true;
			nextInput.value = "";
	}
}

var _sortBeforeValue = "";
function chageSort(obj){
	prevValue = _sortBeforeValue;
	nextValue = obj.value;
	var type = document.querySelectorAll("select[name^=sort_] option:checked");
	for(var i=0; i < type.length; i++){
		if(type[i].value == nextValue){

		}
	}
}

function focusSortValue(obj){
	_sortBeforeValue = obj.options[obj.selectedIndex].value;
}
