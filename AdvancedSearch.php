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
    <body>
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
                    if($row_permcheck['cfiles_rperm'] == 1){
                ?>
                <div class="web-page">
                    <br><br>
                    <div class="advinputs-container">
                        <form name="addform" action="advsearch.php" method="post" enctype="multipart/form-data">
                            <h2 class="advinputs-h2">البحث المتقدم</h2>
                            <div class="advinputs">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">نوع الملف</font></p>
                                    <select class="table-header-selector" name="file_type" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value="" ></option>
                                        <option value="مدني -عمالى" <?php if($_GET['type'] === 'مدني -عمالى'){ echo 'selected'; }?>>مدني -عمالى</option>
                                        <option value="أحوال شخصية" <?php if($_GET['type'] === 'أحوال شخصية'){ echo 'selected'; }?>>أحوال شخصية</option>
                                        <option value="جزاء" <?php if($_GET['type'] === 'جزاء'){ echo 'selected'; }?>>جزاء</option>
                                        <option value="المنازعات الإيجارية" <?php if($_GET['type'] === 'المنازعات الإيجارية'){ echo 'selected'; }?>>المنازعات الإيجارية</option>
                                    </select> 
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الفرع المختص</font></p>
                                    <select name="frelated_place" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <option value="الشارقة" <?php if($_GET['place'] === 'الشارقة'){ echo 'selected'; }?>>الشارقة</option>
                                        <option value="دبي" <?php if($_GET['place'] === 'دبي'){ echo 'selected'; }?> >دبي</option>
                                        <option value="عجمان" <?php if($_GET['place'] === 'عجمان'){ echo 'selected'; }?> >عجمان</option>
                                    </select>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">تصنيف الملف</font></p>
                                    <input type="radio" name="file_class" value="أفراد" style="padding: 10px 0; margin: 10px 0;" <?php if(isset($_GET['class']) && $_GET['class'] === 'أفراد'){ echo 'checked'; }?>> <font color="#676767">أفراد</font><br>
                                    <input type="radio" name="file_class" value="مؤسسات" <?php if(isset($_GET['class']) && $_GET['class'] === 'مؤسسات'){ echo 'checked'; }?>> <font color="#676767">مؤسسات</font>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">رقم الملف</font></p>
                                    <input class="form-input" type="number" name="fid" value="<?php echo $_GET['fid'];?>">
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الموضوع</font></p>
                                    <textarea class="form-input" rows="2" type="text" name="file_subject"><?php echo $_GET['subject'];?></textarea>
                                </div>
                            </div>
                            <div class="advinputs">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الموكل</font></p>
                                    <select name="file_client" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_sclients = "SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='موكل' ORDER BY id DESC";
                                            $result_sclients = mysqli_query($conn, $query_sclients);
                                            
                                            if($result_sclients->num_rows > 0){
                                                while($row_sclients = mysqli_fetch_array($result_sclients)){
                                                    $cli_id = $row_sclients['id'];
                                                    $cli_name = $row_sclients['arname'];
                                        ?>
                                        <option value='<?php echo $cli_id?>' <?php if($_GET['client'] === $cli_id){ echo 'selected'; }?>><?php echo $cli_id.' # '.$cli_name?></option>
                                        <?php
                                                }
                                            } else{
                                                echo '<option value=""></option>';
                                            }
                                        ?>
                                    </select>	
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">صفة الموكل</font></p>
                                    <select name="fclient_characteristic" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_scharachteristics = "SELECT * FROM client_status";
                                            $result_scharachteristics = mysqli_query($conn, $query_scharachteristics);
                                            
                                            if($result_scharachteristics->num_rows > 0){
                                                while($row_scharachteristics = mysqli_fetch_array($result_scharachteristics)){
                                                    $stname = $row_scharachteristics['arname'];
                                        ?>
                                        <option value='<?php echo $stname?>' <?php if($_GET['ccharacteristic'] === $stname){ echo 'selected'; }?>><?php echo $stname?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الخصم</font></p>
                                    <select name="file_opponent" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_sclients = "SELECT * FROM client WHERE terror!='1' AND arname!='' AND client_kind='خصم' ORDER BY id DESC";
                                            $result_sclients = mysqli_query($conn, $query_sclients);
                                            
                                            if($result_sclients->num_rows > 0){
                                                while($row_sclients = mysqli_fetch_array($result_sclients)){
                                                    $cli_id = $row_sclients['id'];
                                                    $cli_name = $row_sclients['arname'];
                                        ?>
                                        <option value='<?php echo $cli_id?>' <?php if($_GET['opponent'] === $cli_id){ echo 'selected'; }?>><?php echo $cli_id.' # '.$cli_name?></option>
                                        <?php
                                                }
                                            } else{
                                                echo '<option value=""></option>';
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">صفة الخصم</font></p>
                                    <select name="fopponent_characteristic" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_scharachteristics = "SELECT * FROM client_status";
                                            $result_scharachteristics = mysqli_query($conn, $query_scharachteristics);
                                            
                                            if($result_scharachteristics->num_rows > 0){
                                                while($row_scharachteristics = mysqli_fetch_array($result_scharachteristics)){
                                                    $stname = $row_scharachteristics['arname'];
                                        ?>
                                        <option value='<?php echo $stname?>' <?php if($_GET['ocharacteristic'] === $stname){ echo 'selected'; }?>><?php echo $stname?></option>";
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">المستشار القانوني</font></p>
                                    <?php
                                        $query_la = "SELECT * FROM user WHERE job_title='10'";
                                        $result_la = mysqli_query($conn, $query_la);
                                    ?>
                                    <select name="flegal_advisor" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            if($result_la->num_rows > 0){
                                                while($row_la = mysqli_fetch_array($result_la)){
                                                    $lr_id = $row_la['id'];
                                                    $la_name = $row_la['name'];
                                        ?>
                                        <option value='<?php echo $lr_id?>' <?php if($_GET['advisor'] === $lr_id){ echo 'selected'; }?>><?php echo $la_name?></option>";
                                        <?php
                                                }
                                            }
                                        ?>
                                   </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الباحث القانوني</font></p>
                                    <?php
                                        $query_lr = "SELECT * FROM user WHERE job_title='11'";
                                        $result_lr = mysqli_query($conn, $query_lr);
                                    ?>
                                    <select name="flegal_researcher" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php
                                            if($result_lr->num_rows > 0){
                                                while($row_lr = mysqli_fetch_array($result_lr)){
                                                    $lr_id = $row_lr['id'];
                                                    $lr_name = $row_lr['name'];
                                        ?>
                                        <option value='<?php echo $lr_id?>' <?php if($_GET['researcher'] === $lr_id){ echo 'selected'; }?>><?php echo $lr_name;?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                   </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">نوع القضية</font></p>
                                    <select name="fcase_type" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_ct = "SELECT * FROM case_type";
                                            $result_ct = mysqli_query($conn, $query_ct);
                                            
                                            if($result_ct->num_rows > 0){
                                                while($row_ct = mysqli_fetch_array($result_ct)){
                                                    $case_name = $row_ct['ct_name'];
                                        ?>
                                        <option value='<?php echo $case_name?>' <?php if($_GET['ctype'] === $case_name){ echo 'selected'; }?>><?php echo $case_name?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>	
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">النيابة</font></p>
                                    <select name="file_prosecution" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_prosecution = "SELECT * FROM prosecution";
                                            $result_prosecution = mysqli_query($conn, $query_prosecution);
                                            
                                            if($result_prosecution->num_rows > 0){
                                                while($row_prosecution = mysqli_fetch_array($result_prosecution)){
                                                    $prosname = $row_prosecution['prosecution_name'];
                                        ?>
                                        <option value='<?php echo $prosname?>' <?php if($_GET['prosec'] === $prosname){ echo 'selected'; }?>><?php echo $prosname?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">مركز الشرطة</font></p>
                                    <select name="fpolice_station" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_ps = "SELECT * FROM police_station";
                                            $result_ps = mysqli_query($conn, $query_ps);
                                            
                                            if($result_ps->num_rows > 0){
                                                while($row_ps = mysqli_fetch_array($result_ps)){
                                                    $cli_name = $row_ps['policestation_name'];
                                        ?>
                                        <option value='<?php echo $cli_name?>' <?php if($_GET['station'] === $cli_name){ echo 'selected'; }?>><?php echo $cli_name;?></option>";
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">المحكمة</font></p>
                                    <select name="file_court" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;">
                                        <option value=""></option>
                                        <?php 
                                            $query_scourt = "SELECT * FROM court";
                                            $result_scourt = mysqli_query($conn, $query_scourt);
                                            
                                            if($result_scourt->num_rows > 0){
                                                while($row_scourt = mysqli_fetch_array($result_scourt)){
                                                    $court_name = $row_scourt['court_name'];
                                        ?>
                                        <option value='<?php echo $court_name?>' <?php if($_GET['court'] === $court_name){ echo 'selected'; }?>><?php echo $court_name?></option>
                                        <?php
                                                }
                                            }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="advinputs3">
                                <table class="info-table opp-table" style="width: 100%; background-color: #e7e7e740; padding: 10px;">
                                    <tr align="center">
                                        <td></td>
                                        <td>الدرجة</td>
                                        <td>رقم القضية</td>
                                        <td>السنة</td>
                                    </tr>
                                    
                                    <tr align="center">
                                        <td><input type="checkbox" name="opp_date" value="1" <?php if($_GET['opp'] === '1'){ echo 'checked'; }?>> قضايا متقابلة</td>
                                        <td>
                                            <select name="session_degree" class="table-header-selector" style="width: 70%; height: fit-content; margin: 10px 0;">
                                                <option value=""></option>
                                                <?php 
                                                    $query_deg = "SELECT * FROM degree";
                                                    $result_deg = mysqli_query($conn, $query_deg);
                                                    
                                                    if($result_deg->num_rows > 0){
                                                        while($row_deg = mysqli_fetch_array($result_deg)){
                                                            $degree_name = $row_deg['degree_name'];
                                                ?>
                                                <option value='<?php echo $degree_name;?>' <?php if($_GET['deg'] === $degree_name){ echo 'selected'; }?>><?php echo $degree_name;?></option>";
                                                <?php
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td><input class="form-input" type="text" name="case_num" value="<?php echo $_GET['cno'];?>" style="width: 70%;"></td>
                                        <td><input class="form-input" type="text"  name="year" value="<?php echo $_GET['year'];?>" style="width: 70%;"></td>
                                    </tr>
                                </table>
                                <p></p>
                                <?php
                                    $where = '';
                                    
                                    $type = $_GET['type'];
                                    if(isset($type) && $type !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_type='$type'";
                                        } else{
                                            $where = $where." AND file_type='$type'";
                                        }
                                    }
                                    
                                    $frelated_place = $_GET['place'];
                                    if(isset($frelated_place) && $frelated_place !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(frelated_place='$frelated_place'";
                                        } else{
                                            $where = $where." AND frelated_place='$frelated_place'";
                                        }
                                    }
                                    
                                    $file_class = $_GET['class'];
                                    if(isset($file_class) && $file_class !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_class='$file_class'";
                                        } else{
                                            $where = $where." AND file_class='$file_class'";
                                        }
                                    }
                                    
                                    $file_id = $_GET['fid'];
                                    if(isset($file_id) && $file_id !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_id='$file_id'";
                                        } else{
                                            $where = $where." AND file_id='$file_id'";
                                        }
                                    }
                                    
                                    $file_subject = $_GET['subject'];
                                    if(isset($file_subject) && $file_subject !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_subject='$file_subject'";
                                        } else{
                                            $where = $where." AND file_subject='$file_subject'";
                                        }
                                    }
                                    
                                    $file_notes = $_GET['notes'];
                                    if(isset($file_notes) && $file_notes !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_notes='$file_notes'";
                                        } else{
                                            $where = $where." AND file_notes='$file_notes'";
                                        }
                                    }
                                    
                                    $file_client = $_GET['client'];
                                    if(isset($file_client) && $file_client !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_client='$file_client'";
                                        } else{
                                            $where = $where." AND file_client='$file_client'";
                                        }
                                    }
                                    
                                    $fclient_characteristic = $_GET['ccharacteristic'];
                                    if(isset($fclient_characteristic) && $fclient_characteristic !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(fclient_characteristic='$fclient_characteristic'";
                                        } else{
                                            $where = $where." AND fclient_characteristic='$fclient_characteristic'";
                                        }
                                    }
                                    
                                    $file_opponent = $_GET['opponent'];
                                    if(isset($file_opponent) && $file_opponent !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_opponent='$file_opponent'";
                                        } else{
                                            $where = $where." AND file_opponent='$file_opponent'";
                                        }
                                    }
                                    
                                    $fopponent_characteristic = $_GET['ocharacteristic'];
                                    if(isset($fopponent_characteristic) && $fopponent_characteristic !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(fopponent_characteristic='$fopponent_characteristic'";
                                        } else{
                                            $where = $where." AND fopponent_characteristic='$fopponent_characteristic'";
                                        }
                                    }
                                    
                                    $flegal_advisor = $_GET['advisor'];
                                    if(isset($flegal_advisor) && $flegal_advisor !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(flegal_advisor='$flegal_advisor'";
                                        } else{
                                            $where = $where." AND flegal_advisor='$flegal_advisor'";
                                        }
                                    }
                                    
                                    $flegal_researcher = $_GET['researcher'];
                                    if(isset($flegal_researcher) && $flegal_researcher !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(flegal_researcher='$flegal_researcher'";
                                        } else{
                                            $where = $where." AND flegal_researcher='$flegal_researcher'";
                                        }
                                    }
                                    
                                    $fcase_type = $_GET['ctype'];
                                    if(isset($fcase_type) && $fcase_type !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(fcase_type='$fcase_type'";
                                        } else{
                                            $where = $where." AND fcase_type='$fcase_type'";
                                        }
                                    }
                                    
                                    $file_prosecution = $_GET['prosec'];
                                    if(isset($file_prosecution) && $file_prosecution !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_prosecution='$file_prosecution'";
                                        } else{
                                            $where = $where." AND file_prosecution='$file_prosecution'";
                                        }
                                    }
                                    
                                    $fpolice_station = $_GET['station'];
                                    if(isset($fpolice_station) && $fpolice_station !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(fpolice_station='$fpolice_station'";
                                        } else{
                                            $where = $where." AND fpolice_station='$fpolice_station'";
                                        }
                                    }
                                    
                                    $file_court = $_GET['court'];
                                    if(isset($file_court) && $file_court !== ''){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_court='$file_court'";
                                        } else{
                                            $where = $where." AND file_court='$file_court'";
                                        }
                                    }
                                    
                                    $opp_check = $_GET['opp'];
                                    if($opp_check === '1'){
                                        $opp_num = $_GET['cno'];
                                        if(isset($opp_num) && $opp_num !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(opp_num='$opp_num'";
                                            } else{
                                                $where = $where." AND opp_num='$opp_num'";
                                            }
                                        }
                                        
                                        $opp_year = $_GET['year'];
                                        if(isset($opp_year) && $opp_year !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(opp_year='$opp_year'";
                                            } else{
                                                $where = $where." AND opp_year='$opp_year'";
                                            }
                                        }
                                    }
                                    
                                    $numoftypes = 0;
                                    
                                    $file_type1 = $_GET['pending'];
                                    $file_type2 = $_GET['fwork'];
                                    $file_type3 = $_GET['archived'];
                                    
                                    if(isset($file_type1) && $file_type1 !== ''){
                                        $numoftypes = $numoftypes + 1;
                                    }
                                    
                                    if(isset($file_type2) && $file_type2 !== ''){
                                        $numoftypes = $numoftypes + 1;
                                    }
                                    
                                    if(isset($file_type3) && $file_type3 !== ''){
                                        $numoftypes = $numoftypes + 1;
                                    }
                                    if($numoftypes == 1){
                                        if(isset($file_type1) && $file_type1 !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(file_status='في الانتظار'";
                                            } else{
                                                $where = $where." AND file_status='في الانتظار'";
                                            }
                                        } else if(isset($file_type2) && $file_type2 !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(file_status='متداول'";
                                            } else{
                                                $where = $where." AND file_status='متداول'";
                                            }
                                        } else if(isset($file_type3) && $file_type3 !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(file_status='مؤرشف'";
                                            } else{
                                                $where = $where." AND file_status='مؤرشف'";
                                            }
                                        }
                                    } else if($numoftypes == 2){
                                        if(isset($file_type1) && $file_type1 !== '' && isset($file_type2) && $file_type2 !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(file_status='في الانتظار' OR file_status='متداول'";
                                            } else{
                                                $where = $where." AND (file_status='في الانتظار' OR file_status='متداول')";
                                            }
                                        } else if(isset($file_type2) && $file_type2 !== '' && isset($file_type3) && $file_type3 !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(file_status='متداول' OR file_status='مؤرشف'";
                                            } else{
                                                $where = $where." AND (file_status='متداول' OR file_status='مؤرشف')";
                                            }
                                        } else if(isset($file_type1) && $file_type1 !== '' && isset($file_type3) && $file_type3 !== ''){
                                            if(isset($where) && $where === ''){
                                                $where = $where."(file_status='في الانتظار' OR file_status='مؤرشف'";
                                            } else{
                                                $where = $where." AND (file_status='في الانتظار' OR file_status='مؤرشف')";
                                            }
                                        }
                                    } else if($numoftypes == 3){
                                        if(isset($where) && $where === ''){
                                            $where = $where."(file_status='في الانتظار' OR file_status='متداول' OR file_status='مؤرشف'";
                                        } else{
                                            $where = $where." AND (file_status='في الانتظار' OR file_status='متداول' OR file_status='مؤرشف')";
                                        }
                                    }
                                    
                                    if($where !== ''){
                                        $where = $where.')';
                                    }
                                    
                                    $where2 = '';
                                    $session_fid = $_GET['fid'];
                                    if(isset($session_fid) && $session_fid !== ''){
                                        if(isset($where2) && $where2 === ''){
                                            $where2 = $where2."session_fid='$session_fid'";
                                        } else{
                                            $where2 = $where2." AND session_fid='$session_fid'";
                                        }
                                    }
                                    
                                    $jud = $_GET['jud'];
                                    if($jud === '1'){
                                        $start_date = $_GET['from'];
                                        $end_date = $_GET['to'];
                                        
                                        function displayDates($start_date, $end_date) {
                                            $start = new DateTime($start_date);
                                            $end = new DateTime($end_date);
                                            
                                            if ($start <= $end) {
                                                $dates = [];
                                                while ($start <= $end) {
                                                    $dates[] =  "session_date='" . $start->format('Y-m-d') . "'";
                                                    $start->modify('+1 day');
                                                }
                                                $session_dates = '';
                                                $session_dates = "(" . implode(' OR ', $dates) . ")";
                                                return $session_dates;
                                            }
                                        }
                                        $session_dates = displayDates($start_date, $end_date);
                                        if(isset($session_dates) && $session_dates !== ''){
                                            if(isset($where2) && $where2 === ''){
                                                $where2 = $where2."$session_dates";
                                            } else{
                                                $where2 = $where2." AND $session_dates";
                                            }
                                        }
                                    }
                                    
                                    $extended = $_GET['extended'];
                                    if(isset($extended) && $extended === '1'){
                                        if(isset($where2) && $where2 === ''){
                                            $where2 = $where2."extended='1'";
                                        } else{
                                            $where2 = $where2." AND extended='1'";
                                        }
                                    }
                                    
                                    $trial = $_GET['judge'];
                                    if(isset($trial) && $trial === '1'){
                                        if(isset($where2) && $where2 === ''){
                                            $where2 = $where2."session_trial!=''";
                                        } else{
                                            $where2 = $where2." AND session_trial!=''";
                                        }
                                    }
                                    
                                    if($where2 !== ''){
                                        $arraycheck = '';
                                        $querys = "SELECT DISTINCT session_fid FROM session WHERE $where2";
                                        $results = mysqli_query($conn, $querys);
                                        
                                        if($results->num_rows > 0){
                                            $arraycheck = '1';
                                            while($rows = mysqli_fetch_array($results)){
                                                $fids[] = $rows['session_fid'];
                                            }
                                        }
                                        
                                        if($arraycheck === '1'){
                                            if (!empty($fids)) {
                                                $fids_list = implode(',', array_map('intval', $fids));
                                                
                                                if($where !== ''){
                                                    $where = $where." OR file_id IN ($fids_list)";
                                                } else{
                                                    $where = "file_id IN ($fids_list)";
                                                }
                                            }
                                        }
                                    }
                                ?>
                                <div class="input-container">
                                    <input type="checkbox" name="jud_session" value="1" <?php if($_GET['jud'] === '1'){ echo 'checked'; }?>/> حجزت للحكم 
                                    من <input class="form-input" style="width:20%" type="date" name="from_date" value="<?php if(isset($_GET['from']) && $_GET['from'] !== ''){ echo $_GET['from']; }?>"> 
                                    الى <input class="form-input" style="width:20%" type="date" name="to_date" value="<?php if(isset($_GET['to']) && $_GET['to'] !== ''){ echo $_GET['to']; }?>"> 
                                </div>
                                <div class="input-container"><input type="checkbox" name="extended" value="1" <?php if($_GET['extended'] === '1'){ echo 'checked'; }?>> مد اجل الحكم</div>
                                <p></p>
                                <p></p>
                                <div class="input-container"><input type="checkbox" name="judge" value="1" <?php if($_GET['judge'] === '1'){ echo 'checked'; }?>/> صدر فيها الحكم</div>
                                <p></p>
                                <div class="input-container" style="height: 50px; background-color: #e7e7e740; align-content: center;">
                                    <table class="info-table" style="width: 100%; padding: 10px;">
                                        <tr align="center">
                                            <td><p class="input-parag"><font class="blue-parag">حالة الملف</font></p></td>
                                            <td width="25%" dir="rtl"> <input type="checkbox" name="pending" value="1" <?php if($_GET['pending'] === '1'){ echo 'checked'; }?>/> في الانتظار</td>
                                            <td width="25%"><input type="checkbox" name="fwork" value="1" <?php if($_GET['fwork'] === '1'){ echo 'checked'; }?>/>  متداولة </td>
                                            <td width="25%"><input type="checkbox" name="archived" value="1" <?php if($_GET['archived'] === '1'){ echo 'checked'; }?>/>  مؤرشفة</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <button type="submit" class="green-button" style="width: 100%; font-size: 20px; margin-top: 10px">البحث في الملفات</button>
                        </form>
                        <?php
                            if($where !== ''){
                                $query = "SELECT * FROM file WHERE $where ORDER BY file_id DESC";
                                $result = mysqli_query($conn, $query);
                                if($result->num_rows > 0){
                        ?>
                        <div class="table-container">
                            <div class="table-header" style="text-align: center; border: none;">
                                <div class="table-header-right">
                                    <h3 style="display: inline-block"><font id="clients-translate">ملفات القضايا</font></h3>
                                </div>
                                <div class="table-header-left">
                                    <div class="table-header-icons" style="margin-right: 10px;"></div>
                                    <div class="table-header-icons" style="margin-right: 20px;"></div>
                                    <div class="table-header-icons" style="margin-right: 30px;"></div>
                                    <div class="modal-overlay" <?php if(isset($_GET['attachments']) && $_GET['attachments'] === '1'){ echo 'style="display: block;"'; }?>>
                                        <div class="modal-content" style="margin: auto; align-content: center">
                                            <div class="notes-displayer">
                                                <div class="addc-header">
                                                    <h4 class="addc-header-parag" style="margin: auto">مرفقات الملف رقم : <?php echo $_GET['id'];?></h4>
                                                    <div class="close-button-container">
                                                        <?php
                                                            $queryString = $_SERVER['QUERY_STRING'];
                                                            
                                                            if ($queryString !== '') {
                                                                if (strpos($queryString, 'attachments') !== false) {
                                                                    parse_str($queryString, $queryParams);
                                                                    unset($queryParams['attachments']);
                                                                    $queryString = http_build_query($queryParams);
                                                                }
                                                                if (strpos($queryString, 'id') !== false) {
                                                                    parse_str($queryString, $queryParams);
                                                                    unset($queryParams['id']);
                                                                    $queryString = http_build_query($queryParams);
                                                                }
                                                            }
                                                        ?>
                                                        <p class="close-button" onclick="location.href='AdvancedSearch.php?<?php echo $queryString;?>';" style="display: inline-block">&times;</p>
                                                    </div>
                                                </div>
                                                <div class="notes-body" style="padding: 10px; text-align: right;">
                                                    <?php
                                                        $getid = $_GET['id'];
                                                        $queryatt = "SELECT * FROM file WHERE file_id='$getid'";
                                                        $resultatt = mysqli_query($conn, $queryatt);
                                                        $rowatt = mysqli_fetch_array($resultatt);
                                                        
                                                        if(isset($rowatt['file_upload1']) && $rowatt['file_upload1'] !== ''){
                                                    ?>
                                                    <div class="attachment-row">
                                                        <p>المرفق (1) : </p>
                                                        <a href="<?php echo $rowatt['file_upload1'];?>" onClick="window.open('<?php echo $rowatt['file_upload1'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowatt['file_upload1']);?>
                                                        </a>
                                                        <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                        <div class="perms-check" onclick="location.href='fattachdel.php?id=<?php echo $rowatt['file_id'];?>&del=file_upload1&page=AdvancedSearch.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                        <?php }?>
                                                    </div>
                                                    <?php
                                                        }
                                                        if(isset($rowatt['file_upload2']) && $rowatt['file_upload2'] !== ''){
                                                    ?>
                                                    <div class="attachment-row">
                                                        <p>المرفق (2) : </p>
                                                        <a href="<?php echo $rowatt['file_upload2'];?>" onClick="window.open('<?php echo $rowatt['file_upload2'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowatt['file_upload2']);?>
                                                        </a>
                                                        <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                        <div class="perms-check" onclick="location.href='fattachdel.php?id=<?php echo $rowatt['file_id'];?>&del=file_upload2&page=AdvancedSearch.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                        <?php }?>
                                                    </div>
                                                    <?php
                                                        }
                                                        if(isset($rowatt['file_upload3']) && $rowatt['file_upload3'] !== ''){
                                                    ?>
                                                    <div class="attachment-row">
                                                        <p>المرفق (3) : </p>
                                                        <a href="<?php echo $rowatt['file_upload3'];?>" onClick="window.open('<?php echo $rowatt['file_upload3'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowatt['file_upload3']);?>
                                                        </a>
                                                        <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                        <div class="perms-check" onclick="location.href='fattachdel.php?id=<?php echo $rowatt['file_id'];?>&del=file_upload3&page=AdvancedSearch.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                        <?php }?>
                                                    </div>
                                                    <?php
                                                        }
                                                        if(isset($rowatt['file_upload4']) && $rowatt['file_upload4'] !== ''){
                                                    ?>
                                                    <div class="attachment-row">
                                                        <p>المرفق (4) : </p>
                                                        <a href="<?php echo $rowatt['file_upload4'];?>" onClick="window.open('<?php echo $rowatt['file_upload4'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowatt['file_upload4']);?>
                                                        </a>
                                                        <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                        <div class="perms-check" onclick="location.href='fattachdel.php?id=<?php echo $rowatt['file_id'];?>&del=file_upload4&page=AdvancedSearch.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                        <?php }?>
                                                    </div>
                                                    <?php
                                                        }
                                                        if(isset($rowatt['file_upload5']) && $rowatt['file_upload5'] !== ''){
                                                    ?>
                                                    <div class="attachment-row">
                                                        <p>المرفق (5) : </p>
                                                        <a href="<?php echo $rowatt['file_upload5'];?>" onClick="window.open('<?php echo $rowatt['file_upload5'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowatt['file_upload5']);?>
                                                        </a>
                                                        <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                        <div class="perms-check" onclick="location.href='fattachdel.php?id=<?php echo $rowatt['file_id'];?>&del=file_upload5&page=AdvancedSearch.php';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
                                                        <?php }?>
                                                    </div>
                                                    <?php
                                                        }
                                                        if(isset($rowatt['file_upload6']) && $rowatt['file_upload6'] !== ''){
                                                    ?>
                                                    <div class="attachment-row">
                                                        <p>المرفق (6) : </p>
                                                        <a href="<?php echo $rowatt['file_upload6'];?>" onClick="window.open('<?php echo $rowatt['file_upload6'];?>', '','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,dependent=no'); return false;">
                                                            <?php echo basename($rowatt['file_upload6']);?>
                                                        </a>
                                                        <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                        <div class="perms-check" onclick="location.href='fattachdel.php?id=<?php echo $rowatt['file_id'];?>&del=file_upload6&page=AdvancedSearch.php&<?php echo $_SERVER['QUERY_STRING'];?>';" style="background-image: url('img/recycle-bin.png'); cursor: pointer;"></div>
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
                                <form name="addform" method="post" action="fileadd.php" enctype="multipart/form-data">
                                <input type="hidden" name="page" value="AdvancedSearch.php">
                                <input type="hidden" name="queryString" value="<?php echo $_SERVER['QUERY_STRING'];?>">
                                <table class="info-table" id="myTable" style="width: 1950px">
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
                                            <th style="position: sticky; right: 40px; width: 50px;">رقم الملف</th>
                                            <th>الموضوع</th>
                                            <th>الموكل</th>
                                            <th>الخصم</th>
                                            <th>رقم القضية</th>
                                            <th>المحكمة</th>
                                            <th>عدد الجلسات</th>
                                            <th>ت. أخر جلسة</th>
                                            <th>اضافة مذكرة</th>
                                            <th>أعمال إدارية</th>
                                            <th>مدة العمل</th>
                                        </tr>
                                    </thead>
                                    
                                    <tbody id="table1">
                                        <?php
                                            $querycfcheck = "SELECT COUNT(*) as countfcheck FROM file WHERE $where ORDER BY file_id DESC";
                                            $resultcfcheck = mysqli_query($conn, $querycfcheck);
                                            $rowcfcheck = mysqli_fetch_array($resultcfcheck);
                                            
                                            $countcfcheck = $rowcfcheck['countcfcheck'];
                                            
                                            while($row=mysqli_fetch_array($result)){
                                                if (!empty($fidscheck)) {
                                                    $fidscheck_list = implode(',', array_map('intval', $fidscheck));
                                                    
                                                    for($i = 0 ; $i < $countcfcheck ; $i++){
                                                        list($checkthis, $ignorethese) = explode(",", $fidscheck_list);
                                                        if($checkthis === $row['file_id']){
                                                            $flag = '0';
                                                        }
                                                    }
                                                }
                                                
                                                if($flag === '0'){
                                                    continue;
                                                }
                                                $fidscheck[] = $row['file_id'];
                                        ?>
                                        <tr class="infotable-body">
                                            <td width="50px" class="options-td" style="background-color: #fff;">
                                                <i class='bx bx-dots-vertical-rounded bx-xs dropbtn' style="cursor: pointer;" onclick="toggleDropdown(event)"></i>
                                                <div class="dropdown">
                                                    <?php if($row_permcheck['cfiles_eperm'] == 1){?>
                                                    <button type="button" onclick="location.href='FileEdit.php?id=<?php echo $row['file_id'];?>';">تعديل</button>
                                                    <?php 
                                                        }
                                                        if($row_permcheck['cfiles_dperm'] == 1){
                                                    ?>
                                                    <button type="button" onclick="location.href='deletefile.php?id=<?php echo $row['file_id'];?>&page=AdvancedSearch.php&<?php echo $_SERVER['QUERY_STRING']?>';">حذف</button>
                                                    <?php }?>
                                                    <button type="button" onclick="location.href='AdvancedSearch.php?<?php if($_SERVER['QUERY_STRING'] !== ''){ echo $_SERVER['QUERY_STRING'].'&'; }?>attachments=1&id=<?php echo $row['file_id'];?>';">المرفقات</button>
                                                </div>
                                            </td>
                                            <td style="position: sticky; right: 0; width: 40px;"><input type="checkbox" name="CheckedD[]" class="user-checkbox" value="<?php echo $row['file_id'];?>"></td>
                                            <td style="color: #007bff; position: sticky; right: 40px; width: 50px;">
                                                <?php 
                                                    $place = $row['frelated_place'];
                                                    $fileid = $row['file_id'];
                                                    if($place === 'عجمان'){
                                                        echo 'AJM';
                                                    } elseif($place === 'دبي'){
                                                        echo 'DXB';
                                                    } elseif($place === 'الشارقة'){
                                                        echo 'SHJ';
                                                    }
                                                    
                                                    echo '<br>'.$fileid;
                                                ?>
                                            </td>
                                            <td><?php echo $row['file_subject'];?></td>
                                            <td>
                                                <?php 
                                                    if(isset($row['file_client']) && $row['file_client'] !== ''){ 
                                                        $cid1 = $row['file_client'];
                                                        $queryc1 = "SELECT * FROM client WHERE id='$cid1'";
                                                        $resultc1 = mysqli_query($conn, $queryc1);
                                                        $rowc1 = mysqli_fetch_array($resultc1);
                                                        echo $rowc1['arname'];
                                                        
                                                        if(isset($row['fclient_characteristic']) && $row['fclient_characteristic'] !== ''){ 
                                                            echo ' / ' . $row['fclient_characteristic'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_client2']) && $row['file_client2'] !== ''){ 
                                                        $cid2 = $row['file_client2'];
                                                        $queryc2 = "SELECT * FROM client WHERE id='$cid2'";
                                                        $resultc2 = mysqli_query($conn, $queryc2);
                                                        $rowc2 = mysqli_fetch_array($resultc2);
                                                        echo $rowc2['arname'];
                                                        
                                                        if(isset($row['fclient_characteristic2']) && $row['fclient_characteristic2'] !== ''){ 
                                                            echo ' / ' . $row['fclient_characteristic2'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_client3']) && $row['file_client3'] !== ''){ 
                                                        $cid3 = $row['file_client3'];
                                                        $queryc3 = "SELECT * FROM client WHERE id='$cid3'";
                                                        $resultc3 = mysqli_query($conn, $queryc3);
                                                        $rowc3 = mysqli_fetch_array($resultc3);
                                                        echo $rowc3['arname'];
                                                        
                                                        if(isset($row['fclient_characteristic3']) && $row['fclient_characteristic3'] !== ''){ 
                                                            echo ' / ' . $row['fclient_characteristic3'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_client4']) && $row['file_client4'] !== ''){ 
                                                        $cid4 = $row['file_client4'];
                                                        $queryc4 = "SELECT * FROM client WHERE id='$cid4'";
                                                        $resultc4 = mysqli_query($conn, $queryc4);
                                                        $rowc4 = mysqli_fetch_array($resultc4);
                                                        echo $rowc4['arname'];
                                                        
                                                        if(isset($row['fclient_characteristic4']) && $row['fclient_characteristic4'] !== ''){ 
                                                            echo ' / ' . $row['fclient_characteristic4'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_client5']) && $row['file_client5'] !== ''){ 
                                                        $cid5 = $row['file_client5'];
                                                        $queryc5 = "SELECT * FROM client WHERE id='$cid5'";
                                                        $resultc5 = mysqli_query($conn, $queryc5);
                                                        $rowc5 = mysqli_fetch_array($resultc5);
                                                        echo $rowc5['arname'];
                                                        
                                                        if(isset($row['fclient_characteristic5']) && $row['fclient_characteristic5'] !== ''){ 
                                                            echo ' / ' . $row['fclient_characteristic5'] . '<br>'; 
                                                        }
                                                    }
                                                ?> 
                                            </td>
                                            <td>
                                                <?php 
                                                    if(isset($row['file_opponent']) && $row['file_opponent'] !== ''){ 
                                                        $oid = $row['file_opponent'];
                                                        $queryo = "SELECT * FROM client WHERE id='$oid'";
                                                        $resulto = mysqli_query($conn, $queryo);
                                                        $rowo = mysqli_fetch_array($resulto);
                                                        echo $rowo['arname'];
                                                        
                                                        if(isset($row['fopponent_characteristic']) && $row['fopponent_characteristic'] !== ''){ 
                                                            echo ' / ' . $row['fopponent_characteristic'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_opponent2']) && $row['file_opponent2'] !== ''){ 
                                                        $oid2 = $row['file_opponent2'];
                                                        $queryo2 = "SELECT * FROM client WHERE id='$oid2'";
                                                        $resulto2 = mysqli_query($conn, $queryo2);
                                                        $rowo2 = mysqli_fetch_array($resulto2);
                                                        echo $rowo2['arname'];
                                                        
                                                        if(isset($row['fopponent_characteristic2']) && $row['fopponent_characteristic2'] !== ''){ 
                                                            echo ' / ' . $row['fopponent_characteristic2'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_opponent3']) && $row['file_opponent3'] !== ''){ 
                                                        $oid3 = $row['file_opponent3'];
                                                        $queryo3 = "SELECT * FROM client WHERE id='$oid3'";
                                                        $resulto3 = mysqli_query($conn, $queryo3);
                                                        $rowo3 = mysqli_fetch_array($resulto3);
                                                        echo $rowo3['arname'];
                                                        
                                                        if(isset($row['fopponent_characteristic3']) && $row['fopponent_characteristic3'] !== ''){ 
                                                            echo ' / ' . $row['fopponent_characteristic3'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_opponent4']) && $row['file_opponent4'] !== ''){ 
                                                        $oid4 = $row['file_opponent4'];
                                                        $queryo4 = "SELECT * FROM client WHERE id='$oid4'";
                                                        $resulto4 = mysqli_query($conn, $queryo4);
                                                        $rowo4 = mysqli_fetch_array($resulto4);
                                                        echo $rowo4['arname'];
                                                        
                                                        if(isset($row['fopponent_characteristic4']) && $row['fopponent_characteristic4'] !== ''){ 
                                                            echo ' / ' . $row['fopponent_characteristic4'] . '<br>'; 
                                                        }
                                                    }
                                                    
                                                    if(isset($row['file_opponent5']) && $row['file_opponent5'] !== ''){ 
                                                        $oid5 = $row['file_opponent5'];
                                                        $queryo5 = "SELECT * FROM client WHERE id='$oid5'";
                                                        $resulto5 = mysqli_query($conn, $queryo5);
                                                        $rowo5 = mysqli_fetch_array($resulto5);
                                                        echo $rowo5['arname'];
                                                        
                                                        if(isset($row['fopponent_characteristic5']) && $row['fopponent_characteristic5'] !== ''){ 
                                                            echo ' / ' . $row['fopponent_characteristic5'] . '<br>'; 
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $fiddeg = $row['file_id'];         
                                                    $query_degs = "SELECT * FROM file_degrees WHERE fid='$fiddeg' ORDER BY created_at DESC";
                                                    $result_degs = mysqli_query($conn, $query_degs);
                                                    if($result_degs->num_rows > 0){
                                                        $row_degs = mysqli_fetch_array($result_degs);
                                                        if(isset($row_degs['fid']) && $row_degs['fid'] !== ''){
                                                            echo $row_degs['case_num'] . '/' . $row_degs['file_year'] . '-' . $row_degs['degree'];
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $row['file_court'];?></td>
                                            <td>
                                                <?php 
                                                    $fiiid = $row['file_id'];
                                                    $query_scount = "SELECT COUNT(*) as sessions_count FROM session WHERE session_fid='$fiiid' AND session_degree!='تنفيذ'";
                                                    $result_scount = mysqli_query($conn, $query_scount);
                                                    $row_scount = mysqli_fetch_array($result_scount);
                                                    $sessions_count = $row_scount['sessions_count'];
                                                    echo $sessions_count;
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $fidsee = $row['file_id'];
                                                    $querysee = "SELECT * FROM session WHERE session_fid='$fidsee' ORDER BY created_at DESC";
                                                    $resultsee = mysqli_query($conn,$querysee);
                                                    if($resultsee->num_rows > 0){
                                                        $rowsee = mysqli_fetch_array($resultsee);
                                                        echo $rowsee['session_date'];
                                                    } else{
                                                        echo '-';
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $thisid = $row['file_id'];
                                                    $query_docs = "SELECT * FROM case_document WHERE dfile_no = $thisid";
                                                    $result_docs = mysqli_query($conn,$query_docs);
                                                    $i = 0;
                                                    while($row3=mysqli_fetch_array($result_docs)){
                                                            $i++;
                                                        }
                                                    echo $i;
                                                ?>
                                                <img src="img/add-document.png" width="21" height="27" align="absmiddle"  style="cursor:pointer"  title="مذكرة" onclick="open('AddNotes.php?fno=<?php $id=$row['file_id']; echo $id;?>','Pic','width=800 height=800 scrollbars=yes')"/> 
                                            </td>
                                            <td>
                                                <?php
                                                    if(isset($row['file_id']) && $row['file_id'] !== ''){
                                                        $fiddds = $row['file_id'];
                                                        $query_tno = "SELECT COUNT(*) as tcount FROM tasks WHERE file_no='$fiddds'";
                                                        $result_tno = mysqli_query($conn, $query_tno);
                                                        $row_tno = mysqli_fetch_array($result_tno);
                                                        $tno = $row_tno['tcount'];
                                                        
                                                        echo $tno;
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $row['duration'].'<br>'.$row['done_by'];?></td>
                                        </tr>
                                        <?php }?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="table-footer" style="padding-bottom: 50px;">
                                <?php if($row_permcheck['cfiles_dperm'] == 1){?>
                                <input name="button2" type="submit" value="حذف" class="delete-selected" >
                                <?php } else{ echo '<p></p>'; }?>
                                <div id="pagination"></div>
                                <div id="pageInfo"></div>
                            </form>
                        </div>
                        <?php }}?>
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