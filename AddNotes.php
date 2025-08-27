<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';

    /** @var mysqli $conn */
    /** @var array $row_permcheck */
    /** @var int $admin */

    // Get and validate the file number from the URL.
    $fno = filter_input(INPUT_GET, 'fno', FILTER_VALIDATE_INT);
    if (!$fno) {
        die("Invalid file number provided.");
    }

    // --- Security Check: Fetch case file details securely ---
    $stmt = $conn->prepare("SELECT secret_folder, secret_emps FROM file WHERE file_id = ?");
    $stmt->bind_param("i", $fno);
    $stmt->execute();
    $result = $stmt->get_result();
    $file_details = $result->fetch_assoc();
    $stmt->close();

    if (!$file_details) {
        die("File not found.");
    }

    // --- Authorization Check (IDOR Prevention) ---
    // 1. Check for access to secret folders
    if ($admin != 1 && $file_details['secret_folder'] == 1) {
        $allowed_emps = array_filter(array_map('trim', explode(',', $file_details['secret_emps'])));
        if (!in_array($_SESSION['id'], $allowed_emps)) {
            die("Access Denied: You do not have permission to view this secret file.");
        }
    }
    
    // 2. [IMPORTANT] Add business logic here to check if the user has general access
    // to this non-secret case (e.g., are they the assigned lawyer?).
    // Example: if (!is_user_assigned_to_case($conn, $_SESSION['id'], $fno)) { die("Access Denied."); }


    $is_edit_mode = isset($_GET['id']);
    // Check permissions for adding or editing notes
    if (!($row_permcheck['note_aperm'] == 1 && !$is_edit_mode) && !($row_permcheck['note_eperm'] == 1 && $is_edit_mode)) {
        die("Access Denied: You do not have the required permissions for this action.");
    }

    $document_date = date("Y-m-d");
    $document_subject = 'بلا عنوان';
    $document_details = '';
    $document_id = null;

    if ($is_edit_mode) {
        $document_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $stmt = $conn->prepare("SELECT document_date, document_subject, document_details FROM case_document WHERE did = ?");
        $stmt->bind_param("i", $document_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $note_data = $result->fetch_assoc();
        $stmt->close();

        if ($note_data) {
            $document_date = $note_data['document_date'];
            $document_subject = $note_data['document_subject'];
            $document_details = $note_data['document_details'];
        }
    }

    $case_degree_id = null;
    $stmt_degs = $conn->prepare("SELECT id FROM file_degrees WHERE fid=? ORDER BY created_at DESC LIMIT 1");
    $stmt_degs->bind_param("i", $fno);
    $stmt_degs->execute();
    $result_degs = $stmt_degs->get_result();
    if ($result_degs->num_rows > 0) {
        $case_degree_id = $result_degs->fetch_assoc()['id'];
    }
    $stmt_degs->close();
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <title>إضافة / تعديل مذكرة</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <link href="css/styles.css" rel="stylesheet">
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; }
        .tox-tinymce { height: 100% !important; }
        .submit-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 10000; /* Ensure it's above TinyMCE UI */
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>
<body>
    <form action="<?php echo $is_edit_mode ? 'documentedit.php' : 'documentadd.php'; ?>" method="post">
        <!-- Hidden fields for timer/logging functionality -->
        <input type="hidden" name="timermainid" value="<?php echo htmlspecialchars($fno, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="timeraction" value="<?php echo $is_edit_mode ? 'document_edit' : 'document_add'; ?>">
        <input type="hidden" name="timerdone_date" value="<?php echo date("Y-m-d"); ?>">
        <input type="hidden" name="timerdone_action" value="<?php echo htmlspecialchars($is_edit_mode ? 'تعديل مذكرة في الملف رقم '.$fno : 'كتابة مذكرة للملف رقم '.$fno, ENT_QUOTES, 'UTF-8'); ?>">
        <input type="hidden" name="timer_timestamp" value="<?php echo time(); ?>">

        <!-- Hidden fields for the note data -->
        <?php if ($is_edit_mode && $document_id): ?>
            <input type="hidden" name="did" value="<?php echo htmlspecialchars($document_id, ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        <input type="hidden" name="dfile_no" value="<?php echo htmlspecialchars($fno, ENT_QUOTES, 'UTF-8'); ?>">
        <?php if ($case_degree_id): ?>
            <input type="hidden" name="dcase_no" value="<?php echo htmlspecialchars($case_degree_id, ENT_QUOTES, 'UTF-8'); ?>">
        <?php endif; ?>
        
        <input type="hidden" name="note_date" value="<?php echo htmlspecialchars($document_date, ENT_QUOTES, 'UTF-8'); ?>"> 
        <input type="hidden" name="document_subject" value="<?php echo htmlspecialchars($document_subject, ENT_QUOTES, 'UTF-8'); ?>">
        
        <textarea id="myEditor" name="document_details">
            <?php echo htmlspecialchars($document_details, ENT_QUOTES, 'UTF-8'); ?>
        </textarea>
        
        <button type="submit" class="submit-button">حفظ البيانات</button>
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
            branding: false,
            directionality: 'rtl'
        });
    </script>
</body>
</html>