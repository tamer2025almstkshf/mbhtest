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
                    if($row_permcheck['csched_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">مواعيد الموكلين</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['csched_aperm'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['csched_aperm'] === '1' || $row_permcheck['csched_eperm'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات الطلب"; } else { echo 'طلب جديد'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='clients_schedule.php';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $equery = "SELECT * FROM clients_schedule WHERE id='$id'";
                                                $eresult = mysqli_query($conn, $equery);
                                                $erow = mysqli_fetch_array($eresult);
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'scheduledit.php'; } else{ echo 'addschedule.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <input type="hidden" name="page" value="clients_schedule.php">
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">الموكل<font style="color: #aa0820;">*</font></p>
                                                        <input id="searchInput" onkeyup="searchDatabase()" class="form-input" name="name" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['name']; }?>" type="text" required/>
                                                        <div id="dropdown-cname" class="dropdown-content" style="display: none;"></div>
                                                        
                                                        <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                                                        <input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
                                                        <?php }?>
                                                        
                                                        <script>
                                                            function searchDatabase() {
                                                                var input = document.getElementById('searchInput').value;
                                                                
                                                                if (input.length > 0) {
                                                                    var xhr = new XMLHttpRequest();
                                                                    xhr.onreadystatechange = function() {
                                                                        if (xhr.readyState === 4 && xhr.status === 200) {
                                                                            document.getElementById('dropdown-cname').innerHTML = xhr.responseText;
                                                                            document.getElementById('dropdown-cname').style.display = 'block';
                                                                        }
                                                                    };
                                                                    
                                                                    xhr.open('GET', 'search.php?q=' + encodeURIComponent(input), true);
                                                                    xhr.send();
                                                                } else {
                                                                    document.getElementById('dropdown-cname').style.display = 'none';
                                                                }
                                                            }
                                                            
                                                            function selectOption(value) {
                                                                document.getElementById('searchInput').value = value;
                                                                document.getElementById('dropdown-cname').style.display = 'none';
                                                            }
                                                        </script>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">هاتف الموكل<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="Cell_no" placeholder='009715xxxxxxxx' value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['tel']; }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تاريخ الموعد<font style="color: #aa0820;">*</font></p>
                                                        <input type="date" class="form-input" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['date']; }?>" name="date" rows="2" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">وقت الموعد<font style="color: #aa0820;">*</font></p>
                                                        <div>
                                                            <?php
                                                                $time = $erow['time'];
                                                                list($timeHH, $timeMM) = explode(":", $time);
                                                            ?>
                                                            <input type="number" min="0" max="59" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $timeMM; }?>" placeholder="MM" class="form-input" style="width: 50px;" name="timeMM" rows="2" required>
                                                            <input type="number" min="0" max="23" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $timeHH; }?>" placeholder="HH" class="form-input" style="width: 50px;" name="timeHH" rows="2" required>
                                                        </div>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الاجتماع مع</p>
                                                        <select class="table-header-selector" name="meet_with" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" dir="rtl">
                                                            <option></option>
                                                            <?php
                                                                if(isset($_GET['edit'])){
                                                                    $meet_with = $erow['meet_with'];
                                                                } else{
                                                                    $meet_with = '';
                                                                }
                                                                
                                                                $queryus = "SELECT * FROM user";
                                                                $resultus = mysqli_query($conn, $queryus);
                                                                while($rowus = mysqli_fetch_array($resultus)){
                                                            ?>
                                                            <option value="<?php echo $rowus['id'];?>" <?php if($meet_with === $rowus['id']){ echo 'selected'; }?>><?php echo $rowus['name'];?></option>
                                                            <?php
                                                                }
                                                            ?>
                                                        </select> 
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">التفاصيل</p>
                                                        <textarea class="form-input" name="details" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo $erow['details']; }?></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <h4 class="input-parag" style="padding-bottom: 10px;">محضر الاجتماع</h4>
                                                        <div class="drop-zone" id="dropZone1">
                                                            <input type="file" id="fileInput1" name="meeting" hidden>
                                                            <img src="img/cloud-computing.png" alt="Upload Icon" class="upload-icon">
                                                            <p>سحب وإفلات هنا<br>أو <span class="upload-text" onclick="document.getElementById('fileInput1').click()">إرفاق مستند</span></p>
                                                        </div>
                                                        <div id="fileList1"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $erow['meeting'] !== ''){ echo '<p>📄'.basename($erow['meeting']).'</p>'; }?></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['csched_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['csched_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['csched_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['csched_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['csched_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['csched_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['csched_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['csched_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='clients_schedule.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                                
                                <div class="modal-overlay" <?php if(isset($_GET['attachments']) && $_GET['attachments'] === '1'){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <?php
                                                $getid = $_GET['id'];
                                                $queryatt = "SELECT * FROM clients_schedule WHERE id='$getid'";
                                                $resultatt = mysqli_query($conn, $queryatt);
                                                $rowatt = mysqli_fetch_array($resultatt);
                                            ?>
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto">محضر اجتماع الموكل : <?php echo $rowatt['name'];?></h4>
                                                <div class="close-button-container">
                                                    <p class="close-button" onclick="location.href='clients_schedule.php';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php
                                                    if(isset($rowatt['meeting']) && $rowatt['meeting'] !== ''){
                                                ?>
                                                <div class="attachment-row">
                                                    <p>محضر الاجتماع : </p>
                                                    <a href="<?php echo $rowatt['meeting'];?>" onClick="window.open('<?php echo $rowatt['meeting'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                        <?php echo basename($rowatt['meeting']);?>
                                                    </a>
                                                    <?php if($row_permcheck['csched_eperm'] === '1'){?>
                                                    <div class="perms-check" onclick="location.href='csattachdel.php?id=<?php echo $rowatt['id'];?>&del=meeting';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                    <?php }?>
                                                </div>
                                                <?php
                                                    }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="addschedule.php" method="post">
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
                                            <th style="width: 15%">اسم الموكل</th>
                                            <th style="width: 15%">هاتف الموكل</th>
                                            <th style="width: 15%">تاريخ الموعد</th>
                                            <th style="width: 15%">وقت الموعد</th>
                                            <th style="width: 15%">التفاصيل</th>
                                            <th style="width: 15%">الاجتماع مع</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        $query = "SELECT * FROM clients_schedule ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td class="options-td" style="background-color: #fff;" width="50px">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown">
                                                    <?php if($row_permcheck['csched_eperm'] === '1'){?>
                                                    <button type="button" onclick="location.href='clients_schedule.php?edit=1&id=<?php echo $row['id'];?>';">تعديل</button>
                                                    <?php 
                                                        }
                                                        if($row_permcheck['csched_dperm'] === '1'){
                                                    ?>
                                                    <button type="button" onclick="location.href='deleteclientschedule.php?id=<?php echo $row['id'];?>';">حذف</button>
                                                    <?php }?>
                                                    <button type="button" onclick="location.href='clients_schedule.php?attachments=1&id=<?php echo $row['id'];?>';">المرفقات</button>
                                                </div>
                                            </td>
                                            <td style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id'];?>"></td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['name']) && $row['name'] !== ''){
                                                        echo $row['name'];
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['tel']) && $row['tel'] !== ''){
                                                        echo $row['tel'];
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['date']) && $row['date'] !== ''){
                                                        echo $row['date'];
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['time']) && $row['time'] !== ''){
                                                        echo $row['time'];
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['details']) && $row['details'] !== ''){
                                                        echo $row['details'];
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['meet_with']) && $row['meet_with'] !== ''){
                                                       $userid = $row['meet_with']; 
                                                       $queryusr = "SELECT * FROM user WHERE id='$userid'";
                                                       $resultusr = mysqli_query($conn, $queryusr);
                                                       $rowusr = mysqli_fetch_array($resultusr);
                                                       echo $rowusr['name'];
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <?php if($row_permcheck['csched_dperm'] === '1'){?>
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
        <script src="js/sweetAlerts2.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
    </body>
</html>