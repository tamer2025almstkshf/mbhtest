<?php
    include_once 'connection.php';
    include_once 'login_check.php';
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
            $id = $_SESSION['id'];
            $querymain = "SELECT * FROM user WHERE id='$id'";
            $resultmain = mysqli_query($conn, $querymain);
            $rowmain = mysqli_fetch_array($resultmain);
            
            $myid = $_SESSION['id'];
            $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
            $result_permcheck = mysqli_query($conn, $query_permcheck);
            $row_permcheck = mysqli_fetch_array($result_permcheck);
            
            include_once 'AES256.php';
        ?>
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['acccasecost_rperm'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">اتعاب القضايا</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['acccasecost_aperm'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['acccasecost_aperm'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">اضافة اتعاب للقضية</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="addclient()">&times;</p>
                                            </div>
                                        </div>
                                        
                                        <?php
                                            if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){
                                                $fid = $_GET['Fno'];
                                                $queryr = "SELECT * FROM file WHERE file_id='$fid'";
                                                $resultr = mysqli_query($conn, $queryr);
                                                $rowr = mysqli_fetch_array($resultr);
                                            } else if(isset($_GET['Mname']) && $_GET['Mname'] !== ''){
                                                $Mname = $_GET['Mname'];
                                                $queryrc = "SELECT * FROM client WHERE arname='$Mname' OR engname='$Mname'";
                                                $resultrc = mysqli_query($conn, $queryrc);
                                                
                                                $cid='none';
                                                if($resultrc->num_rows > 0){
                                                    $rowrc = mysqli_fetch_array($resultrc);
                                                    $cid = $rowrc['id'];
                                                    
                                                    $queryr = "SELECT * FROM file WHERE file_client='$cid' ORDER BY file_id DESC";
                                                    $resultr = mysqli_query($conn, $queryr);
                                                    $rowr = mysqli_fetch_array($resultr);
                                                    
                                                    $fid = $rowr['file_id'];
                                                }
                                            }
                                            
                                            $query = "SELECT * FROM cases_fees WHERE fid='$fid'";
                                            $result = mysqli_query($conn, $query);
                                            $row = mysqli_fetch_array($result);
                                        ?>
                                        <div class="addc-body">
                                            <div class="addc-body-form">
                                                <form action="cfees_query.php" method="post" enctype="multipart/form-data">
                                                    <input type="hidden" name="param" value="<?php echo $_SERVER['QUERY_STRING'];?>">
                                                    <div class="input-container">
                                                        <p class="input-parag">بحث بــ</p>
                                                        <input type="radio" name="SearchBY" value="Cli" style="padding: 10px 0; margin: 10px 0;" onclick="submit()" <?php if(isset($_GET['SearchBY']) && $_GET['SearchBY'] === 'Cli'){ echo 'checked'; }?>/> اسم الموكل <br>
                                                        <input type="radio" name="SearchBY" value="FileNo" onclick="submit()" <?php if(isset($_GET['SearchBY']) && $_GET['SearchBY'] === 'FileNo'){ echo 'checked'; }?>/> رقم الملف 
                                                    </div>
                                                    <?php if(isset($_GET['SearchBY']) && $_GET['SearchBY'] === 'FileNo'){?>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم الملف</p>
                                                        <input type="number" class="form-input" name="Fno" value="<?php if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){ echo $_GET['Fno']; }?>" onChange="submit()">
                                                        
                                                        <?php if(isset($_GET['Fno']) && $_GET['Fno'] !== '' && $resultr->num_rows > 0){?>
                                                        <div align="justify" class="form-input">
                                                            قيمة الاتعاب : <font color=red><?php if(isset($row['fees']) && $row['fees'] !== ''){ echo $row['fees']; } else{ echo '0'; }?></font> AED<br>
                                                            الاعمال الإدارية : <font color=red><?php if(isset($row['bm_fees']) && $row['bm_fees'] !== ''){ echo $row['bm_fees']; } else{ echo '0'; }?></font> AED<br>
                                                            تنبية الحد الادني للأعمال : <font color=red><?php if(isset($row['bm_alert']) && $row['bm_alert'] !== ''){ echo $row['bm_alert']; } else{ echo '0'; }?></font> AED
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                    
                                                    <?php } else if(isset($_GET['SearchBY']) && $_GET['SearchBY'] === 'Cli'){?>
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم الموكل<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="Mname" value="<?php if(isset($_GET['Mname']) && $_GET['Mname'] !== ''){ echo $_GET['Mname']; }?>" type="text" required>
                                                        
                                                        <?php if(isset($_GET['Mname']) && $_GET['Mname'] !== '' && $resultr->num_rows > 0){?>
                                                        <div align="justify" class="form-input">
                                                            قيمة الاتعاب : <font color=red><?php if(isset($row['fees']) && $row['fees'] !== ''){ echo $row['fees']; } else{ echo '0'; }?></font> AED<br>
                                                            الاعمال الإدارية : <font color=red><?php if(isset($row['bm_fees']) && $row['bm_fees'] !== ''){ echo $row['bm_fees']; } else{ echo '0'; }?></font> AED<br>
                                                            تنبية الحد الادني للأعمال : <font color=red><?php if(isset($row['bm_alert']) && $row['bm_alert'] !== ''){ echo $row['bm_alert']; } else{ echo '0'; }?></font> AED
                                                        </div>
                                                        <?php }?>
                                                    </div>
                                                    <?php } if((isset($_GET['SearchBY']) && $_GET['SearchBY'] !== '') && ((isset($_GET['Fno']) && $_GET['Fno'] !== '') || (isset($_GET['Mname']) && $_GET['Mname'] !== ''))){?>
                                                        <table class="info-table" style="max-width: 100%; height: 400px overflow: visible;">
                                                            <tr class="infotable-header" style="text-align: center">
                                                                <td width="14%">رقم الملف</td>
                                                                <td width="25%">الموضوع</td>
                                                                <td width="11%">رقم القضية</td>
                                                                <td width="25%">الموكل</td>
                                                                <td width="25%">الخصم</td>
                                                            </tr>
                                                            
                                                            <?php
                                                                if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){
                                                                    $fid = $_GET['Fno'];
                                                                    $queryr = "SELECT * FROM file WHERE file_id='$fid'";
                                                                    $resultr = mysqli_query($conn, $queryr);
                                                                } 
                                                                
                                                                else if(isset($_GET['Mname']) && $_GET['Mname'] !== ''){
                                                                    $Mname = $_GET['Mname'];
                                                                    $queryrc = "SELECT * FROM client WHERE arname='$Mname' OR engname='$Mname'";
                                                                    $resultrc = mysqli_query($conn, $queryrc);
                                                                    
                                                                    $cid='';
                                                                    if($resultrc->num_rows > 0){
                                                                        $rowrc = mysqli_fetch_array($resultrc);
                                                                        $cid = $rowrc['id'];
                                                                        $queryr = "SELECT * FROM file WHERE file_client='$cid' ORDER BY file_id DESC";
                                                                        $resultr = mysqli_query($conn, $queryr);
                                                                    }
                                                                }
                                                                while($rowr = mysqli_fetch_array($resultr)){
                                                            ?>
                                                            <tr class="infotable-body" onclick="location.href='CasesFees.php?addmore=1&SearchBY=FileNo&Fno=<?php echo $rowr['file_id'];?>';">
                                                                <td width="14%">
                                                                    <?php 
                                                                        if(isset($rowr['frelated_place']) && $rowr['frelated_place'] !== ''){
                                                                            $place = $rowr['frelated_place'];
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
                                                                        echo ' '.$rowr['file_id'];
                                                                    ?>
                                                                </td>
                                                                <td width="25%">
                                                                    <?php 
                                                                        if(isset($rowr['file_subject']) && $rowr['file_subject'] !== ''){ 
                                                                            echo $rowr['file_subject']; 
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td width="11%">
                                                                    <?php 
                                                                        $fid = $rowr['file_id'];
                                                                        $queryfid = "SELECT * FROM file_degrees WHERE fid='$fid' ORDER BY created_at DESC";
                                                                        $resultfid = mysqli_query($conn, $queryfid);
                                                                        $rowfid = mysqli_fetch_array($resultfid);
                                                                        if(isset($rowfid['degree']) && $rowfid['degree'] !== ''){ 
                                                                            echo $rowfid['degree'];
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
                                                                        if(isset($rowr['file_client']) && $rowr['file_client'] !== ''){ 
                                                                            $cid1 = $rowr['file_client'];
                                                                            $queryc1 = "SELECT * FROM client WHERE id='$cid1'";
                                                                            $resultc1 = mysqli_query($conn, $queryc1);
                                                                            $rowc1 = mysqli_fetch_array($resultc1);
                                                                            echo $rowc1['arname'];
                                                                            
                                                                            if(isset($rowr['fclient_characteristic']) && $rowr['fclient_characteristic'] !== ''){ 
                                                                                echo ' / ' . $rowr['fclient_characteristic'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_client2']) && $rowr['file_client2'] !== ''){ 
                                                                            $cid2 = $rowr['file_client2'];
                                                                            $queryc2 = "SELECT * FROM client WHERE id='$cid2'";
                                                                            $resultc2 = mysqli_query($conn, $queryc2);
                                                                            $rowc2 = mysqli_fetch_array($resultc2);
                                                                            echo $rowc2['arname'];
                                                                            
                                                                            if(isset($rowr['fclient_characteristic2']) && $rowr['fclient_characteristic2'] !== ''){ 
                                                                                echo ' / ' . $rowr['fclient_characteristic2'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_client3']) && $rowr['file_client3'] !== ''){ 
                                                                            $cid3 = $rowr['file_client3'];
                                                                            $queryc3 = "SELECT * FROM client WHERE id='$cid3'";
                                                                            $resultc3 = mysqli_query($conn, $queryc3);
                                                                            $rowc3 = mysqli_fetch_array($resultc3);
                                                                            echo $rowc3['arname'];
                                                                            
                                                                            if(isset($rowr['fclient_characteristic3']) && $rowr['fclient_characteristic3'] !== ''){ 
                                                                                echo ' / ' . $rowr['fclient_characteristic3'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_client4']) && $rowr['file_client4'] !== ''){ 
                                                                            $cid4 = $rowr['file_client4'];
                                                                            $queryc4 = "SELECT * FROM client WHERE id='$cid4'";
                                                                            $resultc4 = mysqli_query($conn, $queryc4);
                                                                            $rowc4 = mysqli_fetch_array($resultc4);
                                                                            echo $rowc4['arname'];
                                                                            
                                                                            if(isset($rowr['fclient_characteristic4']) && $rowr['fclient_characteristic4'] !== ''){ 
                                                                                echo ' / ' . $rowr['fclient_characteristic4'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_client5']) && $rowr['file_client5'] !== ''){ 
                                                                            $cid5 = $rowr['file_client5'];
                                                                            $queryc5 = "SELECT * FROM client WHERE id='$cid5'";
                                                                            $resultc5 = mysqli_query($conn, $queryc5);
                                                                            $rowc5 = mysqli_fetch_array($resultc5);
                                                                            echo $rowc5['arname'];
                                                                            
                                                                            if(isset($rowr['fclient_characteristic5']) && $rowr['fclient_characteristic5'] !== ''){ 
                                                                                echo ' / ' . $rowr['fclient_characteristic5'] . '<br>'; 
                                                                            }
                                                                        }
                                                                    ?> 
                                                                </td>
                                                                <td width="25%">
                                                                    <?php 
                                                                        if(isset($rowr['file_opponent']) && $rowr['file_opponent'] !== ''){ 
                                                                            $oid = $rowr['file_opponent'];
                                                                            $queryo = "SELECT * FROM client WHERE id='$oid'";
                                                                            $resulto = mysqli_query($conn, $queryo);
                                                                            $rowo = mysqli_fetch_array($resulto);
                                                                            echo $rowo['arname'];
                                                                            
                                                                            if(isset($rowr['fopponent_characteristic']) && $rowr['fopponent_characteristic'] !== ''){ 
                                                                                echo ' / ' . $rowr['fopponent_characteristic'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_opponent2']) && $rowr['file_opponent2'] !== ''){ 
                                                                            $oid2 = $rowr['file_opponent2'];
                                                                            $queryo2 = "SELECT * FROM client WHERE id='$oid2'";
                                                                            $resulto2 = mysqli_query($conn, $queryo2);
                                                                            $rowo2 = mysqli_fetch_array($resulto2);
                                                                            echo $rowo2['arname'];
                                                                            
                                                                            if(isset($rowr['fopponent_characteristic2']) && $rowr['fopponent_characteristic2'] !== ''){ 
                                                                                echo ' / ' . $rowr['fopponent_characteristic2'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_opponent3']) && $rowr['file_opponent3'] !== ''){ 
                                                                            $oid3 = $rowr['file_opponent3'];
                                                                            $queryo3 = "SELECT * FROM client WHERE id='$oid3'";
                                                                            $resulto3 = mysqli_query($conn, $queryo3);
                                                                            $rowo3 = mysqli_fetch_array($resulto3);
                                                                            echo $rowo3['arname'];
                                                                            
                                                                            if(isset($rowr['fopponent_characteristic3']) && $rowr['fopponent_characteristic3'] !== ''){ 
                                                                                echo ' / ' . $rowr['fopponent_characteristic3'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_opponent4']) && $rowr['file_opponent4'] !== ''){ 
                                                                            $oid4 = $rowr['file_opponent4'];
                                                                            $queryo4 = "SELECT * FROM client WHERE id='$oid4'";
                                                                            $resulto4 = mysqli_query($conn, $queryo4);
                                                                            $rowo4 = mysqli_fetch_array($resulto4);
                                                                            echo $rowo4['arname'];
                                                                            
                                                                            if(isset($rowr['fopponent_characteristic4']) && $rowr['fopponent_characteristic4'] !== ''){ 
                                                                                echo ' / ' . $rowr['fopponent_characteristic4'] . '<br>'; 
                                                                            }
                                                                        }
                                                                        
                                                                        if(isset($rowr['file_opponent5']) && $rowr['file_opponent5'] !== ''){ 
                                                                            $oid5 = $rowr['file_opponent5'];
                                                                            $queryo5 = "SELECT * FROM client WHERE id='$oid5'";
                                                                            $resulto5 = mysqli_query($conn, $queryo5);
                                                                            $rowo5 = mysqli_fetch_array($resulto5);
                                                                            echo $rowo5['arname'];
                                                                            
                                                                            if(isset($rowr['fopponent_characteristic5']) && $rowr['fopponent_characteristic5'] !== ''){ 
                                                                                echo ' / ' . $rowr['fopponent_characteristic5'] . '<br>'; 
                                                                            }
                                                                        }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                            <?php }?>
                                                        </table><br>
                                                    <?php }?>
                                                </form>
                                                
                                            <form name="addform" action="cfees_process.php" method="post" enctype="multipart/form-data" >
                                                <?php 
                                                    if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){
                                                        $fnoid = $_GET['Fno'];
                                                        $querycheckf = "SELECT * FROM file WHERE file_id='$fnoid'";
                                                        $resultcheckf = mysqli_query($conn, $querycheckf);
                                                        
                                                        if($resultcheckf->num_rows > 0){
                                                            $equery = "SELECT * FROM bank_accounts WHERE id='$fnoid'";
                                                            $eresult = mysqli_query($conn, $equery);
                                                            $erow = mysqli_fetch_array($eresult);
                                                ?>
                                                <input type="hidden" name="fid" value="<?php if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){ echo $_GET['Fno']; }?>">
                                                <div class="input-container">
                                                    <p class="input-parag">قيمة الاتعاب</p>
                                                    <input type="number" class="form-input" name="fees" value="<?php if(isset($erow['fees']) && $erow['fees'] !== ''){ echo $erow['fees']; } else{ echo '0'; }?>">
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag">قيمة الاعمال الإدارية</p>
                                                    <input type="number" class="form-input" name="bm_fees" value="<?php if(isset($row['bm_fees']) && $row['bm_fees'] !== ''){ echo $row['bm_fees']; } else{ echo '0'; }?>">
                                                </div>
                                                <div class="input-container">
                                                    <p class="input-parag">نسبة التنبية لقيمة الاعمال الإدارية للمحاسب</p>
                                                    <input type="number" class="form-input" name="bm_alert" value="<?php if(isset($row['bm_alert']) && $row['bm_alert'] !== ''){ echo $row['bm_alert']; } else{ echo '0'; }?>">
                                                </div>
                                                <?php } else{ echo '<font class="blink" color="red">يرجى ادخال رقم ملف صحيح</font>'; }}?>
                                            </div>
                                        </div>
                                        <div class="addc-footer">
                                            <?php if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){?>
                                            <button style="cursor: pointer;" type="submit" class="form-btn submit-btn">حفظ</button>
                                            <button style="cursor: pointer;" type="submit" name="submit_back" value="addmore" class="form-btn cancel-btn">حفظ و انشاء آخر</button>
                                            <?php }?>
                                            <button type="button" class="form-btn cancel-btn" onclick="addclient()">الغاء</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="casesdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%;">
                                    <thead>
                                        <tr class="infotable-search">
                                            <td colspan="19">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block">البحث : </p>
                                                    <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <th width="50px"></th>
                                            <th style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="delall" id="selectAll"></th>
                                            <th>رقم الملف</th>
                                            <th>قيمة الاتعاب</th>
                                            <th>قيمة الاعمال الادارية</th>
                                            <th>نسبة التنبية</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $query = "SELECT * FROM cases_fees ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td class="options-td" style="background-color: #fff;" width="50px">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown">
                                                    <?php if($row_permcheck['acccasecost_aperm'] === '1'){?>
                                                    <button type="button" onclick="location.href='deletecasecost.php?id=<?php echo $row['id'];?>';">حذف</button>
                                                    <?php }?>
                                                </div>
                                            </td>
                                            <td style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id'];?>"></td>
                                            <td>
                                                <?php 
                                                    if(isset($row['fid']) && $row['fid'] !== ''){
                                                        echo $row['fid'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['fees']) && $row['fees'] !== ''){
                                                        echo $row['fees'].' AED';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['bm_fees']) && $row['bm_fees'] !== ''){
                                                        echo $row['bm_fees'].' AED';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['bm_alert']) && $row['bm_alert'] !== ''){
                                                        echo $row['bm_alert'].' AED';
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <?php if($row_permcheck['acccasecost_aperm'] === '1'){?>
                                <input name="button2" type="submit" value="حذف" class="delete-selected" >
                                <?php } else{ echo '<p></p>'; }?>
                                <div id="pagination"></div>
                                <div id="pageInfo"></div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        
        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>