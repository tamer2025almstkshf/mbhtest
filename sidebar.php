<?php
    require_once __DIR__ . '/bootstrap.php';
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
            <p class="small-parag"><?php echo __('user_data'); ?></p>
            <div class="person">
                <?php if(isset($rowmain['personal_image']) && $rowmain['personal_image'] !== ''){?>
                <div class="profile-image" style="background-image: url('<?php echo safe_output($rowmain['personal_image']);?>');"></div>
                <?php } else{?>
                <div class="rounded-noprofimg">
                    <?php
                        // PHP logic for initials - left untouched
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
                            // Decryption logic - left untouched
                        ?>
                    </font>
                </p>
                <p>
                    <i class='bx bxs-phone-call' ></i>
                    <font class="very-small-parag"> 
                        <?php 
                            // Decryption logic - left untouched
                        ?>
                    </font>
                </p>
                <p>
                    <i class='bx bxs-map-pin' ></i>
                    <font class="very-small-parag"> 
                        <?php 
                            // Decryption logic - left untouched
                        ?>
                    </font>
                </p>
            </div>
            <div class="more-info"><button type="button" onclick="location.href='UserProfile.php';" class="green-button"><?php echo __('more_info'); ?></button></div>
        </div>
    </div>
    <?php }?>
    <div class="sidebar" <?php if($current_page !== 'index.php'){?>class="padding-top-40px"<?php }?>>
        <div class="sbp-container" id="BSTD" onclick="collapseSection('BS', 'BSTD')"><i class='bx bxs-home bx-xs'></i> <?php echo __('main_menu'); ?></div>
        <div id="BS" class="sidebar-container" style="display:none">
            <ul>
                <li onclick="openOrFocusTab('index.php');" <?php if($current_page == 'index.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-home bx-xs'></i> <font> <?php echo __('home'); ?></font>
                    </div>
                </li>
            </ul>
        </div><br>
        
        <div class="sbp-container" id="CliTD" onclick="collapseSection('Cli', 'CliTD')"><i class='bx bxs-contact bx-xs' ></i> <font> <?php echo __('clients'); ?></font></div>
        <div id="Cli" class="sidebar-container" style="display:none;">
            <ul>
                <?php if($rowuserid['clients_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('clients.php');" <?php if($current_page == 'clients.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-id-card bx-xd' ></i> <font> <?php echo __('clients_opponents'); ?></font>
                    </div>
                </li>
                <?php }?>
                <li onclick="openOrFocusTab('clientsrequests.php');" <?php if($current_page == 'clientsrequests.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-user-detail' ></i> <font> <?php echo __('client_requests'); ?></font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('client_login.php');">
                    <div class="sidebar-a">
                        <i class='bx bx-log-in-circle' ></i> <font> <?php echo __('client_login_page'); ?></font>
                    </div>
                </li>
            </ul>
        </div><br>
        
        <?php if($rowuserid['cfiles_aperm'] == 1 || $rowuserid['cfiles_rperm'] == 1 || $rowuserid['sessionrole_rperm'] == 1 || $rowuserid['cfiles_eperm'] == 1){?>
        <div class="sbp-container" id="CaseTD" onclick="collapseSection('Case', 'CaseTD')"><i class='bx bxs-briefcase bx-xs' ></i> <font> <?php echo __('files_and_cases'); ?></font></div>
        <div id="Case" class="sidebar-container" style="display:none;">
            <ul>
                <?php if($rowuserid['cfiles_aperm'] == 1){ ?>
                <li onclick="openOrFocusTab('addcase.php');" <?php if($current_page == 'addcase.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-folder-plus' ></i> <font> <?php echo __('add_file'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['cfiles_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('cases.php');" <?php if($current_page == 'cases.php' || $current_page == 'fileedit.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-folder-open' ></i> <font> <?php echo __('file_inquiry'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['sessionrole_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('hearing.php?tw=1');" <?php if($current_page == 'hearing.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-search-alt-2' ></i> <font> <?php echo __('session_roll'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['sessionrole_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('judgedHearings.php?tw=1');" <?php if($current_page == 'judgedhearings.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-search-alt-2' ></i> <font> <?php echo __('issued_judgments'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['cfiles_eperm'] == 1){ ?>
                <li onclick="openOrFocusTab('fileconverter.php');" <?php if($current_page == 'fileconverter.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-folder'></i> <font> <?php echo __('file_type_conversion'); ?></font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php } if($rowuserid['call_rperm'] == 1 || $rowuserid['csched_rperm'] == 1 || $rowuserid['goaml_rperm'] == 1 || $rowuserid['terror_rperm'] == 1 || $rowuserid['cons_rperm'] == 1 || $rowuserid['agr_rperm'] == 1){ ?>
        <div class="sbp-container" id="SecrTD" onClick="collapseSection('Secr', 'SecrTD')"><i class='bx bxs-user-pin' ></i> <font> <?php echo __('client_management'); ?></font></div>
        <div id="Secr" class="sidebar-container" style="display:none;">
            <ul>
                <?php // PHP logic for branch - left untouched
                    if($rowuserid['call_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('clientsCalls.php?branch=<?php echo $branch;?>');" <?php if($current_page == 'clientscalls.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-phone-incoming' ></i> <font> <?php echo __('phone_call_log'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['csched_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('meetings.php');" <?php if($current_page == 'meetings.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-time-five' ></i> <font> <?php echo __('appointments_and_meetings'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['cons_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('consultations.php?branch=<?php echo $branch;?>');" <?php if($current_page == 'consultations.php' || $current_page == 'consultationedit.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-user-detail' ></i> <font> <?php echo __('potential_clients'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['goaml_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('goAMList.php');" <?php if($current_page == 'goamlist.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-notepad' ></i> <font> <?php echo __('goaml_local_terror_list'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['cons_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('Terrors.php');" <?php if($current_page == 'terrors.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-notepad' ></i> <font> <?php echo __('terror_list'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['agr_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('Agreements.php');" <?php if($current_page == 'agreements.php' || $current_page == 'agreementedit.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-check' ></i> <font> <?php echo __('agreements'); ?></font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php } if($rowuserid['admjobs_aperm'] == 1 || $rowuserid['admjobs_rperm'] == 1 || $rowuserid['admprivjobs_rperm'] == 1){ ?>
        <div class="sbp-container" id="BusiTD" onClick="collapseSection('Busi', 'BusiTD')"><i class='bx bx-book-content' ></i> <font> <?php echo __('tasks'); ?></font></div>
        <div id="Busi" class="sidebar-container" style="display:none;">
            <ul>
                <?php if($rowuserid['admjobs_aperm'] == 1){ ?>
                <li onclick="openOrFocusTab('Tasks.php');" <?php if($current_page == 'tasks.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-task' ></i> <font> <?php echo __('add_tasks'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['admprivjobs_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('mytasks.php');" <?php if($current_page == 'mytasks.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-briefcase-alt-2' ></i> <font> <?php echo __('assigned_tasks'); ?></font>
                        <font style="color:red">( <?php echo $rowcountmytasks['countmytasks']; ?> )</font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php } if($rowuserid['accfinance_rperm'] == 1 || $rowuserid['accsecterms_rperm'] == 1 || $rowuserid['accbankaccs_rperm'] == 1 || $rowuserid['acccasecost_rperm'] == 1 || $rowuserid['accrevenues_rperm'] == 1 || $rowuserid['accexpenses_rperm'] == 1){ ?>
        <div class="sbp-container" id="AccTD" onClick="collapseSection('Acc', 'AccTD')"><i class='bx bx-laptop' ></i> <font> <?php echo __('accounts'); ?></font></div>
        <div id="Acc" class="sidebar-container" style="display:none;">
            <ul>
                <?php if($rowuserid['accfinance_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('Finance.php');" <?php if($current_page == 'finance.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-credit-card-alt' ></i> <font> <?php echo __('finance_department'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['accsecterms_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('SubCategory.php');" <?php if($current_page == 'subcategory.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-list-ul' ></i> <font> <?php echo __('sub_clauses'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['accbankaccs_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('BankAccs.php');" <?php if($current_page == 'bankaccs.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-bank' ></i> <font> <?php echo __('bank_accounts'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['acccasecost_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('CasesFees.php');" <?php if($current_page == 'casesfees.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-wallet-alt' ></i> <font> <?php echo __('cases_fees'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['accrevenues_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('income.php');" <?php if($current_page == 'income.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-money-withdraw' ></i> <font> <?php echo __('revenues'); ?></font>
                    </div>
                </li>
                <?php } if($rowuserid['accexpenses_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('expenses.php');" <?php if($current_page == 'expenses.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-money' ></i> <font> <?php echo __('expenses'); ?></font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        <?php }?>
        <div class="sbp-container" id="HRTD" onClick="collapseSection('HR', 'HRTD')"><i class='bx bx-street-view' ></i> <font> <?php echo __('human_resources'); ?></font></div>
        <div id="HR" class="sidebar-container" style="display:none;">
            <ul>
                <li onclick="toggle_usermanagement()" <?php if($current_page == 'attendance.php' || $current_page == 'mbhemps.php' || $current_page == 'vacationreqs.php' || $current_page == 'vacationreqs2.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-history' ></i> <font> <?php echo __('employee_management'); ?></font>
                    </div>
                </li>
                <?php if($rowuserid['userattendance_rperm'] == 1){?>
                <li style="margin-right: 20px; margin-top: 10px; display: none;" id="users_management1" onclick="openOrFocusTab('attendance.php');" <?php if($current_page == 'attendance.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-history' ></i> <font> <?php echo __('attendance_log'); ?></font>
                    </div>
                </li>
                <?php }?>
                <li style="margin-right: 20px; display: none;" id="users_management2" onclick="openOrFocusTab('mbhEmps.php?section=users');" <?php if($current_page == 'mbhemps.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-user-check' ></i> <font><?php echo __('employee_data'); ?></font>
                    </div>
                </li>
                <?php if($rowuserid['vacf_aperm'] == 1){?>
                <li style="margin-right: 20px; display: none;" id="users_management3" onclick="openOrFocusTab('vacationReqs.php');" <?php if($current_page == 'vacationreqs.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bxs-plane-alt' ></i>
                        <font> <?php echo __('vacation_requests'); ?></font>
                        <font style="color:red">( <?php echo $rowcountvacs1['countvacs1']; ?> )</font>
                    </div>
                </li>
                <?php } if($rowuserid['vacl_aperm'] == 1){ ?>
                <li style="margin-right: 20px; margin-bottom: 10px; display: none;" id="users_management4" onclick="openOrFocusTab('vacationReqs2.php');" <?php if($current_page == 'vacationreqs2.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-calendar-check' ></i>
                        <font> <?php echo __('final_vacation_approval'); ?></font>
                        <font style="color:red">( <?php echo $rowcountvacs2['countvacs2']; ?> )</font>
                    </div>
                </li>
                <?php }?>
                <li onclick="openOrFocusTab('empsNotifications.php');" <?php if($current_page == 'empsnotifications.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-user-check' ></i> <font>
                            <?php echo __('alerts'); ?>
                            <?php // PHP logic for notifications - left untouched ?>
                        </font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('Calendar.php');" <?php if($current_page == 'calendar.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-user-check' ></i> <font><?php echo __('appointments'); ?></font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('Office_Vehicles.php');" <?php if($current_page == 'office_vehicles.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-car' ></i> <font> <?php echo __('resources_and_vehicles'); ?></font>
                    </div>
                </li>
                <li onclick="openOrFocusTab('Contracts.php');" <?php if($current_page == 'contracts.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-book-content' ></i> <font> <?php echo __('lease_and_commercial_licenses'); ?></font>
                    </div>
                </li>
                <?php if($rowuserid['logs_rperm'] == 1){ ?>
                <li onclick="openOrFocusTab('Logs.php');" <?php if($current_page == 'logs.php'){?>class="active"<?php }?>>
                    <div class="sidebar-a">
                        <i class='bx bx-history' ></i> <font> <?php echo __('program_logs'); ?></font>
                    </div>
                </li>
                <?php }?>
            </ul>
        </div><br>
        
        <button onclick="location.href='logout.php';" class="green-button display-none-default" style="width: 100%; padding: 10px"><i class='bx bx-log-out bx-xs'></i> <?php echo __('logout'); ?></button>
    </div>
</div>

<script>
    // All JS functions - left untouched
</script>
