<?php
    include_once 'connection.php';
    include_once 'login_check.php';

    /** @var mysqli $conn */
    $selectedDegree = $_POST['degree_id_sess'] ?? '';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>حجزت القضية للحكم</title>
        <link rel="stylesheet" type="text/css" href="css/sites.css">
        <link href="css/login.css" rel="stylesheet" type="text/css" />
    </head>
    
    <body>
        <SCRIPT LANGUAGE="JavaScript" SRC="CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>
        <form action="ab.php" method="post" name="SearchForm" enctype="multipart/form-data">
            <table width="98%" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#FFFFFF"  >
                <tr>
                    <th colspan="2" style="font-size:18px">حجزت القضية للحكم</th>
                </tr>
                <?php $fid = '165';?>
                <input name="fid" type="hidden" value="<?php echo $fid;?>">
                    
                <tr>
                    <td width="15%" align="left"> حتى تاريخ :</td>
                    <td width="85%" align="right">
                        <input type="text" name="booked_todate" dir="rtl" size="7" value="" style="text-align:center; font-weight:bold; color:#F00" > 
                        <label style="cursor:pointer" onClick="cal13.select(document.SearchForm.booked_todate,'booked_todate','dd/MM/yyyy'); return false;"  NAME="booked_todate" ID="booked_todate">
                            <img src="images/calendar.png" align="absmiddle">
                        </label> 
                        <font color="#FF0000">*</font>
                    </td>
                </tr>
                
                <tr>
                    <td align="left">درجة التقاضي : </td>
                    <td align="right">
                        <select name="degree_id_sess" dir="rtl" style="width:90%;">
                            <option value=""></option>
                                                        <?php
                                                            $fiddif = '179';
                                                            $query_ade = "SELECT * FROM file_degrees WHERE fid='$fiddif'";
                                                            $result_ade = mysqli_query($conn, $query_ade);
                                                            if($result_ade->num_rows > 0){
                                                                while($row_ade=mysqli_fetch_array($result_ade)){
                                                        ?>
                                                                                                <option value="<?php echo $row_ade['degree'];?>" <?php echo ($selectedDegree == $row_ade['degree']) ? 'selected' : ''; ?>><?php echo $row_ade['file_year'].'/'.$row_ade['case_num'].'-'.$row_ade['degree'];?></option>
                                                                                                <?php
                                                                }
                                                            }
                                                        ?>
                        </select>
                    </td>
                </tr>
                
                <tr>
                    <td align="left">تفاصيل : </td>
                    <td align="right">
                        <textarea name="booked_detail"  dir="rtl" style="width:90%" rows="2" wrap="physical"></textarea> <font color="#FF0000">*</font>
                    </td>
                </tr>
                
                <?php
                    $queryread = "SELECT * FROM file WHERE file_id='$fid'";
                    $resultread = mysqli_query($conn, $queryread);
                    $rowread = mysqli_fetch_array($resultread);
                    
                    if(isset($rowread['file_type']) && $rowread['file_type'] === 'مدني -عمالى'){
                ?>
                <tr>
                    <td align="left">
                        <input type="checkbox" name="amount" value="1">
                    </td>
                    <td align="right">اكثر من 500,000 درهم </td>
                </tr>
                <?php
                    } else if(isset($_GET['deg']) && $_GET['deg'] === 'امر على عريضة'){
                ?>
                <tr>
                    <td align="left">قرار القاضي : </td>
                    <td align="right">
                        <input type="radio" name="decission" value="0"> رفض
                        <input type="radio" name="decission" value="1"> قبول
                    </td>
                </tr>
                <?php
                    } else if(isset($_GET['deg']) && $_GET['deg'] === 'امر اداء'){
                ?>
                <tr>
                    <td align="left">
                        <input type="checkbox" name="amount" value="2">
                    </td>
                    <td align="right">اكثر من 50,000 درهم </td>
                </tr>
                <?php
                    }
                ?>
                
                <tr>
                    <th>&nbsp;</th>
                    <th align="right">
                        <input type="submit"  value="حفظ البيانات" class="button"/>    
                    </th>
                </tr>
            </table>
        </form>
    </body>
</html>