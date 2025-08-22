<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="rtl">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <title>محمد بني هاشم للمحاماة والاستشارات القانونية</title>

        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <link rel="SHORTCUT ICON" href="images/favicon.ico">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link  rel="alternate stylesheet" type="text/css" media="screen" title="selver-theme"  href="css/selver.css" />
        <link  rel="alternate stylesheet" type="text/css" media="screen" title="blue-theme"  href="css/blue.css" />
        <script type="text/javascript" src="js/switch.js.txt"></script>

        <SCRIPT LANGUAGE="JavaScript" SRC="../CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>

        <script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
        <script language="javascript" type="text/javascript">
            tinyMCE.init({
                mode : "exact",
                elements : "elm1,elm2,elm3,elm4",
                theme : "advanced",
                plugins : "style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable",
                theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright, justifyfull,fontformat",
                theme_advanced_buttons1_add : "fontselect,fontsizeselect",
                theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,separator,forecolor,backcolor",
                theme_advanced_buttons2 : "bullist,numlist,separator,undo,redo,separator,link,unlink",
                theme_advanced_buttons3_add : "emotions,advhr,image,code,separator",
                theme_advanced_buttons3 : "charmap",
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_buttons2_add_before: "cut,copy,paste,separator,search,replace,separator",
                plugin_insertdate_dateFormat : "%Y-%m-%d",
                plugin_insertdate_timeFormat : "%H:%M:%S",	
                external_image_list_url : "example_image_list.php",
                extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]"
            });

            function fileBrowserCallBack(field_name, url, type, win) {
                my_window= window.open ("uploadImage.php","mywindow1","status=0,width=250,height=100");
                win.location.reload(true);
            }
        </script>
    </head>
    <body>
        <div class="container">
            <?php 
                include_once 'userInfo.php';
                include_once 'sidebar.php';
                
                $myid = $_SESSION['id'];
                $query_permcheck = "SELECT * FROM user WHERE id='$myid'";
                $result_permcheck = mysqli_query($conn, $query_permcheck);
                $row_permcheck = mysqli_fetch_array($result_permcheck);
                
                if($row_permcheck['accfinance_rperm'] === '1'){
            ?>
            <div class="l_data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" dir="rtl">
                            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <?php include_once 'search_main.php';?>
                            </table>
                            <br />
                        </td>
                    </tr>
                    <tr>
                        <td align="center" dir="rtl">
                            <div id="PrintMainDiv">
                                <table width="99%" border="0" cellspacing="1" cellpadding="1" dir="rtl">
                                    <tr valign="top" bgcolor="#FFFFFF">
                                        <th align="right" colspan="2" dir="rtl" class="table2 red" ><a href="index.php?LoadMenue=BS" class="Main"><img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية</a> &raquo; قسم المالية</th>
                                    </tr>
                                    
                                    <tr>
                                        <th>
                                            <table width="100%"  border="0" cellspacing="1" cellpadding="3" align="center" class="table" bgcolor="#ffffff"  >
                                                <tr > 
                                                    <th >
                                                        <input type="text" placeholder="Search...." dir="ltr" id="SearchBox">
                                                        <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr" id="editableTable" class="table">
                                                            <thead class="header_table">
                                                                <tr>
                                                                    <th width="9%" dir="ltr">Client Code</th>
                                                                    <th width="19%" dir="ltr">Client Name</th>
                                                                    <th width="25%" dir="rtl">Terms</th>
                                                                    <th width="5%" dir="rtl">Agreement Date</th>
                                                                    <th width="9%" dir="rtl">Professional Fees</th>
                                                                    <th width="15%" dir="rtl">Upon Signing The Agreement</th>
                                                                    <th width="20%" dir="rtl">Dates</th>
                                                                    <th width="9%" dir="rtl">After The Judgement</th>
                                                                </tr>
                                                            </thead>
                                                            
                                                            <form method="POST" action="finedit.php" id="editableForm">
                                                                <input type="hidden" name="changed" id="changedField" value="">
                                                                <input type="hidden" name="newValue" id="newValueField" value="">
                                                                <input type="hidden" name="scrollPosition" id="scrollPosition" value="">
                                                                <input type="hidden" name="cid" id="rowIdField" value="">
                                                                <tbody id="table1">
                                                                    <?php
                                                                        $query = "SELECT * FROM client WHERE arname!='' AND engname!='' AND client_kind!='' ORDER BY id DESC";
                                                                        $result = mysqli_query($conn, $query);
                                                                        if($result->num_rows > 0){
                                                                            while($row = mysqli_fetch_array($result)){
                                                                                $cid = $row['id'];
                                                                                $queryfin = "SELECT * FROM finance WHERE cid = $cid";
                                                                                $resultfin = mysqli_query($conn, $queryfin);
                                                                                $rowfin = mysqli_fetch_array($resultfin);
                                                                    ?>
                                                                    <tr valign="top" class="title1" bgcolor="#eaeaea" onMouseOver="bgColor='#ffffff'" onMouseOut="bgColor='#eaeaea'" data-id="1">
                                                                        <td contenteditable="false"><?php echo $row['id'];?></td>
                                                                        <td contenteditable="false"><?php echo $row['engname'];?></td>
                                                                        <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="terms" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                            <?php echo $rowfin['terms']; ?>
                                                                        </td>
                                                                        <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="agreement_date" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                            <?php echo $rowfin['agreement_date']; ?>
                                                                        </td>
                                                                        <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="professional_fees" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                            <?php echo $rowfin['professional_fees']; ?>
                                                                        </td>
                                                                        <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="signing_agreement" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                            <?php echo $rowfin['signing_agreement']; ?>
                                                                        </td>
                                                                        <td contenteditable="false" style="height: 100%; vertical-align: middle; padding: 0;" onclick="toggleModal(<?php echo $row['id'];?>)"> 
                                                                            <div class="display-btn-container" style="height: 100%; display: flex; align-items: stretch;">
                                                                                <div style="flex: 1;">
                                                                                    <?php 
                                                                                        $dates = '';
                                                                                        
                                                                                        if(isset($rowfin['dates_JAN']) && $rowfin['dates_JAN'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_JAN'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_JAN'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_FEB']) && $rowfin['dates_FEB'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_FEB'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_FEB'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_MAR']) && $rowfin['dates_MAR'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_MAR'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_MAR'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_APR']) && $rowfin['dates_APR'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_APR'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_APR'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_MAY']) && $rowfin['dates_MAY'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_MAY'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_MAY'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_JUN']) && $rowfin['dates_JUN'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_JUN'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_JUN'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_JUL']) && $rowfin['dates_JUL'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_JUL'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_JUL'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_AUG']) && $rowfin['dates_AUG'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_AUG'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_AUG'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_SEP']) && $rowfin['dates_SEP'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_SEP'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_SEP'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_OCT']) && $rowfin['dates_OCT'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_OCT'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_OCT'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_NOV']) && $rowfin['dates_NOV'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_NOV'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_NOV'];
                                                                                            }
                                                                                        }
                                                                                        if(isset($rowfin['dates_DEC']) && $rowfin['dates_DEC'] !== ''){
                                                                                            if($dates === ''){
                                                                                                $dates = $rowfin['dates_DEC'];
                                                                                            } else{
                                                                                                $dates = $dates.'<br>'.$rowfin['dates_DEC'];
                                                                                            }
                                                                                        }
                                                                                        
                                                                                        echo $dates;
                                                                                    ?>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                            <div id="taskaddplus-btn-<?php echo $row['id'];?>" class="modal-overlay" onclick="event.stopPropagation()">
                                                                                <div class="modal-content">
                                                                                    <span class="close-button" onclick="closeModal(<?php echo $row['id'];?>)">&times;</span>
                                                                                    <table width="100%" border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr" id="editableTable" class="table">
                                                                                        <thead class="header_table">
                                                                                            <tr>
                                                                                                <th width="10%" dir="ltr">Client Code</th>
                                                                                                <th width="18%" dir="ltr">Client Name</th>
                                                                                                <th width="6%" dir="rtl">JAN</th>
                                                                                                <th width="6%" dir="rtl">FEB</th>
                                                                                                <th width="6%" dir="rtl">MAR</th>
                                                                                                <th width="6%" dir="rtl">APR</th>
                                                                                                <th width="6%" dir="rtl">MAY</th>
                                                                                                <th width="6%" dir="rtl">JUN</th>
                                                                                                <th width="6%" dir="rtl">JUL</th>
                                                                                                <th width="6%" dir="rtl">AUG</th>
                                                                                                <th width="6%" dir="rtl">SEP</th>
                                                                                                <th width="6%" dir="rtl">OCT</th>
                                                                                                <th width="6%" dir="rtl">NOV</th>
                                                                                                <th width="6%" dir="rtl">DEC</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="table1">
                                                                                            <tr valign="top" class="title1" bgcolor="#eaeaea" onMouseOver="bgColor='#ffffff'" onMouseOut="bgColor='#eaeaea'" data-id="1">
                                                                                                <td contenteditable="false"><?php echo $row['id'];?></td>
                                                                                                <td contenteditable="false"><?php echo $row['engname'];?></td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_JAN" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_JAN']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_FEB" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_FEB']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_MAR" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_MAR']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_APR" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_APR']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_MAY" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_MAY']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_JUN" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_JUN']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_JUL" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_JUL']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_AUG" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_AUG']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_SEP" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_SEP']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_OCT" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_OCT']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_NOV" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_NOV']; ?>
                                                                                                </td>
                                                                                                <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="dates_DEC" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                                                    <?php echo $rowfin['dates_DEC']; ?>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </td>
                                                                        <td contenteditable="<?php if($row_permcheck['accfinance_eperm'] === '1'){ echo 'true'; } else{ echo 'false'; }?>" data-field="judge_after" data-id="<?php echo $row['id']; ?>" onkeydown="handleEnter(event, this)">
                                                                            <?php echo $rowfin['judge_after']; ?>
                                                                        </td>
                                                                    </tr>
                                                                    <?php
                                                                            }
                                                                        }
                                                                    ?>
                                                                </tbody>
                                                            </form>
                                                        </table>
                                                    </th>
                                                </tr>
                                            </table>
                                        </th>
                                    </tr>
                                </table>
                                <br>    
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <?php }?>
        </div>
        <div class="footer">محمد بني هاشم للمحاماة والاستشارات القانونية<img alt="" src="images/f.png" width="29" height="31" /><img alt="" src="images/w.png" width="29" height="31" /></div>
    </body>
