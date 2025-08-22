<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    
    $page = stripslashes($_REQUEST['page']);
    $page = mysqli_real_escape_string($conn, $page);
    
    if(isset($_REQUEST['actionid']) && $_REQUEST['actionid'] !== ''){
        $actionid = stripslashes($_REQUEST['actionid']);
        $actionid = mysqli_real_escape_string($conn, $actionid);
        
        $ending_datetime = date("Y-m-d H:i:s");
        
        $queryr = "SELECT * FROM working_time WHERE id='$actionid'";
        $resultr = mysqli_query($conn, $queryr);
        $rowr = mysqli_fetch_array($resultr);
        
        $starting_datetime = $rowr['starting_time'];
        
        list($starting_date, $starting_time) = explode(" ", $starting_datetime);
        list($ending_date, $ending_time) = explode(" ", $ending_datetime);
        
        list($sHH, $sMM, $sSS) = explode(":", $starting_time);
        list($eHH, $eMM, $eSS) = explode(":", $ending_time);
        
        $interval = (new DateTime($starting_date))->diff(new DateTime($ending_date))->days;
        $DaysDuration = $interval*24;
        
        $HoursDuration = $eHH - $sHH;
        $MinsDuration = $eMM - $sMM;
        
        if($MinsDuration < 0){
            $MinsDuration = 60 + $MinsDuration;
            $HoursDuration = $HoursDuraion + 1;
        }
        echo $starting_datetime.' - '.$ending_datetime;
        echo '<br'.$HoursDuration;
        echo '<br>'.$MinsDuration.'<br>';
        echo $DaysDuration.'<br><br>';
        
        $HoursDuration = $DaysDuration + $HoursDuration;
        $Duration = $HoursDuration.':'.$MinsDuration;
        echo $Duration;
        exit();
        //lets say: I started at 28/04/2025 1:56, ended at 28/04/2025 3:34, the duration between the times is 1hour and 38 mins, so
        //lets do the following: since the result is in minus, 34-56 = -22, so we must get it off the full hour, 60-22 = 38
        //then again since the result is minus, we will add an hour to the starting_time to make it 2 and then 3-2 = 1,
        //so in this case the Duration in time is 1 hour 38 mins
        //but if i started at 1:34, ended at 3:56, then the mins will be: 56-34 = 22, and the hours will be 3-1=2, so the duration is 2 hours 22 mins
        
        //lets say: I started at 28/04/2025 1:56, ended at 01/05/2025 18:40, the duration between them is 
        $minDuration = $eMM - $sMM;
        
        echo $ending_time.' - '.$starting_time.' = '.$duration;exit;
        $query = "UPDATE working_time SET ending_time='$ending_datetime' WHERE id='$actionid'";
        $result = mysqli_query($conn, $query);
        
        header("Location: $page");
        exit();
    }
    header("Location: $page");
    exit();
?>