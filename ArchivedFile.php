<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'src/I18n.php';
    
    $i18n = new I18n('translations/ArchivedFile.yaml');

    // --- 1. Permission & Input Validation ---
    if (empty($perm_row['archives_rperm'])) {
        die($i18n->get('access_denied_view_archived'));
    }

    $file_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
    if (!$file_id) {
        die($i18n->get('invalid_file_id'));
    }

    // --- 2. Authorization (IDOR Prevention) ---
    // First, securely fetch file details to check its status and any special access rights.
    $stmt_file = $conn->prepare("SELECT file_status, secret_folder, secret_emps FROM file WHERE file_id = ?");
    $stmt_file->bind_param("i", $file_id);
    $stmt_file->execute();
    $result_file = $stmt_file->get_result();
    $file_details = $result_file->fetch_assoc();
    $stmt_file->close();

    if (!$file_details || $file_details['file_status'] !== 'مؤرشف') {
        die($i18n->get('archived_file_not_found'));
    }
    
    // Check access for secret files
    if ($admin != 1 && $file_details['secret_folder'] == 1) {
        $allowed_emps = array_filter(array_map('trim', explode(',', $file_details['secret_emps'])));
        if (!in_array($_SESSION['id'], $allowed_emps)) {
            die($i18n->get('access_denied_view_specific_file'));
        }
    }
    // [Placeholder]: Add other business logic here to check if the user is assigned to this case.

    // --- 3. Secure Data Retrieval ---
    $notes = [];
    $stmt_notes = $conn->prepare("SELECT * FROM file_note WHERE file_id = ? ORDER BY timestamp DESC");
    $stmt_notes->bind_param("i", $file_id);
    $stmt_notes->execute();
    $result_notes = $stmt_notes->get_result();
    while ($row = $result_notes->fetch_assoc()) {
        $notes[] = $row;
    }
    $stmt_notes->close();

    $edit_note_content = '';
    $edit_note_id = null;
    if (isset($_GET['action']) && $_GET['action'] === 'notedit' && !empty($perm_row['note_eperm'])) {
        $edit_note_id = filter_input(INPUT_GET, 'nid', FILTER_VALIDATE_INT);
        if ($edit_note_id) {
            $stmt_edit_note = $conn->prepare("SELECT note FROM file_note WHERE id = ? AND file_id = ?");
            $stmt_edit_note->bind_param("ii", $edit_note_id, $file_id);
            $stmt_edit_note->execute();
            $edit_note_row = $stmt_edit_note->get_result()->fetch_assoc();
            $stmt_edit_note->close();
            if ($edit_note_row) {
                $edit_note_content = $edit_note_row['note'];
            }
        }
    }
?>
<!DOCTYPE html>
<html dir="<?php echo $i18n->getDirection(); ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $i18n->get('archived_file_notes'); ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <style>
        .note-container { border: 1px solid #ccc; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .note-meta { font-size: 0.8em; color: #666; margin-bottom: 5px; }
        .note-actions a { margin-left: 10px; }
    </style>
</head>
<body>
<div class="container">
    <?php include_once 'sidebar.php'; ?>
    <div class="l_data">
        <h2><?php echo $i18n->get('archived_file_notes_for_id'); ?> <?php echo htmlspecialchars($file_id, ENT_QUOTES, 'UTF-8'); ?></h2>

        <?php if (!empty($perm_row['note_aperm']) || ($edit_note_id && !empty($perm_row['note_eperm']))): ?>
            <!-- Note Add/Edit Form -->
            <div class="note-form">
                <h3><?php echo $edit_note_id ? $i18n->get('edit_note') : $i18n->get('add_new_note'); ?></h3>
                <form action="<?php echo $edit_note_id ? 'noteedit_secure.php' : 'noteadd_secure.php'; ?>" method="post">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($file_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php if ($edit_note_id): ?>
                        <input type="hidden" name="nid" value="<?php echo htmlspecialchars($edit_note_id, ENT_QUOTES, 'UTF-8'); ?>">
                    <?php endif; ?>
                    <textarea style="width:98%;" name="note" rows="10" required><?php echo htmlspecialchars($edit_note_content, ENT_QUOTES, 'UTF-8'); ?></textarea>
                    <br>
                    <input type="submit" value="<?php echo $i18n->get('save'); ?>" class="button" />
                    <?php if ($edit_note_id): ?>
                        <a href="ArchivedFile.php?id=<?php echo htmlspecialchars($file_id, ENT_QUOTES, 'UTF-8'); ?>" class="button"><?php echo $i18n->get('cancel_edit'); ?></a>
                    <?php endif; ?>
                </form>
            </div>
            <hr>
        <?php endif; ?>
        
        <!-- Display Notes -->
        <div class="notes-list">
            <h3><?php echo $i18n->get('registered_notes'); ?></h3>
            <?php if (empty($notes)): ?>
                <p><?php echo $i18n->get('no_notes_for_this_file'); ?></p>
            <?php else: ?>
                <?php foreach ($notes as $note): ?>
                    <div class="note-container">
                        <div class="note-meta">
                            <?php echo $i18n->get('date'); ?> <?php echo htmlspecialchars($note['timestamp'], ENT_QUOTES, 'UTF-8'); ?> | 
                            <?php echo $i18n->get('by'); ?> <?php echo htmlspecialchars($note['doneby'], ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                        <div class="note-content">
                            <?php echo nl2br(htmlspecialchars($note['note'], ENT_QUOTES, 'UTF-8')); ?>
                        </div>
                        <div class="note-actions">
                            <?php if (!empty($perm_row['note_eperm'])): ?>
                                <a href="ArchivedFile.php?id=<?php echo htmlspecialchars($file_id, ENT_QUOTES, 'UTF-8'); ?>&action=notedit&nid=<?php echo htmlspecialchars($note['id'], ENT_QUOTES, 'UTF-8'); ?>">
                                    <img src="images/edit.png" alt="<?php echo $i18n->get('edit'); ?>" border="0"/>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>