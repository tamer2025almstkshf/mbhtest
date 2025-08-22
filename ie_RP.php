<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<script language="JavaScript" type="text/JavaScript">
    function MM_openBrWindow(theURL,winName,features) { //v2.0
      window.open(theURL,winName,features);
    }
</script>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1256" />
        <title>الحسابات- سند قبض</title>
        <style>
            .normal
            {
            	font-weight:normal;
            }
        </style>
    </head>

    <body>
        <?php
            $id = $_GET['id'];
            
            $total_amounts = 0;
            $querytamounts = "SELECT * FROM incomes_expenses WHERE id='$id'";
            $resulttamounts = mysqli_query($conn, $querytamounts);
            while($rowtamounts = mysqli_fetch_array($resulttamounts)){
                $total_amounts = $total_amounts + $rowtamounts['amount'];
            }
            
            $total_cheques = 0;
            $querytcheques = "SELECT * FROM cheques WHERE ie_id='$id'";
            $resulttcheques = mysqli_query($conn, $querytcheques);
            while($rowtcheques = mysqli_fetch_array($resulttcheques)){
                $total_cheques = $total_cheques + $rowtcheques['cheque_value'];
            }
            
            $total_income = $total_amounts + $total_cheques;
            
            $query = "SELECT * FROM incomes_expenses WHERE id='$id'";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_array($result);
        ?>
        <table width="100%" border="0" cellspacing="3" cellpadding="3" style="font-family: 'Times New Roman', Times, serif; font-weight:bold">
            <tr>
                <td colspan="3" align="center">
                </td>
            </tr>
            
            <tr>
                <td colspan="3" align="center">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <th width="180" align="left">No. <?php echo $row['id'];?>
                                <font  style="font-size:24px" class="normal"></font>
                            </th>
                            <td  align="center">
                                <img src="img/RV.jpg"  border="0" align="absmiddle"/>
                            </td>
                            <th align="center" width="180" style="background-image:url(img/RPV.png); background-repeat:no-repeat; width:180; height:64;font-size:24px">
                                #<?php echo $total_income;?>#
                            </th>
                        </tr>
                    </table>
                </td>
            </tr>
          
            <tr>
                <td width="24%" align="left" dir="ltr">Date: </td>
                <td width="55%" align="center" class="normal"><?php echo $row['amount_date'];?></td>
                <td width="21%" align="right" dir="rtl">التاريخ : </td>
            </tr>
            
            <tr>
                <td align="left" dir="ltr">Received from Mr/Mrs :</td>
                <td align="center" class="normal"><?php echo $row['recive_from'];?></td>
                <td align="right" dir="rtl">استلمنا من السيد/السيدة : </td>
              </tr>
              
            <tr>
                <td align="left" dir="ltr">The sum of :</td>
                <td align="center" class="normal" dir="ltr">AED<?php echo $total_income;?>درهم إماراتي</td>
                <td align="right" dir="rtl">مبلغاَ وقدرة :</td>
            </tr>
            
            <tr valign="top">
                <td align="left" dir="ltr">For :</td>
                <td align="center" class="normal"><?php echo $row['recive_reason'];?><br><br />
                </td>
                <td align="right" dir="rtl">وذلك عن :</td>
            </tr>
            
            <tr  valign="top">
                <td align="left" dir="ltr">Cash / Cheque</td>
                <td align="center" class="normal" dir="rtl">
                    <?php
                        if(isset($row['amount']) && $row['amount'] !== '' && $row['amount'] !== '0'){
                            echo 'نقدا ( ' . $row['amount'] . ' )' . '<br>';
                        }
                        $query2 = "SELECT * FROM cheques WHERE ie_id='$id'";
                        $result2 = mysqli_query($conn, $query2);
                        if($result2->num_rows > 0){
                            echo 'شيك';
                        }
                    ?>
                </td>
                <td align="right" dir="rtl">نقداَ/ بموجب شيك رقم :</td>
            </tr>
            
            <tr  valign="top">
                <td colspan="3" align="center" dir="rtl">
                    <table width="95%" border="1" bordercolor="#cccccc"  align="center" cellspacing="0" cellpadding="3"  >
                        <tr valign="top" align="center" style="font-weight:bold">
                            <td width="18%">رقم الشيك</td>
                            <td width="17%">قيمة الشيك</td>
                            <td width="24%">تاريخ الاستحقاق</td>
                            <td width="41%">البنك التابع له</td>
                        </tr>
                        
                        <?php
                            while($row2 = mysqli_fetch_array($result2)){
                        ?>
                        <tr  align="center" valign="top" style="font-weight:normal; font-size:14px">
                            <th align="center" ><?php echo $row2['chque_number'];?></th>
                            <th class="red"><?php echo $row2['cheque_value'];?></th>
                            <th  style="color:#00F" dir="rtl"><?php echo $row2['cheque_duedate'];?></th>
                            <td><?php echo $row2['cheque_bank'];?></td>
                        </tr>
                        <?php
                            }
                        ?>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td colspan="3" align="left" dir="ltr">
                    <table width="100%" border="0" cellspacing="3" cellpadding="3">
                        <tr>
                            <td width="50%" align="left"><img src="img/RPA.png"  border="0"/></td>
                            <td align="right"><img src="img/RPR.png"  border="0"/></td>
                        </tr>
                        
                        <tr>
                            <td align="left">
                                <?php
                                    $myid = $_SESSION['id'];
                                    
                                    $myquery = "SELECT * FROM user WHERE id='$myid'";
                                    $myresult = mysqli_query($conn, $myquery);
                                    $myrow = mysqli_fetch_array($myresult);
                                    
                                    echo $myrow['name'];
                                ?>
                            </td>
                            <td align="right"><?php echo $row['recive_from'];?></td>
                        </tr>
                        <tr>
                            <td align="left">&nbsp;</td>
                            <td align="right">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>