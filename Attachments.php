<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php'; // Assuming a generic permission check file

    // --- 1. Permission Check ---
    // Replace 'attachments_rperm' with the actual permission required for this page.
    if (empty($perm_row['attachments_rperm'])) {
        die("Access Denied: You do not have permission to view this page.");
    }

    // --- 2. Path Traversal Prevention ---
    $base_dir = realpath(__DIR__ . '/files_images/file_upload');
    
    // Get the folder from the URL and sanitize it to prevent traversal.
    // This removes '.', '/', and '\' to ensure the user cannot navigate up the directory tree.
    $selected_folder_unsafe = $_GET['folder'] ?? '';
    $selected_folder_safe = basename(str_replace(['..', '/', '\\'], '', $selected_folder_unsafe));
    
    $directory = $base_dir . DIRECTORY_SEPARATOR . $selected_folder_safe;
    $resolved_path = realpath($directory);

    // Final security check: ensure the resolved path is still within the base directory.
    if ($resolved_path === false || strpos($resolved_path, $base_dir) !== 0) {
        die("Access Denied: Invalid directory specified.");
    }

?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>المرفقات</title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <div class="web-page">
                <table width="99%" border="1" cellspacing="0" cellpadding="0" align="center" class="info-table">
                    <thead>
                        <tr class="header_table">
                            <th colspan="4" align="center">
                                <input type="text" placeholder="بحث..." dir="ltr" id="SearchBox" class="form-input">
                            </th>
                        </tr>
                        <tr class="header_table">
                            <th colspan="4" align="center">رقم الملف</th>
                        </tr>
                    </thead>
                    <tbody id="table1">
                        <?php
                            $subfolders = [];
                            if (is_dir($directory)) {
                                $contents = scandir($directory);
                                $subfolders = array_filter($contents, function($item) use ($directory) {
                                    return is_dir($directory . DIRECTORY_SEPARATOR . $item) && !in_array($item, ['.', '..']);
                                });
                            }

                            if (!empty($subfolders)):
                                foreach ($subfolders as $folder):
                                    // --- 3. XSS Prevention ---
                                    $folder_encoded = htmlspecialchars($folder, ENT_QUOTES, 'UTF-8');
                                    $selected_folder_encoded = htmlspecialchars($selected_folder_safe, ENT_QUOTES, 'UTF-8');
                        ?>
                        <tr class="infotable-body">
                            <th align="right" style="padding-right:10px;" colspan='4'>
                                <a href="fileidAttachment.php?folder=<?php echo urlencode("$selected_folder_encoded/$folder_encoded"); ?>&fid=<?php echo $folder_encoded; ?>">
                                    <?php echo $folder_encoded; ?>
                                </a>
                            </th>
                        </tr>
                        <?php
                                endforeach;
                            else:
                        ?>
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 20px;">المجلد فارغ أو غير موجود.</td>
                        </tr>
                        <?php
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function(){
            $('#SearchBox').on("keyup", function(){
                var value = $(this).val().toLowerCase();
                $("#table1 tr").filter(function(){
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>