<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php';
    
    $session_id = filter_input(INPUT_POST, 'session_id', FILTER_SANITIZE_NUMBER_INT);
    if(isset($_REQUEST['booked_todate']) && $_REQUEST['booked_todate'] !== ''){
        if($row_permcheck['session_aperm'] == 1 || $row_permcheck['session_eperm'] == 1){
            $fid = filter_input(INPUT_POST, 'session_fid', FILTER_SANITIZE_NUMBER_INT);
            
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
            
            $flag = '0';
            $action = "تم مد اجل الجلسة : <br>رقم الملف : $fid<br>";
            
            $stmt_get = $conn->prepare("SELECT * FROM session WHERE session_id=?");
            $stmt_get->bind_param("i", $session_id);
            $stmt_get->execute();
            $result_get = $stmt_get->get_result();
            $row_get = $result_get->fetch_assoc();
            $stmt_get->close();
            
            $degree_id_sess = $row_get['session_degree'];
            $year = $row_get['year'];
            $case_num = $row_get['case_num'];
            if(isset($degree_id_sess) && $degree_id_sess !== ''){
                $flag = '1';
                
                $action = $action."<br>درجة التقاضي : $year/$case_num-$degree_id_sess";
            }
            
            $olddate = $row_get['session_date'];
            $session_trial = $row_get['session_trial'];
            $resume_appeal = $row_get['resume_appeal'];
            
            $session_fid = filter_input(INPUT_POST, 'session_fid', FILTER_SANITIZE_NUMBER_INT);
            $date = filter_input(INPUT_POST, 'booked_todate', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($date) && $date !== ''){
                $flag = '1';
                
                $action = $action."<br>تم مد اجل الجلسة : من تاريخ $olddate حتى $date";
            }
            
            $booked_detail = filter_input(INPUT_POST, 'booked_detail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if(isset($booked_detail) && $booked_detail !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل الجلسة : $booked_detail";
            }
            
            $jud_dec = 0;
            $file_amount = 0;
            
            $jud_session = 1;
            $timestamp = date("Y-m-d");
            $extended = '1';
            
            $stmtread = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtread->bind_param("i", $fid);
            $stmtread->execute();
            $resultread = $stmtread->get_result();
            $rowread = $resultread->fetch_assoc();
            $stmtread->close();
            
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
                if($jud_dec === '1'){
                    $days = 7;
                } else if($jud_dec === '0'){
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
            
            if(isset($flag) && $flag === '1'){
                include_once 'addlog.php';
            }
            
            $stmt = $conn->prepare("INSERT INTO session (session_fid, jud_session, session_degree, year, case_num, session_trial, session_date, extended, session_details, timestamp, resume_appeal, resume_overdue, resume_daysno, jud_dec, file_amount) VALUES 
            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iisiississisiii", $session_fid, $jud_session, $degree_id_sess, $year, $case_num, $session_trial, $date, $extended, $booked_detail, $timestamp, $resume_appeal, $newDateFormatted, $differenceDays, $jud_dec, $file_amount);
            $stmt->execute();
            $stmt->close();
        }
    }
    header("Location: ExtendJud.php?sid=$session_id");
    exit();
?>