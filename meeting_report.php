<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
    include_once 'errorscheck.php'
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
        <script src="https://cdn.tiny.cloud/1/gd376gc1vijt6traz7koo07ehsqo0339n48p189rs3k22q47/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script language="javascript" type="text/javascript" src="jscripts/tiny_mce/tiny_mce.js"></script>
        <style>
            .test-button {
                position: fixed;
                bottom: 20px;
                right: 20px;
                z-index: 1000;
                padding: 10px 20px;
                font-size: 16px;
            }
        </style>
    </head>
    <body>
        <?php if($row_permcheck['csched_eperm'] == 1){?>
        <div style="background-color: #125483; color: #fff; padding: 10px; text-align: center;">محضر الاجتماع</div>
        <form action="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo'mreport_edit.php'; } else{ echo 'mreport_add.php'; }?>" method="post" name="SearchForm" enctype="multipart/form-data">
            <?php
                $mstmt = $conn->prepare("SELECT * FROM clients_schedule WHERE id=?");
                $mstmt->bind_param("i", $_GET['id']);
                $mstmt->execute();
                $mresult = $mstmt->get_result();
                $mrow = $mresult->fetch_assoc();
                $mstmt->close();
            ?>
            <input type="hidden" name="timermainid" value="<?php echo safe_output($_GET['id']);?>">
            <input type="hidden" name="timeraction" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'meetingreport_edit'; } else { echo 'meetingreport_add'; }?>">
            <input type="hidden" name="timerdone_date" value="<?php echo date("Y-m-d");?>">
            <input type="hidden" name="timerdone_action" value="<?php if(isset($_GET['edit']) && $_GET['edit'] === '1'){ echo 'تعديل محضر الاجتماع : '.safe_output($mrow['name']); } else { echo 'كتابة محضر الاجتماع : '.safe_output($mrow['name']); }?>">
            <input type="hidden" name="timer_timestamp" value="<?php echo time();?>">
            
            <?php
                if(isset($_GET['rid']) && $_GET['rid'] !== ''){
                    $rstmt = $conn->prepare("SELECT * FROM meetings_reports WHERE id=?");
                    $rstmt->bind_param("i", $_GET['rid']);
                    $rstmt->execute();
                    $rresult = $rstmt->get_result();
                    $rrow = $rresult->fetch_assoc();
                    $rstmt->close();
            ?>
            <input type="hidden" name="rid" value="<?php echo safe_output($_GET['rid']);?>">
            <?php }?>
            <input type="hidden" name="mid" value="<?php echo safe_output($_GET['id']);?>">
            <div class="input-container" style="align-content: center;">
                <p class="input-parag">اسم المحضر</p>
                <input type="text" class="form-input" value="<?php if(isset($rrow['name']) && $rrow['name'] !== ''){ echo $rrow['name']; }?>" name="report_name">
            </div>
            <textarea id="myEditor" name="report_details"><?php if(isset($rrow['details']) && $rrow['details'] !== ''){ echo $rrow['details']; }?></textarea>
            
            <button type="submit" class="green-button test-button">حفظ البيانات</button>
        </form>
        <script>
            tinymce.init({
                selector: '#myEditor',
                plugins: 'advlist autolink lists link image charmap print preview anchor fontselect table',
                toolbar: 'undo redo | fontselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table',
                menubar: 'file edit view insert format tools table help',
                font_formats: 'Arial=arial,helvetica,sans-serif; Comic Sans MS=comic sans ms,sans-serif; Courier New=courier,monospace; Georgia=georgia,palatino,serif; Times New Roman=times,serif; Verdana=verdana,geneva,sans-serif;',
                fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
                branding: false
            });
        </script>
        <?php }?>
    </body>
</html>