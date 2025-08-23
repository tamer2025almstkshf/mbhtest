<?php
$pageTitle = 'تسجيل دخول الموظفين';
include_once 'layout/header.php';
include_once 'AES256.php';
?>

<div class="container">
    <div class="login-container mx-auto">
        <div class="text-center mb-4">
            <img src="img/gov_logo.jpg" width="60" height="75" alt="Logo">
            <h2 class="mt-3">تسجيل الدخول</h2>
        </div>

        <?php if(isset($_GET['error']) && $_GET['error'] === 'wrong'): ?>
            <div class="alert alert-danger">
                اسم المستخدم او كلمة المرور غير صحيحة
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" action="logemp.php">
            <?php
                $saved_username = isset($_COOKIE['username']) ? $_COOKIE['username'] : '';
                $saved_password = isset($_COOKIE['password']) ? $_COOKIE['password'] : '';
                $decrypted_password = '';
                if (!empty($saved_password)) {
                    $decrypted_password = openssl_decrypt($saved_password, $cipher, $key, $options, $iv);
                }
            ?>
            <div class="mb-3">
                <label for="username" class="form-label">اسم المستخدم</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($saved_username); ?>" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($decrypted_password); ?>" required>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember_me" id="remember_me">
                <label class="form-check-label" for="remember_me">
                    تذكرني
                </label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">تسجيل الدخول</button>
            </div>
            
            <p class="mt-3 text-center"><a href="index.php">العودة إلى الصفحة الرئيسية</a></p>
        </form>
    </div>
</div>

<?php include_once 'layout/footer.php'; ?>