</html>

<script>
    let isModalOpen = false;

    function toggleModal(id) {
        var taskaddplus = document.getElementById('taskaddplus-btn-' + id);
        if (isModalOpen) {
            taskaddplus.style.display = 'none';
            isModalOpen = false;
        } else {
            taskaddplus.style.display = 'block';
            isModalOpen = true;
        }
    }

    function closeModal(id) {
        event.stopPropagation();
        var taskaddplus = document.getElementById('taskaddplus-btn-' + id);
        taskaddplus.style.display = 'none';
        isModalOpen = false;
    }
</script>

<script>
    function handleEnter(event, element) {
        if (event.key === "Enter") {
            event.preventDefault(); // Prevent adding a new line

            const form = document.getElementById("editableForm");
            const changedField = document.getElementById("changedField");
            const newValueField = document.getElementById("newValueField");
            const rowIdField = document.getElementById("rowIdField");
            const scrollPosition = document.getElementById("scrollPosition");

            // Set the field name, new value, and row ID in the form
            changedField.value = element.getAttribute("data-field");
            newValueField.value = element.textContent.trim();
            rowIdField.value = element.getAttribute("data-id");

            // Capture the current scroll position
            scrollPosition.value = window.scrollY;

            // Submit the form
            form.submit();
        }
    }

    // Restore scroll position after page load
    window.onload = function () {
        const scrollPosition = new URLSearchParams(window.location.search).get('scroll');
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition, 10));
        }
    };
</script>

<script>
    $(document).ready(function(){
        $('#SearchBox').on("keyup", function(){
            var value = $(this).val().toLowerCase();
            $("#table1 tr").filter(function(){
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    });
</script>

<script language="JavaScript" type="text/JavaScript">
    function MM_openBrWindow(theURL,winName,features) {
        window.open(theURL,winName,features);
    }
</script>