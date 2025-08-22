<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'golden_pass.php';
    include_once 'AES256.php';
    
    $stmtbranch = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmtbranch->bind_param("i", $_SESSION['id']);
    $stmtbranch->execute();
    $resultbranch = $stmtbranch->get_result();
    $rowbranch = $resultbranch->fetch_assoc();
    $stmtbranch->close();
    $branch = $rowbranch['work_place'];
    if($admin != 1){
        if(!isset($_GET['branch']) || $_GET['branch'] !== $branch){
            header("Location: consultations.php?branch=$branch");
            exit();
        }
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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/exceljs/dist/exceljs.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
        <link href="css/styles.css" rel="stylesheet">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
    </head>
    <body style="overflow: auto">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['call_rperm'] == 1){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <form action="ctypechange.php" method="post" enctype="multipart/form-data">
                                    <h3 style="display: inline-block"><font id="clients-translate">سجل المكالمات الهاتفية</font></h3>
                                    <?php if($admin == 1){?>
                                    <select class="table-header-selector" name="branch" onchange="submit()" style="padding: 0 5px;">
                                        <option value="select">اختر الفرع</option>
                                        <option value="Dubai" <?php if($_GET['branch'] === 'Dubai'){ echo 'selected'; }?>><font id="clients-translate">Dubai</font></option>
                                        <option value="Sharjah" <?php if($_GET['branch'] === 'Sharjah'){ echo 'selected'; }?>><font id="opponents-translate">Sharjah</font></option>
                                        <option value="Ajman" <?php if($_GET['branch'] === 'Ajman'){ echo 'selected'; }?>><font id="subs-translate">Ajman</font></option>
                                        <?php
                                            $stmtbranchs = $conn->prepare("SELECT * FROM branchs");
                                            $stmtbranchs->execute();
                                            $resultbranchs = $stmtbranchs->get_result();
                                            if($resultbranchs->num_rows > 0){
                                                while($rowbranchs = $resultbranchs->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo safe_output($rowbranchs['branch']);?>"<?php if($_GET['branch'] === $rowbranchs['branch']){ echo 'selected'; };?>><?php echo safe_output($rowbranchs['branch']);?></option>
                                        <?php
                                                }
                                            }
                                            $stmtbranchs->close();
                                        ?>
                                    </select>
                                    <?php if($row_permcheck['selectors_rperm'] == 1){?>
                                    <img src="img/add-button.png" width="20px" height="20px" title="اضافة" onclick="MM_openBrWindow('selector/Branchs.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=600,height=400')" align="absmiddle" style="cursor:pointer"/>
                                    <?php }}?>
                                </form>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"><?php if($row_permcheck['call_rperm'] == 1){?><img src="img/xlsx.png" width="25px" height="25px" onclick="exportToExcel()"><?php }?></div>
                                <?php
                                    if($row_permcheck['call_aperm'] == 1){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['call_aperm'] == 1 || $row_permcheck['call_eperm'] == 1){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات المكالمة"; } else { echo 'مكالمة جديدة'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ $branch = $_GET['branch']; echo "location.href='clientsCalls.php?branch=$branch';"; } else{ echo 'addclient()'; }?>">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $estmt = $conn->prepare("SELECT * FROM clientsCalls WHERE id=?");
                                                $estmt->bind_param("i", $id);
                                                $estmt->execute();
                                                $eresult = $estmt->get_result();
                                                $erow = $eresult->fetch_assoc();
                                                $estmt->close();
                                            }
                                        ?>
                                        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'calledit.php'; } else{ echo 'calladd.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <input type="hidden" value="<?php echo safe_output($_SERVER['QUERY_STRING']);?>" name="queryString">
                                            <input type="hidden" value="<?php echo safe_output($_GET['branch']);?>" name="branch">
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم المتصل<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="cname" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['caller_name']); }?>" type="text" required>
                                                        <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?>
                                                        <input type="hidden" name="id" value="<?php echo safe_output($_GET['id']);?>">
                                                        <?php }?>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">رقم المتصل<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" name="cno" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['caller_no']); }?>" type="text" required>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تفاصيل المكالمة</p>
                                                        <textarea class="form-input" name="details" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['details']); }?></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">الاجراء</p>
                                                        <textarea class="form-input" name="action" rows="2"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo safe_output($erow['action']); }?></textarea>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">تم تحويل المكالمة الى</p>
                                                        <select class="table-header-selector" name="moved_to" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" dir="rtl">
                                                            <option></option>
                                                            <?php
                                                                if(isset($_GET['edit'])){
                                                                    $moved_to = $erow['moved_to'];
                                                                } else{
                                                                    $moved_to = '';
                                                                }
                                                                
                                                                $stmtus = $conn->prepare("SELECT * FROM user");
                                                                $stmtus->execute();
                                                                $resultus = $stmtus->get_result();
                                                                if($resultus->num_rows > 0){
                                                                    while($rowus = $resultus->fetch_assoc()){
                                                            ?>
                                                            <option value="<?php echo safe_output($rowus['id']);?>" <?php if($moved_to === $rowus['id']){ echo 'selected'; }?>><?php echo safe_output($rowus['name']);?></option>
                                                            <?php
                                                                    }
                                                                }
                                                                $stmtus->close();
                                                            ?>
                                                        </select> 
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['call_eperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['call_aperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['call_eperm'] == 1){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['call_aperm'] == 1){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['call_eperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['call_aperm'] == 1){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['call_eperm'] == 1){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['call_aperm'] == 1){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='clientsCalls.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body">
                            <form action="calldel.php" method="post">
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
                                            <th style="width: 15%">اسم المتصل</th>
                                            <th style="width: 15%;">رقم المتصل</th>
                                            <th style="width: 15%">تفاصيل المكالمة</th>
                                            <th style="width: 15%">الاجراء</th>
                                            <th style="width: 15%">تم تحويل المكالمة الى</th>
                                            <th width="150px">مُدخل البيانات</th>
                                            <th width="50px">الاجراءات</th>
                                        </tr>
                                    </thead>
                                    
                                    <?php
                                        if(isset($_GET['branch']) && $_GET['branch'] !== ''){
                                            $branch = $_GET['branch'];
                                            $stmt = $conn->prepare("SELECT * FROM clientsCalls WHERE branch=? ORDER BY id DESC");
                                            $stmt->bind_param("s", $branch);
                                        } else{
                                            $stmt = $conn->prepare("SELECT * FROM clientsCalls ORDER BY id DESC");
                                        }
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        if($result->num_rows > 0){
                                            while($row = $result->fetch_assoc()){
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['caller_name']) && $row['caller_name'] !== ''){
                                                        echo safe_output($row['caller_name']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%;">
                                                <?php 
                                                    if(isset($row['caller_no']) && $row['caller_no'] !== ''){
                                                        echo safe_output($row['caller_no']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['details']) && $row['details'] !== ''){
                                                        echo safe_output($row['details']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['action']) && $row['action'] !== ''){
                                                        echo safe_output($row['action']);
                                                    }
                                                ?>
                                            </td>
                                            <td style="width: 15%">
                                                <?php 
                                                    if(isset($row['moved_to']) && $row['moved_to'] !== ''){
                                                       $userid = $row['moved_to']; 
                                                       $stmtusr = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                       $stmtusr->bind_param("i", $userid);
                                                       $stmtusr->execute();
                                                       $resultusr = $stmtusr->get_result();
                                                       $rowusr = $resultusr->fetch_assoc();
                                                       $stmtusr->close();
                                                       echo safe_output($rowusr['name']);
                                                    }
                                                ?>
                                            </td>
                                            <td width="150px">
                                                <?php 
                                                    if(isset($row['timestamp']) && $row['timestamp'] !== ''){
                                                        $tmid = $row['timestamp'];
                                                        list($myid, $date) = explode(" <br> ", $tmid);
                                                        $stmtme = $conn->prepare("SELECT * FROM user WHERE id=?");
                                                        $stmtme->bind_param("i", $myid);
                                                        $stmtme->execute();
                                                        $resultme = $stmtme->get_result();
                                                        $rowme = $resultme->fetch_assoc();
                                                        $stmtme->close();
                                                        $myname = $rowme['name'];
                                                        echo safe_output($myname) . '<br>' . safe_output($date);
                                                    }
                                                ?>
                                            </td>
                                            <td style="align-content: center;">
                                                <?php if($row_permcheck['call_eperm'] == 1){?>
                                                <img src="img/edit.png" style="cursor: pointer;" title="تعديل" height="20px" width="20px" onclick="location.href='clientsCalls.php?edit=1&id=<?php echo safe_output($row['id']);?>&branch=<?php echo safe_output($_GET['branch']);?>';">
                                                <?php } if($row_permcheck['call_dperm'] == 1){?>
                                                <img src="img/recycle-bin.png" style="cursor: pointer;" title="حذف" height="20px" width="20px" onclick="location.href='deletecall.php?id=<?php echo safe_output($row['id']);?>&branch=<?php echo safe_output($_GET['branch']);?>';">
                                                <?php }?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php 
                                            }
                                        }
                                        $stmt->close();
                                    ?>
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
        <script>
            async function exportToExcel() {
                const response = await fetch("callsxlsx.php?branch=<?php echo safe_output($_GET['branch']);?>");
                const data = await response.json();
                
                if (data.error) {
                    alert("Error: " + data.error);
                    return;
                }
                
                const workbook = new ExcelJS.Workbook();
                const sheet = workbook.addWorksheet("MBHCalls");
                
                sheet.columns = [
                    { 
                        header: "Date", 
                        key: "date", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Employee Name", 
                        key: "employee_name", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Contact Name", 
                        key: "contact_name", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Contact Number", 
                        key: "contact_no", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Details", 
                        key: "details", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Call Forwarded To", 
                        key: "forwarded_to", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    },
                    { 
                        header: "Status", 
                        key: "status", 
                        width: 40,
                        style: { alignment: { wrapText: true, } } 
                    }
                ];
                
                const headerRow = sheet.getRow(1);
                headerRow.font = { color: { argb: "FFFFFFFF" }, bold: true };
                headerRow.fill = {
                    type: "pattern",
                    pattern: "solid",
                    fgColor: { argb: "FF006400" }
                };
                
                data.forEach((rowData, index) => {
                    const row = sheet.addRow(rowData);
                    
                    row.font = { color: { argb: "FF000000" } };
                    row.alignment = { wrapText: true, vertical: 'top' };
                    row.fill = {
                        type: "pattern",
                        pattern: "solid",
                        fgColor: { argb: index % 2 === 0 ? "FF90EE90" : "FFFFFFFF" }
                    };
                    
                    const longestText = Math.max(
                    ...Object.values(rowData).map(val => (val ? val.toString().length : 0))
                    );
                    
                    row.height = Math.ceil(longestText / 50) * 20;
                });
                
                const buffer = await workbook.xlsx.writeBuffer();
                const blob = new Blob([buffer], { type: "application/octet-stream" });
                const link = document.createElement("a");
                link.href = URL.createObjectURL(blob);
                link.download = "MBHCalls.xlsx";
                link.click();
            }
        </script>
    </body>
</html>