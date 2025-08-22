<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    if(isset($_GET['id']) && $_GET['id'] !== ''){
        $session_id = $_GET['id'];
        $query = "SELECT * FROM session WHERE session_id='$session_id'";
        $result = mysqli_query($conn, $query);
        if($result->num_rows == 0){
            exit();
        }
        $row = mysqli_fetch_array($result);
    } else{
        exit();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>القرارات والملاحظات</title>
        <link rel="stylesheet" type="text/css" href="css/sites.css">
        <link href="css/login.css" rel="stylesheet" type="text/css" />
        <SCRIPT LANGUAGE="JavaScript" SRC="../CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">
            var cal13 = new CalendarPopup();
        </SCRIPT>
        <script type="text/javascript">
            function CloseWindo(){
                <!--window.opener.document.location.href='index.php?pg=Hearing_Reports&LoadMenue=Case';-->
                window.close();
            }
        </script>
    </head>

    <body>
        <form method="post" name="addform" action="sdedit.php" enctype="multipart/form-data">
            <input type="hidden" name="session_id" value="<?php echo $session_id;?>">
            <table width="98%" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#FFFFFF"  >
                <tr>
                    <th colspan="2" style="font-size:18px"> تاريخ الجلسة </th>
                </tr>

                <tr>
                    <th width="15%" align="left">التاريخ الحالي : <br><br></th>
                    <td width="85%" align="right">
                        <?php
                            echo $row['session_date'];
                        ?><br><br>
                    </td>
                </tr>

                <tr>
                    <th align="left"> ت.الجلسة :<br><br></th>
                    <td align="right">
                        <?php 
                            $date = $row['session_date'];
                            list($y, $m, $d) = explode("-", $date);
                            $date = "$d/$m/$y";
                        ?>
                        <input type="text" name="Hearing_dt" dir="ltr" style="text-align:center; font-weight:bold; background-color:#FF9; color:#00F; font-size:14px; width:25%" value="<?php echo $date;?>">
                        <label style="cursor:pointer" onClick="cal13.select(document.addform.Hearing_dt,'Hearing_dt','dd/MM/yyyy'); return false;"  NAME="Hearing_dt" ID="Hearing_dt">
                            <img src="images/calendar.png" align="absmiddle">
                        </label><br><br>
                    </td>
                </tr>

                <tr>
                    <th>&nbsp;</th>
                    <th align="right">
                        <input type="submit" value="حفظ البيانات" class="button"/>
                    </th>
                </tr>
            </table>
        </form>
    </body>
</html>