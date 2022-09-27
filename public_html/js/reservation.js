function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}

function change_date(str){
    str = String(str);
    var str1 = str.substring(0,4);
    var str2 = str.substring(4,6);
    var str3 = str.substring(6,8);
    return str1+"."+str2+"."+str3;
}

// 선택형
// date_select_type 값이 1일때 사용하는 함수
var select_date_arr = new Array();
function select_days1(year,month,day){
    month = pad(month,2);
    day = pad(day,2);

    if(select_date_arr.indexOf(full_date) < 0){
        select_date_arr.push(full_date);
    }else{
        var index_arr = select_date_arr.indexOf(full_date);
        select_date_arr.splice(index_arr,1);
        $("td[data-date="+full_date+"]").removeClass("on");
    }

    select_date_arr.sort(function(a,b){
        return a - b;
    });

    if(select_date_arr.length>0){
        var max_date = select_date_arr[select_date_arr.length-1];

        var sta_str = select_date_arr[0].substr(0,4)+"-"+select_date_arr[0].substr(4,2)+"-"+select_date_arr[0].substr(6,2);
        var end_str = max_date.substr(0,4)+"-"+max_date.substr(4,2)+"-"+max_date.substr(6,2);
        var this_str = year+"-"+month+"-"+day;

        var start_diff = new Date(sta_str);
        var end_diff = new Date(end_str);
        var this_diff = new Date(this_str);

        var timeDiff = Math.abs(this_diff.getTime() - start_diff.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        var timeDiff2 = Math.abs(end_diff.getTime() - this_diff.getTime());
        var diffDays2 = Math.ceil(timeDiff2 / (1000 * 3600 * 24));
        if(auth === false){
            if(diffDays >= max_days){
                select_date_arr.splice(select_date_arr.length-1,1);
                alert("최대 "+max_days+"일 만큼 예약할수있습니다.");
                return;
            }

            if(diffDays2 >= max_days){
                select_date_arr.splice(0,1);
                alert("최대 "+max_days+"일 만큼 예약할수있습니다.");
                return;
            }
        }
    }


    return fill_cal1();
}

function fill_cal1(){
    var html_txt = "";
    var temp_select_date_arr = new Array();
    for(var i = 0; i<select_date_arr.length; i++){
        $("td[data-date='"+select_date_arr[i]+"']").addClass("on");
        html_txt += "<span>"+change_date(select_date_arr[i])+"</span>";
        temp_select_date_arr.push(select_date_arr[i]);
    }
    $("#reservation_dates").html(html_txt);
    $("#reservation_date").val(temp_select_date_arr.join("|"));
}
// date_select_type 값이 1일때 사용하는 함수 끝


// 복합형
// date_select_type 값이 2일때 사용하는 함수
var select_turn = 0;
function select_days2(year,month,day){
    month = pad(month,2)
    day = pad(day,2)
	select_turn ++;
	select_turn = select_turn == 3 ? 1 : select_turn;
	if(select_turn == 1){
		$("#reservation_start").val(year+"."+month+"."+day);
        $("#reservation_end").val("");
        $("td[data-date]").removeClass("on");
        var cal_start_date = $("#reservation_start").val().replace(/\./gi,"");
        $("td[data-date='"+cal_start_date+"']").addClass("on");
	}else{
        var end_str = year+"."+month+"."+day;
        var start_diff = new Date($("#reservation_start").val().replace(/\./gi,"-"));
        var end_diff = new Date(end_str.replace(/\./gi,"-"));
        var timeDiff = Math.abs(end_diff.getTime() - start_diff.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
        if(diffDays >= max_days && auth===false){
            alert("최대 "+max_days+"일 만큼 예약할수있습니다.");
            select_turn = 0;
            select_days2(year,month,day);
            return;
        }

        if(end_str >= $("#reservation_start").val() && check_btween_none_day(year,month,day)){
            $("#reservation_end").val(end_str);
            call_fun2();
        }else{
            select_turn = 0;
            select_days2(year,month,day);
        }
	}
}

function check_btween_none_day(year,month,day){
    var cal_start_date = $("#reservation_start").val().replace(/\./gi,"");
    var cal_end_date = $("#reservation_end").val().replace(/\./gi,"");
    var none_days = $("#cal_table tbody tr td.none");
    var result = true;
    var end_date = String(year)+String(month)+String(day);
    $.each(none_days,function(index,value){
        if(cal_start_date <= $(this).data("date") && end_date >= $(this).data("date")){
            return result = false;
        }
    })
    return result;
}

function call_fun2(){
	if($("#reservation_end").val() != "")
		fill_cal2();
}

function fill_cal2(){
	var cal_start_date = $("#reservation_start").val().replace(/\./gi,"");
	var cal_end_date = $("#reservation_end").val().replace(/\./gi,"");
    $("td[data-date]").removeClass("on");

    for(var i = cal_start_date; i <= cal_end_date; i++){
        $("td[data-date='"+i+"']").addClass("on");
    }

    $("#reservation_date").val(cal_start_date+"~"+cal_end_date);

}
// date_select_type 값이 2일때 사용하는 함수 끝

//SMS 전송
var sms_send_flag = false;
function sms_send() {
	var phone_tmp1 = $("select[name='phone1']").val();
	var phone_tmp2 = $("input[name='phone2']").val();
	var phone_tmp3 = $("input[name='phone3']").val();

	if( phone_tmp1 == "" || phone_tmp2 == "" || phone_tmp3 == "" ){
		alert("휴대폰번호를 올바르게 입력해주세요.");
		return false;
	}

	if( phone_tmp1.length != 3 || phone_tmp2.length != 4 || phone_tmp3.length != 4 ){
		alert("휴대폰번호를 올바르게 입력해주세요.");
		return false;
	}

	var data = phone_tmp1 + "-" + phone_tmp2 + "-" + phone_tmp3;
    if(sms_send_flag === false){
    	$.ajax({
    		type : "POST",
    		url : "/ko_admin/inc/sms_ajax.php",
    		data : {
    			data_send : data
    		},
            beforeSend: function () {
                sms_send_flag = true;
            },
    		success : function(args) {
    				alert(args);
                    sms_send_flag = false;
    		}
    	});
    }
}

function sms_auth() {
	var sms_auth = $("input[name='sms_auth']").val();

	if( sms_auth == ""){
		alert("인증번호를 올바르게 입력해주세요.");
		return false;
	}

	var phone_tmp1 = $("select[name='phone1']").val();
	var phone_tmp2 = $("input[name='phone2']").val();
	var phone_tmp3 = $("input[name='phone3']").val();

	if( phone_tmp1 == "" || phone_tmp2 == "" || phone_tmp3 == "" ){
		alert("휴대폰번호를 올바르게 입력해주세요.");
		return false;
	}

	if( phone_tmp1.length != 3 || phone_tmp2.length != 4 || phone_tmp3.length != 4 ){
		alert("휴대폰번호를 올바르게 입력해주세요.");
		return false;
	}

	var data = phone_tmp1 + "-" + phone_tmp2 + "-" + phone_tmp3;

	$.ajax({
		type : "POST",
		dataType: 'json',
		url : "/ko_admin/inc/sms_auth_ajax.php",
		data : {
			sms_auth : sms_auth,
			data_send : data
		},
		success : function(request) {
			alert(request.message);
			$("#auth_message").html(request.tag);
			if(request.result == "true"){
				$("#sms_auth2").val("Y");
			}
		}
	});
}
