<?php
$pageTitle = 'لوحة التحكم الرئيسية'; // Set the page title for our new header
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'safe_output.php';
include_once 'AES256.php';
include_once 'general_notifications.php';
include_once 'layout/header.php'; // Use the modern header

$sessionid = $_SESSION['id'] ?? null;
?>

<div class="container-fluid">
    <?php 
    // If sidebar.php and header.php are your main layout components, include them here.
    // Note: The new layout/header.php replaces the old <head> section, not the visual header component.
    include_once 'sidebar.php'; 
    ?>
    <div class="website">
        <?php include_once 'header.php'; // This is likely your visual header bar ?>
        <div class="web-page p-3">
            
            <h3>لوحة التحكم</h3>
            <p class="lead">اختر من القوائم التالية للبدء.</p>
            <hr>

            <div class="row g-3">
                
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="MM_openBrWindow('selector/CourtLink.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <div class="card-body">
                            <img src="img/court.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">المحاكم</h5>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="MM_openBrWindow('cases.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <div class="card-body">
                            <img src="img/judges.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">القضايا</h5>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="MM_openBrWindow('hearing.php?tw=1','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <div class="card-body">
                            <img src="img/session.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">الجلسات</h5>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="MM_openBrWindow('Tasks.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <div class="card-body">
                            <img src="img/tasks.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">المهام</h5>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="MM_openBrWindow('clients.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <div class="card-body">
                            <img src="img/clients.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">الموكلين</h5>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="MM_openBrWindow('Accounts.php','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                        <div class="card-body">
                            <img src="img/accounting.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">الحسابات</h5>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card text-center h-100" style="cursor: pointer;" onclick="location.href='mbhEmps.php'">
                        <div class="card-body">
                            <img src="img/hr.png" width="80" height="80" class="mb-2">
                            <h5 class="card-title">الموارد البشرية</h5>
                        </div>
                    </div>
                </div>
                
                <!-- Add other links in the same pattern -->

            </div>

            <!-- The dashboard columns for late decisions, appeals, etc. would go here -->
            <!-- This section also needs refactoring but is left for a future step to keep this change focused -->

        </div>
    </div>
</div>

<script src="js/newWindow.js"></script>
<?php include_once 'layout/footer.php'; // Use the modern footer ?>
