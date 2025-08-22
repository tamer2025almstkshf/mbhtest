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
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link  rel="alternate stylesheet" type="text/css" media="screen" title="selver-theme"  href="css/selver.css" />
        <link  rel="alternate stylesheet" type="text/css" media="screen" title="blue-theme"  href="css/blue.css" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" src="js/switch.js.txt"></script>
        <SCRIPT LANGUAGE="JavaScript" SRC="../CalendarPopup.js"></SCRIPT>
        <SCRIPT LANGUAGE="JavaScript" ID="js13">var cal13 = new CalendarPopup();</SCRIPT>
        <script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
        <style>
            .dropdown-content {
                position: absolute;
                background-color: #f1f1f1;
                border: 1px solid #ddd;
                max-height: 100px;
                overflow-y: auto;
                text-align: center;
                min-width: 200px;
                z-index: 1;
                display: none;
            }
            
            .dropdown-content div {
                cursor: pointer;
                background-color: #ffffff;
            }
            
            .dropdown-content div:nth-of-type(even) {
                background-color: #ebe6d7;
            }
            
            .dropdown-content div:hover {
                background-color: #f1d691;
                color: #990000;
            }
            
            #searchInput {
                width: 40%;
                text-align: center;
                color: #00F;
                font-weight: bold;
            }
            
            .dform {
                position: relative;
                display: inline-block;
            }
        </style>
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
                
                if(isset($_GET['id']) && $_GET['id'] !== ''){
                    $csidd = $_GET['id'];
                    $queryre = "SELECT * FROM clients_schedule WHERE id='$csidd'";
                    $resultre = mysqli_query($conn, $queryre);
                    $rowre = mysqli_fetch_array($resultre);
                }
                
                if($row_permcheck['csched_aperm'] === '1' || (isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1')){
            ?>
            <div class="l_data">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" dir="rtl">
                            <table width="99%" align="center" border="0" cellspacing="0" cellpadding="0">
                                <?php include_once 'search_main.php';?>
                            </table> <br />
                        </td>
                    </tr>
                    
                    <tr>
                        <td align="center" dir="rtl">
                            <div id="PrintMainDiv">
                                <form action="<?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo 'scheduledit.php'; } else if($row_permcheck['cshed_aperm'] === '1'){ echo 'addschedule.php'; }?>" method="post" enctype="multipart/form-data" >
                                    <table width="99%" border="0" cellspacing="2" cellpadding="2" dir="rtl">
                                        <tr valign="top" >
                                            <th align="right" colspan="2" dir="rtl" class="table2 red">
                                                <a href="index.php" class="Main">
                                                    <img src="images/homepage.png"   align="absmiddle" border="0"/> الصفحة الرئيسية
                                                </a> &raquo; مواعيد الموكلين
                                            </th>
                                        </tr>
                                        
                                        <tr>
                                            <th align="right" colspan="2" dir="ltr" >
                                                <div style="display:block" id="item1000">
                                                    <table width="100%" border="0" cellspacing="0" cellpadding="0"  dir="rtl">
                                                        <tr valign="top" >
                                                            <td width="60%">
                                                                <table width="100%"  border="0" cellspacing="3" cellpadding="3" class="table" align="center"  dir="ltr"  bgcolor="#FFFFFF" >
                                                                    <tr id="edit">
                                                                        <th colspan="2" class="header_table">اضافة </th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th width="80%" align="right"  dir="rtl">
                                                                            <?php if(isset($_GET['id']) && $_GET['id'] !== ''){?>
                                                                            <input type="hidden" name="cshid" value="<?php echo $_GET['id'];?>">
                                                                            <?php }?>
                                                                            
                                                                            <input type="text" name="name" value="<?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo $rowre['name']; } else if(isset($_GET['name'])){echo $_GET['name'];}?>" id="searchInput" onkeyup="searchDatabase()" style="min-width: 40%; color: #00F; font-weight: bold;" /><br>
                                                                            <?php if(isset($_GET['n']) && $_GET['n'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى كتابة اسم الموكل</span><?php }?>
                                                                            <div id="dropdown" class="dropdown-content"></div>
                                                                        </th>
                                                                        <th width="20%" align="left"  dir="rtl">الموكل :</th>
                                                                    </tr>
                                                                    
                                                                    <script>
                                                                        function searchDatabase() {
                                                                            var input = document.getElementById('searchInput').value;
                                                                            
                                                                            if (input.length > 0) {
                                                                                var xhr = new XMLHttpRequest();
                                                                                xhr.onreadystatechange = function() {
                                                                                    if (xhr.readyState === 4 && xhr.status === 200) {
                                                                                        document.getElementById('dropdown').innerHTML = xhr.responseText;
                                                                                        document.getElementById('dropdown').style.display = 'block';
                                                                                    }
                                                                                };
                                                                                
                                                                                xhr.open('GET', 'search.php?q=' + encodeURIComponent(input), true);
                                                                                xhr.send();
                                                                            } else {
                                                                                document.getElementById('dropdown').style.display = 'none';
                                                                            }
                                                                        }
                                                                        
                                                                        function selectOption(value) {
                                                                            document.getElementById('searchInput').value = value;
                                                                            document.getElementById('dropdown').style.display = 'none';
                                                                        }
                                                                    </script>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="text" name="Cell_no" style="width:25%;text-align:center; background-color:#FF0" value="<?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo $rowre['tel']; } else{ echo '97150'; }?>"> 
                                                                            <font color="#FF0000"> الرجاء ادخال الهاتف بالشكل 97150xxxxxxx</font>
                                                                        </th>
                                                                        <th align="left"  dir="rtl"> هاتف الموكل :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="date" value="<?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo $rowre['date']; }?>" name="date" dir="rtl" size="7" style="text-align:center; font-weight:bold; color:#F00" ><br>
                                                                            <?php if(isset($_GET['d']) && $_GET['d'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى كتابة تاريخ الموعد</span><?php }?> 
                                                                        </th>
                                                                        <th align="left"  dir="rtl">تاريخ الموعد :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right" dir="rtl">
                                                                            <input type="time" name="time" size="7" value="<?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo $rowre['time']; }?>" style="text-align:center"><br>
                                                                            <?php if(isset($_GET['t']) && $_GET['t'] == 0){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى كتابة وقت الموعد</span><?php }?>
                                                                        </th>
                                                                        <th align="left"  dir="rtl">الوقت :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <select name="meet_with" dir="rtl">
                                                                                <?php
                                                                                    $queryusers = "SELECT * FROM user";
                                                                                    $resultusers = mysqli_query($conn, $queryusers);
                                                                                    $selecteduserid = $rowre['meet_with'];
                                                                                    while($rowusers = mysqli_fetch_array($resultusers)){
                                                                                ?>
                                                                                <option value="<?php echo $rowusers['id'];?>" <?php if($rowusers['id'] === $selecteduserid){ echo 'selected'; }?>><?php echo $rowusers['name'];?></option>
                                                                                <?php
                                                                                    }
                                                                                ?>
                                                                            </select>
                                                                        </th>
                                                                        <th align="left"  dir="rtl"> الاجتماع مع :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <textarea dir="rtl" wrap="physical" rows="2" style="width:80%" name="details"><?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo $rowre['details']; }?></textarea>
                                                                        </th>
                                                                        <th align="left"  dir="rtl"> التفاصيل :</th>
                                                                    </tr>
                                                                    
                                                                    <tr >
                                                                        <th align="right"  dir="rtl">
                                                                            <input type="file" style="font-size: 10px;" name="meeting">
                                                                        </th>
                                                                        <th align="left"  dir="rtl"> محضر الاجتماع :</th>
                                                                    </tr>
                                                                    
                                                                    <tr > 
                                                                        <th align="right"><input type="submit" class="button" value="<?php if(isset($_GET['id']) && $row_permcheck['csched_eperm'] === '1'){ echo 'تعديل الموعد'; } else{ echo 'اضافة موعد'; }?>" style="text-decoration: none;"></th>
                                                                        <th>&nbsp;</th>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </th>
                                        </tr>
                                    </table>
                                </form>
                                <table width="99%"  border="0" cellspacing="2" cellpadding="2" align="center" >
                                    <tr > 
                                        <th >
                                            <table width="100%"  border="0" cellspacing="1" cellpadding="1" class="table" dir="ltr">
                                                <form name="mainform" action="scheduledel.php" method="post">
                                                    <input type="hidden" name="Action" value="">
                                                    <input type="hidden" name="ID" value="">
                                                    <input type="hidden" name="page" value="1">
                                                    <tr class="header_table">
                                                        <th colspan="3">&nbsp;</th>
                                                        <th colspan="3">
                                                            <input type="text" placeholder="Search...." dir="ltr" id="SearchBox">
                                                        </th>
                                                        <th colspan="3">&nbsp;</th>
                                                    </tr>
                                                    <tr class="header_table">
                                                        <th width="4%" dir="ltr"> الكل 
                                                            <input type="checkbox" style="border:0px;" onClick="var T=null; T=document.getElementsByName('datachk');  for(var y=0; y<T.length; y++)	T[y].checked=checked;">
                                                        </th>
                                                        <th width="4%" dir="ltr">تعديل</th>
                                                        <th width="26%" dir="ltr">محضر الاجتماع</th>
                                                        <th width="26%" dir="ltr">تفاصيل</th>
                                                        <th width="12%" dir="ltr">الاجتماع مع</th>
                                                        <th width="15%" dir="ltr">تاريخ و وقت الموعد</th>
                                                        <th width="10%" dir="ltr">هاتف الموكل</th>
                                                        <th width="25%" dir="ltr">الموكل</th>
                                                        <th width="10%" dir="ltr"></th>
                                                    </tr>
                                                    
                                                    <tbody id="table1">
                                                        <?php
                                                            $query = "SELECT * FROM clients_schedule";
                                                            $result = mysqli_query($conn, $query);
                                                            
                                                            if($result->num_rows > 0){
                                                                while($row=mysqli_fetch_array($result)){
                                                        ?>
                                                        <tr valign="top" bgcolor="#ffffff" style="font-weight:normal" onMouseOver="bgColor='#eaeaea'" onMouseOut="bgColor='#ffffff'">
                                                            <th>
                                                                <input type="checkbox" name="CheckedD[]" style="border:0;background : transparent;" value= <?php echo $row['id'];?>>
                                                            </th>
                                                            <th>
                                                                <?php if($row_permcheck['csched_eperm'] === '1'){?>
                                                                <img src="images/Edit.png"  border="0" onClick="location.href='scheduleadd.php?id=<?php echo $row['id'];?>';"  title="اضغط هنا للتعديل" style="cursor:pointer" />
                                                                <?php }?>
                                                            </th>
                                                            <th>
                                                                <a href="<?php echo $row['meeting'];?>" onclick="window.open(this.href, '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                                    <?php echo basename($row['meeting']);?>
                                                                </a>
                                                            </th>
                                                            <th ><?php if(isset($row['details'])){echo $row['details'];}?></th>
                                                            <th >
                                                                <?php 
                                                                    if(isset($row['meet_with'])){
                                                                        $meet_id = $row['meet_with'];
                                                                        $queryr = "SELECT * FROM user WHERE id='$meet_id'";
                                                                        $resultr = mysqli_query($conn, $queryr);
                                                                        $rowr = mysqli_fetch_array($resultr);
                                                                        echo $rowr['name'];
                                                                    }
                                                                ?>
                                                            </th>
                                                            <th ><?php if(isset($row['date'])){echo $row['date'];} if(isset($row['time'])){echo '<br>'.$row['time'];}?></th>
                                                            <th ><?php if(isset($row['tel'])){echo $row['tel'];}?></th>
                                                            <th  style="color:#00F" ><?php if(isset($row['client_id'])){echo $row['client_id'] . ' # ' . $row['name'];}?></th>
                                                            <th  style="color:gray; font-size: 12px;" >
                                                                <?php 
                                                                    if(isset($row['timestamp'])){
                                                                        $timestamp = $row['timestamp']; 
                                                                        list($empid, $date) = explode(" <br> ", $timestamp); 
                                                                        $queryuser = "SELECT * FROM user WHERE id='$empid'"; 
                                                                        $resultuser = mysqli_query($conn, $queryuser); 
                                                                        $rowuser = mysqli_fetch_array($resultuser); 
                                                                        $emp_name = $rowuser['name']; 
                                                                        echo $emp_name."<br>".$date;
                                                                    }
                                                                ?>
                                                            </th>
                                                        </tr>
                                                        <?php 
                                                                }
                                                            }
                                                        ?>
                                                    </tbody>
                                                    
                                                    <td>
                                                        <?php if($row_permcheck['csched_dperm'] === '1'){?>
                                                        <input type="submit" value="حذف" class="button">
                                                        <?php }?>
                                                    </td>
                                                    <?php if(isset($_GET['error']) && $_GET['error'] === 'null'){?><span class="blink" style="color:#FF0000; font-size:14px;">يرجى اختيار المواعيد المراد حذفها</span><?php }?>
                                                </form>
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
        </div>
    </body>
</html>

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

<script>
    function CheckSearchKey(){
        if(document.SearchForm.SearchKey.value=="" || document.SearchForm.SearchKey.value==" " || document.SearchForm.SearchKey.value=="   "){
            alert ("الرجاء ادخال كلمة البحث");
            document.SearchForm.SearchKey.focus()
            return false;
        }
        SearchForm.submit();
	}
</script>	