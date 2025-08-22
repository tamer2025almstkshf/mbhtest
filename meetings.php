<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    
    
    $month = $_GET['month'] ?? date('m');
    $year = $_GET['year'] ?? date('Y');
    
    $month = (int)$month;
    $year = (int)$year;
    
    $firstDay = mktime(0, 0, 0, $month, 1, $year);
    $daysInMonth = date('t', $firstDay);
    $monthName = date('F', $firstDay);
    $startDayOfWeek = date('w', $firstDay);
    
    $events = [];
    $stmt = $conn->prepare("SELECT * FROM events WHERE MONTH(event_date) = ? AND YEAR(event_date) = ?");
    $stmt->bind_param("ii", $month, $year);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $id = $row['id'].'<br><br><br>';
        $title = '';
        $participants = '';
        $Branch = '';
        $time = '';
        if(isset($row['title']) && $row['title'] !== ''){
            $title = $row['title'];
            $title = "$title";
        }
        if(isset($row['participants']) && $row['participants'] !== ''){
            $participants = $row['participants'];
            $participant = explode(",", $participants);
            
            $placeholders = implode(',', array_fill(0, count($participant), '?'));
            $stmtp = $conn->prepare("SELECT * FROM user WHERE id IN ($placeholders)");
            $types = str_repeat('i', count($participant));
            
            $stmtp->bind_param($types, ...$participant);
            $stmtp->execute();
            $resultp = $stmtp->get_result();
            
            $participants = "<br><br>المشاركون : ";
            $participants_names = '';
            while($rowp = $resultp->fetch_assoc()){
                if($participants_names === ''){
                    $participants_names = $rowp['name'];
                } else{
                    $participants_names .= ', '.$rowp['name'];
                }
            }
            
            $participants .= $participants_names;
        }
        if(isset($row['branch']) && $row['branch'] !== ''){
            $Branch = $row['branch'];
            $Branch = "<br>المكان : $Branch";
        }
        if(isset($row['time']) && $row['time'] !== ''){
            $time = $row['time'];
            $time = "<br><font color='#999999'>@ $time</font>";
            if(isset($row['branch']) && $row['branch'] !== ''){
                $Branch = $row['branch'];
                $Branch = "<font color='#999999'> - $Branch</font>";
            }
        } else{
            if(isset($row['branch']) && $row['branch'] !== ''){
                $Branch = $row['branch'];
                $Branch = "<br><font color='#999999'>$Branch</font>";
            }
        }
        $events[$row['event_date']][] = $id . $title . $participants . $time . $Branch;
    }
    $stmt->close();
