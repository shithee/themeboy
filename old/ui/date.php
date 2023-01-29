<?php
    function getDateInfo($date,$m,$w,$d,$h=null) {
        $wd = str_replace("_0", "_",date("D_W", $date));
        if(
            in_array(date("n", $date),$m) &&
            in_array(date("j", $date),$d) &&
            (in_array(date("D", $date),$w) || in_array($wd,$w))
        ){
            if(!empty($h)){
                $hr = ltrim(date("H", $date),'0');
                if(in_array($hr,$h)){
                    return $date;
                }else{
                    return false;
                }
            }else{
                return $date;
            }
        }else{
            return false;
        }
    }

	
	function findnext($params){
	    
    	$months = $params['months'];
    	$weeks = $params['weeks'];
    	$days = $params['days'];
    	$hours = $params['hours'];
    	$periodic = isset($params['periodic']) ? $params['periodic'] : array();
    	$start = $params['start'];
    	$end = $params['end'];
    	$timezone = $params['timezone'];
    	
    	$now = time();
    	$start_date = strtotime($start);
    	$end_date = !empty($end) ? strtotime($end) : strtotime("+4 years");
    	
    	date_default_timezone_set($timezone);
        $actual_date = null;
    	if (!empty($periodic)) {
            $unit = $periodic['unit'];
            $duration = $periodic['duration'];
            if ($now < $start_date) {
                $now = $start_date;
            }
            $stop = false;
            while (!$stop) {
                $info = getDateInfo($now,$months,$weeks,$days,$hours);
                // echo date('d M y h:i A',$now);echo "\n";
                // var_dump($info);
                if($info){
                    $actual_date = $info;
                    $stop = true;
                }
                if($now>$end_date){
                    $stop = true;
                }
                $now = strtotime("+$duration $unit", $now);
            }
        } else {
            if ($now < $start_date) {
                $now = $start_date;
            }
            $stop = false;
            while (!$stop) {
                $info = getDateInfo($now,$months,$weeks,$days);
                if($info){
                    $loop_date = strtotime(date('d-m-Y',$info));
                    $hour_matched = false;
                    for($i=0;$i<=23;$i++){
                        if($hour_matched) continue;
                        $hour_info = getDateInfo($loop_date,$months,$weeks,$days,$hours);
                        // var_dump($hour_info);
                        if(
                            $hour_info && 
                            ($hour_info > $start_date) &&
                            ($hour_info <= $end_date)
                        ){
                            $stop = true;
                            $actual_date = $hour_info;
                            $hour_matched = true;
                        }
                        $loop_date = strtotime("+1 hour", $loop_date);
                    }
                }
                if($now>$end_date){
                    $stop = true;
                }
                $now = strtotime("+1 days", $now);
            }
        }
        return $actual_date;
	}
	
	$months = array(1,2,3);
	$weeks = array('Sun','Mon','Tue','Wed');
	$days = array(1,2,3,4,5,6,7);
	$hours = array('9','12','15','14');
	$duration = '3';
	$unit = 'hours';
	$start = '29-01-2023 10:00 am';
	$end = '30-04-2023 12:00 am';
	
	$params = array(
	    "weeks" => $weeks,
	    "months" => $months,
	    "days" => $days,
	    "hours" => $hours,
	    "start" => $start,
	    "end" => $end,
	    "timezone" => 'Asia/kolkata',
	    "periodic" => array(
	        "unit" => $unit,
	        "duration" => $duration
	    )
	);
	
    // $start_date = new DateTime($start);
    $res = findnext($params);
    print_r(date('d M y h:i A',$res));exit;
	
?>