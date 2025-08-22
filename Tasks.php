<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'AES256.php';
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
    <body style="overflow: auto">
        <?php
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['admjobs_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <form action="tchangetype.php" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="page" value="Tasks.php">
                                <div class="table-header-right">
                                    <h3 style="display: inline-block"><font id="clients-translate">المهام</font></h3>
                                    <select class="table-header-selector" name="type" onchange="submit()" style="padding: 0 5px;">
                                        <option value="select">اختر التصنيف</option>
                                        <option value="all" <?php if($_GET['type'] === 'all'){ echo 'selected'; }?>><font id="all-translate">اجمالى المهام</font></option>
                                        <option value="inprogress" <?php if($_GET['type'] === 'inprogress'){ echo 'selected'; }?>><font id="clients-translate">مهام جارى العمل عليها</font></option>
                                        <option value="pending" <?php if($_GET['type'] === 'pending'){ echo 'selected'; }?>><font id="opponents-translate">مهام لم يتخذ بها إجراء</font></option>
                                        <option value="finished" <?php if($_GET['type'] === 'finished'){ echo 'selected'; }?>><font id="opponents-translate">مهام منتهية</font></option>
                                    </select>
                                </div>
                                <input type="hidden" name="page" value="Tasks.php">
                            </form>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px; <?php if($row_permcheck['admjobs_pperm'] == 1){?> background-image: url('img/printer.png'); <?php }?>" <?php if($row_permcheck['admjobs_pperm'] == 1){?> onclick="printDiv();" <?php }?>></div>
                                <?php
                                    if($row_permcheck['admjobs_aperm'] == 1){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['admjobs_aperm'] == 1){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] == 1)){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">مهمة جديدة</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='Tasks.php';">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
                                                $stmt->bind_param("i", $id);
                                                $stmt->execute();
                                                $eresult = $stmt->get_result();
                                                $erow = $eresult->fetch_array();
                                            }
                                        ?>
                                        <form action="task_process.php" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container" style="border-bottom: 2px solid #f8f8f8;">
                                                        <p class="input-parag">اضافة المهمة حسب</p>
                                                        <input type="radio" name="SearchType" value="1" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '1'){echo 'checked';}?>> رقم الملف <br>
                                                        <input type="radio" name="SearchType" value="2" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '2'){echo 'checked';}?>> اسم الموكل/الخصم <br>
                                                        <input type="radio" name="SearchType" value="3" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '3'){echo 'checked';}?>> رقم القضية <br>
                                                        <input type="radio" name="SearchType" value="4" onclick="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['section']) && $_GET['section'] === '4'){echo 'checked';}?>> اضافة مهمة بدون رقم ملف 
                                                    </div><br>
                                                    <?php 
                                                        if(isset($_GET['section']) && $_GET['section'] === '1'){
                                                    ?>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم الملف</p>
                                                        <input type="number" class="form-input" value="<?php if(isset($_GET['fno']) && $_GET['fno'] !== ''){echo safe_output($_GET['fno']);}?>" name="file_id" onChange="submit()">
                                                    </div>
                                                    <?php 
                                                        } elseif(isset($_GET['section']) && $_GET['section'] === '2'){
                                                    ?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الموكل/الخصم</p>
                                                        <input type="text" class="form-input" value="<?php if(isset($_GET['cn']) && $_GET['cn'] !== ''){echo safe_output($_GET['cn']);}?>" name="SearchByClient" onChange="submit()">
                                                        <input type="radio" name="Ckind" value="1" onchange="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['ck']) && $_GET['ck'] === '1'){echo 'checked';}?>> موكل <br>
                                                        <input type="radio" name="Ckind" value="2" onchange="submit()" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['ck']) && $_GET['ck'] === '2'){echo 'checked';}?>> خصم 
                                                    </div>
                                                    <?php 
                                                        } elseif(isset($_GET['section']) && $_GET['section'] === '3'){
                                                    ?>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم القضية</p>
                                                        <input type="number" name="case_no" class="form-input" placeholder="رقم القضية" value="<?php if(isset($_GET['cno']) && $_GET['cno'] !== ''){ echo $_GET['cno']; }?>" style="display: inline-block; width: 80px"> / <input type="number" name="case_no_year" class="form-input" placeholder="السنة" value="<?php if(isset($_GET['cy']) && $_GET['cy'] !== ''){ echo $_GET['cy']; }?>" style="display: inline-block; width: 80px">
                                                        <input type="image" src="img/magnifying-glass.png" width="20px" height="20px" align="absmiddle" onClick="submit()" style="border:none">
                                                    </div>
                                                    <?php 
                                                        } 
                                                        if((isset($_GET['fno']) && $_GET['fno'] !== '') || (isset($_GET['cn']) && $_GET['cn'] !== '') || (isset($_GET['cno']) && $_GET['cno'] !== '' && isset($_GET['cy']) && $_GET['cy'] !== '')){
                                                            if(isset($_GET['section']) && $_GET['section'] !== '4'){
                                                    ?>
                                                    <div class="input-container">
                                                        <?php
                                                            if(isset($_GET['fno'])){
                                                                $fid = $_GET['fno'];
                                                                $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
                                                                $stmt->bind_param("i", $fid);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                            } else if(isset($_GET['cn']) && isset($_GET['ck'])){
                                                                $cn = $_GET['cn'];
                                                                $ck = $_GET['ck'];
                                                                
                                                                // First search for client
                                                                $stmt = $conn->prepare("SELECT * FROM client WHERE arname LIKE ?");
                                                                $search_term = "%$cn%";
                                                                $stmt->bind_param("s", $search_term);
                                                                $stmt->execute();
                                                                $resultc = $stmt->get_result();
                                                                if($resultc->num_rows > 0){
                                                                    $rowc = $resultc->fetch_array();
                                                                    $cn = $rowc['id'];
                                                                }
                                                                
                                                                if($_GET['ck'] === '1'){
                                                                    $stmt = $conn->prepare("SELECT * FROM file WHERE file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?");
                                                                    $stmt->bind_param("iiiii", $cn, $cn, $cn, $cn, $cn);
                                                                    $stmt->execute();
                                                                    $result = $stmt->get_result();
                                                                } else {
                                                                    $stmt = $conn->prepare("SELECT * FROM file WHERE file_opponent = ? OR file_opponent2 = ? OR file_opponent3 = ? OR file_opponent4 = ? OR file_opponent5 = ?");
                                                                    $stmt->bind_param("iiiii", $cn, $cn, $cn, $cn, $cn);
                                                                    $stmt->execute();
                                                                    $result = $stmt->get_result();
                                                                }
                                                            } else if(isset($_GET['cno']) && $_GET['cno'] !== '' && isset($_GET['cy']) &&$_GET['cy'] !== ''){
                                                                $cno = $_GET['cno'];
                                                                $cy = $_GET['cy'];
                                                                
                                                                // First get the file ID
                                                                $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE case_num = ? AND file_year = ?");
                                                                $stmt->bind_param("ii", $cno, $cy);
                                                                $stmt->execute();
                                                                $result1 = $stmt->get_result();
                                                                $row1 = $result1->fetch_array();
                                                                $fid = $row1['fid'];
                                                                
                                                                // Then get the file details
                                                                $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
                                                                $stmt->bind_param("i", $fid);
                                                                $stmt->execute();
                                                                $result = $stmt->get_result();
                                                            }
                                                            
                                                            if(isset($_GET['section']) && $_GET['section'] !== '4' && $result->num_rows == 0){
                                                                exit();
                                                            }
                                                            
                                                            if(($result->num_rows > 0)){
                                                        ?>
                                                        <table class="info-table" style="max-width: 100%; height: 400px; overflow: visible;">
                                                            <tr class="infotable-header" style="text-align: center">
                                                                <td width="14%">رقم الملف</td>
                                                                <td width="25%">الموضوع</td>
                                                                <td width="11%">رقم القضية</td>
                                                                <td width="25%">الموكل</td>
                                                                <td width="25%">الخصم</td>
                                                            </tr>
                                                            
                                                            <?php
                                                                while($row = $result->fetch_array()){
                                                            ?>
                                                            <tr class="infotable-body" onclick="location.href='Tasks.php?addmore=1&section=<?php if(isset($_GET['section']) && $_GET['section'] !== ''){ echo '1';} if(isset($row['file_id']) && $row['file_id'] !== ''){echo '&fno=' . $row['file_id'];}?>&agree=1';">
                                                                <td width="14%">
                                                                    <?php 
                                                                        if(isset($row['frelated_place']) && $row['frelated_place'] !== ''){
                                                                            $place = $row['frelated_place'];
                                                                            if($place === 'عجمان'){
                                                                                echo 'AJM';
                                                                            }
                                                                            else if($place === 'دبي'){
                                                                                echo 'DXB';
                                                                            }
                                                                            else if($place === 'الشارقة'){
                                                                                echo 'SHJ';
                                                                            }
                                                                        }
                                                                        echo ' '.$row['file_id'];
                                                                    ?>
                                                                </td>
                                                                <td width="25%">
                                                                    <?php 
                                                                        if(isset($row['file_subject']) && $row['file_subject'] !== ''){ 
                                                                            echo safe_output($row['file_subject']); 
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td width="11%">
                                                                    <?php 
                                                                        $fid = $row['file_id'];
                                                                        $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
                                                                        $stmt->bind_param("i", $fid);
                                                                        $stmt->execute();
                                                                        $resultfid = $stmt->get_result();
                                                                        $rowfid = $resultfid->fetch_array();
                                                                        if(isset($rowfid['degree']) && $rowfid['degree'] !== ''){ 
                                                                            echo safe_output($rowfid['degree']);
                                                                        }
                                                                        echo '<br>'; 
                                                                        if(isset($rowfid['case_num']) && $rowfid['case_num'] !== ''){ 
                                                                            echo '-'.$rowfid['case_num']; 
                                                                        }
                                                                        if(isset($rowfid['file_year']) && $rowfid['file_year'] !== ''){ 
                                                                            echo '/'.$rowfid['file_year']; 
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td width="25%">
                                                                    <?php 
                                                                        if(isset($row['file_client']) && $row['file_client'] !== ''){ 
                                                                            $cid1 = $row['file_client'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $cid1);
                                                                            $stmt->execute();
                                                                            $resultc1 = $stmt->get_result();
                                                                            $rowc1 = $resultc1->fetch_array();
                                                                            echo safe_output($rowc1['arname']);
                                                                            
                                                                            if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fclient_characteristic']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_client2']) && $row['file_client2'] !== ''){ 
                                                                            $cid2 = $row['file_client2'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $cid2);
                                                                            $stmt->execute();
                                                                            $resultc2 = $stmt->get_result();
                                                                            $rowc2 = $resultc2->fetch_array();
                                                                            echo safe_output($rowc2['arname']);
                                                                            
                                                                            if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fclient_characteristic2']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_client3']) && $row['file_client3'] !== ''){ 
                                                                            $cid3 = $row['file_client3'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $cid3);
                                                                            $stmt->execute();
                                                                            $resultc3 = $stmt->get_result();
                                                                            $rowc3 = $resultc3->fetch_array();
                                                                            echo safe_output($rowc3['arname']);
                                                                            
                                                                            if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fclient_characteristic3']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_client4']) && $row['file_client4'] !== ''){ 
                                                                            $cid4 = $row['file_client4'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $cid4);
                                                                            $stmt->execute();
                                                                            $resultc4 = $stmt->get_result();
                                                                            $rowc4 = $resultc4->fetch_array();
                                                                            echo safe_output($rowc4['arname']);
                                                                            
                                                                            if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fclient_characteristic4']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_client5']) && $row['file_client5'] !== ''){ 
                                                                            $cid5 = $row['file_client5'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $cid5);
                                                                            $stmt->execute();
                                                                            $resultc5 = $stmt->get_result();
                                                                            $rowc5 = $resultc5->fetch_array();
                                                                            echo safe_output($rowc5['arname']);
                                                                            
                                                                            if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fclient_characteristic5']) . '<br>'; 
                                                                            }
                                                                        }
                                                                    ?> 
                                                                </td>
                                                                <td width="25%">
                                                                    <?php 
                                                                        if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){ 
                                                                            $oid = $row['file_opponent'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $oid);
                                                                            $stmt->execute();
                                                                            $resulto = $stmt->get_result();
                                                                            $rowo = $resulto->fetch_array();
                                                                            echo safe_output($rowo['arname']);
                                                                            
                                                                            if(isset($row['fopponent_characteristic']) && $row['fopponent_characteristic'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fopponent_characteristic']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_opponent2']) && $row['file_opponent2'] !== ''){ 
                                                                            $oid2 = $row['file_opponent2'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $oid2);
                                                                            $stmt->execute();
                                                                            $resulto2 = $stmt->get_result();
                                                                            $rowo2 = $resulto2->fetch_array();
                                                                            echo safe_output($rowo2['arname']);
                                                                            
                                                                            if(isset($row['fopponent_characteristic2']) && $row['fopponent_characteristic2'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fopponent_characteristic2']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_opponent3']) && $row['file_opponent3'] !== ''){ 
                                                                            $oid3 = $row['file_opponent3'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $oid3);
                                                                            $stmt->execute();
                                                                            $resulto3 = $stmt->get_result();
                                                                            $rowo3 = $resulto3->fetch_array();
                                                                            echo safe_output($rowo3['arname']);
                                                                            
                                                                            if(isset($row['fopponent_characteristic3']) && $row['fopponent_characteristic3'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fopponent_characteristic3']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_opponent4']) && $row['file_opponent4'] !== ''){ 
                                                                            $oid4 = $row['file_opponent4'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $oid4);
                                                                            $stmt->execute();
                                                                            $resulto4 = $stmt->get_result();
                                                                            $rowo4 = $resulto4->fetch_array();
                                                                            echo safe_output($rowo4['arname']);
                                                                            
                                                                            if(isset($row['fopponent_characteristic4']) && $row['fopponent_characteristic4'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fopponent_characteristic4']) . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($row['file_opponent5']) && $row['file_opponent5'] !== ''){ 
                                                                            $oid5 = $row['file_opponent5'];
                                                                            $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
                                                                            $stmt->bind_param("i", $oid5);
                                                                            $stmt->execute();
                                                                            $resulto5 = $stmt->get_result();
                                                                            $rowo5 = $resulto5->fetch_array();
                                                                            echo safe_output($rowo5['arname']);
                                                                            
                                                                            if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ 
                                                                                echo ' / ' . safe_output($row['fopponent_characteristic5']) . '<br>'; 
                                                                            }
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php }?>
                                                        </table><br>
                                                        <?php }?>
                                                    </div>
                                                    <?php
                                                            }
                                                        }
                                                        if(isset($_GET['agree']) && $_GET['agree'] === '1'){
                                                            echo '<input type="hidden" name="agree" value="1">';
                                                            
                                                            if(isset($_GET['fno']) && $_GET['fno'] !== ''){
                                                                $fid = $_GET['fno'];
                                                                $stmt = $conn->prepare("SELECT * FROM file WHERE file_id = ?");
                                                                $stmt->bind_param("i", $fid);
                                                                $stmt->execute();
                                                                $result1 = $stmt->get_result();
                                                                $row1 = $result1->fetch_array();
                                                            }
                                                            
                                                            if(isset($_GET['section']) && $_GET['section'] !== '4'){
                                                    ?>
                                                    <div class="input-container" style="border-bottom: 2px solid #f8f8f8;">
                                                        <p class="input-parag">الفرع</p>
                                                        <p class="form-input">
                                                            <?php
                                                                if(isset($row1['frelated_place']) && $row1['frelated_place'] !== ''){
                                                                    echo safe_output($row1['frelated_place']);
                                                                }
                                                            ?>
                                                        <p class="input-parag">نوع القضية</p>
                                                        <p class="form-input">
                                                            <?php
                                                                if(isset($row1['fcase_type']) && $row1['fcase_type'] !== ''){ 
                                                                    echo safe_output($row1['fcase_type']);
                                                                }
                                                            ?>
                                                        </p>
                                                        </p>
                                                    </div><br>
                                                    <?php }?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الموظف المكلف<font color="#FF0000">*</font></p>
                                                        <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                                                        <select class="table-header-selector" name="position_id" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" onchange="submit()" >
                                                            <option value=""></option>
                                                            <?php
                                                                $stmt = $conn->prepare("SELECT * FROM positions");
                                                                $stmt->execute();
                                                                $result_positions = $stmt->get_result();
                                                                if($result_positions->num_rows > 0){
                                                                    while($row_positions = $result_positions->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($row_positions['id']);?>"<?php if($row_positions['id'] == $selected_position){ echo 'selected'; } ?>><?php echo safe_output($row_positions['position_name']);?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                        <select class="table-header-selector" name="re_name" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <?php 
                                                                if(isset($_GET['pi']) && $_GET['pi'] !== ''){ 
                                                                    $pnid = $_GET['pi'];
                                                                    
                                                                    if(isset($_GET['rn']) && $_GET['rn'] !== ''){ $selected_emp = $_GET['rn']; } else{ $selected_emp = ''; }
                                                                    $stmt = $conn->prepare("SELECT * FROM user WHERE job_title = ?");
                                                                    $stmt->bind_param("i", $pnid);
                                                                    $stmt->execute();
                                                                    $resultemps = $stmt->get_result();
                                                                    if($resultemps->num_rows > 0){
                                                                        while($rowemps = $resultemps->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($rowemps['id']);?>" <?php if($rowemps['id'] == $selected_emp){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']);?></option>
                                                            <?php
                                                                        }
                                                                    }
                                                                } else{
                                                            ?>
                                                            <option value="" ></option>
                                                            <?php }?>
                                                        </select>
                                                        
                                                        <?php if(isset($_GET['error']) && $_GET['error'] === '1'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار الموظف</span><?php }?>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">نوع المهمة<font color="#FF0000">*</font></p>
                                                        <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                                                        <select class="table-header-selector" name="type_name" dir="rtl" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;" required>
                                                            <option value=""></option>
                                                            <?php
                                                              $stmt = $conn->prepare("SELECT * FROM job_name");
                                                              $stmt->execute();
                                                              $result_job = $stmt->get_result();
                                                              if(isset($_GET['tn']) && $_GET['tn'] !== ''){
                                                                  $selectedTn = $_GET['tn'];
                                                              } else {
                                                                  $selectedTn = '';
                                                              }
                                                              if($result_job->num_rows > 0){
                                                                while($row_job = $result_job->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($row_job['id']);?>" <?php if($row_job['id'] == $selectedTn){ echo 'selected'; }?>><?php echo safe_output($row_job['job_name']);?></option>
                                                            <?php
                                                                }
                                                              } else{
                                                            ?>
                                                            <option value=""></option>
                                                            <?php
                                                              }
                                                            ?>
                                                        </select> 
                                                        <?php if($row_permcheck['admjobs_rperm'] == 1){?> 
                                                        <img src="img/add-button.png" align="absmiddle" width="25px" height="25px" title="اضافة" onclick="addnew()" style="cursor:pointer"/>
                                                        <?php }?>
                                                    </div>
                                                    <?php if(isset($_GET['section']) && $_GET['section'] !== '4'){?>
                                                    <div class="input-container">
                                                        <p class="input-parag">درجة التقاضي</p>
                                                        <?php if(isset($_GET['pi']) && $_GET['pi'] !== ''){ $selected_position = $_GET['pi']; } else{ $selected_position = ''; }?>
                                                        <select class="table-header-selector" name="degree_id" dir="rtl" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <?php 
                                                                if(isset($_GET['di']) && $_GET['di'] !== ''){
                                                                    $selectedDeg = $_GET['di'];
                                                                } else {
                                                                    $selectedDeg = '';
                                                                }
                                                                $fid = $_GET['fno'];
                                                                $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
                                                                $stmt->bind_param("i", $fid);
                                                                $stmt->execute();
                                                                $resultdegs = $stmt->get_result();
                                                                if($resultdegs->num_rows > 0){
                                                                    while($rowdegs = $resultdegs->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($rowdegs['id']);?>" <?php if($rowdegs['id'] == $selectedDeg){ echo 'selected'; }?>><?php echo safe_output($rowdegs['case_num']) . '/' . safe_output($rowdegs['file_year']) . '-' . safe_output($rowdegs['degree']);?></option>
                                                            <?php }}?>
                                                        </select> 
                                                    </div>
                                                    <?php }?>
                                                    <div class="input-container">
                                                        <p class="input-parag">الاهمية</p>
                                                        <input type="radio" name="busi_priority" value="0" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['bp']) && $_GET['bp'] === '0'){ echo 'checked'; } if(!isset($_GET['bp'])){ echo 'checked'; }?>> عادي <br>
                                                        <input type="radio" name="busi_priority" value="1" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['bp']) && $_GET['bp'] === '1'){ echo 'checked'; }?>> عاجل <br>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ تنفيذ المهمة</p>
                                                        <input type="date" class="form-input" value="<?php if(isset($_GET['bd']) && $_GET['bd'] !== ''){ echo safe_output($_GET['bd']);}?>" name="busi_date">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">التفاصيل</p>
                                                        <textarea class="form-input" name="busi_notes" rows="2"><?php if(isset($_GET['bn']) && $_GET['bn'] !== ''){echo safe_output($_GET['bn']);}?></textarea>
                                                    </div>
                                                    <?php }?>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer;" type="submit" name="save_task_fid" class="form-btn submit-btn">حفظ</button>
                                                <button style="cursor: pointer;" type="submit" name="submit_back" value="addmore" class="form-btn cancel-btn">حفظ و انشاء آخر</button>
                                                <button type="button" class="form-btn cancel-btn" onclick="location.href='Tasks.php';">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                                <div class="modal-overlay" <?php if(isset($_GET['actions']) && $_GET['actions'] == 1){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">اجراءات المهام</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='Tasks.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <form action="task_statusedit.php" method="post">
                                                    <input type="hidden" name="page" value="mytasks.php">
                                                    <th width="95%" align="right">
                                                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                            <tr>
                                                                <td>
                                                                    <input type="hidden" name="page" value="Tasks.php";>
                                                                    <font  style="font-size:16px; font-weight:bold">تحويل الى :</font> 
                                                                    <input type="hidden" name="idddd" value="<?php echo safe_output($_GET['taskid']); ?>">
                                                                    <select class="table-header-selector" name="re_name" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                                        <?php
                                                                            $oldemp = '';
                                                                            
                                                                            $oldtskold = safe_output($_GET['taskid']);
                                                                            $stmttskold = $conn->prepare("SELECT * FROM tasks WHERE id=?");
                                                                            $stmttskold->bind_param("i", $oldtskold);
                                                                            $stmttskold->execute();
                                                                            $resulttskold = $stmttskold->get_result();
                                                                            if($resulttskold->num_rows > 0){
                                                                                $rowtskold = $resulttskold->fetch_assoc();
                                                                                $oldemp = $rowtskold['employee_id'];
                                                                            }
                                                                            
                                                                            $stmtemps = $conn->prepare("SELECT * FROM user");
                                                                            $stmtemps->execute();
                                                                            $resultemps = $stmtemps->get_result();
                                                                            if($resultemps->num_rows > 0){
                                                                                while($rowemps = $resultemps->fetch_assoc()){
                                                                        ?>
                                                                        <option value="<?php echo safe_output($rowemps['id']); ?>" <?php if($rowemps['id'] == $oldemp){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']); ?></option>
                                                                        <?php
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </select>
                                                                    <br>
                                                                    <p style="text-align: right"><font style="font-size:16px; font-weight:bold">الملاحظة :</font> </p>
                                                                    <textarea name="t_note" rows="2" class="form-input"></textarea>
                                                                    <input type="hidden" name="getfid" value="<?php echo safe_output($_GET['id']); ?>">
                                                                    <br><br>
                                                                    <input type="submit" value="جاري العمل" name="inpt" style="cursor:pointer;" class="form-btn submit-btn">
                                                                    <input type="submit" value="تم الانتهاء" name="endt" style="cursor:pointer;" class="form-btn submit-btn">
                                                                </td>
                                                            </tr>
                                                        </table>
                                                    </th>
                                                    
                                                    <br>
                                                    <td width="5%" align="right">
                                                        <button type="submit" name="submit_re_name" class="form-btn submit-btn">حفظ</button>
                                                    </td>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if($row_permcheck['admjobs_eperm'] == 1){?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">تعديل المهمة</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='Tasks.php';">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ?");
                                                $stmt->bind_param("i", $id);
                                                $stmt->execute();
                                                $eresult = $stmt->get_result();
                                                $erow = $eresult->fetch_array();
                                            }
                                        ?>
                                        <form action="tedit.php" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">الموظف المكلف<font color="#FF0000">*</font></p>
                                                        <select class="table-header-selector" name="re_name" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <?php 
                                                                if(isset($erow['employee_id']) && $erow['employee_id'] !== ''){
                                                                    
                                                                    if(isset($erow['employee_id']) && $erow['employee_id'] !== ''){ $selected_emp = $erow['employee_id']; } else{ $selected_emp = ''; }
                                                                    $stmt = $conn->prepare("SELECT * FROM user");
                                                                    $stmt->execute();
                                                                    $resultemps = $stmt->get_result();
                                                                    if($resultemps->num_rows > 0){
                                                                        while($rowemps = $resultemps->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($rowemps['id']);?>" <?php if($rowemps['id'] == $selected_emp){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']);?></option>
                                                            <?php
                                                                        }
                                                                    }
                                                                } else{
                                                            ?>
                                                            <option value=""></option>
                                                            <?php }?>
                                                        </select>
                                                        
                                                        <?php if(isset($_GET['error']) && $_GET['error'] === '1'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار الموظف</span><?php }?>
                                                    </div>
                                                    <input type="hidden" name="tid" value="<?php echo safe_output($_GET['id']);?>">
                                                    <div class="input-container">
                                                        <p class="input-parag">نوع المهمة<font color="#FF0000">*</font></p>
                                                        <select class="table-header-selector" name="type_name" dir="rtl" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;" required>
                                                            <option value=""></option>
                                                            <?php
                                                                $stmt = $conn->prepare("SELECT * FROM job_name");
                                                                $stmt->execute();
                                                                $result_job = $stmt->get_result();
                                                                if(isset($erow['task_type']) && $erow['task_type'] !== ''){$selectedTn = $erow['task_type'];} else {$selectedTn = '';}
                                                                if($result_job->num_rows > 0){
                                                                    while($row_job = $result_job->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($row_job['id']);?>" <?php if($row_job['id'] == $selectedTn){ echo 'selected'; }?>><?php echo safe_output($row_job['job_name']);?></option>
                                                            <?php
                                                                    }
                                                                } else{
                                                            ?>
                                                            <option value=""></option>
                                                            <?php }?>
                                                        </select> 
                                                        <?php if($row_permcheck['admjobs_rperm'] == 1){?> 
                                                        <img src="img/add-button.png" align="absmiddle" width="25px" height="25px" title="اضافة" onclick="addnew()" style="cursor:pointer"/>
                                                        <?php }?>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">درجة التقاضي</p>
                                                        <select class="table-header-selector" name="degree_id" dir="rtl" style="width: 60%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value=""></option>
                                                            <?php 
                                                                if(isset($erow['degree']) && $erow['degree'] !== ''){$selectedDeg = $erow['degree'];} else{$selectedDeg = '';}
                                                                $fid = $erow['file_no'];
                                                                $stmt = $conn->prepare("SELECT * FROM file_degrees WHERE fid = ? ORDER BY created_at DESC");
                                                                $stmt->bind_param("i", $fid);
                                                                $stmt->execute();
                                                                $resultdegs = $stmt->get_result();
                                                                if($resultdegs->num_rows > 0){
                                                                    while($rowdegs = $resultdegs->fetch_array()){
                                                            ?>
                                                            <option value="<?php echo safe_output($rowdegs['id']);?>" <?php if($rowdegs['id'] == $selectedDeg){ echo 'selected'; }?>><?php echo safe_output($rowdegs['case_num']) . '/' . safe_output($rowdegs['file_year']) . '-' . safe_output($rowdegs['degree']);?></option>
                                                            <?php }}?>
                                                        </select> 
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الاهمية</p>
                                                        <input type="radio" name="busi_priority" value="0" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($erow['priority']) && $erow['priority'] == 0){ echo 'checked'; } if(!isset($erow['priority'])){ echo 'checked'; }?>> عادي <br>
                                                        <input type="radio" name="busi_priority" value="1" style="color: #43425d; padding: 10px 0; margin: 10px 0;" <?php if(isset($erow['priority']) && $erow['priority'] == 1){ echo 'checked'; }?>> عاجل <br>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ تنفيذ المهمة</p>
                                                        <input type="date" class="form-input" value="<?php if(isset($erow['duedate']) && $erow['duedate'] !== ''){ echo safe_output($erow['duedate']);}?>" name="busi_date">
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">التفاصيل</p>
                                                        <textarea class="form-input" name="busi_notes" rows="2"><?php if(isset($erow['details']) && $erow['details'] !== ''){echo safe_output($erow['details']);}?></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer;" type="submit" name="save_task_fid" class="form-btn submit-btn">حفظ</button>
                                                <button style="cursor: pointer;" type="submit" name="submit_back" value="addmore" class="form-btn cancel-btn">حفظ و انشاء جديد</button>
                                                <button type="button" class="form-btn cancel-btn" onclick="location.href='Tasks.php';">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                
                                <?php 
                                    }
                                    if($row_permcheck['admjobs_rperm'] == 1){
                                ?>
                                <div id="addnew-btn" class="modal-overlay" style="<?php if($_GET['addplus'] === '1' || isset($_GET['jnid'])){?>display: block; <?php }?>">
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto"><?php if(!isset($_GET['jnid'])){ echo 'اضافة انواع مهام'; } else{ echo 'تعديل انواع المهام'; }?></h4>
                                                <div class="close-button-container">
                                                    <?php
                                                        $queryString = $_SERVER['QUERY_STRING'];
                                                        $idquery = $rowjn['id'];
                                                        if($queryString !== ''){
                                                            if(isset($_GET['jnid'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['jnid']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            if(isset($_GET['addplus'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['addplus']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            if(isset($_GET['savedjtype'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['savedjtype']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            if(isset($_GET['editedjtype'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['editedjtype']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            $queryString = "?$queryString";
                                                        } else{
                                                            $queryString = "";
                                                        }
                                                    ?>
                                                    <p class="close-button" onclick="location.href='Tasks.php<?php echo safe_output($queryString);?>';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php if($row_permcheck['admjobs_aperm'] == 1){?>
                                                <form action="<?php if(isset($_GET['jnid']) && $_GET['jnid']){ echo 'ejob.php'; } else{ echo 'jobadd.php'; }?>" method="post" enctype="multipart/form-data">    
                                                    <div class="input-container">
                                                        <p class="input-parag">نوع المهمة</p>
                                                        <?php
                                                            if(isset($_GET['jnid']) && $_GET['jnid']){
                                                                $jnid = $_GET['jnid'];
                                                                
                                                                $stmt = $conn->prepare("SELECT * FROM job_name WHERE id = ?");
                                                                $stmt->bind_param("i", $jnid);
                                                                $stmt->execute();
                                                                $resultjnid = $stmt->get_result();
                                                                $rowjnid = $resultjnid->fetch_array();
                                                            }
                                                        ?>
                                                        <input type="text" class="form-input" name="job_name" value="<?php echo safe_output($rowjnid['job_name']);?>" required>
                                                        <?php
                                                            if(isset($_GET['jnid']) && $_GET['jnid'] !== ''){
                                                        ?>
                                                        <input type="hidden" class="form-input" name="jnid" value="<?php echo safe_output($rowjnid['id']);?>">
                                                        <?php
                                                            }
                                                        ?>
                                                        <input type="hidden" name="queryString" value="<?php echo safe_output($_SERVER['QUERY_STRING']);?>">
                                                    </div>
                                                    <div class="input-container">
                                                        <button style="cursor: pointer;" type="submit" name="save_task_fid" class="form-btn submit-btn">حفظ</button>
                                                    </div>
                                                </form>
                                                <?php }?>
                                                <div class="table-body" style="overflow: visible;">
                                                    <form action="taskdel.php" method="post">
                                                        <table class="info-table" style="width: 100%;">
                                                            <?php
                                                                if($row_permcheck['admjobs_rperm'] == 1){
                                                            ?>
                                                            <tr class="infotable-header">
                                                                <th>انواع المهام</th>
                                                                <th width="80px">الاجراءات</th>
                                                            </tr>
                                                            
                                                            <?php
                                                                $stmtjn = $conn->prepare("SELECT * FROM job_name");
                                                                $stmtjn->execute();
                                                                $resultjn = $stmtjn->get_result();
                                                                
                                                                if($resultjn->num_rows > 0){
                                                                    while($rowjn = $resultjn->fetch_array()){
                                                            ?>
                                                            <tr class="infotable-body">
                                                                <td>
                                                                    <?php echo safe_output($rowjn['job_name']);?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                        $queryString = $_SERVER['QUERY_STRING'];
                                                                        $idquery = $rowjn['id'];
                                                                        if($queryString !== ''){
                                                                            if(isset($_GET['jnid'])){
                                                                                parse_str($queryString, $queryParams);
                                                                                unset($queryParams['jnid']);
                                                                                $queryString = http_build_query($queryParams);
                                                                            }
                                                                            if (strpos($queryString, 'savedjtype') !== false) {
                                                                                parse_str($queryString, $queryParams);
                                                                                unset($queryParams['savedjtype']);
                                                                                $queryString = http_build_query($queryParams);
                                                                            }
                                                                            if (strpos($queryString, 'editedjtype') !== false) {
                                                                                parse_str($queryString, $queryParams);
                                                                                unset($queryParams['editedjtype']);
                                                                                $queryString = http_build_query($queryParams);
                                                                            }
                                                                            $queryString = "?$queryString&jnid=$idquery";
                                                                        } else{
                                                                            $queryString = "?jnid=$idquery";
                                                                        }
                                                                        if($row_permcheck['admjobs_eperm'] == 1){
                                                                    ?>
                                                                    <img src="img/edit.png" width="20px" height="20px" style="cursor: pointer" title="تعديل" onclick="location.href='Tasks.php<?php echo safe_output($queryString);?>';">
                                                                    <?php }if($row_permcheck['admjobs_dperm'] == 1){?>
                                                                    <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" title="حذف" onclick="location.href='deletejob.php?id=<?php echo safe_output($rowjn['id']);?>&<?php echo safe_output($_SERVER['QUERY_STRING']);?>';">
                                                                    <?php }?>
                                                                </td>
                                                            </tr>
                                                            <?php }}}?>
                                                        </table>
                                                    </div>
                                                    
                                                    <div class="table-footer">
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body" id="printSection">
                            <form action="taskdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%; background-color: #99999940">
                                    <?php
                                        if($row_permcheck['admjobs_rperm'] == 1){
                                    ?>
                                    <thead>
                                        <tr class="infotable-search no-print">
                                            <td colspan="19">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block">البحث : </p>
                                                    <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                                </div>
                                            </td>
                                        </tr>
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <td align="center" width="20px">ت</td>
                                            <td align="center">رقم الملف</td>
                                            <td align="center">ت.التنفيذ</td>
                                            <td align="center">المهمة <font color="#ff0000">*</font></td>
                                            <td align="center">التفاصيل</td>
                                            <td align="center">اهمية المهمة</td>
                                            <td align="center">درجة التقاضي</td>
                                            <td align="center">الموظف المكلف <font color="#ff0000">*</font></td>
                                            <td align="center">ت.م الادخال</td>
                                            <td align="center" width="80px" class="no-print">الاجراءات</td>
                                        </tr>
                                    </thead>
                                    
                                    <tbody id="table1">
                                        <?php 
                                            if(isset($_GET['type']) && $_GET['type'] !== ''){
                                                $type = $_GET['type'];
                                                if($type === 'pending'){
                                                    $status = '0';
                                                } else if($type === 'inprogress'){
                                                    $status = '1';
                                                } else if($type === 'finished'){
                                                    $status = '2';
                                                }
                                                
                                                if(isset($status)){
                                                    $stmt = $conn->prepare("SELECT * FROM tasks WHERE task_status = ? ORDER BY duedate DESC");
                                                    $stmt->bind_param("s", $status);
                                                } else {
                                                    $stmt = $conn->prepare("SELECT * FROM tasks ORDER BY duedate DESC");
                                                }
                                            } else {
                                                $stmt = $conn->prepare("SELECT * FROM tasks ORDER BY duedate DESC");
                                            }
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            if($result->num_rows > 0){
                                                $counttsk = 0;
                                                while($rowtsk = $result->fetch_assoc()){
                                                    $counttsk++;
                                        ?>
                                        <tr class="infotable-body" id="row-<?php echo safe_output($row['id']); ?>">
                                            <td align="center" <?php if($rowtsk['task_status'] == 1){ echo "style = 'background-color: #faffa6;'"; } else if($rowtsk['task_status'] == 2){ echo "style = 'background-color: #B5F3A3;'"; }?>><?php echo safe_output($counttsk); ?></td>
                                            
                                            <td align="center" <?php if($row_permcheck['cfiles_rperm'] == 1 && $rowtsk['file_no'] !== '' && $rowtsk['file_no'] != 0){?>style="cursor: pointer;" onclick="location.href='FileEdit.php?id=<?php echo safe_output($rowtsk['file_no']);?>';"<?php }?>><?php if($rowtsk['file_no'] != 0){ echo safe_output($rowtsk['file_no']); }?></td>
                                            
                                            <td align="center"><?php if(isset($rowtsk['duedate']) && $rowtsk['duedate'] !== ''){echo safe_output($rowtsk['duedate']);}?></td>
                                            
                                            <td align="center">
                                                <?php
                                                    if(isset($rowtsk['task_type']) && $rowtsk['task_type'] !== ''){
                                                        $tskkid = $rowtsk['task_type'];
                                                        
                                                        $stmttskkid = $conn->prepare("SELECT * FROM job_name WHERE id=?");
                                                        $stmttskkid->bind_param("i", $tskkid);
                                                        $stmttskkid->execute();
                                                        $resulttskkid = $stmttskkid->get_result();
                                                        if($resulttskkid->num_rows > 0){
                                                            $rowtskkid = $resulttskkid->fetch_assoc();
                                                            if(isset($rowtskkid['job_name'])){ echo safe_output($rowtskkid['job_name']); }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if(isset($rowtsk['details']) && $rowtsk['details'] !== ''){
                                                        echo safe_output($rowtsk['details']).'<br>';
                                                    }
                                                    
                                                    $stmttsknotes = $conn->prepare("SELECT * FROM task_notes WHERE taskid=?");
                                                    $stmttsknotes->bind_param("i", $rowtsk['id']);
                                                    $stmttsknotes->execute();
                                                    $resulttsknotes = $stmttsknotes->get_result();
                                                    if($resulttsknotes->num_rows > 0){
                                                        $counttsknotes = 0;
                                                        while($rowtsknotes = $resulttsknotes->fetch_assoc()){
                                                            $counttsknotes++;
                                                ?>
                                                <br>
                                                <ul>
                                                    <li style='display: grid; grid-template-columns: 1fr 20px;'>
                                                        <p><?php echo '&#8226; '. $rowtsknotes['note'];?></p>
                                                        <?php if($rowtsk['employee_id'] == $_SESSION['id'] || $admin == 1){?>
                                                        <img src="img/recycle-bin.png" width="20px" height="20px" style="cursor: pointer" onclick="location.href='task_statusedit.php?tsknoteid=<?php echo safe_output($rowtsknotes['id']);?>&fidnotetsk=<?php echo safe_output($_GET['id']);?>';">
                                                        <?php }?>
                                                    </li>
                                                </ul>
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if(isset($rowtsk['priority']) && $rowtsk['priority'] !== ''){
                                                        if($rowtsk['priority'] == 0){
                                                            echo 'عادي';
                                                        } else if($rowtsk['priority'] == 1){
                                                            echo '<font class="blink" style="font-size: 18px; color: #ff0000">عاجل</font>';
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center">
                                                <?php 
                                                    if(isset($rowtsk['degree']) && $rowtsk['degree'] !== ''){
                                                        $didd = $rowtsk['degree'];
                                                        
                                                        $stmtdidd = $conn->prepare("SELECT * FROM file_degrees WHERE id=?");
                                                        $stmtdidd->bind_param("i", $didd);
                                                        $stmtdidd->execute();
                                                        $resultdidd = $stmtdidd->get_result();
                                                        if($resultdidd->num_rows > 0){
                                                            $rowdidd = $resultdidd->fetch_assoc();
                                                            if(isset($rowdidd['degree'])){ echo safe_output($rowdidd['degree']); }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center" style="<?php if($rowtsk['employee_id'] == $_SESSION['id']){echo 'color: red';}?>">
                                                <?php
                                                    if(isset($rowtsk['employee_id']) && $rowtsk['employee_id'] !== ''){
                                                        $empppid = $rowtsk['employee_id'];
                                                        
                                                        $stmtemppidd = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtemppidd->bind_param("i", $empppid);
                                                        $stmtemppidd->execute();
                                                        $resultemppidd = $stmtemppidd->get_result();
                                                        if($resultemppidd->num_rows > 0){
                                                            $rowemppidd = $resultemppidd->fetch_assoc();
                                                            if(isset($rowemppidd['name'])){ echo safe_output($rowemppidd['name']); }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td align="center" style="color: #333; font-size: 12px;">
                                                <?php 
                                                    if(isset($rowtsk['responsible']) && $rowtsk['responsible'] !== ''){
                                                        $etid = $rowtsk['responsible'];
                                                        
                                                        $stmtem = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtem->bind_param("i", $etid);
                                                        $stmtem->execute();
                                                        $resultem = $stmtem->get_result();
                                                        
                                                        $rowem = $resultem->fetch_assoc();
                                                        echo safe_output($rowem['name']).'<br>'.safe_output($rowtsk['timestamp']);
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td style="align-content: center;" class="no-print">
                                                <?php if($rowtsk['employee_id'] == $_SESSION['id']){?>
                                                <img src="img/actions.png" style="cursor: pointer;" title="الاجراءات" height="20px" width="20px" onclick="location.href='Tasks.php?actions=1&taskid=<?php echo safe_output($rowtsk['id']);?>';">
                                                <?php } if($row_permcheck['admjobs_eperm'] == 1){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='Tasks.php?edit=1&id=<?php echo safe_output($rowtsk['id']);?>';">
                                                <?php } if($row_permcheck['admjobs_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='tdel.php?tid=<?php echo safe_output($rowtsk['id']);?>';">
                                                <?php }if($row_permcheck['admjobs_pperm'] == 1){?>
                                                <img src="img/printer.png" style="cursor: pointer;" title="طباعة" height="20px" width="20px" onclick="printRow('row-<?php echo safe_output($rowtsk['id']); ?>');">
                                                <?php }?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <p></p>
                                <div id="pagination"></div>
                                <div id="pageInfo"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
        <script src="js/printDiv.js"></script>
        <script src="js/printRow.js"></script>
    </body>
</html>