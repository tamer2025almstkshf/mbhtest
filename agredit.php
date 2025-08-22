<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    if($row_permcheck['agr_eperm'] === '1'){
        if(isset($_REQUEST['client_name']) && $_REQUEST['client_name'] !== ''){
            
            $id = stripslashes($_REQUEST['id']);
            $id = mysqli_real_escape_string($conn, $id);
            
            $flag = '0';
            $action = 'تم تغيير بيانات احد الاتفاقيات : <br>';
            
            $querycheck = "SELECT * FROM consultations WHERE id='$id'";
            $resultcheck = mysqli_query($conn,$querycheck);
            $rowcheck = mysqli_fetch_array($resultcheck);
            
            $oldname = $rowcheck['client_name'];
            $oldnationality = $rowcheck['nationality'];
            $oldtelno = $rowcheck['telno'];
            $oldemail = $rowcheck['email'];
            $oldot1 = $rowcheck['others1'];
            $oldot2 = $rowcheck['others2'];
            $oldot3 = $rowcheck['others3'];
            $oldplace = $rowcheck['place'];
            $oldway = $rowcheck['way'];
            $oldsign_date = $rowcheck['sign_date'];
            
            $client_name = stripslashes($_REQUEST['client_name']);
            $client_name = mysqli_real_escape_string($conn, $client_name);
            if(isset($client_name) && $client_name !== $oldname){
                $flag = '1';
                
                $action = $action."<br>تم تغيير اسم الموكل : من $oldname الى $client_name";
            }
            
            $nationality = stripslashes($_REQUEST['nationality']);
            $nationality = mysqli_real_escape_string($conn, $nationality);
            if(isset($nationality) && $nationality !== $oldnationality){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الجنسية : من $oldnationality الى $nationality";
            }
            
            $telno = stripslashes($_REQUEST['telno']);
            $telno = mysqli_real_escape_string($conn, $telno);
            if(isset($telno) && $telno !== $oldtelno){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الهاتف : من $oldtelno الى $telno";
            }
            
            $email = stripslashes($_REQUEST['email']);
            $email = mysqli_real_escape_string($conn, $email);
            if(isset($email) && $email !== $oldemail){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الايميل : من $oldemail الى $email";
            }
            
            $others1 = stripslashes($_REQUEST['others1']);
            $others1 = mysqli_real_escape_string($conn, $others1);
            if(isset($others1) && $others1 !== $oldot1){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$oldot1'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname1 = $rowc['name'];
                
                $queryc = "SELECT * FROM user WHERE id='$others1'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>تم تغيير الحضور : من $oname1 الى $oname";
            }
            
            $others2 = stripslashes($_REQUEST['others2']);
            $others2 = mysqli_real_escape_string($conn, $others2);
            if(isset($others2) && $others2 !== $oldot2){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$oldot2'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname1 = $rowc['name'];
                
                $queryc = "SELECT * FROM user WHERE id='$others2'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>تم تغيير الحضور : من $oname1 الى $oname";
            }
            
            $others3 = stripslashes($_REQUEST['others3']);
            $others3 = mysqli_real_escape_string($conn, $others3);
            if(isset($others3) && $others3 !== $oldot3){
                $flag = '1';
                
                $queryc = "SELECT * FROM user WHERE id='$oldot3'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname1 = $rowc['name'];
                
                $queryc = "SELECT * FROM user WHERE id='$others3'";
                $resultc = mysqli_query($conn, $queryc);
                $rowc = mysqli_fetch_array($resultc);
                $oname = $rowc['name'];
                
                $action = $action."<br>تم تغيير الحضور : من $oname1 الى $oname";
            }
            
            $place = stripslashes($_REQUEST['place']);
            $place = mysqli_real_escape_string($conn, $place);
            if(isset($place) && $place !== $oldplace){
                $flag = '1';
                
                $action = $action."<br>تم تغيير الفرع : من $oldplace الى $place";
            }
            
            $way = stripslashes($_REQUEST['way']);
            $way = mysqli_real_escape_string($conn, $way);
            if(isset($way) && $way !== $oldway){
                $flag = '1';
                
                $action = $action."<br>تم تغيير (كيف عرفت عن المكتب) : من $oldway الى $way";
            }
            
            $sign_date = stripslashes($_REQUEST['sign_date']);
            $sign_date = mysqli_real_escape_string($conn, $sign_date);
            if(isset($sign_date) && $sign_date !== $oldcons_type){
                $flag = '1';
                
                $action = $action."<br>تم تغيير تاريخ توقيع الاتفاقية : من $oldsign_date الى $sign_date";
            }
            
            $query22 = "UPDATE consultations SET client_name='$client_name', nationality='$nationality', telno='$telno', email='$email', others1='$others1', others2='$others2', others3='$others3', place='$place', way='$way', sign_date='$sign_date' WHERE id='$id'";
            $result22 = mysqli_query($conn, $query22);
            
            if($flag === '1'){
                $empid = $_SESSION['id'];
                
                $queryu = "SELECT * FROM user WHERE id='$empid'";
                $resultu = mysqli_query($conn, $queryu);
                $rowu = mysqli_fetch_array($resultu);
                $emp_name = $rowu['name'];
                
                $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
                $resultlog = mysqli_query($conn, $querylog);
            }
            
            $submit_back = stripslashes($_REQUEST['submit_back']);
            $submit_back = mysqli_real_escape_string($conn, $submit_back);
            
            if(isset($submit_back) && $submit_back === 'addmore'){
                header("Location: Agreements.php?agredited=1&addmore=1");
                exit();
            } else{
                header("Location: Agreements.php?agredited=1");
                exit();
            }
        } else{
            header("Location: Agreements.php?error=0");
            exit();
        }
    }
    header("Location: Agreements.php");
    exit();
?>