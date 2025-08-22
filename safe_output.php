<?php
    function safe_output($input) {
        $allowed_tags = '<p><br><b><i><strong><em><ul><ol><li><div><span><u>';
        
        return strip_tags((string) $input, $allowed_tags);
    }
?>