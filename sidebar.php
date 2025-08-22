<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    
    $current_page = basename($_SERVER['PHP_SELF']);
    $current_page = strtolower(strtok($current_page, '?'));
    
    $userID = $_SESSION['id'];
    
    $stmt_checkno = $conn->prepare("SELECT COUNT(*) as count_no FROM tasks WHERE employee_id=? AND task_status!=?");
    $task_status = '2';
    $stmt_checkno->bind_param("is", $userID, $task_status);
    $stmt_checkno->execute();
    $result_checkno = $stmt_checkno->get_result();
    $row_checkno = $result_checkno->fetch_array();
    $tasks_no = $row_checkno['count_no'];
    $stmt_checkno->close();
    
    $stmtuserid = $conn->prepare("SELECT * FROM user WHERE id=?");
    $stmtuserid->bind_param("i", $userID);
    $stmtuserid->execute();
    $resultuserid = $stmtuserid->get_result();
    $rowuserid = $resultuserid->fetch_array();
    $stmtuserid->close();
    
    if($current_page !== 'case.php'){
        include_once 'golden_pass.php';
    }
    
    if(isset($rowmain['job_title'])) {
        $job_id = $rowmain['job_title'];
        $stmtmain_position = $conn->prepare("SELECT * FROM positions WHERE id=?");
        $stmtmain_position->bind_param("i", $job_id);
        $stmtmain_position->execute();
        $resultmain_position = $stmtmain_position->get_result();
        $rowmain_position = $resultmain_position->fetch_array();
        $stmtmain_position->close();
    }
    
    $stmtcountmytasks = $conn->prepare("SELECT COUNT(*) as countmytasks FROM tasks WHERE employee_id=? AND task_status!=?");
    $stmtcountmytasks->bind_param("is", $myid, $task_status);
    $stmtcountmytasks->execute();
    $resultcountmytasks = $stmtcountmytasks->get_result();
    $rowcountmytasks = $resultcountmytasks->fetch_array();
    $stmtcountmytasks->close();
    
    $stmtcountvacs1 = $conn->prepare("SELECT COUNT(*) as countvacs1 FROM vacations WHERE ask=?");
    $ask_status = '1';
    $stmtcountvacs1->bind_param("s", $ask_status);
    $stmtcountvacs1->execute();
    $resultcountvacs1 = $stmtcountvacs1->get_result();
    $rowcountvacs1 = $resultcountvacs1->fetch_array();
    $stmtcountvacs1->close();
    
    $stmtcountvacs2 = $conn->prepare("SELECT COUNT(*) as countvacs2 FROM vacations WHERE ask2=?");
    $ask2_status = '1';
    $stmtcountvacs2->bind_param("s", $ask2_status);
    $stmtcountvacs2->execute();
    $resultcountvacs2 = $stmtcountvacs2->get_result();
    $rowcountvacs2 = $resultcountvacs2->fetch_array();
    $stmtcountvacs2->close();
?>
<div class="small-menu">
    <div></div>
    <?php if($current_page !== 'mbhemps.php'){?>
    <div style="cursor: pointer" onclick="toggleSidebar()"><i class='bx bx-menu bx-md'></i></div>
    <?php }?>
