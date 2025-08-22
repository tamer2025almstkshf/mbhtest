<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $param = '';
    
    $file_type = stripslashes($_REQUEST['file_type']);
    $file_type = mysqli_real_escape_string($conn, $file_type);
    if(isset($file_type) && $file_type !== ''){
        if(isset($param) && $param === ''){
            $param = $param."type=$file_type";
        } else{
            $param = $param."&type=$file_type";
        }
    }
    
    $frelated_place = stripslashes($_REQUEST['frelated_place']);
    $frelated_place = mysqli_real_escape_string($conn, $frelated_place);
    if(isset($frelated_place) && $frelated_place !== ''){
        if(isset($param) && $param === ''){
            $param = $param."place=$frelated_place";
        } else{
            $param = $param."&place=$frelated_place";
        }
    }
    
    $file_class = stripslashes($_REQUEST['file_class']);
    $file_class = mysqli_real_escape_string($conn, $file_class);
    if(isset($file_class) && $file_class !== ''){
        if(isset($param) && $param === ''){
            $param = $param."class=$file_class";
        } else{
            $param = $param."&class=$file_class";
        }
    }
    
    $fid = stripslashes($_REQUEST['fid']);
    $fid = mysqli_real_escape_string($conn, $fid);
    if(isset($fid) && $fid !== ''){
        if(isset($param) && $param === ''){
            $param = $param."fid=$fid";
        } else{
            $param = $param."&fid=$fid";
        }
    }
    
    $file_subject = stripslashes($_REQUEST['file_subject']);
    $file_subject = mysqli_real_escape_string($conn, $file_subject);
    if(isset($file_subject) && $file_subject !== ''){
        if(isset($param) && $param === ''){
            $param = $param."subject=$file_subject";
        } else{
            $param = $param."&subject=$file_subject";
        }
    }
    
    $file_notes = stripslashes($_REQUEST['file_notes']);
    $file_notes = mysqli_real_escape_string($conn, $file_notes);
    if(isset($file_notes) && $file_notes !== ''){
        if(isset($param) && $param === ''){
            $param = $param."notes=$file_notes";
        } else{
            $param = $param."&notes=$file_notes";
        }
    }
    
    $file_client = stripslashes($_REQUEST['file_client']);
    $file_client = mysqli_real_escape_string($conn, $file_client);
    if(isset($file_client) && $file_client !== ''){
        if(isset($param) && $param === ''){
            $param = $param."client=$file_client";
        } else{
            $param = $param."&client=$file_client";
        }
    }
    
    $fclient_characteristic = stripslashes($_REQUEST['fclient_characteristic']);
    $fclient_characteristic = mysqli_real_escape_string($conn, $fclient_characteristic);
    if(isset($fclient_characteristic) && $fclient_characteristic !== ''){
        if(isset($param) && $param === ''){
            $param = $param."ccharacteristic=$fclient_characteristic";
        } else{
            $param = $param."&ccharacteristic=$fclient_characteristic";
        }
    }
    
    $file_opponent = stripslashes($_REQUEST['file_opponent']);
    $file_opponent = mysqli_real_escape_string($conn, $file_opponent);
    if(isset($file_opponent) && $file_opponent !== ''){
        if(isset($param) && $param === ''){
            $param = $param."opponent=$file_opponent";
        } else{
            $param = $param."&opponent=$file_opponent";
        }
    }
    
    $fopponent_characteristic = stripslashes($_REQUEST['fopponent_characteristic']);
    $fopponent_characteristic = mysqli_real_escape_string($conn, $fopponent_characteristic);
    if(isset($fopponent_characteristic) && $fopponent_characteristic !== ''){
        if(isset($param) && $param === ''){
            $param = $param."ocharacteristic=$fopponent_characteristic";
        } else{
            $param = $param."&ocharacteristic=$fopponent_characteristic";
        }
    }
    
    $flegal_advisor = stripslashes($_REQUEST['flegal_advisor']);
    $flegal_advisor = mysqli_real_escape_string($conn, $flegal_advisor);
    if(isset($flegal_advisor) && $flegal_advisor !== ''){
        if(isset($param) && $param === ''){
            $param = $param."advisor=$flegal_advisor";
        } else{
            $param = $param."&advisor=$flegal_advisor";
        }
    }
    
    $flegal_researcher = stripslashes($_REQUEST['flegal_researcher']);
    $flegal_researcher = mysqli_real_escape_string($conn, $flegal_researcher);
    if(isset($flegal_researcher) && $flegal_researcher !== ''){
        if(isset($param) && $param === ''){
            $param = $param."researcher=$flegal_researcher";
        } else{
            $param = $param."&researcher=$flegal_researcher";
        }
    }
    
    $fcase_type = stripslashes($_REQUEST['fcase_type']);
    $fcase_type = mysqli_real_escape_string($conn, $fcase_type);
    if(isset($fcase_type) && $fcase_type !== ''){
        if(isset($param) && $param === ''){
            $param = $param."ctype=$fcase_type";
        } else{
            $param = $param."&ctype=$fcase_type";
        }
    }
    
    $file_prosecution = stripslashes($_REQUEST['file_prosecution']);
    $file_prosecution = mysqli_real_escape_string($conn, $file_prosecution);
    if(isset($file_prosecution) && $file_prosecution !== ''){
        if(isset($param) && $param === ''){
            $param = $param."prosec=$file_prosecution";
        } else{
            $param = $param."&prosec=$file_prosecution";
        }
    }
    
    $fpolice_station = stripslashes($_REQUEST['fpolice_station']);
    $fpolice_station = mysqli_real_escape_string($conn, $fpolice_station);
    if(isset($fpolice_station) && $fpolice_station !== ''){
        if(isset($param) && $param === ''){
            $param = $param."station=$fpolice_station";
        } else{
            $param = $param."&station=$fpolice_station";
        }
    }
    
    $file_court = stripslashes($_REQUEST['file_court']);
    $file_court = mysqli_real_escape_string($conn, $file_court);
    if(isset($file_court) && $file_court !== ''){
        if(isset($param) && $param === ''){
            $param = $param."court=$file_court";
        } else{
            $param = $param."&court=$file_court";
        }
    }
    
    $opp_date = stripslashes($_REQUEST['opp_date']);
    $opp_date = mysqli_real_escape_string($conn, $opp_date);
    if(isset($opp_date) && $opp_date !== ''){
        if(isset($param) && $param === ''){
            $param = $param."opp=$opp_date";
        } else{
            $param = $param."&opp=$opp_date";
        }
    }
    
    $session_degree = stripslashes($_REQUEST['session_degree']);
    $session_degree = mysqli_real_escape_string($conn, $session_degree);
    if(isset($session_degree) && $session_degree !== ''){
        if(isset($param) && $param === ''){
            $param = $param."deg=$session_degree";
        } else{
            $param = $param."&deg=$session_degree";
        }
    }
    
    $case_num = stripslashes($_REQUEST['case_num']);
    $case_num = mysqli_real_escape_string($conn, $case_num);
    if(isset($case_num) && $case_num !== ''){
        if(isset($param) && $param === ''){
            $param = $param."cno=$case_num";
        } else{
            $param = $param."&cno=$case_num";
        }
    }
    
    $year = stripslashes($_REQUEST['year']);
    $year = mysqli_real_escape_string($conn, $year);
    if(isset($year) && $year !== ''){
        if(isset($param) && $param === ''){
            $param = $param."year=$year";
        } else{
            $param = $param."&year=$year";
        }
    }
    
    $jud_session = stripslashes($_REQUEST['jud_session']);
    $jud_session = mysqli_real_escape_string($conn, $jud_session);
    if(isset($jud_session) && $jud_session !== ''){
        if(isset($param) && $param === ''){
            $param = $param."jud=$jud_session";
        } else{
            $param = $param."&jud=$jud_session";
        }
    }
    
    $from_date = stripslashes($_REQUEST['from_date']);
    $from_date = mysqli_real_escape_string($conn, $from_date);
    if(isset($from_date) && $from_date !== ''){
        if(isset($param) && $param === ''){
            $param = $param."from=$from_date";
        } else{
            $param = $param."&from=$from_date";
        }
    }
    
    $to_date = stripslashes($_REQUEST['to_date']);
    $to_date = mysqli_real_escape_string($conn, $to_date);
    if(isset($to_date) && $to_date !== ''){
        if(isset($param) && $param === ''){
            $param = $param."to=$to_date";
        } else{
            $param = $param."&to=$to_date";
        }
    }
    
    $extended = stripslashes($_REQUEST['extended']);
    $extended = mysqli_real_escape_string($conn, $extended);
    if(isset($extended) && $extended !== ''){
        if(isset($param) && $param === ''){
            $param = $param."extended=$extended";
        } else{
            $param = $param."&extended=$extended";
        }
    }
    
    $pending = stripslashes($_REQUEST['pending']);
    $pending = mysqli_real_escape_string($conn, $pending);
    if(isset($pending) && $pending !== ''){
        if(isset($param) && $param === ''){
            $param = $param."pending=$pending";
        } else{
            $param = $param."&pending=$pending";
        }
    }
    
    $fwork = stripslashes($_REQUEST['fwork']);
    $fwork = mysqli_real_escape_string($conn, $fwork);
    if(isset($fwork) && $fwork !== ''){
        if(isset($param) && $param === ''){
            $param = $param."fwork=$fwork";
        } else{
            $param = $param."&fwork=$fwork";
        }
    }
    
    $archived = stripslashes($_REQUEST['archived']);
    $archived = mysqli_real_escape_string($conn, $archived);
    if(isset($archived) && $archived !== ''){
        if(isset($param) && $param === ''){
            $param = $param."archived=$archived";
        } else{
            $param = $param."&archived=$archived";
        }
    }
    
    $judge = stripslashes($_REQUEST['judge']);
    $judge = mysqli_real_escape_string($conn, $judge);
    if(isset($judge) && $judge !== ''){
        if(isset($param) && $param === ''){
            $param = $param."judge=$judge";
        } else{
            $param = $param."&judge=$judge";
        }
    }
    
    header("Location: AdvancedSearch.php?$param");
    exit();
?>