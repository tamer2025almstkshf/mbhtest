<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'permissions_check.php';
    include_once 'golden_pass.php';
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
            .tox-tinymce{
                height: 100vh !important;
            }
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
        <?php 
            $fno = isset($_GET['fno']) ? (int)$_GET['fno'] : 0;
            
            $stmtid = $conn->prepare("SELECT * FROM file WHERE file_id=?");
            $stmtid->bind_param("i", $fno);
            $stmtid->execute();
            $resultid = $stmtid->get_result();
            $row_details = $resultid->fetch_assoc();
            $stmtid->close();
            if($admin != 1){
                if($row_details['secret_folder'] == 1){
                    $empids = $row_details['secret_emps'];
                    $empids = array_filter(array_map('trim', explode(',', $empids)));
                    if (!in_array($_SESSION['id'], $empids)) {
                        exit();
                    }
                }
            }
            
            if(($row_permcheck['note_aperm'] == 1 && !isset($_GET['edit'])) || ($row_permcheck['note_eperm'] == 1 && isset($_GET['id']))){
        ?>
        <form action="<?php if(isset($_GET['id'])){ echo'documentedit.php'; } else{ echo 'documentadd.php'; }?>" method="post" name="SearchForm" enctype="multipart/form-data">
            <input type="hidden" name="timermainid" value="<?php echo safe_output($_GET['fno']);?>">
            <input type="hidden" name="timeraction" value="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'document_edit'; } else { echo 'document_add'; }?>">
            <input type="hidden" name="timerdone_date" value="<?php echo date("Y-m-d");?>">
            <input type="hidden" name="timerdone_action" value="<?php if(isset($_GET['id']) && $_GET['id'] !== ''){ echo 'تعديل مذكرة في الملف رقم '.$_GET['fno']; } else { echo 'كتابة مذكرة للملف رقم '.$_GET['fno']; }?>">
            <input type="hidden" name="timer_timestamp" value="<?php echo time();?>">
            <?php 
                $document_date = date("Y-m-d");
                $document_subject = 'بلا عنوان';
                $document_details = '';
                
                if(isset($_GET['edit']) && $_GET['edit'] === '1'){
                    $id = $_GET['id'];
                    $stmtid = $conn->prepare("SELECT * FROM case_document WHERE did=?");
                    $stmtid->bind_param("i", $id);
                    $stmtid->execute();
                    $resultid = $stmtid->get_result();
                    $rowid = $resultid->fetch_assoc();
                    $stmtid->close();
                    
                    $document_date = $rowid['document_date'];
                    $document_subject = $rowid['document_subject'];
                    $document_details = $rowid['document_details'];
                }
                
                $fileidget = $_GET['fno'];
                $stmt_get = $conn->prepare("SELECT * FROM file WHERE file_id=?");
                $stmt_get->bind_param("i", $fileidget);
                $stmt_get->execute();
                $query_get = "SELECT * FROM file WHERE file_id='$fileidget'";
                $result_get = $stmt_get->get_result();
                
                if($result_get->num_rows == 0){
                    exit();
                }
                $stmt_get->close();
                
                $fiddeg = $_GET['fno'];   
                $stmt_degs = $conn->prepare("SELECT * FROM file_degrees WHERE fid=? ORDER BY created_at DESC");
                $stmt_degs->bind_param("i", $fiddeg);
                $stmt_degs->execute();
                $result_degs = $stmt_degs->get_result();
                if($result_degs->num_rows > 0){
                    $row_degs = $result_degs->fetch_assoc();
                    $caseno = $row_degs['case_num'] . '/' . $row_degs['file_year'];
                    $caseno1 = $row_degs['id'];
                }
                $stmt_degs->close();
                
                if(isset($_GET['edit']) && $_GET['edit'] === '1'){
            ?>
            <input type="hidden" name="did" value="<?php echo safe_output($id);?>">
            <?php }?>
            <input type="hidden" name="dfile_no" value="<?php echo safe_output($fileidget)?>">
            <input type="hidden" name="dcase_no" value="<?php echo safe_output($caseno1)?>">
            
            <input type="hidden" name="note_date" value="<?php echo safe_output($document_date);?>"> 
            <input type="hidden" name="document_subject" value="<?php echo safe_output($document_subject);?>">
            <textarea id="myEditor" name="document_details"><?php echo $document_details?></textarea>
            
            <button type="submit" class="green-button test-button">حفظ البيانات</button>
        </form>
        <script>
            tinymce.init({
                selector: '#myEditor',
                height: '100vh',
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