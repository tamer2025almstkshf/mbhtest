<?php
    function formatTimeDiff($seconds) {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remaining_seconds = $seconds % 60;
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $remaining_seconds);
    }
    
    $timermainid = (int)filter_input(INPUT_POST, 'timermainid', FILTER_SANITIZE_NUMBER_INT);
    if(!isset($timermainid)){
        $timermainid = '';
    }
    
    $timersubid = (int)filter_input(INPUT_POST, 'timersubid', FILTER_SANITIZE_NUMBER_INT);
    if(!isset($timersubid)){
        $timersubid = '';
    }
    
    if(isset($timer_flag) && $timer_flag !== ''){
        $timeraction = $timer_flag;
    } else{
        $timeraction = filter_input(INPUT_POST, 'timeraction', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if(!isset($timeraction)){
        $timeraction = '';
    }
    
    if(isset($timersubinfo_flag) && $timersubinfo_flag !== ''){
        $timersubinfo = $timersubinfo_flag;
    } else{
        $timersubinfo = filter_input(INPUT_POST, 'timersubinfo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if(!isset($timersubinfo)){
        $timersubinfo = '';
    }
    
    $timerdone_date = filter_input(INPUT_POST, 'timerdone_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if(!isset($timerdone_date)){
        $timerdone_date = '';
    }
    
    if(isset($timer_flag) && $timer_flag !== ''){
        if($timer_flag === 'file_edit'){
            $timerdone_action = 'تعديل بيانات الملف رقم '.$timermainid;
        } else if($timer_flag === 'degree_add'){
            $timerdone_action = 'اضافة درجة تقاضي على الملف رقم '.$timermainid;
        } else if($timer_flag === 'judicialwarn_add'){
            $timerdone_action = 'اضافة انذار عدلي على الملف رقم '.$timermainid;
        } else if($timer_flag === 'hearing_add'){
            $timerdone_action = 'اضافة جلسة جديدة على الملف رقم '.$timermainid;
        } else if($timer_flag === 'task_add'){
            $timerdone_action = 'تكليف موظف بمهمة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'execution_add'){
            $timerdone_action = 'اضافة تنفيذ جديد في الملف رقم '.$timermainid;
        } else if($timer_flag === 'petition_add'){
            $timerdone_action = 'اضافة امر على عريضة جديد على الملف رقم '.$timermainid;
        } else if($timer_flag === 'hearing_referral'){
            $timerdone_action = 'تعديل حالة الجلسة بالملف رقم '.$timermainid.' الى تمت الاحالة';
        } else if($timer_flag === 'hearing_reconciliation'){
            $timerdone_action = 'تعديل حالة الجلسة بالملف رقم '.$timermainid.' الى تم الصلح';
        } else if($timer_flag === 'file_investigationstart'){
            $timerdone_action = 'تعديل حالة الملف رقم '.$timermainid.' الى قيد التحقيق';
        } else if($timer_flag === 'file_investigationdone'){
            $timerdone_action = 'انهاء التحقيق بالملف رقم '.$timermainid;
        } else if($timer_flag === 'hearing_edit'){
            $timerdone_action = 'التعديل على جلسة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'task_edit'){
            $timerdone_action = 'التعديل على مهمة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'petition_edit'){
            $timerdone_action = 'التعديل على الامر على عريضة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'execution_edit'){
            $timerdone_action = 'التعديل على تنفيذ في الملف رقم '.$timermainid;
        } else if($timer_flag === 'document_edit'){
            $timerdone_action = 'التعديل على مذكرة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'judicialwarn_edit'){
            $timerdone_action = 'التعديل على الانذار العدلي في الملف رقم '.$timermainid;
        } else if($timer_flag === 'hearingreq_add'){
            $timerdone_action = 'اضافة طلب على جلسة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'hearingreq_edit'){
            $timerdone_action = 'تعديل طلب على جلسة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'doc_approval'){
            $timerdone_action = 'اعتماد مذكرة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'case_edit'){
            $timerdone_action = 'طلب تعديلات على مذكرة في الملف رقم '.$timermainid;
        } else if($timer_flag === 'doc_refusing'){
            $timerdone_action = 'ارجاع مذكرة في الملف رقم '.$timermainid;
        }
    } else{
        $timerdone_action = filter_input(INPUT_POST, 'timerdone_action', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    }
    if(!isset($timerdone_action)){
        $timerdone_action = '';
    }
    
    $timer_timestamp = (int)filter_input(INPUT_POST, 'timer_timestamp', FILTER_SANITIZE_NUMBER_INT);
    if(!isset($timer_timestamp)){
        $timer_timestamp = '';
    }
    
    $end_timestamp = time();
    $time_diff = $end_timestamp - $timer_timestamp;
    $elapsed_time = formatTimeDiff($time_diff);
    
    if(isset($timermainid) && $timermainid !== '' && isset($timeraction) && $timeraction !== '' && isset($timerdone_action) && $timerdone_action !== ''){
        $stmttimer = $conn->prepare("INSERT INTO working_time (emp_id,  mainid, subid, action, subinfo, done_date, done_action, duration) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmttimer->bind_param("iiisssss", $_SESSION['id'], $timermainid, $timersubid, $timeraction, $timersubinfo, $timerdone_date, $timerdone_action, $elapsed_time);
        $stmttimer->execute();
    }
?>