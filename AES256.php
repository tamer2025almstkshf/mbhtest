<?php

    $cipher = "AES-256-CBC";

    $key = "b3fffe78a867a985188004e92d79e4e19fefb83386822f940cb2dce9f24ea064";

    $options = 0;

    $iv = str_repeat("0", openssl_cipher_iv_length($cipher));

?>