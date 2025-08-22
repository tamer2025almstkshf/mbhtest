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
                    if($row_permcheck['accfinance_rperm'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">قسم المالية</font></h3>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <div class="table-header-icons" style="margin-right: 30px;"></div>
                            </div>
                        </div>
                        <div class="table-body" id="printSection">
                            <table class="info-table" id="myTable" style="width: 100%;">
                                <thead>
                                    <tr class="infotable-search no-print">
                                        <td colspan="19">
                                            <div class="input-container">
                                                <p class="input-parag" style="display: inline-block">البحث : </p>
                                                <input class="form-input" style="display: inline-block; width: 50%;" type="text" id="SearchBox">
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <?php
                                        if(!isset($_GET['client']) || $_GET['client'] === ''){
                                            $query = "SELECT * FROM client WHERE arname!='' AND engname!='' AND tel1!='' ORDER BY id DESC";
                                            $result = mysqli_query($conn, $query);
                                            
                                            if($result->num_rows > 0){
                                    ?>
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th>Client's Name (E)</th>
                                        <th>Client's Name</th>
                                        <th>Client's Code</th>
                                    </tr>
                                </thead>
                                
                                <?php
                                    while($row = mysqli_fetch_array($result)){
                                ?>
                                <tbody id="table1">
                                    <tr class="infotable-body" style="cursor: pointer;" onclick="location.href='newTheme.php?client=<?php echo $row['id'];?>';">
                                        <td>
                                            <?php 
                                                if(isset($row['engname']) && $row['engname'] !== ''){ 
                                                    echo $row['engname'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($row['arname']) && $row['arname'] !== ''){ 
                                                    echo $row['arname'];
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(isset($row['id']) && $row['id'] !== ''){ 
                                                    echo $row['id'];
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <?php
                                                }
                                            }
                                        } else{
                                            $cid = $_GET['client'];
                                            $query = "SELECT * FROM file WHERE file_client='$cid' OR file_client2='$cid' OR file_client3='$cid' OR file_client4='$cid' OR file_client5='$cid' ORDER BY file_id DESC";
                                            $result = mysqli_query($conn, $query);
                                            
                                            if($result->num_rows > 0){
                                ?>
                                    <tr class="infotable-header" style="position: sticky; top: 0; z-index: 3;">
                                        <th>After The Judgement</th>
                                        <th>Dates</th>
                                        <th>Upon Signing The Agreement</th>
                                        <th>Professional Fees</th>
                                        <th>Agreement Date</th>
                                        <th>Terms</th>
                                        <th>File Number</th>
                                    </tr>
                                </thead>
                                
                                <form method="POST" action="finedit.php" id="editableForm">
                                    <input type="text" name="changed" id="changedField" value="">
                                    <input type="text" name="newValue" id="newValueField" value="">
                                    <input type="hidden" name="scrollPosition" id="scrollPosition" value="">
                                    <input type="hidden" name="cid" value="<?php echo $_GET['client'];?>">
                                    <?php
                                        while($row = mysqli_fetch_array($result)){
                                            $fid = $row['file_id'];
                                            $query2 = "SELECT * FROM finance WHERE fid='$fid'";
                                            $result2 = mysqli_query($conn, $query2);
                                            $row2 = mysqli_fetch_array($result2);
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td>
                                                <?php
                                                    if(isset($fid) && $fid !== ''){ 
                                                        echo $fid;
                                                    }
                                                ?>
                                            </td>
                                            <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="terms" data-id="<?php echo $row2['id']; ?>" onkeydown="handleEnter(event, this)">
                                                <?php
                                                    if(isset($row2['terms']) && $row2['terms'] !== ''){ 
                                                        echo $row2['terms'];
                                                    }
                                                ?>
                                            </td>
                                            <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="agreement_date" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                <?php
                                                    if(isset($row2['agreement_date']) && $row2['agreement_date'] !== ''){ 
                                                        echo $row2['agreement_date'];
                                                    }
                                                ?>
                                            </td>
                                            <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="professional_fees" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                <?php
                                                    if(isset($row2['professional_fees']) && $row2['professional_fees'] !== ''){ 
                                                        echo $row2['professional_fees'];
                                                    }
                                                ?>
                                            </td>
                                            <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="signing_agreement" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                <?php
                                                    if(isset($row2['signing_agreement']) && $row2['signing_agreement'] !== ''){ 
                                                        echo $row2['signing_agreement'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    $dates = '';
                                                    
                                                    if(isset($row2['dates_JAN']) && $row2['dates_JAN'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_JAN'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_JAN'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_FEB']) && $row2['dates_FEB'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_FEB'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_FEB'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_MAR']) && $row2['dates_MAR'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_MAR'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_MAR'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_APR']) && $row2['dates_APR'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_APR'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_APR'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_MAY']) && $row2['dates_MAY'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_MAY'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_MAY'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_JUN']) && $row2['dates_JUN'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_JUN'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_JUN'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_JUL']) && $row2['dates_JUL'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_JUL'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_JUL'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_AUG']) && $row2['dates_AUG'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_AUG'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_AUG'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_SEP']) && $row2['dates_SEP'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_SEP'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_SEP'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_OCT']) && $row2['dates_OCT'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_OCT'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_OCT'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_NOV']) && $row2['dates_NOV'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_NOV'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_NOV'];
                                                        }
                                                    }
                                                    if(isset($row2['dates_DEC']) && $row2['dates_DEC'] !== ''){
                                                        if($dates === ''){
                                                            $dates = $row2['dates_DEC'];
                                                        } else{
                                                            $dates = $dates.'<br>'.$row2['dates_DEC'];
                                                        }
                                                    }
                                                    
                                                    echo $dates;
                                                ?>
                                            </td>
                                            <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="judge_after" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                <?php
                                                    if(isset($row2['judge_after']) && $row2['judge_after'] !== ''){ 
                                                        echo $row2['judge_after'];
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }} else{?>
                                    <font color="red">لا يوجد اي ملفات باسم هذا الموكل</font>
                                    <?php }}}?>
                                </form>
                            </table>
                        </div>
                        
                        <div class="table-footer">
                            <p></p>
                            <div id="pagination"></div>
                            <div id="pageInfo"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/newWindow.js"></script>
        <script src="js/translate.js"></script>
        <script src="js/toggleSection.js"></script>
        <script src="js/dropfiles.js"></script>
        <script src="js/popups.js"></script>
        <script src="js/randomPassGenerator.js"></script>
        <script src="js/sweetAlerts.js"></script>
        <script src="js/tablePages.js"></script>
        <script src="js/checkAll.js"></script>
        <script src="js/dropdown.js"></script>
        <script src="js/printDiv.js"></script>
        <script src="js/printRow.js"></script>
        <script>
            function handleEnter(event, element) {
                if (event.key === "Enter") {
                    event.preventDefault();
                    
                    const form = document.getElementById("editableForm");
                    const changedField = document.getElementById("changedField");
                    const newValueField = document.getElementById("newValueField");
                    const rowIdField = document.getElementById("rowIdField");
                    const scrollPosition = document.getElementById("scrollPosition");
                    
                    changedField.value = element.getAttribute("data-field");
                    newValueField.value = element.textContent.trim();
                    rowIdField.value = element.getAttribute("data-id");
                    
                    scrollPosition.value = window.scrollY;
                    form.submit();
                }
            }
            
            window.onload = function () {
                const scrollPosition = new URLSearchParams(window.location.search).get('scroll');
                if (scrollPosition) {
                    window.scrollTo(0, parseInt(scrollPosition, 10));
                }
            };
        </script>
    </body>
</html>