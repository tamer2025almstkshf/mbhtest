<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<script language="JavaScript" type="text/JavaScript">
    function MM_openBrWindow(theURL,winName,features) {
        window.open(theURL,winName,features);
    }
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    
    <body>
        <form name="addform" action="chequeadd.php" method="post" enctype="multipart/form-data" >
            <input type="hidden" name="id" value="<?php echo $_GET['ie_id'];?>">
            <table width="100%" border="0" cellspacing="2" cellpadding="2" bgcolor="#FFFFFF">
                <tr>
                    <td width="73%" align="right" style="font-weight:normal">
                        عدد الشيكات /
                        <input type="number" name="CheqNum" class="form-input" style="text-align: center; width:20%;" onChange="submit()">
                   </td>
                </tr>
                
                <tr align="right" dir="rtl" >
                    <td colspan="3" align="center"> 
                        <table class="info-table" style="width: 100%;">
                            <tr class="infotable-header" style="text-align: center">
                                <td>م</td>
                                <td>رقم الشيك</td>
                                <td>قيمة الشيك</td>
                                <td>تاريخ الاستحقاق</td>
                                <td>البنك التابع له</td>
                                <td>حذف</td>
                            </tr>
                            
                            <?php
                                $ie_id = $_GET['ie_id'];
                                $query = "SELECT * FROM cheques WHERE ie_id='$ie_id'";
                                $result = mysqli_query($conn, $query);
                                while($row = mysqli_fetch_array($result)){
                            ?>
                            <tr style="text-align: center">
                                <td></td>
                                <th><?php echo $row['chque_number'];?></th>
                                <th><?php echo $row['cheque_value'];?></th>
                                <th><?php echo $row['cheque_duedate'];?></th>
                                <td><?php echo $row['cheque_bank'];?></td>
                                <td>
                                    <div class="perms-check" onclick="location.href='cheqdel.php?id=<?php echo $row['id'];?>';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                </td>
                            </tr>
                            <?php
                                }
                                
                                $cheqno = $_GET['CheqNum'];
                                for($i = 1 ; $i <= $cheqno ; $i++){
                            ?>
                            <tr class="infotable-body" style="text-align: center">
                                <td><?php echo $i;?></td>
                                <td>
                                    <input type="text" class="form-input" name="cheq_no[<?php echo $i;?>]">
                                </td>
                                <td>
                                    <input type="number" class="form-input" name="cheq_value[<?php echo $i;?>]">
                                </td>
                                <td>
                                    <input type="date" class="form-input" name="cheq_due_date[<?php echo $i;?>]" value="<?php echo date("Y-m-d");?>">
                                </td>
                                <td>
                                    <input type="text" class="form-input" name="cheq_bank[<?php echo $i;?>]">
                                </td>
                            </tr>
                            <?php }?>
                        </table>
                    </td>
                </tr>
                
                <tr align="right" dir="rtl" >
                    <td colspan="3" align="center">
                        <button type="submit" name="save_cheque" value="حفظ وتخزين البيانات" onClick="addform.Action.value='doadd'; addform.submit();" class="green-button">حفظ وتخزين البيانات</button>
                    </td>
                </tr>
            </table>
        </form>
    </body>
</html>