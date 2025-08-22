<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    $fid = filter_input(INPUT_POST, 'fid', FILTER_SANITIZE_NUMBER_INT);
    
    $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
    $stmtid->bind_param("i", $fid);
    $stmtid->execute();
    $resultid = $stmtid->get_result();
    $row_details = $resultid->fetch_assoc();
    $stmtid->close();
    if($admin != 1){
        if($row_details['secret_folder'] == 1){
            $empids = $row_details['secret_emps'];
            $empids = array_filter(array_map('trim', explode(',', $empids)));
            if (!in_array($_SESSION['id'], $empids)) {
                exit();
            }
        }
    }
    if(isset($_REQUEST['booked_todate']) && $_REQUEST['booked_todate'] !== ''){
        if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
            
            $flag = '0';
            $action = "تم حجز جلسة للحكم :<br>رقم الملف : $fid";
            
            $date = filter_input(INPUT_POST, "booked_todate", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($date) && $date !== ''){
                $flag = '1';
                
                $action = $action."<br>لتاريخ : $date";
            }
            
            $degree_id_sess = filter_input(INPUT_POST, "degree_id_sess", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($degree_id_sess) && $degree_id_sess !== ''){
                $flag = '1';
                
                $action = $action."<br>درجة التقاضي : $degree_id_sess";
            }
                
            list($ycn, $degree) = explode('-', $degree_id_sess);
            list($year, $case_num) = explode('/', $ycn);
            
            $booked_detail = filter_input(INPUT_POST, "booked_detail", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($booked_detail) && $booked_detail !== ''){
                $flag = '1';
                
                $action = $action."<br>التفاصيل : $booked_detail";
            }
            
            if(isset($_REQUEST['amount']) && $_REQUEST['amount'] !== ''){
                $file_amount = filter_input(INPUT_POST, "amount", FILTER_SANITIZE_NUMBER_INT);
            } else{
                $file_amount = '0';
            }
            if(isset($file_amount) && $file_amount === '1'){
                $flag = '1';
                
                $fa = 'اكثر من 500,000';
                $action = $action."<br>المبلغ : $fa";
            }
            
            $jud_session = 1;
            $timestamp = date("Y-m-d");
            
            $decission = filter_input(INPUT_POST, "decission", FILTER_SANITIZE_NUMBER_INT);
            if(isset($decission) && $decission !== ''){
                $flag = '1';
                
                if($decission === '0'){
                    $dec = 'رفض';
                } else if($decission === '1'){
                    $dec = 'قبول';
                }
                $action = $action."<br>قرار القاضي : $dec";
            }
            
            $stmtread = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtread->bind_param("i", $fid);
            $stmtread->execute();
            $resultread = $stmtread->get_result();
            $rowread = $resultread->fetch_assoc();
            $stmtread->close();
            
            $days = 0;
            $resume_appeal = '0';
            if(isset($rowread['file_type']) && $rowread['file_type'] !== ''){
                $filetype = $rowread['file_type'];
            }
            if($filetype === 'مدني -عمالى' && $degree === 'ابتدائي'){
                $resume_appeal = '1';
                $days = 30;
            } else if($filetype === 'مدني -عمالى' && $file_amount === '1' && $degree === 'استئناف'){
                $resume_appeal = '2';
                $days = 30;
            } else if($filetype === 'أحوال شخصية' && $degree === 'ابتدائي'){
                $resume_appeal = '1';
                $days = 30;
            } else if($filetype === 'أحوال شخصية' && $degree === 'استئناف'){
                $resume_appeal = '2';
                $days = 30;
            } else if($filetype === 'جزاء' && $degree === 'ابتدائي'){
                $resume_appeal = '1';
                $days = 15;
            } else if($filetype === 'جزاء' && $degree === 'استئناف'){
                $resume_appeal = '2';
                $days = 30;
            } else if($degree === 'امر على عريضة' || $degree === 'حجز تحفظي'){
                if($decission === '1'){
                    $days = 7;
                } else if($decission === '0'){
                    $resume_appeal = '3';
                    $days = 15;
                }
            } else if($degree === 'معارضة' && $filetype === 'جزاء') {
                $resume_appeal = 1;
                $days = 15;
            } else if($degree === 'امر اداء'){
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
            
            $stmt1 = $conn->prepare("INSERT INTO session (session_fid, jud_session, session_date, session_details, session_degree, year, case_num, timestamp, resume_appeal, resume_overdue, resume_daysno, file_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt1->bind_param("iisssiisisii", $fid, $jud_session, $date, $booked_detail, $degree, $year, $case_num, $timestamp, $resume_appeal, $newDateFormatted, $differenceDays, $file_amount);
            $stmt1->execute();
            $stmt1->close();
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            header("Location: BookedJud.php?fid=$fid&success=1");
            exit();
            
        }
    }
    header("Location: BookedJud.php?fid=$fid");
    exit();
?>