</div>
<div class="<?php if($current_page === 'index.php'){ echo 'sidebar-menus'; } else { echo 'sidebar-fullmenu'; }?>" <?php if($current_page !== 'index.php'){?>class="padding-top-20px"<?php }?> id="display-sidebar">
    <div class="display-none"></div>
    <div style="position: absolute; margin-right: 300px; width: calc(100% - 300px); height: 100vh; cursor: pointer" onclick="toggleSidebar()"></div>
    <?php if($current_page === 'index.php'){?>
    <div class="user-profile">
        <div class="profile-card">
            <p class="small-parag" id="userinfo-translate">بيانات المستخدم</p>
            <div class="person">
                <?php if(isset($rowmain['personal_image']) && $rowmain['personal_image'] !== ''){?>
                <div class="profile-image" style="background-image: url('<?php echo safe_output($rowmain['personal_image']);?>');"></div>
                <?php } else{?>
                <div class="rounded-noprofimg">
                    <?php
                        $name = trim($rowmain['name']);
                        $nameParts = explode(" ", $name);
                        
                        $firstname = $nameParts[0] ?? '';
                        $lastname = $nameParts[1] ?? '';
                        
                        $firstLetterFirst = mb_substr($firstname, 0, 1, "UTF-8");
                        $firstLetterLast = mb_substr($lastname, 0, 1, "UTF-8");
                        
                        $arabicToLatin = [
                            'ا' => 'A', 'أ' => 'A', 'إ' => 'A', 'آ' => 'A',
                            'ب' => 'B',
                            'ت' => 'T',
                            'ث' => 'T',
                            'ج' => 'J',
                            'ح' => 'H',
                            'خ' => 'K',
                            'د' => 'D',
                            'ذ' => 'D',
                            'ر' => 'R',
                            'ز' => 'Z',
                            'س' => 'S',
                            'ش' => 'S',
                            'ص' => 'S',
                            'ض' => 'D',
                            'ط' => 'T',
                            'ظ' => 'Z',
                            'ع' => 'A',
                            'غ' => 'G',
                            'ف' => 'F',
                            'ق' => 'Q',
                            'ك' => 'K',
                            'ل' => 'L',
                            'م' => 'M',
                            'ن' => 'N',
                            'ه' => 'H',
                            'و' => 'W',
                            'ي' => 'Y',
                        ];
                        
                        $convertedFirst = $arabicToLatin[$firstLetterFirst] ?? strtoupper($firstLetterFirst);
                        $convertedLast = $arabicToLatin[$firstLetterLast] ?? strtoupper($firstLetterLast);
                        
                        echo safe_output($convertedFirst.$convertedLast);
                    ?>
                </div>
                <?php }?>
                <div class="personal">
                    <p class="small-parag"><?php echo $rowmain['name'];?></p>
                    <p class="very-small-parag">
                        <?php
                            echo $rowmain_position['position_name'];
                        ?>
                    </p><br>
                    <div class="stars"><i class='bx bx-star' ></i><i class='bx bxs-star-half' ></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i><i class='bx bxs-star'></i></div>
                </div>
            </div>
            <div class="personal-info">
                <p>
                    <i class='bx bx-envelope' ></i>
                    <font class="very-small-parag"> 
                        <?php 
                            $email = $rowmain['email'];
                            $decrypted_email = openssl_decrypt($email, $cipher, $key, $options, $iv);
                            echo $decrypted_email;
                        ?>
                    </font>
                </p>
                <p>
                    <i class='bx bxs-phone-call' ></i>
                    <font class="very-small-parag"> 
                        <?php 
                            $tel1 = $rowmain['tel1'];
                            $decrypted_tel1 = openssl_decrypt($tel1, $cipher, $key, $options, $iv);
                            echo $decrypted_tel1;
                        ?>
                    </font>
                </p>
                <p>
                    <i class='bx bxs-map-pin' ></i>
                    <font class="very-small-parag"> 
                        <?php 
                            $address = $rowmain['address'];
                            $decrypted_address = openssl_decrypt($address, $cipher, $key, $options, $iv);
                            echo $decrypted_address;
                        ?>
                    </font>
                </p>
            </div>
            <div class="more-info"><button type="button" onclick="location.href='UserProfile.php';" class="green-button" id="info-translate">المزيد من المعلومات</button></div>
        </div>
    </div>
    <?php }?>
    <div class="sidebar" <?php if($current_page !== 'index.php'){?>class="padding-top-40px"<?php }?>>
        <div class="sbp-container" id="BSTD" onclick="collapseSection('BS', 'BSTD')"><i class='bx bxs-home bx-xs'></i> القائمة الرئيسية</div>
        <div id="BS" class="sidebar-container" style="display:none">
            <ul>
                <li onclick="openOrFocusTab('index.php');" <?php if($current_page == 'index.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-home bx-xs'></i> <font id="home-translate"> الصفحة الرئيسية</font>
                    </div>
                </li>
            </ul>
        </div><br>
        
        <div class="sbp-container" id="CliTD" onclick="collapseSection('Cli', 'CliTD')"><i class='bx bxs-contact bx-xs' ></i> <font id="clients-translate"> الموكلين</font></div>
        <div id="Cli" class="sidebar-container" style="display:none;">
            <ul>
                <?php
                    if($rowuserid['clients_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('clients.php');" <?php if($current_page == 'clients.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-id-card bx-xd' ></i> <font id="clientsopponents-translate"> الموكلين / الخصوم</font>
                    </div>
                </li>
                <?php }?>
                <li onclick="openOrFocusTab('clientsrequests.php');" <?php if($current_page == 'clientsrequests.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-user-detail' ></i> <font id="clientsrequests-translate"> طلبات الموكلين</font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('client_login.php');">
                    <div class="sidebar-a">
                        <i class='bx bx-log-in-circle' ></i> <font id="clientslogin-translate"> صفحة دخول الموكلين</font>
                    </div>
                </li>
            </ul>
        </div><br>
        
        <?php if($rowuserid['cfiles_aperm'] == 1 || $rowuserid['cfiles_rperm'] == 1 || $rowuserid['sessionrole_rperm'] == 1 || $rowuserid['cfiles_eperm'] == 1){?>
        <div class="sbp-container" id="CaseTD" onclick="collapseSection('Case', 'CaseTD')"><i class='bx bxs-briefcase bx-xs' ></i> <font id="casesandfolders-translate"> الملفات و القضايا</font></div>
        <div id="Case" class="sidebar-container" style="display:none;">
            <ul>
                <?php
                    if($rowuserid['cfiles_aperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('addcase.php');" <?php if($current_page == 'addcase.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-folder-plus' ></i> <font id="addfolder-translate"> اضافة ملف</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['cfiles_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('cases.php');" <?php if($current_page == 'cases.php' || $current_page == 'fileedit.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-folder-open' ></i> <font id="folders-translate"> استعلام الملفات</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['sessionrole_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('hearing.php?tw=1');" <?php if($current_page == 'hearing.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-search-alt-2' ></i> <font id="hearingroll-translate"> رول الجلسات</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['sessionrole_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('judgedHearings.php?tw=1');" <?php if($current_page == 'judgedhearings.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-search-alt-2' ></i> <font id="hearingroll-translate">الاحكام الصادرة</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['cfiles_eperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('fileconverter.php');" <?php if($current_page == 'fileconverter.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-folder'></i> <font id="foldertypeconverter-translate"> تحويل نوع الملف</font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php
            }
            if($rowuserid['call_rperm'] == 1 || $rowuserid['csched_rperm'] == 1 || $rowuserid['goaml_rperm'] == 1 || $rowuserid['terror_rperm'] == 1 || $rowuserid['cons_rperm'] == 1 || $rowuserid['agr_rperm'] == 1){
        ?>
        <div class="sbp-container" id="SecrTD" onClick="collapseSection('Secr', 'SecrTD')"><i class='bx bxs-user-pin' ></i> <font id="secretarial-translate"> ادارة الموكلين</font></div>
        <div id="Secr" class="sidebar-container" style="display:none;">
            <ul>
                <?php
                    $stmtbranch = $conn->prepare("SELECT * FROM user WHERE id=?");
                    $stmtbranch->bind_param("i", $_SESSION['id']);
                    $stmtbranch->execute();
                    $resultbranch = $stmtbranch->get_result();
                    $rowbranch = $resultbranch->fetch_assoc();
                    $stmtbranch->close();
                    $branch = $rowbranch['work_place'];
                    if($rowuserid['call_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('clientsCalls.php?branch=<?php echo $branch;?>');" <?php if($current_page == 'clientscalls.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-phone-incoming' ></i> <font id="callslog-translate"> سجل المكالمات الهاتفية</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['csched_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('meetings.php');" <?php if($current_page == 'meetings.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-time-five' ></i> <font id="clientsappointments-translate"> المواعيد و الاجتماعات</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['cons_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('consultations.php?branch=<?php echo $branch;?>');" <?php if($current_page == 'consultations.php' || $current_page == 'consultationedit.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-user-detail' ></i> <font id="legalconsultations-translate"> العملاء المحتملين</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['goaml_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('goAMList.php');" <?php if($current_page == 'goamlist.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-notepad' ></i> <font id="terrorismlist-translate"> كشف قائمة الارهاب المحلية goAML</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['cons_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('Terrors.php');" <?php if($current_page == 'terrors.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-notepad' ></i> <font id="terrorismlist-translate"> قائمة الارهاب</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['agr_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('Agreements.php');" <?php if($current_page == 'agreements.php' || $current_page == 'agreementedit.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-check' ></i> <font id="agreements-translate"> الاتفاقيات</font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php 
            }
            if($rowuserid['admjobs_aperm'] == 1 || $rowuserid['admjobs_rperm'] == 1 || $rowuserid['admprivjobs_rperm'] == 1){
        ?>
        <div class="sbp-container" id="BusiTD" onClick="collapseSection('Busi', 'BusiTD')"><i class='bx bx-book-content' ></i> <font id="tasks-translate"> المهام</font></div>
        <div id="Busi" class="sidebar-container" style="display:none;">
            <ul>
                <?php
                    if($rowuserid['admjobs_aperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('Tasks.php');" <?php if($current_page == 'tasks.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-task' ></i> <font id="tasks-translate"> اصافة مهام</font>
                    </div>
                </li>
                <?php 
                    }
                    if($rowuserid['admprivjobs_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('mytasks.php');" <?php if($current_page == 'mytasks.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-briefcase-alt-2' ></i> <font id="mytasks-translate"> مهام موكلة</font>
                        <font style="color:red">( 
                            <?php
                                echo $rowcountmytasks['countmytasks'];
                            ?>
                        )</font>
                    
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php
            }
            if($rowuserid['accfinance_rperm'] == 1 || $rowuserid['accsecterms_rperm'] == 1 || $rowuserid['accbankaccs_rperm'] == 1 || $rowuserid['acccasecost_rperm'] == 1 || $rowuserid['accrevenues_rperm'] == 1 || $rowuserid['accexpenses_rperm'] == 1){
        ?>
        <div class="sbp-container" id="AccTD" onClick="collapseSection('Acc', 'AccTD')"><i class='bx bx-laptop' ></i> <font id="accounting-translate"> الحسابات</font></div>
        <div id="Acc" class="sidebar-container" style="display:none;">
            <ul>
                <?php
                    if($rowuserid['accfinance_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('Finance.php');" <?php if($current_page == 'finance.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-credit-card-alt' ></i> <font id="finance-translate"> قسم المالية</font>
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['accsecterms_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('SubCategory.php');" <?php if($current_page == 'subcategory.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-list-ul' ></i> <font id="subclause-translate"> البنود الفرعية</font>
                    </div>
                </li>
                <?php 
                    }
                    if($rowuserid['accbankaccs_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('BankAccs.php');" <?php if($current_page == 'bankaccs.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-bank' ></i> <font id="bankaccounts-translate"> حسابات البنوك</font>
                    </div>
                </li>
                <?php 
                    }
                    if($rowuserid['acccasecost_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('CasesFees.php');" <?php if($current_page == 'casesfees.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-wallet-alt' ></i> <font id="casesfees-translate"> اتعاب القضايا</font>
                    </div>
                </li>
                <?php 
                    }
                    if($rowuserid['accrevenues_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('income.php');" <?php if($current_page == 'income.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-money-withdraw' ></i> <font id="revenues-translate"> الايرادات</font>
                    </div>
                </li>
                <?php 
                    }
                    if($rowuserid['accexpenses_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('expenses.php');" <?php if($current_page == 'expenses.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-money' ></i> <font id="expenses-translate"> المصروفات</font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php }?>
        <div class="sbp-container" id="HRTD" onClick="collapseSection('HR', 'HRTD')"><i class='bx bx-street-view' ></i> <font id="humanresources-translate"> الموارد البشرية</font></div>
        <div id="HR" class="sidebar-container" style="display:none;">
            <ul>
                <li onclick="toggle_usermanagement()" <?php if($current_page == 'attendance.php' || $current_page == 'mbhemps.php' || $current_page == 'vacationreqs.php' || $current_page == 'vacationreqs2.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-history' ></i> <font id="logs-translate"> ادارة الموظفين</font>
                    </div>
                </li>
                <?php if($rowuserid['userattendance_rperm'] == 1){?>
                <li style="margin-right: 20px; margin-top: 10px; display: none;" id="users_management1" onclick="openOrFocusTab('attendance.php');" <?php if($current_page == 'attendance.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-history' ></i> <font id="logs-translate"> سجل الحضور</font>
                    </div>
                </li>
                <?php }?>
                <li style="margin-right: 20px; display: none;" id="users_management2" onclick="openOrFocusTab('mbhEmps.php?section=users');" <?php if($current_page == 'mbhemps.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-user-check' ></i> <font id="employeespermissions-translate">بيانات الموظفين</font>
                    </div>
                </li>
                <?php if($rowuserid['vacf_aperm'] == 1){?>
                <li style="margin-right: 20px; display: none;" id="users_management3" onclick="openOrFocusTab('vacationReqs.php');" <?php if($current_page == 'vacationreqs.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-plane-alt' ></i>
                        <font id="vacationrequests-translate"> طلبات الاجازات</font>
                        <font style="color:red">
                            ( <?php
                                echo $rowcountvacs1['countvacs1'];
                            ?> )
                        </font>
                    
                    </div>
                </li>
                <?php
                    }
                    if($rowuserid['vacl_aperm'] == 1){
                ?>
                <li style="margin-right: 20px; margin-bottom: 10px; display: none;" id="users_management4" onclick="openOrFocusTab('vacationReqs2.php');" <?php if($current_page == 'vacationreqs2.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-calendar-check' ></i>
                        <font id="vacationagreement-translate"> القبول النهائي للاجازات</font>
                        <font style="color:red">
                            ( <?php
                                echo $rowcountvacs2['countvacs2'];
                            ?> )
                        </font>
                    
                    </div>
                </li>
                <?php }?>
                <li onclick="openOrFocusTab('empsNotifications.php');" <?php if($current_page == 'empsnotifications.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-user-check' ></i> <font id="employeespermissions-translate">
                            التنبيهات
                            <?php
                                $today = date("Y-m-d");
                                
                                $stmt_notno = $conn->prepare("SELECT id, dob, residence_exp, representative_exp, dxblaw_exp, shjlaw_exp, ajmlaw_exp, abdlaw_exp, passport_exp, id_exp, cardno_exp, sigorta_exp FROM user");
                                $stmt_notno->execute();
                                $result_notno = $stmt_notno->get_result();
                                
                                $count = 0;
                                
                                while ($row_notno = $result_notno->fetch_assoc()) {
                                    $fields = [
                                        'dob',
                                        'residence_exp',
                                        'representative_exp',
                                        'dxblaw_exp',
                                        'shjlaw_exp',
                                        'ajmlaw_exp',
                                        'abdlaw_exp',
                                        'passport_exp',
                                        'id_exp',
                                        'cardno_exp',
                                        'sigorta_exp'
                                    ];
                                    
                                    foreach ($fields as $field) {
                                        $exp_date = $row_notno[$field];
                                        
                                        if (!empty($exp_date) && strtotime($exp_date)) {
                                            $days_left = (strtotime($exp_date) - strtotime($today)) / (60 * 60 * 24);
                                            
                                            if ($days_left <= 10 && $days_left >= -10) {
                                                $count++;
                                            }
                                        }
                                    }
                                }
                                $stmt_notno->close();
                                
                                echo "<font color='#ff0000'>( $count )</font>";
                            ?>
                        </font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('Calendar.php');" <?php if($current_page == 'calendar.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-user-check' ></i> <font id="employeespermissions-translate">المواعيد</font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('Office_Vehicles.php');" <?php if($current_page == 'office_vehicles.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-car' ></i> <font id="resourcesandvehicles-translate"> الموارد و المركبات</font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('Contracts.php');" <?php if($current_page == 'contracts.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-book-content' ></i> <font id="leasecontractsandcommerciallicenses-translate"> عقود الايجار و الرخص التجارية</font>
                    </div>
                </li>
                <?php
                    if($rowuserid['logs_rperm'] == 1){
                ?>
                <li onclick="openOrFocusTab('Logs.php');" <?php if($current_page == 'logs.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-history' ></i> <font id="logs-translate"> سجلات البرنامج</font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        
        <button onclick="location.href='logout.php';" class="green-button display-none-default" style="width: 100%; padding: 10px"><i class='bx bx-log-out bx-xs'></i> تسجيل الخروج</button>
    </div>
</div>

<script>
    function collapseSection(sectionId, headerId) {
        var section = document.getElementById(sectionId);
        if (section.style.display === "block") {
            section.style.display = "none";
        } else {
            section.style.display = "block";
        }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        var currentPage = '<?php echo $current_page; ?>';
        
        if (currentPage === 'index.php' || currentPage === 'searchresult.php' || currentPage === 'change_userpassword.php') {
            document.getElementById('BS').style.display = 'block';
        }
        
        if (currentPage === 'clients.php'|| currentPage === 'clientsrequests.php') {
            document.getElementById('Cli').style.display = 'block';
        }
        
        if(currentPage === 'addcase.php' || currentPage === 'cases.php' || currentPage === 'judgedhearings.php' || currentPage === 'archivedfile.php' || currentPage === 'fileconverter.php' || currentPage === 'relatedfiles.php' || currentPage === 'hearing.php' || currentPage === 'fileedit.php'){
            document.getElementById('Case').style.display = 'block';
        }
        
        if( currentPage === 'clientscalls.php' || currentPage === 'callsedit.php' || currentPage === 'meetings.php' || currentPage === 'scheduleadd.php' || currentPage === 'terrors.php' || currentPage === 'terror.php' || currentPage === 'goamlist.php' || currentPage === 'consultations.php' || currentPage === 'consultationedit.php' || currentPage === 'agreements.php' || currentPage === 'agreementedit.php'){
            document.getElementById('Secr').style.display = 'block';
        }
        
        if(currentPage === 'tasks.php' || currentPage === 'mytasks.php'){
            document.getElementById('Busi').style.display = 'block';
        }
        
        if(currentPage === 'accounts.php' || currentPage === 'finance.php' || currentPage === 'subcategory.php' || currentPage === 'bankaccs.php' || currentPage === 'casesfees.php' || currentPage === 'income.php' || currentPage === 'expenses.php' || currentPage === 'pettycash.php'){
            document.getElementById('Acc').style.display = 'block';
        }
        
        if (currentPage === 'mbhemps.php' || currentPage === 'attendance.php' || currentPage === 'empsnotifications.php' || currentPage === 'calendar.php' || currentPage === 'office_vehicles.php' || currentPage === 'contracts.php' || currentPage === 'lawyers.php' || currentPage === 'logs.php' || currentPage === 'vacationreqs.php' || currentPage === 'vacationreqs2.php') {
            document.getElementById('HR').style.display = 'block';
        }
    });
</script>

<script>
    function toggle_usermanagement(){
        const users_management1 = document.getElementById("users_management1");
        const users_management2 = document.getElementById("users_management2");
        const users_management3 = document.getElementById("users_management3");
        const users_management4 = document.getElementById("users_management4");
        
        if(users_management1.style.display === "none" || users_management1.style.display === "") {
            users_management1.style.display = "block";
        } else {
            users_management1.style.display = "none";
        }
        
        if(users_management2.style.display === "none" || users_management2.style.display === "") {
            users_management2.style.display = "block";
        } else {
            users_management2.style.display = "none";
        }
        
        if(users_management3.style.display === "none" || users_management3.style.display === "") {
            users_management3.style.display = "block";
        } else {
            users_management3.style.display = "none";
        }
        
        if(users_management4.style.display === "none" || users_management4.style.display === "") {
            users_management4.style.display = "block";
        } else {
            users_management4.style.display = "none";
        }
    }
</script>

<script>
    function toggleDiv(id) {
        const div = document.getElementById("display-column-"+id);
        if (div.style.display === "none" || div.style.display === "") {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    }
    function toggleSidebar() {
        const div = document.getElementById("display-sidebar");
        if (div.style.display === "none" || div.style.display === "") {
            div.style.display = "grid";
        } else {
            div.style.display = "none";
        }
    }
</script>

<script>
    const openTabs = {};

function openOrFocusTab(pageUrl) {
    const windowName = btoa(pageUrl);
    if (openTabs[windowName] && !openTabs[windowName].closed) {
        openTabs[windowName].focus();
    } else {
        const popup = window.open(pageUrl, windowName, 'resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=yes,width=800,height=800');
        if (popup) {
            openTabs[windowName] = popup;
            popup.focus();
        } else {
            alert('Popup blocked! Please allow popups for this site.');
        }
    }
}
</script>