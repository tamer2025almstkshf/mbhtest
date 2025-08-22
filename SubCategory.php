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
                    if($row_permcheck['accsecterms_rperm'] === '1'){
                ?>
                
                <div class="web-page">
                    <div class="table-container">
                        <div class="table-header">
                            <div class="table-header-right">
                                <h3 style="display: inline-block"><font id="clients-translate">البنود الفرعية</font></h3>
                                <select class="table-header-selector" name="cattype2" id="cattype2" onChange="cattype()" type="text">        
                                    <option value="0"></option>
                                    <option value="1" <?php if($_GET['scattype'] === '1'){ echo 'selected'; }?>>مصروفات</option>
                                    <option value="2" <?php if($_GET['scattype'] === '2'){ echo 'selected'; }?>>إيرادات</option>
                                </select>
                                
                                <select class="table-header-selector" name="maincat2" id="maincat2" type="text" onChange="maincat()">
                                    <option value="0"></option>
                                    <?php
                                        if(isset($_GET['scattype']) && $_GET['scattype'] === '1'){
                                            $queryct = "SELECT * FROM categories WHERE cat_type='مصروفات'";
                                            $resultct = mysqli_query($conn, $queryct);
                                            while($rowct = mysqli_fetch_array($resultct)){
                                    ?>
                                    <option value="<?php echo $rowct['id'];?>" <?php if($_GET['smaincat'] === $rowct['id']){ echo 'selected'; }?>><?php echo $rowct['cat_name'];?></option>
                                    <?php
                                            }
                                        } else if(isset($_GET['scattype']) && $_GET['scattype'] === '2'){
                                            $queryct = "SELECT * FROM categories WHERE cat_type='ايرادات'";
                                            $resultct = mysqli_query($conn, $queryct);
                                            while($rowct = mysqli_fetch_array($resultct)){
                                    ?>
                                    <option value="<?php echo $rowct['id'];?>" <?php if($_GET['smaincat'] === $rowct['id']){ echo 'selected'; }?>><?php echo $rowct['cat_name'];?></option>
                                    <?php
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <div class="table-header-left">
                                <div class="table-header-icons" style="margin-right: 10px;"></div>
                                <div class="table-header-icons" style="margin-right: 20px;"></div>
                                <?php
                                    if($row_permcheck['accsecterms_aperm'] === '1'){
                                ?>
                                <div class="table-header-icons" style="background-image: url('img/arrow.png'); margin-right: 30px;" onclick="addclient()"></div>
                                <?php
                                    }
                                    if($row_permcheck['accsecterms_aperm'] === '1' || $row_permcheck['accsecterms_eperm'] === '1'){
                                ?>
                                <div id="addclient-btn" class="modal-overlay" <?php if((isset($_GET['addmore']) && $_GET['addmore'] === '1') || (isset($_GET['edit']) && $_GET['edit'] === '1')){ echo 'style="display: block;"'; }?>>
                                    <div class="modal-content">
                                        <div class="addc-header">
                                            <h4 class="addc-header-parag" style="margin: auto"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "تعديل بيانات البند الفرعي"; } else { 'بند فرعي جديد'; }?></h4>
                                            <div class="close-button-container">
                                                <p class="close-button" onclick="location.href='SubCategory.php';">&times;</p>
                                            </div>
                                        </div>
                                        <?php
                                            if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                                                $id = $_GET['id'];
                                                $equery = "SELECT * FROM sub_categories WHERE id='$id'";
                                                $eresult = mysqli_query($conn, $equery);
                                                $erow = mysqli_fetch_array($eresult);
                                            }
                                        ?>
                                        <form name="addform" action="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'subcatedit.php'; } else{ echo 'addsubcat.php'; }?>" method="post" enctype="multipart/form-data" >
                                            <?php
                                                if(isset($_GET['id']) && $_GET['id'] !== ''){
                                            ?>
                                            <input type='hidden' name='id' value='<?php echo $_GET['id'];?>'>
                                            <?php
                                                }
                                            ?>
                                            <div class="addc-body">
                                                <div class="addc-body-form">
                                                    <div class="input-container">
                                                        <p class="input-parag">
                                                            البند الرئيسي
                                                            <font style="color: #aa0820;">*</font>
                                                            <?php if($row_permcheck['admjobtypes_rperm'] === '1'){?> 
                                                            <img src="img/add-button.png" align="absmiddle" width="25px" height="25px" title="اضافة" onclick="addnew()" style="cursor:pointer"/>
                                                            <?php }?>
                                                        </p>
                                                        
                                                        <?php if(!isset($_GET['edit']) || $_GET['edit'] !== '1'){?>
                                                        <select class="table-header-selector" name="cat_type" id="cattype" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" onChange="cattype2()">        
                                                            <option value="0"></option>
                                                            <option value="1" <?php if($_GET['cattype'] === '1'){ echo 'selected'; }?>>مصروفات</option>
                                                            <option value="2" <?php if($_GET['cattype'] === '2'){ echo 'selected'; }?>>إيرادات</option>
                                                        </select>
                                                        <?php }?>
                                                        
                                                        <select class="table-header-selector" name="main_category" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" required>
                                                            <option value=""></option>
                                                            <?php
                                                                if(isset($_GET['cattype']) && $_GET['cattype'] === '1'){
                                                                    $queryct = "SELECT * FROM categories WHERE cat_type='مصروفات'";
                                                                    $resultct = mysqli_query($conn, $queryct);
                                                                    while($rowct = mysqli_fetch_array($resultct)){
                                                            ?>
                                                            <option value="<?php echo $rowct['id'];?>" <?php if(isset($erow['main_category']) && $erow['main_category'] === $rowct['id']){ echo 'selected'; }?>><?php echo $rowct['cat_name'];?></option>
                                                            <?php
                                                                    }
                                                                } else if(isset($_GET['cattype']) && $_GET['cattype'] === '2'){
                                                                    $queryct = "SELECT * FROM categories WHERE cat_type='ايرادات'";
                                                                    $resultct = mysqli_query($conn, $queryct);
                                                                    while($rowct = mysqli_fetch_array($resultct)){
                                                            ?>
                                                            <option value="<?php echo $rowct['id'];?>" <?php if(isset($erow['main_category']) && $erow['main_category'] === $rowct['id']){ echo 'selected'; }?>><?php echo $rowct['cat_name'];?></option>
                                                            <?php
                                                                    }
                                                                }
                                                            ?>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم البند الفرعي<font style="color: #aa0820;">*</font></p>
                                                        <input class="form-input" type="text" name="subcat_name" dir="rtl" value="<?php if(isset($erow['subcat_name']) && $erow['subcat_name'] !== ''){ echo $erow['subcat_name']; }?>" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="addc-footer">
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accsecterms_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['accsecterms_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accsecterms_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['accsecterms_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                class="form-btn submit-btn">حفظ</button>
                                                <button 
                                                style="cursor: <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accsecterms_eperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }} else{ if($row_permcheck['accsecterms_aperm'] === '1'){ echo 'pointer'; } else{ echo 'not-allowed'; }}?>" 
                                                type="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ if($row_permcheck['accsecterms_eperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }} else{ if($row_permcheck['accsecterms_aperm'] === '1'){ echo 'submit'; } else{ echo 'button'; }}?>" 
                                                name="submit_back" value="addmore" class="form-btn cancel-btn"><?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "حفظ و انشاء جديد"; } else{ echo 'حفظ و انشاء آخر'; }?></button>
                                                <button type="button" class="form-btn cancel-btn" onclick="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo "location.href='SubCategory.php';"; } else{ echo 'addclient()'; }?>">الغاء</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <?php
                                        }
                                    }
                                ?>
                                <?php 
                                    if($row_permcheck['accmainterms_rperm'] === '1'){
                                ?>
                                <div id="addnew-btn" class="modal-overlay" style="<?php if($_GET['addplus'] === '1' || isset($_GET['mtid'])){?>display: block; <?php }?>">
                                    <div class="modal-content" style="margin: auto; align-content: center">
                                        <div class="notes-displayer">
                                            <div class="addc-header">
                                                <h4 class="addc-header-parag" style="margin: auto"><?php if(!isset($_GET['mtid'])){ echo 'اضافة بند رئيسي'; } else{ echo 'تعديل البند الرئيسي'; }?></h4>
                                                <div class="close-button-container">
                                                    <?php
                                                        $queryString = $_SERVER['QUERY_STRING'];
                                                        $idquery = $rowjn['id'];
                                                        if($queryString !== ''){
                                                            if(isset($_GET['mtid'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['mtid']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            if(isset($_GET['addplus'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['addplus']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            if(isset($_GET['savedmttype'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['savedmttype']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            if(isset($_GET['editedmttype'])){
                                                                parse_str($queryString, $queryParams);
                                                                unset($queryParams['editedmttype']);
                                                                $queryString = http_build_query($queryParams);
                                                            }
                                                            $queryString = "?$queryString";
                                                        } else{
                                                            $queryString = "";
                                                        }
                                                    ?>
                                                    <p class="close-button" onclick="location.href='SubCategory.php<?php echo $queryString;?>';" style="display: inline-block">&times;</p>
                                                </div>
                                            </div>
                                            <div class="notes-body" style="padding: 10px; text-align: right;">
                                                <?php if($row_permcheck['accmainterms_aperm'] === '1'){?>
                                                <form action="<?php if(isset($_GET['mtid']) && $_GET['mtid']){ echo 'mainedit.php'; } else{ echo 'mainadd.php'; }?>" method="post" enctype="multipart/form-data">    
                                                    <?php
                                                        if(isset($_GET['mtid']) && $_GET['mtid']){
                                                    ?>
                                                    <input type="hidden" name="mtid" value="<?php echo $_GET['mtid'];?>">
                                                    <?php
                                                            $mtid = $_GET['mtid'];
                                                            
                                                            $querymtid = "SELECT * FROM categories WHERE id='$mtid'";
                                                            $resultmtid = mysqli_query($conn, $querymtid);
                                                            $rowmtid = mysqli_fetch_array($resultmtid);
                                                        }
                                                    ?>
                                                    <div class="input-container">
                                                        <p class="input-parag">نوع البند الرئيسي</p>
                                                        <select class="table-header-selector" name="cat_type" type="text" style="width: 100%; padding: 10px 0; margin: 10px 0; padding: 5px;" required>        
                                                            <option value=""></option>
                                                            <option value="مصروفات" <?php if($_GET['cate_type'] === 'مصروفات'){ echo 'selected'; } else{ if($rowmtid['cat_type'] === 'مصروفات'){ echo 'selected'; }}?>>مصروفات</option>
                                                            <option value="ايرادات" <?php if($_GET['cate_type'] === 'ايرادات'){ echo 'selected'; } else{ if($rowmtid['cat_type'] === 'ايرادات'){ echo 'selected'; }}?>>ايرادات</option>
                                                        </select>
                                                    </div>
                                                    <div class="input-container">
                                                        <p class="input-parag">اسم البند الرئيسي</p>
                                                        <input type="text" class="form-input" name="cat_name" value="<?php echo $rowmtid['cat_name'];?>" required>
                                                        <?php if(isset($_GET['mtid']) && $_GET['mtid'] !== ''){?>
                                                        <input type="hidden" class="form-input" name="mtid" value="<?php echo $rowmtid['id'];?>">
                                                        <?php }?>
                                                        <input type="hidden" name="queryString" value="<?php echo $_SERVER['QUERY_STRING'];?>">
                                                    </div>
                                                    <div class="input-container">
                                                        <button style="cursor: pointer;" type="submit" class="form-btn submit-btn">حفظ</button>
                                                    </div>
                                                </form>
                                                <?php }?>
                                                <div class="table-body" style="overflow: visible;">
                                                    <table class="info-table" style="width: 100%;">
                                                        <?php
                                                            if($row_permcheck['accmainterms_rperm'] === '1'){
                                                        ?>
                                                        <tr class="infotable-header">
                                                            <th width="50px"></th>
                                                            <th width="100px">نوع البند الرئيسي</th>
                                                            <th>اسم البند الرئيسي</th>
                                                        </tr>
                                                        
                                                        <?php
                                                            $querycats = "SELECT * FROM categories ORDER BY id DESC";
                                                            $resultcats = mysqli_query($conn, $querycats);
                                                            
                                                            if($resultcats->num_rows > 0){
                                                                while($rowjn = mysqli_fetch_array($resultcats)){
                                                        ?>
                                                        <tr class="infotable-body">
                                                            <td class="options-td" style="background-color: #fff;" width="50px">
                                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                                <div class="dropdown">
                                                                    <?php 
                                                                        if($row_permcheck['accmainterms_eperm'] === '1'){
                                                                            $queryString = $_SERVER['QUERY_STRING'];
                                                                            if (strpos($queryString, 'addmore') !== false) {
                                                                                parse_str($queryString, $queryParams);
                                                                                unset($queryParams['editedmttype']);
                                                                                $queryString = http_build_query($queryParams);
                                                                            }
                                                                            $idquery = $rowjn['id'];
                                                                            if($queryString !== ''){
                                                                                if(isset($_GET['mtid'])){
                                                                                    parse_str($queryString, $queryParams);
                                                                                    unset($queryParams['mtid']);
                                                                                    $queryString = http_build_query($queryParams);
                                                                                }
                                                                                if (strpos($queryString, 'savedmttype') !== false) {
                                                                                    parse_str($queryString, $queryParams);
                                                                                    unset($queryParams['savedmttype']);
                                                                                    $queryString = http_build_query($queryParams);
                                                                                }
                                                                                if (strpos($queryString, 'editedmttype') !== false) {
                                                                                    parse_str($queryString, $queryParams);
                                                                                    unset($queryParams['editedmttype']);
                                                                                    $queryString = http_build_query($queryParams);
                                                                                }
                                                                                $queryString = "?$queryString&mtid=$idquery";
                                                                            } else{
                                                                                $queryString = "?mtid=$idquery";
                                                                            }
                                                                    ?>
                                                                    <button type="button" onclick="location.href='SubCategory.php<?php echo $queryString;?>';" style="text-align: right">تعديل</button>
                                                                    <?php 
                                                                        }
                                                                        if($row_permcheck['accmainterms_dperm'] === '1'){
                                                                    ?>
                                                                    <button type="button" onclick="location.href='maindel.php?id=<?php echo $rowjn['id'];?>&<?php echo $_SERVER['QUERY_STRING']?>';" style="text-align: right">حذف</button>
                                                                    <?php }?>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <?php echo $rowjn['cat_type'];?>
                                                            </td>
                                                            <td>
                                                                <?php echo $rowjn['cat_name'];?>
                                                            </td>
                                                        </tr>
                                                        <?php }}}?>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>
                        </div>
                        <div class="table-body" id="printSection">
                            <form action="subcatdel.php" method="post">
                                <table class="info-table" id="myTable" style="width: 100%;">
                                    <?php
                                        if($row_permcheck['accsecterms_rperm'] === '1'){
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
                                            <th width="50px" class="no-print"></th>
                                            <th class="no-print" style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="delall" id="selectAll"></th>
                                            <th width="50px">#</th>
                                            <th>البند الفرعي</th>
                                            <th>اسم البند الفرعي</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        $query = "SELECT * FROM sub_categories ORDER BY id DESC";
                                        if(isset($_GET['scattype']) && $_GET['scattype'] !== ''){
                                            if($_GET['scattype'] === '1'){
                                                $scattypecheck = 'مصروفات';
                                            } else if($_GET['scattype'] === '2'){
                                                $scattypecheck = 'ايرادات';
                                            }
                                        }
                                        if(isset($_GET['smaincat']) && $_GET['smaincat'] !== ''){
                                            $maincat = $_GET['smaincat'];
                                            $query = "SELECT * FROM sub_categories WHERE main_category='$maincat' ORDER BY id DESC";
                                        }
                                        
                                        $result = mysqli_query($conn, $query);
                                        if($result->num_rows > 0){
                                            while($row = mysqli_fetch_array($result)){
                                                $mainid = $row['main_category'];
                                                $querycheck = "SELECT * FROM categories WHERE id='$mainid'";
                                                $resultcheck = mysqli_query($conn, $querycheck);
                                                $rowcheck = mysqli_fetch_array($resultcheck);
                                                if(isset($scattypecheck)){
                                                    if($rowcheck['cat_type'] !== $scattypecheck){
                                                        continue;
                                                    }
                                                }
                                    ?>
                                    <tbody id="table1">
                                        <tr class="infotable-body">
                                            <td class="options-td no-print" style="background-color: #fff;" width="50px">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown" id="actions-btns-<?php echo $row['id'];?>">
                                                    <?php
                                                        $idmaincat = $row['main_category'];
                                                        $mquery = "SELECT * FROM categories WHERE id='$idmaincat'";
                                                        $mresult = mysqli_query($conn, $mquery);
                                                        $mrow = mysqli_fetch_array($mresult);
                                                        
                                                        if($mrow['cat_type'] === 'مصروفات'){
                                                            $cattype = '1';
                                                        } else{
                                                            $cattype = '2';
                                                        }
                                                        if($row_permcheck['accsecterms_eperm'] === '1'){ 
                                                    ?>
                                                    <button type="button" onclick="location.href='SubCategory.php?edit=1&id=<?php echo $row['id'];?>&cattype=<?php echo $cattype;?>';" style="text-align: right">تعديل</button>
                                                    <?php 
                                                        }
                                                        if($row_permcheck['accsecterms_dperm'] === '1'){
                                                    ?>
                                                    <button type="button" onclick="location.href='deletesubcat.php?id=<?php echo $row['id'];?>';" style="text-align: right">حذف</button>
                                                    <?php }?>
                                                </div>
                                            </td>
                                            <td class="no-print" style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['id'];?>"></td>
                                            <td>
                                                <?php 
                                                    if(isset($row['id']) && $row['id'] !== ''){ 
                                                        echo $row['id'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['subcat_name']) && $row['subcat_name'] !== ''){ 
                                                        echo $row['subcat_name'];
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <font color="<?php if($rowcheck['cat_type'] === 'ايرادات'){?>#006600<?php } else{?>#FF0000<?php }?>">
                                                    <?php 
                                                        echo $rowcheck['cat_type'];
                                                    ?>
                                                </font> / 
                                                <?php 
                                                    echo $rowcheck['cat_name'];
                                                ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php }}}?>
                                </table>
                            </div>
                            
                            <div class="table-footer">
                                <?php if($row_permcheck['accsecterms_dperm'] === '1'){?>
                                <input name="button2" type="submit" value="حذف" class="delete-selected" >
                                <?php } else{ echo '<p></p>'; }?>
                                <div id="pagination"></div>
                                <div id="pageInfo"></div>
                            </form>
                        </div>
                    </div>
                </div>
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
        
<?php if(!isset($_GET['id'])){?>
<script>
    function cattype2() {
        var cattype = document.getElementById('cattype').value;
        location.href = 'SubCategory.php?addmore=1&cattype=' + cattype;
    }
</script>

<script>
    function cattype() {
        var cattype2 = document.getElementById('cattype2').value;
        location.href = 'SubCategory.php?scattype=' + cattype2;
    }
</script>

<?php $scattype = $_GET['scattype'];?>
<script>
    function maincat() {
        var scattype = '<?php echo $scattype; ?>';
        var maincat2 = document.getElementById('maincat2').value;
        location.href = 'SubCategory.php?scattype=' + scattype + '&smaincat=' + maincat2;
    }
</script>
<?php 
    } 
    else{
?>
    <script>
        function cattype2() {
            var catid = '<?php echo $_GET['id'];?>';
            var cattype = document.getElementById('cattype').value;
            location.href = 'SubCategory.php?addmore=1&id=' + catid + '&cattype=' + cattype;
        }
    </script>
    
    <script>
        function cattype() {
            var catid = '<?php echo $_GET['id'];?>';
            var catty = '<?php echo $_GET['cattype']; ?>';
            var cattype2 = document.getElementById('cattype2').value;
            location.href = 'SubCategory.php?addmore=1&id=' + catid + '&cattype=' + catty + '&scattype=' + cattype2;
        }
    </script>
    
    <?php $scattype = $_GET['scattype'];?>
    <script>
        function maincat() {
            var catid = '<?php echo $_GET['id'];?>';
            var catty = '<?php echo $_GET['cattype']; ?>';
            var scattype = '<?php echo $scattype; ?>';
            var maincat2 = document.getElementById('maincat2').value;
            location.href = 'SubCategory.php?addmore=1&id=' + catid + '&cattype=' + catty + '&scattype=' + scattype + '&smaincat=' + maincat2;
        }
    </script>
<?php }?>
    </body>
</html>