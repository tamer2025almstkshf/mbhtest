<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $id = $_SESSION['id'];
    $querymain = "SELECT * FROM user WHERE id='$id'";
    $resultmain = mysqli_query($conn, $querymain);
    $rowmain = mysqli_fetch_array($resultmain);
    
    $myid = $_SESSION['id'];
    $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
    $result_permcheck = mysqli_query($conn, $query_permcheck);
    $row_permcheck = mysqli_fetch_array($result_permcheck);
    
    $page = $_GET['page'];
    
    $queryString = '';
    if(isset($_GET['type'])){
        if($queryString === ''){
            $queryString = "type=".$_GET['type'];
        } else{
            $queryString = $queryString."&type=".$_GET['type'];
        }
    }
    
    if(isset($_GET['place'])){
        if($queryString === ''){
            $queryString = "place=".$_GET['place'];
        } else{
            $queryString = $queryString."&place=".$_GET['place'];
        }
    }
    
    if(isset($_GET['class'])){
        if($queryString === ''){
            $queryString = "class=".$_GET['class'];
        } else{
            $queryString = $queryString."&class=".$_GET['class'];
        }
    }
    
    if(isset($_GET['fid'])){
        if($queryString === ''){
            $queryString = "fid=".$_GET['fid'];
        } else{
            $queryString = $queryString."&fid=".$_GET['fid'];
        }
    }
    
    if(isset($_GET['subject'])){
        if($queryString === ''){
            $queryString = "subject=".$_GET['subject'];
        } else{
            $queryString = $queryString."&subject=".$_GET['subject'];
        }
    }
    
    if(isset($_GET['client'])){
        if($queryString === ''){
            $queryString = "client=".$_GET['client'];
        } else{
            $queryString = $queryString."&client=".$_GET['client'];
        }
    }
    
    if(isset($_GET['ccharacteristic'])){
        if($queryString === ''){
            $queryString = "ccharacteristic=".$_GET['ccharacteristic'];
        } else{
            $queryString = $queryString."&ccharacteristic=".$_GET['ccharacteristic'];
        }
    }
    
    if(isset($_GET['opponent'])){
        if($queryString === ''){
            $queryString = "opponent=".$_GET['opponent'];
        } else{
            $queryString = $queryString."&opponent=".$_GET['opponent'];
        }
    }
    
    if(isset($_GET['ocharacteristic'])){
        if($queryString === ''){
            $queryString = "ocharacteristic=".$_GET['ocharacteristic'];
        } else{
            $queryString = $queryString."&ocharacteristic=".$_GET['ocharacteristic'];
        }
    }
    
    if(isset($_GET['advisor'])){
        if($queryString === ''){
            $queryString = "advisor=".$_GET['advisor'];
        } else{
            $queryString = $queryString."&advisor=".$_GET['advisor'];
        }
    }
    
    if(isset($_GET['researcher'])){
        if($queryString === ''){
            $queryString = "researcher=".$_GET['researcher'];
        } else{
            $queryString = $queryString."&researcher=".$_GET['researcher'];
        }
    }
    
    if(isset($_GET['ctype'])){
        if($queryString === ''){
            $queryString = "ctype=".$_GET['ctype'];
        } else{
            $queryString = $queryString."&ctype=".$_GET['ctype'];
        }
    }
    
    if(isset($_GET['prosec'])){
        if($queryString === ''){
            $queryString = "prosec=".$_GET['prosec'];
        } else{
            $queryString = $queryString."&prosec=".$_GET['prosec'];
        }
    }
    
    if(isset($_GET['station'])){
        if($queryString === ''){
            $queryString = "station=".$_GET['station'];
        } else{
            $queryString = $queryString."&station=".$_GET['station'];
        }
    }
    
    if(isset($_GET['court'])){
        if($queryString === ''){
            $queryString = "court=".$_GET['court'];
        } else{
            $queryString = $queryString."&court=".$_GET['court'];
        }
    }
    
    if(isset($_GET['opp'])){
        if($queryString === ''){
            $queryString = "opp=".$_GET['opp'];
        } else{
            $queryString = $queryString."&opp=".$_GET['opp'];
        }
    }
    
    if(isset($_GET['deg'])){
        if($queryString === ''){
            $queryString = "deg=".$_GET['deg'];
        } else{
            $queryString = $queryString."&deg=".$_GET['deg'];
        }
    }
    
    if(isset($_GET['cno'])){
        if($queryString === ''){
            $queryString = "cno=".$_GET['cno'];
        } else{
            $queryString = $queryString."&cno=".$_GET['cno'];
        }
    }
    
    if(isset($_GET['year'])){
        if($queryString === ''){
            $queryString = "year=".$_GET['year'];
        } else{
            $queryString = $queryString."&year=".$_GET['year'];
        }
    }
    
    if(isset($_GET['jud'])){
        if($queryString === ''){
            $queryString = "jud=".$_GET['jud'];
        } else{
            $queryString = $queryString."&jud=".$_GET['jud'];
        }
    }
    
    if(isset($_GET['from'])){
        if($queryString === ''){
            $queryString = "from=".$_GET['from'];
        } else{
            $queryString = $queryString."&from=".$_GET['from'];
        }
    }
    
    if(isset($_GET['to'])){
        if($queryString === ''){
            $queryString = "to=".$_GET['to'];
        } else{
            $queryString = $queryString."&to=".$_GET['to'];
        }
    }
    
    if(isset($_GET['extended'])){
        if($queryString === ''){
            $queryString = "extended=".$_GET['extended'];
        } else{
            $queryString = $queryString."&extended=".$_GET['extended'];
        }
    }
    
    if(isset($_GET['judge'])){
        if($queryString === ''){
            $queryString = "judge=".$_GET['judge'];
        } else{
            $queryString = $queryString."&judge=".$_GET['judge'];
        }
    }
    
    if(isset($_GET['pending'])){
        if($queryString === ''){
            $queryString = "pending=".$_GET['pending'];
        } else{
            $queryString = $queryString."&pending=".$_GET['pending'];
        }
    }
    
    if(isset($_GET['fwork'])){
        if($queryString === ''){
            $queryString = "fwork=".$_GET['fwork'];
        } else{
            $queryString = $queryString."&fwork=".$_GET['fwork'];
        }
    }
    
    if(isset($_GET['archived'])){
        if($queryString === ''){
            $queryString = "archived=".$_GET['archived'];
        } else{
            $queryString = $queryString."&archived=".$_GET['archived'];
        }
    }
    
    if(isset($_GET['attachments'])){
        if($queryString === ''){
            $queryString = "attachments=".$_GET['attachments'];
        } else{
            $queryString = $queryString."&attachments=".$_GET['attachments'];
        }
    }
    
    if(isset($_GET['id'])){
        if($queryString === ''){
            $queryString = "id=".$_GET['id'];
        } else{
            $queryString = $queryString."&id=".$_GET['id'];
        }
    }
    
    if ($queryString !== '') {
        if (strpos($queryString, 'error') !== false) {
            parse_str($queryString, $queryParams);
            unset($queryParams['error']);
            $queryString = http_build_query($queryParams);
        }
    }
    
    if($row_permcheck['attachments_dperm'] === '1'){
        $id = $_GET['id'];
        if(isset($_GET['id']) && $_GET['id'] !== ''){
            $action = "تم حذف مرفق من الملف رقم : $id";
            
            $query = "DELETE FROM files_attachments WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            
            $empid = $_SESSION['id'];
            
            $queryu = "SELECT * FROM user WHERE id='$empid'";
            $resultu = mysqli_query($conn, $queryu);
            $rowu = mysqli_fetch_array($resultu);
            $emp_name = $rowu['name'];
            
            $querylog = "INSERT INTO logs (empid, emp_name, action) VALUES ('$empid', '$emp_name', '$action')";
            $resultlog = mysqli_query($conn, $querylog);
        }
        if($page === 'cases.php'){
            header("Location: cases.php?attachments=1&id=$id");
            exit();
        } else if($page === 'FileEdit.php'){
            $fid = $_GET['fid'];
            
            header("Location: FileEdit.php?id=$fid");
            exit();
        }
        header("Location: $page?$queryString");
        exit();
    } else {
        header("Location: $page");
        exit();
    }
?>