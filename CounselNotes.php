<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>ملاحظات على الملف</title>
        <link rel="stylesheet" type="text/css" href="css/sites.css">
        <link href="css/login.css" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <?php
            if(isset($_GET['nid'])){
                $nid = $_GET['nid'];
            } else{
                exit();
            }

            $query = "SELECT * FROM file_note WHERE id='$nid'";
            $result = mysqli_query($conn, $query);

            if($result->num_rows === 0){
                exit();
            }

            $row = mysqli_fetch_array($result);
            $fid = $row['file_id'];

            $query2 = "SELECT * FROM file WHERE file_id='$fid'";
            $result2 = mysqli_query($conn, $query2);
            $row2 = mysqli_fetch_array($result2);
            
        ?>
        <table width="98%" border="0" cellspacing="1" cellpadding="1" align="center" bgcolor="#FFFFFF"  >
            <tr>
                <th colspan="2" style="font-size:16px; background-color:#FF9" class="table2"> رقم الملف 
                    <font color="#FF0000">
                        <?php
                            if($row2['frelated_place'] === 'عجمان'){
                                echo 'AJM';
                            } elseif($row2['frelated_place'] === 'دبي'){
                                echo 'DXB';
                            } elseif($row2['frelated_place'] === 'الشارقة'){
                                echo 'SHJ';
                            }
                        ?>
                    </font><?php echo $fid;?>
                </th>
            </tr>
            <tr>
                <th width="100%" colspan="2" style="font-size:16px">
                    <div align="justify" dir="rtl"><?php if(isset($row['note'])){ echo $row['note'];}?><br />
                        <font color="#0000FF" style=" font-size:14px"><?php if(isset($row['timestamp'])){echo $row['timestamp'];} if(isset($row['doneby'])){echo ' ' . $row['doneby'];}?></font>
                    </div>
                </th>
            </tr>
        </table>
    </body>
</html>