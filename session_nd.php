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
        <script type="text/javascript">
            function CloseWindo(){
                <!--window.opener.document.location.href='index.php?pg=Hearing_Reports&LoadMenue=Case';-->
                window.close();
            }
        </script>
    </head>

    <body>
        <form method="post" action="ndadd.php" enctype="multipart/form-data">
            <input type="hidden" name="session_id" value="<?php echo $session_id;?>">
            <table width="98%" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#FFFFFF"  >
                <tr>
                    <th colspan="2" style="font-size:18px">قرارت الجلسة والملاحظات</th>
                </tr>
                
                <?php
                    $sid = $_GET['id'];
                    $query = "SELECT * FROM session WHERE session_id='$sid'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_array($result);
                ?>
                
                <tr>
                    <th width="15%" align="left">قرار الجلسة : </th>
                    <td width="85%" align="right">
                        <textarea name="session_decission"  dir="rtl" style="width:90%; background-color:#FF9" rows="2" wrap="physical"><?php echo $row['session_trial'];?></textarea> 
                    </td>
                </tr>

                <tr>
                    <th align="left"> ملاحظات :</th>
                    <td align="right">
                        <textarea name="session_notes"  dir="rtl" style="width:90%; background-color:#FF9" rows="2" wrap="physical"><?php echo $row['session_note'];?></textarea>
                    </td>
                </tr>

                <tr>
                    <th align="left">م.ت اخر تعديل : </th>
                    <th align="right"> - </th>
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