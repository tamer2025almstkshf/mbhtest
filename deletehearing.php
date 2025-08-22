<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'golden_check.php';
    include_once 'errorscheck.php';
    
    if($row_permcheck['session_dperm'] == 1){
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $page = $_GET['page'];
            if($page === 'judgedHearings.php'){
                $queryString = "tw=1";
            } else if($page === 'hearing.php'){
                $queryString = '';
                if(isset($_GET['from'])){
                    if($queryString === ''){
                        $queryString = "from=".$_GET['from'];
                    } else{
                        $queryString = $queryString.'&from='.$_GET['form'];
                    }
                }
                if(isset($_GET['to'])){
                    if($queryString === ''){
                        $queryString = "to=".$_GET['to'];
                    } else{
                        $queryString = $queryString.'&to='.$_GET['to'];
                    }
                }
                if(isset($_GET['court'])){
                    if($queryString === ''){
                        $queryString = "court=".$_GET['court'];
                    } else{
                        $queryString = $queryString.'&court='.$_GET['court'];
                    }
                }
                if(isset($_GET['tw'])){
                    $queryString = "tw=1";
                }
            }
            $sid = $_GET['id'];
            
            $stmtr = $conn->prepare("SELECT * FROM session WHERE session_id=?");
            $stmtr->bind_param("i", $sid);
            $stmtr->execute();
            $resultr = $stmtr->get_result();
            $rowr = $resultr->fetch_assoc();
            $stmtr->close();
            
            $fid = $rowr['session_fid'];
            
            $flag = '0';
            $action = "تم حذف جلسة :<br>رقم الملف : $fid<br>";
            
            $oldjuds = $rowr['jud_session'];
            if($oldjuds === '1'){
                $flag = '1';
                
                $action = $action."<br>حجزت للحكم : نعم";
            } else{
                $flag = '1';
                
                $action = $action."<br>حجزت للحكم : لا";
            }
            
            $resume_appeal = $rowr['resume_appeal'];
            
            if(isset($resume_appeal) && $resume_appeal !== '' && $resume_appeal !== '0'){
                if($resume_appeal === '1'){
                    $ra = 'حذف استئناف';
                    $ran = 'تم حذف الاستئناف بناءا على طلب الموكل';
                } else if($resume_appeal === '2'){
                    $ra = 'حذف طعن';
                    $ran = 'تم حذف الطعن بناءا على طلب الموكل';
                } else if($resume_appeal === '3'){
                    $ra = 'حذف تظلم';
                    $ran = 'تم حذف تظلم بناءا على طلب الموكل';
                } else if($resume_appeal === '4'){
                    $ra = 'حذف معارضة';
                    $ran = 'تم حذف المعارضة بناءا على طلب الموكل';
                } else{
                    $ra = '';
                    $ran = '';
                }
                
                $flag = '1';
                
                $action = $action."<br>$ra";
                
                $id = $_SESSION['id'];
                $stmtd = $conn->prepare("SELECT * FROM user WHERE id=?");
                $stmtd->bind_param("i", $id);
                $stmtd->execute();
                $resultd = $stmtd->get_result();
                $rowd = $resultd->fetch_assoc();
                $stmtd->close();
                $name = $rowd['name'];
                
                $timestamp = date("Y-m-d");
                $stmt2 = $conn->prepare("INSERT INTO file_note (file_id, notename, note, doneby, timestamp) VALUES (?, ?, ?, ?, ?)");
                $stmt2->bind_param("issss", $fid, $ra, $ran, $name, $timestamp);
                $stmt2->execute();
                $stmt2->close();
            }
            
            $oldoverd = $rowr['resume_overdue'];
            if(isset($oldoverd) && $oldoverd !== ''){
                $flag = '1';
                
                $action = $action."<br>حجزت لتاريخ : $oldoverd";
            }
            
            $olddno = $rowr['resume_daysno'];
            if(isset($olddno) && $olddno !== ''){
                $flag = '1';
                
                $action = $action."<br>عدد الايام المتبقية : $olddno";
            }
            
            $olddet = $rowr['session_details'];
            if(isset($olddet) && $olddet !== ''){
                $flag = '1';
                
                $action = $action."<br>تفاصيل الجلسة : $olddet";
            }
            
            $olddno = $rowr['resume_daysno'];
            if(isset($olddno) && $olddno !== ''){
                $flag = '1';
                
                $action = $action."<br>عدد الايام المتبقية : $olddno";
            }
            
            $olddeg = $rowr['session_degree'];
            $oldcno = $rowr['case_num'];
            $oldyr = $rowr['year'];
            if(isset($olddeg) && $olddeg !== ''){
                $flag = '1';
                
                $action = $action."<br>درجة التقاضي : $oldyr/$oldcno-$olddeg";
            }
            
            $oldtr = $rowr['session_trial'];
            if(isset($oldtr) && $oldtr !== ''){
                $flag = '1';
                
                $action = $action."<br>منطوق الحكم : $oldtr";
            }
            
            $oldex = $rowr['expert_session'];
            if($oldex === '1'){
                $flag = '1';
                
                $action = $action."<br>جلسة خبرة : نعم";
            } else{
                $flag = '1';
                
                $action = $action."<br>جلسة خبرة : لا";
            }
            
            $oldexn = $rowr['expert_name'];
            if(isset($oldexn) && $oldexn !== ''){
                $flag = '1';
                
                $action = $action."<br>اسم الخبير : $oldexn";
            }
            
            $oldexp = $rowr['expert_phone'];
            if(isset($oldexp) && $oldexp !== ''){
                $flag = '1';
                
                $action = $action."<br>هاتف الخبير : $oldexp";
            }
            
            $oldexa = $rowr['expert_amount'];
            if(isset($oldexa) && $oldexa !== ''){
                $flag = '1';
                
                $action = $action."<br>مبلغ امانة الخبير : $oldexa";
            }
            
            $oldexad = $rowr['expert_address'];
            if(isset($oldexad) && $oldexad !== ''){
                $flag = '1';
                
                $action = $action."<br>عنوان الخبير : $oldexad";
            }
            
            $rcc = $rowr['referral_reconciliation'];
            if($rcc = '1'){
                $flag = '1';
                
                $action = $action."<br>تمت الاحالة";
            } else if($rcc = '2'){
                $flag = '1';
                
                $action = $action."<br>تم الصلح";
            }
            
            if(isset($flag) && $flag !== ''){
                include_once 'addlog.php';
            }
            
            $stmtd = $conn->prepare("DELETE FROM session WHERE session_id=?");
            $stmtd->bind_param("i", $sid);
            $stmtd->execute();
            $stmtd->close();
        } else{
            if(isset($_GET['page']) && $_GET['page'] !== ''){
                $page = filter_input(INPUT_GET, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                if($page === 'judgedHearings.php'){
                $queryString = "tw=1";
                } else if($page === 'hearing.php'){
                    $queryString = '';
                    if(isset($_GET['from'])){
                        if($queryString === ''){
                            $queryString = "from=".$_GET['from'];
                        } else{
                            $queryString = $queryString.'&from='.$_GET['form'];
                        }
                    }
                    if(isset($_GET['to'])){
                        if($queryString === ''){
                            $queryString = "to=".$_GET['to'];
                        } else{
                            $queryString = $queryString.'&to='.$_GET['to'];
                        }
                    }
                    if(isset($_GET['court'])){
                        if($queryString === ''){
                            $queryString = "court=".$_GET['court'];
                        } else{
                            $queryString = $queryString.'&court='.$_GET['court'];
                        }
                    }
                    if(isset($_GET['tw'])){
                        $queryString = "tw=1";
                    }
                }
            } else{
                $page = filter_input(INPUT_POST, "page", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                
                if($page === 'judgedHearings.php'){
                    $queryString = "tw=1";
                } else if($page === 'hearing.php'){
                    $queryString = '';
                    
                    $from = filter_input(INPUT_POST, "from", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $to = filter_input(INPUT_POST, "to", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $court = filter_input(INPUT_POST, "court", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    $tw = filter_input(INPUT_POST, "tw", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
                    
                    if(isset($from) && $from !== '') {
                        if($queryString === ''){
                            $queryString = "from=".$from;
                        } else{
                            $queryString = $queryString.'&from='.$from;
                        }
                    }
                    if(isset($to) && $to !== '') {
                        if($queryString === ''){
                            $queryString = "to=".$to;
                        } else{
                            $queryString = $queryString.'&to='.$to;
                        }
                    }
                    if(isset($court) && $court !== '') {
                        if($queryString === ''){
                            $queryString = "court=".$court;
                        } else{
                            $queryString = $queryString.'&court='.$court;
                        }
                    }
                    if(isset($tw) && $tw !== ''){
                        $queryString = "tw=1";
                    }
                }
            }
            header("Location: $page?$queryString");
            exit();
        }
    }
    header("Location: $page?$queryString");
    exit();
?>