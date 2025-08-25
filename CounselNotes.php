<?php
// FILE: CounselNotes.php

/**
 * Displays a single counsel note related to a specific file.
 *
 * GET Params:
 * - nid: The Note ID (required, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php'; // Ensures the user is logged in
include_once 'safe_output.php';
include_once 'src/I18n.php';

$i18n = new I18n('translations/CounselNotes.yaml');

// 2. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$noteId = isset($_GET['nid']) ? (int)$_GET['nid'] : 0;

if ($noteId <= 0) {
    http_response_code(400);
    die($i18n->get('invalid_note_id'));
}

// 3. DATA FETCHING
// =============================================================================
$noteData = null;
$fileData = null;

// Fetch the note details
$stmt = $conn->prepare("SELECT * FROM file_note WHERE id = ?");
$stmt->bind_param("i", $noteId);
$stmt->execute();
$result = $stmt->get_result();
$noteData = $result->fetch_assoc();
$stmt->close();

if (!$noteData) {
    http_response_code(404);
    die($i18n->get('note_not_found'));
}

// Fetch the associated file details using the file_id from the note
$fileId = $noteData['file_id'];
$stmt = $conn->prepare("SELECT file_id, frelated_place FROM file WHERE file_id = ?");
$stmt->bind_param("i", $fileId);
$stmt->execute();
$result = $stmt->get_result();
$fileData = $result->fetch_assoc();
$stmt->close();

if (!$fileData) {
    // This indicates a data integrity issue (a note without a file), but we should handle it.
    http_response_code(404);
    die($i18n->get('associated_file_not_found'));
}

// 4. HELPER FUNCTIONS
// =============================================================================
function getFilePrefix($place) {
    $prefixes = [
        'عجمان' => 'AJM',
        'دبي' => 'DXB',
        'الشارقة' => 'SHJ'
    ];
    return $prefixes[$place] ?? '';
}

?>
<!DOCTYPE html>
<html dir="<?php echo $i18n->getDirection(); ?>" lang="<?php echo $i18n->getLocale(); ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $i18n->get('notes_on_file'); ?></title>
    <link href="css/styles.css" rel="stylesheet"> <!-- Assuming a general stylesheet -->
    <style>
        body {
            background-color: #f4f7f9;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .note-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            overflow: hidden; /* To contain floated elements and rounded corners */
        }
        .note-header {
            background-color: #f9f9f9;
            padding: 15px 20px;
            border-bottom: 1px solid #ddd;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        .file-id-prefix {
            color: #d9534f; /* Reddish color for emphasis */
        }
        .note-body {
            padding: 20px;
            font-size: 1rem;
            line-height: 1.6;
            color: #555;
            white-space: pre-wrap; /* Respects newlines and whitespace */
        }
        .note-footer {
            background-color: #f9f9f9;
            padding: 10px 20px;
            border-top: 1px solid #ddd;
            text-align: left;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="note-container">
        <header class="note-header">
            <?php echo $i18n->get('notes_for_file_number'); ?>
            <span class="file-id-prefix">
                <?php echo safe_output(getFilePrefix($fileData['frelated_place'])); ?>
            </span>
            <?php echo safe_output($fileData['file_id']); ?>
        </header>

        <main class="note-body">
            <p><?php echo nl2br(safe_output($noteData['note'])); ?></p>
        </main>
        
        <footer class="note-footer">
            <span><?php echo safe_output($noteData['timestamp']); ?></span>
            <?php if (!empty($noteData['doneby'])): ?>
                <span> | <?php echo $i18n->get('by'); ?>: <?php echo safe_output($noteData['doneby']); ?></span>
            <?php endif; ?>
        </footer>
    </div>

</body>
</html>
