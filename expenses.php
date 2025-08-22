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
                    if($row_permcheck['accexpenses_rperm'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">المصروفات</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['accexpenses_aperm'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['accexpenses_aperm'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if(isset($_GET['addmore']) && $_GET['addmore'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto">مصروفات جديدة</h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="addclient()">&times;</p>
                                            </div>
                                        </div>
                                        
                                        <form action="incprocess.php" method="post" enctype="multipart/form-data" >
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container" style="border-bottom: 1px solid #00000140">
                                                        <input type="hidden" name="page" value="expenses.php">
                                                        <input type="hidden" name="ie_type" value="مصروفات">
                                                        <p class="input-parag">تصنيف الخدمة</p>
                                                        <select class="table-header-selector" name="subcat_id" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" onChange="submit()">
                                                            <option value=""></option>
                                                            <?php
                                                                $querysubcat = "SELECT * FROM sub_categories";
                                                                $resultsubcat = mysqli_query($conn, $querysubcat);
                                                                if($resultsubcat->num_rows > 0){
                                                                    while($rowsubcat = mysqli_fetch_array($resultsubcat)){
                                                                        $main_category = $rowsubcat['main_category'];
                                                                        
                                                                        $querymain = "SELECT * FROM categories WHERE id='$main_category'";
                                                                        $resultmain = mysqli_query($conn, $querymain);
                                                                        $rowmain = mysqli_fetch_array($resultmain);
                                                                        $cat_type = $rowmain['cat_type'];
                                                                        
                                                                        if($cat_type === 'ايرادات'){
                                                                            continue;
                                                                        }
                                                            ?>
                                                            <option value="<?php echo $rowsubcat['id'];?>" <?php if($_GET['subcat_id'] === $rowsubcat['id']){ echo 'selected'; }?>><?php echo $rowsubcat['subcat_name'];?></option>
                                                            <?php }}?>
                                                        </select>
                                                        <select class="table-header-selector" name="service" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" onChange="submit()">
                                                            <option value=""></option>
                                                            <?php
                                                                if(isset($_GET['subcat_id'])){
                                                                    $subcat_id = $_GET['subcat_id'];
                                                                } else{
                                                                    $subcat_id = '';
                                                                }
                                                                
                                                                $queryservice = "SELECT * FROM services WHERE subcat_id='$subcat_id'";
                                                                $resultservice = mysqli_query($conn, $queryservice);
                                                                if($resultservice->num_rows > 0){
                                                                    while($rowservice = mysqli_fetch_array($resultservice)){
                                                            ?>
                                                            <option value="<?php echo $rowservice['id'];?>" <?php if($_GET['ser'] === $rowservice['id']){ echo 'selected'; }?>><?php echo $rowservice['name'];?></option>
                                                            <?php }}?>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">بحث بــ</p>
                                                        <input type="radio" name="SearchBY" value="Cli" style="padding: 10px 0; margin: 10px 0;" onclick="submit()" <?php if(isset($_GET['SearchBy']) && $_GET['SearchBy'] === 'Cli'){ echo 'checked'; }?>> اسم الموكل <br>
                                                        <input type="radio" name="SearchBY" value="FileNo" onclick="submit()" <?php if(isset($_GET['SearchBy']) && $_GET['SearchBy'] === 'FileNo'){ echo 'checked'; }?>> رقم الملف 
                                                    </div><br>
                                                    <?php if(isset($_GET['SearchBy']) && $_GET['SearchBy'] === 'FileNo'){?>
                                                    <div class="input-container" style="border-bottom: 1px solid #00000140;">
                                                        <p class="input-parag">رقم الملف</p>
                                                        <input type="text" class="form-input" name="Fno" value="<?php if(isset($_GET['Fno']) && $_GET['Fno'] !== ''){ echo $_GET['Fno']; }?>" onchange="submit()">
                                                    </div>
                                                    <?php }if(isset($_GET['SearchBy']) && $_GET['SearchBy'] === 'Cli'){?>
                                                    <div class="input-container" style="border-bottom: 1px solid #00000140;">
                                                        <p class="input-parag">اسم الموكل</p>
                                                        <input type="text" class="form-input" name="Mname" value="<?php if(isset($_GET['Mname']) && $_GET['Mname'] !== ''){ echo $_GET['Mname']; }?>" onchange="submit()">
                                                    </div>
                                                    <?php } if((isset($_GET['SearchBy']) && $_GET['SearchBy'] !== '') && ((isset($_GET['Fno']) && $_GET['Fno'] !== '') || (isset($_GET['Mname']) && $_GET['Mname'] !== ''))){?>
                                                        <table class="info-table" style="max-width: 100%; height: 400px overflow: visible; margin-top: 10px">
                                                            <tr class="infotable-header" style="text-align: center">
                                                                <td>رقم الملف</td>
                                                                <td>الموضوع</td>
                                                                <td>رقم القضية</td>
                                                                <td>الموكل</td>
                                                                <td>الخصم</td>
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
                                                                
                                                                $getsubcatid = $_GET['subcat_id'];
                                                                while($rowr = mysqli_fetch_array($resultr)){
                                                            ?>
                                                            <tr class="infotable-body" onclick="location.href='expenses.php?subcat_id=<?php echo $getsubcatid;?>&SearchBy=FileNo&Fno=<?php echo $rowr['file_id'];?>&addmore=1';">
                                                                <td>
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
                                                                <td>
                                                                    <?php 
                                                                        if(isset($rowr['file_subject']) && $rowr['file_subject'] !== ''){ 
                                                                            echo $rowr['file_subject']; 
                                                                        }
                                                                    ?>
                                                                </td>
                                                                <td>
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
                                                                <td>
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
                                                                <td>
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
                                                    <div class="input-container">
                                                        <p class="input-parag">طريقة الدفع</p>
                                                        <input type="checkbox" name="recive_cash" value="1" style="padding: 10px 0; margin: 10px 0;" onclick="submit()" <?php if(isset($_GET['recive_cash']) && $_GET['recive_cash'] !== ''){ echo 'checked'; }?>> <font color="#006600">نقدا</font><br>
                                                        <input type="checkbox" name="recive_cheq" value="1" onclick="submit()"  <?php if(isset($_GET['recive_cheq']) && $_GET['recive_cheq'] !== ''){ echo 'checked'; }?>> <font color="#FF0000">شيك</font>
                                                    </div><br>
                                                    <?php if(isset($_GET['recive_cash']) && $_GET['recive_cash'] === '1'){?>
                                                    <div class="input-container" style="border-bottom: 1px solid #00000140;">
                                                        <p class="input-parag">مبلغ وقدرة بالدرهم</p>
                                                        <input type="number" class="form-input" name="amount">
                                                    </div>
                                                    <?php } if(isset($_GET['recive_cheq']) && $_GET['recive_cheq'] === '1'){?>
                                                    <div class="input-container" style="border-bottom: 1px solid #00000140;">
                                                        <p class="input-parag">عدد الشيكات</p>
                                                        <input type="number" class="form-input" name="CheqNum" onchange="submit()">
                                                    </div>
                                                    <?php 
                                                        }
                                                        if(isset($_GET['CheqNum']) && $_GET['CheqNum'] !== '' && $_GET['CheqNum'] !== '0'){
                                                            $cheqno = $_GET['CheqNum'];
                                                    ?>
                                                    <table class="info-table" style="max-width: 100%; height: 400px overflow: visible; margin-top: 10px">
                                                        <tr class="infotable-header" style="text-align: center">
                                                            <td>م</td>
                                                            <td>رقم الشيك</td>
                                                            <td>قيمة الشيك</td>
                                                            <td>تاريخ الاستحقاق</td>
                                                            <td>البنك التابع له</td>
                                                        </tr>
                                                        
                                                        <?php
                                                            for($i = 1 ; $i <= $cheqno ; $i++){
                                                        ?>
                                                        <tr class="infotable-body">
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
                                                    </table><br>
                                                    <?php }?>
                                                    <div class="input-container">
                                                        <p class="input-parag">استلمنا من السيد/السيدة<font color="red">*</font></p>
                                                        <input type="text" class="form-input" name="recive_from" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">وذلك عن</p>
                                                        <textarea rows='2' class="form-input" name="recive_reason"></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">استلام الى حساب بنكي</p>
                                                        <select class="table-header-selector" name="account_id" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;">
                                                            <option value="" ></option>
                                                            <?php
                                                                $querybnkaccs = "SELECT * FROM bank_accounts";
                                                                $resultbnkaccs = mysqli_query($conn, $querybnkaccs);
                                                                if($resultbnkaccs->num_rows > 0){
                                                                    while($rowbnkaccs = mysqli_fetch_array($resultbnkaccs)){
                                                            ?>
                                                            <option value="<?php echo $rowbnkaccs['id']?>"><?php echo $rowbnkaccs['name'] . ' - ' . $rowbnkaccs['account_no'];?></option>
                                                            <?php }}?>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ الاستلام</p>
                                                        <input type="date" class="form-input" name="amount_date" value="<?php echo date("d/m/Y");?>">
                                                    </div>
                                                    <div class="moreinps-container">
                                                        <button type="button" class="moreinps-toggle-btn" onclick="toggleSection(event)">
                                                            <span><i class='bx bxs-folder-open' ></i> <p>المرفقات</p></span> 
                                                            <i id="downArrow" class='bx bx-chevron-down bx-md' style="display:none;"></i>
                                                            <i id="leftArrow" class='bx bx-chevron-left bx-md' ></i>
                                                        </button>
                                                        <div class="moreinps-content" id="contactInfo" id="fileUploadSection">
                                                            <div class="input-container">
                                                                <h4 class="input-parag" style="padding-bottom: 10px;">المرفق 1</h4>
                                                                <div class="drop-zone" id="dropZone1">
                                                                    <input type="file" id="fileInput1" name="attach_file1" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList1"></div>
                                                            </div>
                                                            
                                                            <div class="input-container">
                                                                <h4 class="input-parag" style="padding-bottom: 10px;">المرفق 2</h4>
                                                                <div class="drop-zone" id="dropZone2">
                                                                    <input type="file" id="fileInput2" name="attach_file2" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput2').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList2"></div>
                                                            </div>
                                                            
                                                            <div class="input-container">
                                                                <h4 class="input-parag" style="padding-bottom: 10px;">المرفق 3</h4>
                                                                <div class="drop-zone" id="dropZone3">
                                                                    <input type="file" id="fileInput3" name="attach_file3" hidden>
                                                                    <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                                    <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput3').click()">إرفاق مستند</span></p>
                                                                </div>
                                                                <div id="fileList3"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button style="cursor: pointer;" type="submit" name="save_data" class="form-btn submit-btn">حفظ</button>
                                                <p></p>
                                                <button type="button" class="form-btn cancel-btn" onclick="addclient();">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <div class="modal-overlay" <?php if(isset($_GET['attachments']) && $_GET['attachments'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">مرفقات المصروفات</h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='expenses.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    $getid = $_GET['id'];
                                                    $queryatt = "SELECT * FROM incomes_expenses WHERE id='$getid'";
                                                    $resultatt = mysqli_query($conn, $queryatt);
                                                    $rowatt = mysqli_fetch_array($resultatt);
                                                    
                                                    if(isset($rowatt['attach_file1']) && $rowatt['attach_file1'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>المرفق 1 : </p>
                                                    <a href="<?php echo $rowatt['attach_file1'];?>" onClick="window.open('<?php echo $rowatt['attach_file1'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attach_file1']);?>
                                                    </a>
                                                    <?php if($row_permcheck['accexpenses_aperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='ieattachdel.php?id=<?php echo $rowatt['id'];?>&del=attach_file1&page=expenses.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                
                                                <?php }if(isset($rowatt['attach_file2']) && $rowatt['attach_file2'] !== ''){?>
                                                <div class="attachment-row">
                                                    <p>المرفق 2 : </p>
                                                    <a href="<?php echo $rowatt['attach_file2'];?>" onClick="window.open('<?php echo $rowatt['attach_file2'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attach_file2']);?>
                                                    </a>
                                                    <?php if($row_permcheck['accexpenses_aperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='ieattachdel.php?id=<?php echo $rowatt['id'];?>&del=attach_file2&page=expenses.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                
                                                <?php }if(isset($rowatt['attach_file3']) && $rowatt['attach_file3'] !== ''){?>
                                                <div class="attachment-row">
                                                    <p>المرفق 3 : </p>
                                                    <a href="<?php echo $rowatt['attach_file3'];?>" onClick="window.open('<?php echo $rowatt['attach_file3'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['attach_file3']);?>
                                                    </a>
                                                    <?php if($row_permcheck['accexpenses_aperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='ieattachdel.php?id=<?php echo $rowatt['id'];?>&del=attach_file3&page=expenses.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="ie_del.php" method="post">
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
                                        
                                        <tr style="height: 30px;">
                                            <td align="center">
                                                <div class="input-container">
                                                    <p class="input-parag" style="display: inline-block; width: fit-content">من تاريخ</p>
                                                    <input type="date" class="form-input" name="from_amount_date" style="width: fit-content; display:inline-block;">
                                                </div>
                                            </td>
                                            <td align="center">
                                                <div class="input-container">
                                                    <p class="input-parag">الى تاريخ</p>
                                                    <input type="date" class="form-input" name="to_amount_date">
                                                </div>
                                            </td>
                                            <td>
                                                <img style="margin-right: 20px" src="img/magnifying-glass.png" onclick="submit()" class="h-search-btn">
                                            </td>
                                            <td align="center">
                                                <p class="input-parag">البحث عن طريق تصنيف الخدمة</p>
                                                <input type="hidden" name="page" value="expenses.php">
                                                <select class="table-header-selector" name="SearchBYcat_id" onchange="submit()" style="padding: 5px 10px; text-align:">
                                                    <option value="" ></option>
                                                    <?php
                                                        $querysubcat2 = "SELECT * FROM sub_categories";
                                                        $resultsubcat2 = mysqli_query($conn, $querysubcat2);
                                                        if($resultsubcat2->num_rows > 0){
                                                            while($rowsubcat2 = mysqli_fetch_array($resultsubcat2)){
                                                                $main_category2 = $rowsubcat2['main_category'];
                                                                
                                                                $querymain2 = "SELECT * FROM categories WHERE id='$main_category2'";
                                                                $resultmain2 = mysqli_query($conn, $querymain2);
                                                                $rowmain2 = mysqli_fetch_array($resultmain2);
                                                                $cat_type2 = $rowmain2['cat_type'];
                                                                
                                                                if($cat_type2 === 'ايرادات'){
                                                                    continue;
                                                                }
                                                    ?>
                                                    <option value="<?php echo $rowsubcat2['id'];?>" <?php if($_GET['SearchBYcat_id'] === $rowsubcat2['id']){ echo 'selected'; }?>><?php echo $rowsubcat2['subcat_name'];?></option>
                                                    <?php }}?>
                                                </select>
                                            </td>
                                            <td align="center" colspan="3">
                                                إجمالى الإيرادات 
                                                ( <font color="#FF0000" style="font-size:20px">
                                                    <?php
                                                        $total_amounts = 0;
                                                        $querytamounts = "SELECT * FROM incomes_expenses WHERE ie_type='مصروفات'";
                                                        $resulttamounts = mysqli_query($conn, $querytamounts);
                                                        while($rowtamounts = mysqli_fetch_array($resulttamounts)){
                                                            $total_amounts = $total_amounts + $rowtamounts['amount'];
                                                        }
                                                        
                                                        $total_cheques = 0;
                                                        $querytcheques = "SELECT * FROM cheques WHERE ie_type='مصروفات'";
                                                        $resulttcheques = mysqli_query($conn, $querytcheques);
                                                        while($rowtcheques = mysqli_fetch_array($resulttcheques)){
                                                            $total_cheques = $total_cheques + $rowtcheques['cheque_value'];
                                                        }
                                                        
                                                        $total_income = $total_amounts + $total_cheques;
                                                        echo $total_income;
                                                    ?>
                                                </font> ) AED
                                            </td>
                                        </tr>
                                        
                                        <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                            <th width="50px"></th>
                                            <th style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="delall" id="selectAll"></th>
                                            <th>بيانات الموكل/  تصنيف الخدمة</th>
                                            <th>وذلك عن / طريقة الدفع</th>
                                            <th>الى حساب بنكي</th>
                                            <th>سند القبض</th>
                                            <th width="150px">مُدخل البيانات</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $where = '';
                                        if(isset($_GET['from_amount_date']) && $_GET['from_amount_date'] !== '' && isset($_GET['to_amount_date']) && $_GET['to_amount_date'] !== ''){
                                            $from = $_GET['from_amount_date'];
                                            $to = $_GET['to_amount_date'];
                                            
                                            $where = $where." AND amount_date BETWEEN '$from' AND '$to'";
                                        }
                                        if(isset($_GET['SearchBYcat_id']) && $_GET['SearchBYcat_id'] !== ''){
                                            $subcat_id = $_GET['SearchBYcat_id'];
                                            
                                            $where = $where." AND subcat_id='$subcat_id'";
                                        }
                                        
                                        $query = "SELECT * FROM incomes_expenses WHERE ie_type='مصروفات'$where";
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td class="options-td" style="background-color: #fff;" width="50px">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown">
                                                    <?php 
                                                        if($row_permcheck['accexpenses_dperm'] === '1'){
                                                    ?>
                                                    <button type="button" onclick="location.href='deleterevenue.php?id=<?php echo $row['id'];?>';">حذف</button>
                                                    <?php }?>
                                                    <button type="button" onclick="location.href='expenses.php?attachments=1&id=<?php echo $row['id'];?>';">المرفقات</button>
                                                </div>
                                            </td>
                                            <td style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id'];?>"></td>
                                            <td>
                                                <?php
                                                    $subcat_id = $row['subcat_id'];
                                                    $querysub = "SELECT * FROM sub_categories WHERE id='$subcat_id'";
                                                    $resultsub = mysqli_query($conn, $querysub);
                                                    $rowsub = mysqli_fetch_array($resultsub);
                                                    echo $rowsub['subcat_name'];
                                                    
                                                    if(isset($row['service']) && $row['service'] !== '' && $row['service'] !== '0'){
                                                        $service = $row['service'];
                                                        $queryserv = "SELECT * FROM services WHERE id='$service'";
                                                        $resultserv = mysqli_query($conn, $queryserv);
                                                        $rowserv = mysqli_fetch_array($resultserv);
                                                        echo ' / ' . $rowserv['name'];
                                                    }
                                                ?><br /><?php echo $row['recive_from'];?>
                                            </td>
                                            <td>
                                                <?php
                                                    if(isset($row['amount']) && $row['amount'] !== '' && $row['amount'] !== '0'){
                                                        echo 'نقدا';
                                                ?>
                                                (<font color=red><?php echo $row['amount'];?></font>)
                                                <?php
                                                    }
                                                    $ie_id1 = $row['id'];
                                                    $querycc = "SELECT COUNT(*) as countcheques FROM cheques WHERE ie_id='$ie_id1'";
                                                    $resultcc = mysqli_query($conn, $querycc);
                                                    if($resultcc->num_rows > 0){
                                                        $rowcc = mysqli_fetch_array($resultcc);
                                                        $ccount = $rowcc['countcheques'];
                                                ?>
                                                <font color="red"> 
                                                    <a href="#null" onclick="MM_openBrWindow('cheques.php?ie_id=<?php echo $row['id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=250')"><br>
                                                        شيك ( <?php echo $ccount;?> )
                                                    </a>
                                                </font>
                                                <?php
                                                    }
                                                ?>
                                                <div class="input-container">
                                                    <p class="input-parag"><?php echo $row['recive_reason'];?></p>
                                                </div>
                                            </td>
                                            <td>
                                                <?php
                                                    $bankid = $row['bank_accountid'];
                                                    $querybnk = "SELECT * FROM bank_accounts WHERE id='$bankid'";
                                                    $resultbnk = mysqli_query($conn, $querybnk);
                                                    $rowbnk = mysqli_fetch_array($resultbnk);
                                                    echo $rowbnk['name'] . ' - ' . $rowbnk['account_no'];
                                                ?>
                                            </td>
                                            <td>
                                                <img src="img/google-docs.png" width="30px" height="30px" border="0" style="cursor:pointer" onclick="MM_openBrWindow('ie_RP.php?id=<?php echo $row['id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=500')"/>
                                            </td>
                                            <td width="150px">
                                                ت.الاستلام 
                                                ( <?php echo $row['amount_date'];?> )
                                                <font style="font-size:10px; color: #666"><?php echo $row['timestamp'];?><br /></font>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <?php if($row_permcheck['accexpenses_dperm'] === '1'){?>
                                <input name="submit_del" type="submit" value="حذف" class="delete-selected" >
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