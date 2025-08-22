<?php
    include_once 'connection.php';
    include_once 'login_check.php';
        
    $id = stripslashes($_REQUEST['id']);
    $id = mysqli_real_escape_string($conn, $id);
    
    if(isset($_REQUEST['vtype']) && $_REQUEST['vtype'] !== ''){
        
        $emp_id = $_SESSION['id'];
        
        $queryu = "SELECT * FROM user WHERE id='$emp_id'";
        $resultu = mysqli_query($conn, $queryu);
        $rowu = mysqli_fetch_array($resultu);
        $emp_name = $rowu['name'];
        
        $flag = '0';
        $action = "تم تعديل بيانات تقديم الاجازة :<br>اسم الموظف : $emp_name<br>";
        
        $queryr = "SELECT * FROM vacations WHERE id='$id'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        
        $type = stripslashes($_REQUEST['vtype']);
        $type = mysqli_real_escape_string($conn, $type);
        $oldtype = $rowr['type'];
        if(isset($type) && $type !== $oldtype){
            $flag = '1';
            
            $action = $action."<br>تم تغيير نوع الاجازة : من $oldtype الى $type";
        }
        
        $starting_date = stripslashes($_REQUEST['starting_date']);
        $starting_date = mysqli_real_escape_string($conn, $starting_date);
        $oldst = $rowr['starting_date'];
        if(isset($starting_date) && $starting_date !== $oldst){
            $flag = '1';
            
            $action = $action."<br>تم تغيير تاريخ بداية الاجازة : من $oldst الى $starting_date";
        }
        
        $ending_date = stripslashes($_REQUEST['ending_date']);
        $ending_date = mysqli_real_escape_string($conn, $ending_date);
        $oldet = $rowr['ending_date'];
        if(isset($ending_date) && $ending_date !== $oldet){
            $flag = '1';
            
            $action = $action."<br>تم تغيير تاريخ نهاية الاجازة : من $oldet الى $ending_date";
        }
        
        $notes = stripslashes($_REQUEST['notes']);
        $notes = mysqli_real_escape_string($conn, $notes);
        $oldnotes = $rowr['notes'];
        if(isset($notes) && $notes !== $oldnotes){
            $flag = '1';
            
            $action = $action."<br>تم تغيير الملاحظات : من $oldnotes الى $notes";
        }
        
        $ask = '1';
        $oldask = $rowr['ask'];
        if(isset($ask) && $ask !== $oldask){
            $flag = '1';
            
            if($oldask === '0'){
                $olda = "مرفوض";
            } else if($oldask === '1'){
                $olda = "في الانتظار";
            } else if($oldask === '2'){
                $olda = "مقبول";
            }
            
            $action = $action."<br>تم تغيير الملاحظات : من $olda الى في الانتظار";
        }
        
        $query = "UPDATE vacations SET emp_id='$emp_id', type='$type', starting_date='$starting_date', ending_date='$ending_date', notes='$notes', ask='$ask' WHERE id='$id'";
        $result = mysqli_query($conn, $query);
        
        if($flag === '1'){
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$emp_id', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }
        
        header("Location: vacationEdit.php?id=$id");
        exit();
    } else{
        header("Location: vacationEdit.php?id=$id&vtype=0");
        exit();
    }
?>