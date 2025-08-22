<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;700&display=swap" rel="stylesheet">
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
        <link href="css/login.css" rel="stylesheet">
    </head>
    <body>
        <?php include_once 'AES256.php';?>
        
        <div class="login-container">
            <div class="login-box">
                <div class="logo">
                    <img src="img/gov_logo.jpg" width="60px" height="75px">
                </div>
                <h2>تسجيل الدخول</h2>
                <?php if(isset($_GET['error']) && $_GET['error'] === 'wrong'){?><p style="color: red;">اسم المستخدم او كلمة المرور غير صحيحة</p><?php }?>
                <form method="post" enctype="multipart/form-data" action="logemp.php">
                    <?php
                        $saved_username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
                        $saved_password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
                        $decrypted_password = openssl_decrypt($saved_password, $cipher, $key, $options, $iv);
                    ?>
                    <div class="input-group">
                        <input type="text" name="username" value="<?php echo $saved_username;?>" placeholder="اسم المستخدم" required>
                        <span class="icon"><i class='bx bxs-envelope'></i></span>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" value="<?php echo $decrypted_password;?>" placeholder="كلمة المرور" required>
                        <span class="icon"><i class='bx bxs-lock' ></i></span>
                    </div>
                    <div class="options">
                        <label><input type="checkbox" name="remember_me"> تذكرني</label>
                    </div>
                    <button type="submit">تسجيل الدخول</button>
                    <p class="return-home"><a href="index.php">العودة إلى الصفحة الرئيسية</a></p>
                </form>
            </div>
        </div>
    </body>
</html>