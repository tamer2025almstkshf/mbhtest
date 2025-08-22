<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';

    if(!isset($_GET['id'])){
        header("Location: clients.php");
        exit();
    }
    if($_GET['id'] === ''){
        header("Location: clients.php");
        exit();
    }
?>
<!DOCTYPE html>
<html dir="rtl">
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
        <?php
            if($row_permcheck['cfiles_rperm'] == 1){
                if(isset($_GET['id'])){
                    $cid = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    
                    // Main client query
                    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                    $stmt->bind_param("i", $cid);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_array();
                    $stmt->close();
    
                    // Count total files
                    $stmt_count = $conn->prepare("SELECT COUNT(*) as count FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?)");
                    $stmt_count->bind_param("iiiii", $cid, $cid, $cid, $cid, $cid);
                    $stmt_count->execute();
                    $result_count = $stmt_count->get_result();
                    $row_count = $result_count->fetch_array();
                    $count = $row_count['count'];
                    $stmt_count->close();
    
                    // Count pending files
                    $pending_status = 'في الانتظار';
                    $stmt_pcount = $conn->prepare("SELECT COUNT(*) as countp FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?) AND file_status = ?");
                    $stmt_pcount->bind_param("iiiiis", $cid, $cid, $cid, $cid, $cid, $pending_status);
                    $stmt_pcount->execute();
                    $result_pcount = $stmt_pcount->get_result();
                    $row_pcount = $result_pcount->fetch_array();
                    $pending = $row_pcount['countp'];
                    $stmt_pcount->close();
    
                    // Count in process files
                    $inprocess_status = 'متداول';
                    $stmt_icount = $conn->prepare("SELECT COUNT(*) as counti FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?) AND file_status = ?");
                    $stmt_icount->bind_param("iiiiis", $cid, $cid, $cid, $cid, $cid, $inprocess_status);
                    $stmt_icount->execute();
                    $result_icount = $stmt_icount->get_result();
                    $row_icount = $result_icount->fetch_array();
                    $inprocess = $row_icount['counti'];
                    $stmt_icount->close();
    
                    // Count finished files
                    $finished_status = 'منتهي';
                    $stmt_fcount = $conn->prepare("SELECT COUNT(*) as countf FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?) AND file_status = ?");
                    $stmt_fcount->bind_param("iiiiis", $cid, $cid, $cid, $cid, $cid, $finished_status);
                    $stmt_fcount->execute();
                    $result_fcount = $stmt_fcount->get_result();
                    $row_fcount = $result_fcount->fetch_array();
                    $finished = $row_fcount['countf'];
                    $stmt_fcount->close();
                }
        ?>
        <div align="left"><img id="print-btn" alt="" src="images/Print.png"  style="cursor:pointer" /></div>
        <br />
        <div id="content-to-print">
            <table width="100%" border="1" cellspacing="0" cellpadding="0" bordercolor="#666666">
                <tr valign="middle">
                    <th width="60%" style="padding:3px; font-size:16px">
                        القضايا المسجلة فى النظام لـ  : 
                        <font color="#0000FF"><?php echo safe_output($row['arname']);?></font> 
                        <br /> التصنيف : 
                        <font color=#ff0000><?php echo safe_output($row['client_kind']);?></font>   
                    </th>
                    <td>
                        <table width="100%" border="0" cellspacing="2" cellpadding="2">
                            <tr style="font-size:16px; color:#000; cursor:pointer" align="center" onClick="location.href='clientcases.php?id=<?php echo safe_output($cid);?>'">
                                <td width="9%">&nbsp;</td>
                                <td width="8%" class="red"><?php echo safe_output($count);?></td>
                                <td width="83%" align="right">اجمالى  القضايا</td>
                            </tr>
                
                            <tr <?php if(isset($_GET['type']) && $_GET['type'] === 'pending'){ echo 'style="font-size:16px; cursor:pointer; background-color:rgba(0,0,0,0.2);"';} else{echo 'style="font-size:16px; cursor:pointer;"';}?> align="center" onClick="location.href='clientcases.php?id=<?php echo safe_output($cid);?>&type=pending'">
                                <td bgcolor="#FFFF00">&nbsp;</td>
                                <td class="red"><?php echo safe_output($pending);?></td>
                                <td align="right">قضايا فى الانتظار</td>
                            </tr>

                            <tr <?php if(isset($_GET['type']) && $_GET['type'] === 'inprocess'){ echo 'style="font-size:16px; cursor:pointer; background-color:rgba(0,0,0,0.2);"';} else{echo 'style="font-size:16px; cursor:pointer;"';}?> align="center" onClick="location.href='clientcases.php?id=<?php echo safe_output($cid);?>&type=inprocess'">
                                <td bgcolor="orange">&nbsp;</td>
                                <td class="red"><?php echo safe_output($inprocess);?></td>
                                <td align="right">قضايا متداولة</td>
                            </tr>

                            <tr >
                                <td colspan="3"><hr /></td>
                            </tr>

                            <tr <?php if(isset($_GET['type']) && $_GET['type'] === 'finished'){ echo 'style="font-size:16px; cursor:pointer; background-color:rgba(0,0,0,0.2);"';} else{echo 'style="font-size:16px; cursor:pointer;"';}?> align="center" onClick="location.href='clientcases.php?id=<?php echo safe_output($cid);?>&type=finished'">
                                <td bgcolor="#006600">&nbsp;</td>
                                <td class="red"><?php echo safe_output($finished);?></td>
                                <td align="right">قضايا منتهية</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
  
            <table  style="font-size:14px" width="100%" border="1" cellspacing="0" cellpadding="0" align="center"  bordercolor="#eaeaea"  bgcolor="#FFFFFF" >
                <tr   height="40">
                    <th width="3%" align="center">م</th>
                    <th width="21%" align="center">تفاصيل القضية</th>
                    <th width="21%" align="center">الموكل/الخصم</th>
                    <th width="19%" align="center">درجات التقاضي</th>
                    <th width="12%" align="center">الباحث القانوني/المستشار القانوني</th>
                    <th width="17%" align="center">الموضوع</th>
                    <th width="7%" align="center">م.ت/ الإدخال</th>
                </tr>
                
                <?php 
                    if(isset($_GET['type']) && $_GET['type'] === 'inprocess'){
                        $stmt_file = $conn->prepare("SELECT * FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?) AND file_status = 'متداول'");
                        $stmt_file->bind_param("iiiii", $cid, $cid, $cid, $cid, $cid);
                        $stmt_file->execute();
                        $result_file = $stmt_file->get_result();
                    } else if(isset($_GET['type']) && $_GET['type'] === 'finished'){
                        $stmt_file = $conn->prepare("SELECT * FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?) AND file_status = 'منتهي'");
                        $stmt_file->bind_param("iiiii", $cid, $cid, $cid, $cid, $cid);
                        $stmt_file->execute();
                        $result_file = $stmt_file->get_result();
                    } else if(isset($_GET['type']) && $_GET['type'] === 'pending'){
                        $stmt_file = $conn->prepare("SELECT * FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?) AND file_status = 'في الانتظار'");
                        $stmt_file->bind_param("iiiii", $cid, $cid, $cid, $cid, $cid);
                        $stmt_file->execute();
                        $result_file = $stmt_file->get_result();
                    }else{
                        $stmt_file = $conn->prepare("SELECT * FROM file WHERE (file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?)");
                        $stmt_file->bind_param("iiiii", $cid, $cid, $cid, $cid, $cid);
                        $stmt_file->execute();
                        $result_file = $stmt_file->get_result();
                    }
                    $num = 1;
                    while($row_file = $result_file->fetch_array()){
                ?>
                <tr valign="top" style="color:#000;font-weight:normal;">
                    <td align="center"  bgcolor="#FF9900"  width="3%"><?php echo safe_output($num);?></td>

                    <td align="right" style="cursor:pointer" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo safe_output($row_file['file_id']);?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <b>رقم الملف :</b>  
                        <font color="#FF0000">
                            <?php 
                                if($row_file['frelated_place'] === 'عجمان'){
                                    echo 'AJM';
                                }
                                else if($row_file['frelated_place'] === 'دبي'){
                                    echo 'DXB';
                                }
                                else if($row_file['frelated_place'] === 'الشارقة'){
                                    echo 'SHJ';
                                }
                            ?>
                        </font><?php if(isset($row_file['file_id']) && $row_file['file_id'] !== ''){echo safe_output($row_file['file_id']);}?><br />
                        <b>رقم القضية :</b>
                            <?php 
                                $fid = $row_file['file_id'];
                                $stmt_case = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
                                $stmt_case->bind_param("i", $fid);
                                $stmt_case->execute();
                                $resultc = $stmt_case->get_result();
                                if($resultc->num_rows > 0){
                                    $rowc = $resultc->fetch_array();
                                    echo safe_output($rowc['case_num']).'/'.safe_output($rowc['file_year']).'-'.safe_output($rowc['degree']);
                                }
                                $stmt_case->close();
                            ?><br />
                        <b>نوع القضية :</b><?php if(isset($row_file['fcase_type']) && $row_file['fcase_type'] !== ''){echo safe_output($row_file['fcase_type']);}?><br />
                        <b>المحكمة :</b><?php if(isset($row_file['file_court']) && $row_file['file_court'] !== ''){echo safe_output($row_file['file_court']);}?><br />
                    </td>

                    <th align="right" > <b>الموكل : </b> 
                        <font color="#009900">
                            <b>
                                <?php 
                                    if(isset($row_file['file_client']) && $row_file['file_client'] !== ''){
                                        $stmt_cl = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_cl->bind_param("i", $row_file['file_client']);
                                        $stmt_cl->execute();
                                        $resultcl = $stmt_cl->get_result();
                                        if($resultcl->num_rows > 0){
                                            $rowcl = $resultcl->fetch_array();
                                            echo safe_output($rowcl['arname']);
                                        }
                                        $stmt_cl->close();
                                    }
                                    if(isset($row_file['fclient_characteristic']) && $row_file['fclient_characteristic'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fclient_characteristic']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_client2']) && $row_file['file_client2'] !== ''){
                                        $cid2 = $row_file['file_client2'];
                                        $stmt_cl2 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_cl2->bind_param("i", $cid2);
                                        $stmt_cl2->execute();
                                        $resultcl2 = $stmt_cl2->get_result();
                                        if($resultcl2->num_rows > 0){
                                            $rowcl2 = $resultcl2->fetch_array();
                                            echo safe_output($rowcl2['arname']);
                                        }
                                        $stmt_cl2->close();
                                    }
                                    if(isset($row_file['fclient_characteristic2']) && $row_file['fclient_characteristic2'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fclient_characteristic2']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_client3']) && $row_file['file_client3'] !== ''){
                                        $cid3 = $row_file['file_client3'];
                                        $stmt_cl3 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_cl3->bind_param("i", $cid3);
                                        $stmt_cl3->execute();
                                        $resultcl3 = $stmt_cl3->get_result();
                                        if($resultcl3->num_rows > 0){
                                            $rowcl3 = $resultcl3->fetch_array();
                                            echo safe_output($rowcl3['arname']);
                                        }
                                        $stmt_cl3->close();
                                    }
                                    if(isset($row_file['fclient_characteristic3']) && $row_file['fclient_characteristic3'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fclient_characteristic3']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_client4']) && $row_file['file_client4'] !== ''){
                                        $cid4 = $row_file['file_client4'];
                                        $stmt_cl4 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_cl4->bind_param("i", $cid4);
                                        $stmt_cl4->execute();
                                        $resultcl4 = $stmt_cl4->get_result();
                                        if($resultcl4->num_rows > 0){
                                            $rowcl4 = $resultcl4->fetch_array();
                                            echo safe_output($rowcl4['arname']);
                                        }
                                        $stmt_cl4->close();
                                    }
                                    if(isset($row_file['fclient_characteristic4']) && $row_file['fclient_characteristic4'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fclient_characteristic4']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_client5']) && $row_file['file_client5'] !== ''){
                                        $cid5 = $row_file['file_client5'];
                                        $stmt_cl5 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_cl5->bind_param("i", $cid5);
                                        $stmt_cl5->execute();
                                        $resultcl5 = $stmt_cl5->get_result();
                                        if($resultcl5->num_rows > 0){
                                            $rowcl5 = $resultcl5->fetch_array();
                                            echo safe_output($rowcl5['arname']);
                                        }
                                        $stmt_cl5->close();
                                    }
                                    if(isset($row_file['fclient_characteristic5']) && $row_file['fclient_characteristic5'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fclient_characteristic5']);?></font><br>
                                <?php }?>
                            </b>
                        </font>
                        <b>الخصم : </b> 
                        <font color="#FF0000">
                            <b>
                                <?php
                                    if(isset($row_file['file_opponent']) && $row_file['file_opponent'] !== ''){
                                        $oid = $row_file['file_opponent'];
                                        $stmt_op = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_op->bind_param("i", $oid);
                                        $stmt_op->execute();
                                        $resultop = $stmt_op->get_result();
                                        if($resultop->num_rows > 0){
                                            $rowop = $resultop->fetch_array();
                                            echo safe_output($rowop['arname']);
                                        }
                                        $stmt_op->close();
                                    }
                                    if(isset($row_file['fopponent_characteristic']) && $row_file['fopponent_characteristic'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fopponent_characteristic']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_opponent2']) && $row_file['file_opponent2'] !== ''){
                                        $oid2 = $row_file['file_opponent2'];
                                        $stmt_op2 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_op2->bind_param("i", $oid2);
                                        $stmt_op2->execute();
                                        $resultop2 = $stmt_op2->get_result();
                                        if($resultop2->num_rows > 0){
                                            $rowop2 = $resultop2->fetch_array();
                                            echo safe_output($rowop2['arname']);
                                        }
                                        $stmt_op2->close();
                                    }
                                    if(isset($row_file['fopponent_characteristic2']) && $row_file['fopponent_characteristic2'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fopponent_characteristic2']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_opponent3']) && $row_file['file_opponent3'] !== ''){
                                        $oid3 = $row_file['file_opponent3'];
                                        $stmt_op3 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_op3->bind_param("i", $oid3);
                                        $stmt_op3->execute();
                                        $resultop3 = $stmt_op3->get_result();
                                        if($resultop3->num_rows > 0){
                                            $rowop3 = $resultop3->fetch_array();
                                            echo safe_output($rowop3['arname']);
                                        }
                                        $stmt_op3->close();
                                    }
                                    if(isset($row_file['fopponent_characteristic3']) && $row_file['fopponent_characteristic3'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fopponent_characteristic3']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_opponent4']) && $row_file['file_opponent4'] !== ''){
                                        $oid4 = $row_file['file_opponent4'];
                                        $stmt_op4 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_op4->bind_param("i", $oid4);
                                        $stmt_op4->execute();
                                        $resultop4 = $stmt_op4->get_result();
                                        if($resultop4->num_rows > 0){
                                            $rowop4 = $resultop4->fetch_array();
                                            echo safe_output($rowop4['arname']);
                                        }
                                        $stmt_op4->close();
                                    }
                                    if(isset($row_file['fopponent_characteristic4']) && $row_file['fopponent_characteristic4'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fopponent_characteristic4']);?></font><br>
                                <?php
                                    }
                                    if(isset($row_file['file_opponent5']) && $row_file['file_opponent5'] !== ''){
                                        $oid5 = $row_file['file_opponent5'];
                                        $stmt_op5 = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                        $stmt_op5->bind_param("i", $oid5);
                                        $stmt_op5->execute();
                                        $resultop5 = $stmt_op5->get_result();
                                        if($resultop5->num_rows > 0){
                                            $rowop5 = $resultop5->fetch_array();
                                            echo safe_output($rowop5['arname']);
                                        }
                                        $stmt_op5->close();
                                    }
                                    if(isset($row_file['fopponent_characteristic5']) && $row_file['fopponent_characteristic5'] !== ''){
                                        echo '/';
                                ?>
                                <font color=blue><?php echo safe_output($row_file['fopponent_characteristic5']);?></font><br>
                                <?php }?>
                            </b>
                        </font>
                    </th>
                    
                    <th align="right">
                        <?php 
                            $fid = $row_file['file_id'];
                            $stmt_case = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
                            $stmt_case->bind_param("i", $fid);
                            $stmt_case->execute();
                            $resultc = $stmt_case->get_result();
                            if($resultc->num_rows > 0){
                                while($rowfds = $resultc->fetch_array()){
                                    echo safe_output($rowfds['case_num']).'/'.safe_output($rowfds['file_year']).'-'.safe_output($rowfds['degree']).'<br>';
                                }
                            }
                            $stmt_case->close();
                        ?>
                    </th>
                    
                    <th align="center">
                        <?php 
                            if(isset($row_file['flegal_researcher']) && $row_file['flegal_researcher'] !== ''){
                                $flr = $row_file['flegal_researcher'];
                                $stmtflr = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                $stmtflr->bind_param("i", $flr);
                                $stmtflr->execute();
                                $resultflr = $stmtflr->get_result();
                                if($resultflr->num_rows > 0){
                                    $rowflr = $resultflr->fetch_array();
                                    echo safe_output($rowflr['name']);
                                }
                                $stmtflr->close();
                            }
                            echo ' / ';
                            if(isset($row_file['flegal_advisor']) && $row_file['flegal_advisor'] !== ''){
                                $fla = $row_file['flegal_advisor'];
                                $stmtfla = $conn->prepare("SELECT * FROM user WHERE id = ?");
                                $stmtfla->bind_param("i", $fla);
                                $stmtfla->execute();
                                $resultfla = $stmtfla->get_result();
                                if($resultfla->num_rows > 0){
                                    $rowfla = $resultfla->fetch_array();
                                    echo safe_output($rowfla['name']);
                                }
                                $stmtfla->close();
                            }
                        ?></th>
                    
                    <th align="center" bgcolor="#FFFFCC"><?php if(isset($row_file['file_subject']) && $row_file['file_subject'] !== ''){ echo safe_output($row_file['file_subject']);}?></th>
                    
                    <?php 
                        if(isset($row_file['file_timestamp']) && $row_file['file_timestamp'] !== ''){
                            $timestamp = $row_file['file_timestamp'];
                            list($date, $time) = explode(' ', $timestamp);
                            $formatted_timestamp = $date;
                        } else{
                            $formatted_timestamp = '';
                        }
                    ?>
                    <td align="center" style="color:#333"><?php echo safe_output($row_file['done_by']).'<br>'.safe_output($formatted_timestamp);?></td>
                </tr>
                <?php
                    $num++;
                    }
                ?>
            </table>
        </div>
        <?php }?>
    </body>
</html>

<script language="JavaScript" type="text/JavaScript">
    function MM_openBrWindow(theURL,winName,features) { //v2.0
        window.open(theURL,winName,features);
    }
</script>

<script>
    document.getElementById('print-btn').addEventListener('click', function () {
        window.print();
    });
</script>