?>
<!DOCTYPE html>
<html dir="rtl">
    <head>
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
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
        <link rel="stylesheet" href="css/calendar_style.css">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
        <style>
            .event-edit:hover {
                border-left: 6px solid #0078d4;
                background-color: #3c88cc40;
            }
            #hiddenContent {
                padding: 30px;
                font-family: 'Arial', sans-serif;
                direction: rtl;
            }
            
            #hiddenContent h1, h2, h3 {
                text-align: center;
            }
            
            #hiddenContent p {
                line-height: 1.8;
                margin: 10px 0;
            }
            
            #hiddenContent strong {
                font-weight: bold;
            }
            
            #hiddenContent em {
                font-style: italic;
            }
        </style>
    </head>
    <body style="overflow: auto; padding-bottom: 50px;">
        <div class="container">
            <?php include_once 'sidebar.php';?>
            <div class="website">
                <?php 
                    include_once 'header.php';
                    if($row_permcheck['csched_rperm'] == 1){
                ?>
                <div class="web-page">
                    <br><br>
                    <div class="calendar-container">
                        <div class="calendar-header">
                            <button onclick="navigateMonth(1, <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo '1'; } else{ echo '0'; }?>)">▶</button>
                            <h2><?= "$monthName $year" ?> <?php if($row_permcheck['csched_eperm'] == 1){?><img src="img/<?php if(!isset($_GET['edit']) || $_GET['edit'] !== '1'){ echo 'edit.png'; } else{ echo 'add-button.png'; }?>" width="20px" height="20px" style="cursor: pointer" onclick="location.href='meetings.php?month=<?php echo safe_output($month);?>&year=<?php echo safe_output($year); if(!isset($_GET['edit']) || $_GET['edit'] !== '1'){ echo '&edit=1'; }?>';"><?php }?></h2>
                            <button onclick="navigateMonth(-1, <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo '1'; } else{ echo '0'; }?>)">◀</button>
                        </div>
                        
                        <div class="calendar-grid">
                            <?php
                                $dayNames = ['الأحد', 'الإثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
                                foreach ($dayNames as $dayName) echo "<div class='day-header' style='background-color: #125483; color: #fff'>".safe_output($dayName)."</div>";
                                
                                for ($i = 0; $i < $startDayOfWeek; $i++) echo "<div class='day-box empty'></div>";
                                
                                for ($day = 1; $day <= $daysInMonth; $day++) {
                                    $dateKey = sprintf('%04d-%02d-%02d', $year, $month, $day);
                            ?>
                            <div class='day-box' <?php if(!isset($_GET['edit']) || (isset($_GET['edit']) && $_GET['edit'] !== '1')){?> onclick='<?php echo "openModal(\"".safe_output($dateKey)."\")";?>' <?php } else{?> style="cursor: default;" <?php }?>>
                                <div class='day-number'><?php echo safe_output($day);?></div>
                                <?php if (isset($events[$dateKey])) {?>
                                <div class='event'>
                                    <?php 
                                        foreach($events[$dateKey] as $event){
                                            list($eventid, $event) = explode("<br><br><br>", $event);
                                    ?>
                                    <div class='event-item <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?>event-edit<?php }?>' <?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){?> style="cursor: pointer;" onclick="togglemodal('<?php echo safe_output($eventid);?>');" <?php }?>><?php echo safe_output($event);?></div>
                                    
                                    <?php if(isset($_GET['edit']) && $_GET['edit'] === '1' && $row_permcheck['csched_eperm'] == 1){?>
                                    <div id="eventedit-<?php echo safe_output($eventid);?>" class="modal2">
                                        <br><br>
                                        <div class="modal-content2" style="max-height: 350px; overflow: auto;">
                                            <div style="display: grid; grid-template-columns: 1fr auto; background-color: #125483; color: #fff; padding: 5px; border-radius: 3px;">
                                                <h3 style="">تعديل الموعد</h3>
                                                <span class="close2" onclick="togglemodal('<?php echo safe_output($eventid);?>')">&times;</span>
                                            </div>
                                            <form action="edit_event.php" method="POST">
                                                <input type="hidden" name="id" value="<?php echo safe_output($eventid);?>">
                                                <?php
                                                    $stmte = $conn->prepare("SELECT * FROM events WHERE id=?");
                                                    $stmte->bind_param("i", $eventid);
                                                    $stmte->execute();
                                                    $resulte = $stmte->get_result();
                                                    $rowe = $resulte->fetch_assoc();
                                                    $stmte->close();
                                                ?>
                                                <input type="text" class="form-input" style="width: 95%" name="title" placeholder="الموضوع" value="<?php if(isset($rowe['title']) && $rowe['title'] !== ''){ echo safe_output($rowe['title']); }?>" required>
                                                <div class="select-container input-container">
                                                    <p class="input-parag"><font class="blue-parag">المشاركين</font></p>
                                                    <?php
                                                        $participants2 = $rowe['participants'];
                                                        $participant2 = explode(",", $participants2);
                                                        
                                                        $placeholders2 = implode(',', array_fill(0, count($participant2), '?'));
                                                        $stmtp2 = $conn->prepare("SELECT * FROM user WHERE id IN ($placeholders2)");
                                                        $types2 = str_repeat('i', count($participant2));
                                                        
                                                        $stmtp2->bind_param($types2, ...$participant2);
                                                        $stmtp2->execute();
                                                        $resultp2 = $stmtp2->get_result();
                                                        if($resultp2->num_rows > 0){
                                                            while($rowp2 = $resultp2->fetch_assoc()){
                                                    ?>
                                                    <select name="employee_id[]" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;" onchange="handleSelect(this)">
                                                        <option></option>
                                                        <?php
                                                            $stmtemps = $conn->prepare("SELECT * FROM user");
                                                            $stmtemps->execute();
                                                            $resultemps = $stmtemps->get_result();
                                                            if($resultemps->num_rows > 0){
                                                                while($rowemps = $resultemps->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo safe_output($rowemps['id']);?>" <?php if($rowemps['id'] === $rowp2['id']){ echo 'selected'; }?>><?php echo safe_output($rowemps['name']);?></option>
                                                        <?php
                                                                }
                                                            }
                                                        ?>
                                                    </select>
                                                    <?php
                                                            }
                                                        }
                                                    ?>
                                                    <select name="employee_id[]" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;" onchange="handleSelect(this)">
                                                        <option></option>
                                                        <?php
                                                            $stmtemps = $conn->prepare("SELECT * FROM user");
                                                            $stmtemps->execute();
                                                            $resultemps = $stmtemps->get_result();
                                                            if($resultemps->num_rows > 0){
                                                                while($rowemps = $resultemps->fetch_assoc()){
                                                        ?>
                                                        <option value="<?php echo safe_output($rowemps['id']);?>"><?php echo safe_output($rowemps['name']);?></option>
                                                        <?php 
                                                                }
                                                            }
                                                            $stmtemps->close();
                                                        ?>
                                                    </select>
                                                </div>
                                                <?php
                                                    list($timeHH, $timeMM) = explode(":", $rowe['time']);
                                                ?>
                                                <input type="number" class="form-input" min="0" max="59"name="timeMM" value="<?php if(isset($timeMM) && safe_output($timeMM) !== ''){ echo $timeMM; }?>" style="width: 50px" placeholder="MM"> : <input type="number" class="form-input" min="0" max="23" name="timeHH" value="<?php if(isset($timeHH) && $timeHH !== ''){ echo safe_output($timeHH); }?>" style="width: 50px" placeholder="HH">
                                                <input type="text" class="form-input" style="width: 95%" name="Branch" value="<?php if(isset($rowe['branch']) && $rowe['branch'] !== ''){ echo safe_output($rowe['branch']); }?>" placeholder="المكان">
                                                <?php if($row_permcheck['csched_eperm'] == 1){?>
                                                <button type="button" onclick="MM_openBrWindow('meeting_report.php?id=<?php echo $eventid;?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">كتابة محضر الاجتماع</button>
                                                <?php
                                                    }
                                                    $rstmt = $conn->prepare("SELECT * FROM meetings_reports WHERE meeting_id=?");
                                                    $rstmt->bind_param("i", $eventid);
                                                    $rstmt->execute();
                                                    $rresult = $rstmt->get_result();
                                                    if($rresult->num_rows > 0){
                                                        echo '<br><br>';
                                                        while($rrow = $rresult->fetch_assoc()){
                                                ?>
                                                <div style="display: grid; grid-template-columns: 1fr auto; background-color: #67676725; padding: 5px; border-radius: 4px; margin-bottom: 2px;">
                                                    <p><?php echo $rrow['name'];?></p>
                                                    <div>
                                                        <?php if($row_permcheck['csched_eperm'] == 1){?>
                                                        <img src="img/message.png" style="cursor: pointer;" onclick="report('<?php echo $rrow['id'];?>');" height="20px" width="20px">
                                                        <img src="img/recycle-bin.png" style="cursor: pointer;" onclick="location.href='delete_mreport.php?id=<?php echo $rrow['id'];?>&month=<?php echo $_GET['month'];?>&year=<?php echo $_GET['year'];?>';" height="20px" width="20px">
                                                        <img src="img/edit.png" style="cursor: pointer;" onclick="MM_openBrWindow('meeting_report.php?id=<?php echo $eventid;?>&edit=1&rid=<?php echo $rrow['id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')" height="20px" width="20px">
                                                        <?php }?>
                                                    </div>
                                                </div>
                                                <?php 
                                                        }
                                                        echo '<br>';
                                                    }
                                                    $rstmt->close();
                                                ?>
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; text-align: right; position: sticky; bottom: 0; background-color: #12548340; border-radius: 3px; blur(10px); padding: 10px">
                                                    <div>
                                                        <button type="submit">حفظ البيانات</button>
                                                    </div>
                                                    <div style="text-align: left;">
                                                        <?php if($row_permcheck['csched_dperm'] == 1){?>
                                                        <button type="button" style="background-color: #d40000;" onclick="location.href='deletemeeting.php?id=<?php echo safe_output($rowe['id']);?>';">الغاء الموعد</button>
                                                        <?php }?>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php }}?>
                                </div>
                                <?php }?>
                            </div>
                            <?php }?>
                        </div>
                    </div>
                    
                    <?php if($row_permcheck['csched_aperm'] == 1){?>
                    <div id="eventModal" class="modal2">
                        <br><br>
                        <div class="modal-content2" style="max-height: 350px; overflow: auto">
                            <div style="display: grid; grid-template-columns: 1fr auto; background-color: #125483; color: #fff; padding: 5px; border-radius: 3px;">
                                <h3 style="">اضافة موعد</h3>
                                <span class="close2" onclick="closeModal()">&times;</span>
                            </div>
                            <form action="save_event.php" method="POST">
                                <input type="hidden" name="event_date" id="event_date">
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">الموضوع</font></p>
                                    <input type="text" class="form-input" name="title" required>
                                </div>
                                <div class="select-container input-container">
                                    <p class="input-parag"><font class="blue-parag">المشاركين</font></p>
                                    <select name="employee_id[]" class="table-header-selector" style="width: 100%; height: fit-content; margin: 10px 0;" onchange="handleSelect(this)">
                                        <option></option>
                                        <?php
                                            if($secret_emps !==  ''){
                                                $ids1 = explode(',', $secret_emps);
                                                $placeholders1 = implode(',', array_fill(0, count($ids1), '?'));
                                                $types1 = str_repeat('i', count($ids1));
                                                
                                                $sql1 = "SELECT * FROM user WHERE id NOT IN ($placeholders1) ORDER BY id DESC";
                                                $stmtemps = $conn->prepare($sql1);
                                                $stmtemps->bind_param($types1, ...$ids1);
                                                $stmtemps->execute();
                                            } else{
                                                $stmtemps = $conn->prepare("SELECT * FROM user");
                                                $stmtemps->execute();
                                            }
                                            $resultemps = $stmtemps->get_result();
                                            if($resultemps->num_rows > 0){
                                                while($rowemps = $resultemps->fetch_assoc()){
                                        ?>
                                        <option value="<?php echo safe_output($rowemps['id']);?>"><?php echo safe_output($rowemps['name']);?></option>
                                        <?php 
                                                }
                                            }
                                            $stmtemps->close();
                                        ?>
                                    </select>
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">وقت الاجتماع</font></p>
                                    <input type="number" class="form-input" min="0" max="59"name="timeMM" style="width: 50px" placeholder="MM"> : <input type="number" class="form-input" min="0" max="23" name="timeHH" style="width: 50px" placeholder="HH">
                                </div>
                                <div class="input-container">
                                    <p class="input-parag"><font class="blue-parag">المكان</font></p>
                                    <input type="text" class="form-input" name="Branch">
                                </div>
                                <button type="submit">حفظ البيانات</button>
                            </form>
                        </div>
                    </div>
                    <?php }?>
                </div>
                <?php }?>
            </div>
        </div>
        
        <script>
            function report(report_id){
                Swal.fire({
                    icon: 'warning',
                    title: 'ارسال المحضر',
                    showDenyButton: true,
                    showConfirmButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'ارسال المحضر في الواتس',
                    denyButtonText: 'ارسال المحضر في البريد',
                    confirmButtonColor: '#28a745',
                    denyButtonColor: '#007bff',
                    background: '#fff',
                    footer: `<button id="cancelButton" class="swal2-cancel swal2-styled" style="background-color: #d33; color: white; padding: 8px 16px; border-radius: 5px; border: none; cursor: pointer;">الغاء</button>`,
                    didRender: () => {
                        document.getElementById('cancelButton').addEventListener('click', () => {
                            Swal.close(); // ✅ Close the popup when clicking "الغاء"
                        });
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `reportwhatssend.php?id=${report_id}`;
                    } else if (result.isDenied) {
                        window.location.href = `reportmailsend.php?id=${report_id}`;
                    }
                });
            }
        </script>
        <script>
            function navigateMonth(offset, edit) {
                const currentMonth = <?= (int)$month ?>;
                const currentYear = <?= (int)$year ?>;
                let newMonth = currentMonth + offset;
                let newYear = currentYear;
                
                if (newMonth < 1) { newMonth = 12; newYear--; }
                if (newMonth > 12) { newMonth = 1; newYear++; }
                
                if(edit == 1){
                    window.location.href = `?month=${newMonth}&year=${newYear}&edit=1`;
                } else{
                    window.location.href = `?month=${newMonth}&year=${newYear}`;
                }
                
            }
            
            function openModal(date) {
                document.getElementById('event_date').value = date;
                document.getElementById('eventModal').style.display = 'block';
            }
            
            function closeModal() {
                document.getElementById('eventModal').style.display = 'none';
            }
            
            function togglemodal(id){
                var modal = document.getElementById('eventedit-' + id);
                if(modal.style.display === 'none' || modal.style.display === ''){
                    modal.style.display = 'block';
                } else{
                    modal.style.display = 'none';
                }
            }
        </script>

        <script>
            function saveHiddenAsPDF() {
                // Temporarily show the hidden element
                const hidden = document.getElementById('hiddenContent');
                hidden.style.display = 'block';
                
                // Generate the PDF
                html2pdf().from(hidden).set({
                    margin: 1,
                    filename: 'hidden_document.pdf',
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2 },
                    jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
                }).save().then(() => {
                    // Re-hide the element after saving
                    hidden.style.display = 'none';
                });
            }
        </script>

        <script src="js/dynamic_selectors.js"></script>
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
