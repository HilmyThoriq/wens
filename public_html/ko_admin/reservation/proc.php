<?

$ip = $REMOTE_ADDR;
if (!$reg_date) $reg_date = date("Y-m-d H:i:s");
$return_url = explode("?", $return_url);
$phone = $phone1."-".$phone2."-".$phone3;
if($email2) $email3 = $email2;
$email = $email1."@".$email3;

$online_config_no = $reservation['online_config_no'];
$online_query = "SELECT * FROM koweb_online_config where no='$online_config_no'";
$online_result = mysql_query($online_query);
$online = mysql_fetch_array($online_result);

$member = get_member();
if(!$member) $member['auth_level'] = 10;
if($online['use_member']<$member['auth_level']){
    error("회원권한 - 잘못된 접근입니다.");
}

if($online['use_certi'] == "Y" && $site['use_sms']=="Y" && !empty($site['sms_id']) && !empty($site['sms_key']) && !$is_admin){
	if($_SESSION['auth_phone'] != $phone1."-".$phone2."-".$phone3) error("휴대폰인증을 다시 진행해주세요");
	if($_SESSION['auth_sms'] != $sms_auth) error("휴대폰인증을 다시 진행해주세요");
}

if(!$is_admin){ //관리자 프리패스 , 관리자가 아니라면 예외처리 진행


    //상품 있는지 체크
    $item_query = "SELECT * FROM koweb_reservation_item WHERE no ='$no' AND state='Y'";
    $item_result = mysql_query($item_query);
    $item_row = mysql_fetch_array($item_result);
    if(!$item_row){
      echo "사용할수없는 상품 alert 띄우기";
      exit;
    }


    //예약가능최소일자 체크
    if($reservation['date_select_type'] == "1"){ //선택형
        $dates_ = explode("|",$reservation_date);
        $min_date = $dates_[0];
        $max_date = $dates_[count($dates_)-1];
    }else{ //복합형
        $dates_ = explode("~",$reservation_date);
        $min_date = $dates_[0];
        $max_date = $dates_[1];
    }

    $datetime1 = strtotime($dates_[0]);
    $datetime2 = strtotime(date("Ymd"));

    $day_diff = ($datetime1-$datetime2) / (3600 * 24);

    if($day_diff < $reservation['min_before_days']){
        echo "오늘로부터 {$reservation['min_before_days']}일 이후만 예약이 가능합니다.";
        exit;
    }


    // 수량 체크
    $item_count = array();
    $while_break_flag = false;

    $query = "SELECT * FROM koweb_reservation_date WHERE end_date >= {$min_date} and start_date <= {$max_date} and item_no='$no' and state != '20'";
    $result = mysql_query($query);
    while($row = mysql_fetch_array($result)){

        $start_date_full = $row['start_date'];
        $end_date_full   = $row['end_date'];

        if($start_date_full < $min_date) $start_date_full = $min_date;
        if($end_date_full   > $max_date) $end_date_full   = $max_date;

        while (strtotime($start_date_full) <= strtotime($end_date_full)) {
            if( ($reservation['date_select_type'] == "1" && in_array($start_date_full,$dates_)) || $reservation['date_select_type'] != "1"){
                $item_count[$start_date_full]++;
                if($item_row['item_cnt'] <= $item_count[$start_date_full]){ $while_break_flag = true; break;}
            }

            $start_date_full = date ("Ymd", strtotime("+1 day", strtotime($start_date_full)));
        }
        if($while_break_flag == true){ // 루프종료, 총갯수 이상의 데이터가 넘어왔음으로 Error
            echo "선택된 날짜에서 해당 상품의 재고가 소진된 날짜가 있습니다.";
            exit;
        }
    }
    // 수량 체크 끝

    //선택된 첫날짜와 마지막 날짜의 차이가 설정된 최대날짜 체크
    $date_diff = 0;

    $datetime1 = date_create($min_date);
    $datetime2 = date_create($max_date);

    $interval = date_diff($datetime1, $datetime2);

    $interval->format();
    $date_diff = $interval->d;

    if($reservation['max_days'] <= $date_diff){
        echo "선택할수 있는 날짜 폭을 초과하였습니다.";
        exit;
    }
    //선택된 첫날짜와 마지막 날짜의 차이가 설정된 최대날짜 체크 끝


    //미리 선택할수 있는 달수를 초과 하였는지 체크
    $month_diff = 0;

    $now_date = date("m");

    $target_date = date("m",strtotime($max_date));
    $month_diff = (int)$target_date-(int)$now_date;


    if($reservation['max_month'] < $month_diff){
        echo "미리 선택할수 있는 달수가 초과되었습니다.";
        exit;
    }
}

