<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    /** @var mysqli $conn */
    
    if(isset($_REQUEST['booked_todate']) && $_REQUEST['booked_todate'] !== ''){
        
        $fid = '179';
        
        $session_id = stripslashes($_REQUEST['session_id']);
        $session_id = mysqli_real_escape_string($conn, $session_id);
        
        $query_get = "SELECT * FROM session WHERE session_id='$session_id'";
        $result_get = mysqli_query($conn, $query_get);
        $row_get = mysqli_fetch_array($result_get);
        
        $degree_id_sess = stripslashes($_REQUEST['degree_id_sess']);
        $degree_id_sess = mysqli_real_escape_string($conn, $degree_id_sess);
        
        $session_trial = $row_get['session_trial'];
        $resume_appeal = $row_get['resume_appeal'];
        
        $session_fid = stripslashes($_REQUEST['session_fid']);
        $session_fid = mysqli_real_escape_string($conn, $session_fid);
        
        $booked_todate = stripslashes($_REQUEST['booked_todate']);
        $booked_todate = mysqli_real_escape_string($conn, $booked_todate);
        
        list($d, $m, $y) = explode("/", $booked_todate);
        $date = "$y-$m-$d";
        
        $booked_detail = stripslashes($_REQUEST['booked_detail']);
        $booked_detail = mysqli_real_escape_string($conn, $booked_detail);
        
        $decission = 0;
        $file_amount = 0;
        
        $jud_session = 1;
        $timestamp = date("d-m-Y");
        $extended = '1';
        
        $queryread = "SELECT * FROM file WHERE file_id='$fid'";
        $resultread = mysqli_query($conn, $queryread);
        $rowread = mysqli_fetch_array($resultread);
        
        if(isset($rowread['file_type']) && $rowread['file_type'] !== ''){
            $filetype = $rowread['file_type'];
        }
        
        if($filetype === 'مدني -عمالى' && $degree_id_sess === 'ابتدائي'){
            $resume_appeal = '1';
            $days = 30;
        } else if($filetype === 'مدني -عمالى' && $file_amount === '1' && $degree_id_sess === 'استئناف'){
            $resume_appeal = '2';
            $days = 30;
        } else if($filetype === 'أحوال شخصية' && $degree_id_sess === 'ابتدائي'){
            $resume_appeal = '1';
            $days = 30;
        } else if($filetype === 'أحوال شخصية' && $degree_id_sess === 'استئناف'){
            $resume_appeal = '2';
            $days = 30;
        } else if($filetype === 'جزاء' && $degree_id_sess === 'ابتدائي'){
            $resume_appeal = '1';
            $days = 15;
        } else if($filetype === 'جزاء' && $degree_id_sess === 'استئناف'){
            $resume_appeal = '2';
            $days = 30;
        } else if($degree_id_sess === 'امر على عريضة' || $degree_id_sess === 'حجز تحفظي'){
            if($decission === '1'){
                $days = 7;
            } else if($decission === '0'){
                $resume_appeal = '3';
                $days = 15;
            }
        } else if($degree_id_sess === 'معارضة' && $filetype === 'جزاء') {
            $resume_appeal = 1;
            $days = 15;
        } else if($degree_id_sess === 'امر اداء'){
            if($file_amount === '2'){
                $resume_appeal = '1';
                $days = 30;
            } else{
                $resume_appeal = '3';
                $days = 15;
            }
        }
        
        $newDate = new DateTime($date);
        $newDate->modify("+$days days");
        $newDateFormatted = $newDate->format('Y-m-d');
        
        $today = new DateTime();
        $todayFormatted = $today->format('Y-m-d');

        $newTodayObject = new DateTime($todayFormatted);
        $newDateObject = new DateTime($newDateFormatted);
        $difference = $newTodayObject->diff($newDateObject);
        $differenceDays = $difference->days;
        
        echo $date . ' --=-- ' . $differenceDays . ' --=-- ' . $newDateFormatted . '{' . $days . '}' . ' --=-- ' . $resume_appeal . ' --=-- filetype:' . $filetype . ' --=-- degree:' . $degree_id_sess;
        exit();
        
        $query = "INSERT INTO session (session_fid, jud_session, session_degree, session_trial, session_date, extended, session_details, timestamp, resume_appeal, resume_overdue, resume_daysno, decission, file_amount) VALUES ('$session_fid', '$jud_session', '$degree_id_sess', '$session_trial', '$date', '$extended', '$booked_detail', '$timestamp', '$resume_appeal', '$newDateFormatted', '$differenceDays', '$decission', '$file_amount')";
        $result = mysqli_query($conn, $query);
        
        header("Location: ExtendJud.php?sid=$session_id");
        exit();
    }
?>