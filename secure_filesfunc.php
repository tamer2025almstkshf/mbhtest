<?php
    define('MAX_FILE_SIZE', 10 * 1024 * 1024);
    define('ALLOWED_EXTENSIONS', ['pdf','doc','docx','jpg','jpeg','png','xls','xlsx','zip']);
    define('ALLOWED_MIME_TYPES', [
        'pdf'  => 'application/pdf',
        'doc'  => 'application/msword',
        'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'xls'  => 'application/vnd.ms-excel',
        'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'zip'  => 'application/zip'
    ]);
    
    function secure_file_upload(array $file, string $target_dir): array {
        // 1) basic PHP upload errors
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['status'=>false,'path'=>'','size'=>'','error'=>'No file uploaded'];
        }
        // 2) size check
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['status'=>false,'path'=>'','size'=>'','error'=>'File too large'];
        }
        // 3) extension whitelist
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ALLOWED_EXTENSIONS, true)) {
            return ['status'=>false,'path'=>'','size'=>'','error'=>'Invalid file type'];
        }
        // 4) validate mime type
        $mime = mime_content_type($file['tmp_name']);
        if (!isset(ALLOWED_MIME_TYPES[$ext]) || ALLOWED_MIME_TYPES[$ext] !== $mime) {
            return ['status'=>false,'path'=>'','size'=>'','error'=>'Invalid mime type'];
        }
        // 5) ensure dir exists with safe permissions and parent not world-writable
        $parentDir = dirname($target_dir);
        if (is_dir($parentDir) && (fileperms($parentDir) & 0x0002)) {
            return ['status'=>false,'path'=>'','size'=>'','error'=>'Insecure parent directory'];
        }
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        // 6) build destination using randomized filename
        $safeName   = bin2hex(random_bytes(16)).'.'.$ext;
        $targetPath = rtrim($target_dir, '/').'/'.$safeName;
        // 7) move it
        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return ['status'=>false,'path'=>'','size'=>'','error'=>'Failed to move upload'];
        }
        // 8) human‐readable size
        $size = $file['size'];
        if ($size >= 1073741824) {
            $size = round($size/1073741824,2).' GB';
        } elseif ($size >= 1048576) {
            $size = round($size/1048576,2).' MB';
        } elseif ($size >= 1024) {
            $size = round($size/1024,2).' KB';
        } else {
            $size .= ' B';
        }
        return ['status'=>true,'path'=>$targetPath,'size'=>$size,'error'=>''];
    }
?>