//미리 선택할수 있는 달수를 초과 하였는지 체크 끝

if($mode == "write_proc"){
    //============================== 등록 ============================== //
    if (isblank($no)) error("비정상적 접근");


    //변수 만들기
    $add_query = "";
    for($i = 1; $i <= 10; $i++){
        //변수명 생성
        $tmp_variable = "variable_".$i;

        if(${"variable_type_".$i} == "file"){
            if($_FILES[$tmp_variable][size] > 0){
                ${"variable_".$i} = upload_file($dir, $_FILES[$tmp_variable][tmp_name], $_FILES[$tmp_variable][name]);
            }
        }
        $add_query .=  ", '".${"variable_".$i}."'";
    }

    mysql_query("INSERT INTO {$reservation['online_config_id']} VALUES ('', '$_SESSION[member_id]', '$_SESSION[member_ci]', '$_SESSION[member_di]', '$_SESSION[member_name]', '$password', '$phone', '$email', '$zip', '$address1', '$address2' $add_query , '', '$reg_date', '$ip')");
    $online_no = mysql_insert_id();

    $item_query = "SELECT item_name FROM koweb_reservation_item WHERE no ='$no'";
	$item_result = mysql_query($item_query);
	$item_row = mysql_fetch_array($item_result);
    if($reservation['date_select_type'] == "1"){ //선택형
        $dates = explode("|",$reservation_date);
        foreach ($dates as $key => $date) {
            $year = substr($date,0,4);
            $month = substr($date,4,2);
            $day = substr($date,6,2);
            $insert_sql =
            "INSERT INTO
                koweb_reservation_date
            set
                item_no='$no',
                input_no='$online_no',
                input_id='{$reservation['online_config_id']}',
                item_name='{$item_row['item_name']}',
                date_price='{$item_row['item_price']}',
                start_date='{$year}{$month}{$day}',
                start_year='{$year}',
                start_month='{$month}',
                start_day='{$day}',
                end_date='{$year}{$month}{$day}',
                end_year='{$year}',
                end_month='{$month}',
                end_day='{$day}',
                reg_date='$reg_date',
                state='10'
            ";

            $result = mysql_query($insert_sql);
            if(!$result){
                alert("예약도중 문제가 발생하였습니다. 관리자에게 문의하여주세요.");
                url($return_url[0]."?online_id=$online_id");
                exit;
            }
        }
    }else{ //복합형
        $dates = explode("~",$reservation_date);
        $start_year = substr($dates[0],0,4);
        $start_month = substr($dates[0],4,2);
        $start_day = substr($dates[0],6,2);
        $end_year = substr($dates[1],0,4);
        $end_month = substr($dates[1],4,2);
        $end_day = substr($dates[1],6,2);
        $insert_sql =
        "INSERT INTO
            koweb_reservation_date
        set
            item_no='$no',
            input_no='$online_no',
            input_id='{$reservation['online_config_id']}',
            item_name='{$item_row['item_name']}',
            date_price='{$item_row['item_price']}',
            start_date='{$start_year}{$start_month}{$start_day}',
            start_year='{$start_year}',
            start_month='{$start_month}',
            start_day='{$start_day}',
            end_date='{$end_year}{$end_month}{$end_day}',
            end_year='{$end_year}',
            end_month='{$end_month}',
            end_day='{$end_day}',
            reg_date='$reg_date',
            state='10'
        ";

        $result = mysql_query($insert_sql);
        if(!$result){
            alert("예약도중 문제가 발생하였습니다. 관리자에게 문의하여주세요.");
            url($return_url[0]."?online_id=$online_id");
            exit;
        }
    }



    if($site['use_sms'] == "Y" && !empty($reservation['receive_phone_number'])){
        $sms_content = "예약신청이 접수되었습니다. -{$reservation['title']}-";
        sms_send($site['sms_id'],$site['sms_key'],$reservation['receive_phone_number'],$reservation['receive_phone_number'],$sms_content);
    }
    alert("등록되었습니다.");
    url($return_url[0]."?online_id=$online_id");

    //============================== 등록 끝 ============================== //
}
?>
