<?php
$pageTitle = 'قسم المالية';
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php'; // Use modern header

// Check for permission to view the finance section
if ($row_permcheck['accfinance_rperm'] !== '1') {
    echo '<div class="container mt-5"><div class="alert alert-danger">ليس لديك الصلاحية لعرض هذه الصفحة.</div></div>';
    include_once 'layout/footer.php';
    exit();
}
?>

<div class="container-fluid mt-4">
    <div class="card">
        <div class="card-header">
            <h3><i class="bx bx-money"></i> قسم المالية</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المالية</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            <div class="row g-4">
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bx bx-wallet fs-1 text-primary"></i>
                            <h5 class="card-title mt-3">المصروفات</h5>
                            <p class="card-text">إدارة وتتبع مصروفات المكتب.</p>
                            <a href="expenses.php" class="btn btn-primary">الانتقال إلى المصروفات</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bx bx-line-chart fs-1 text-success"></i>
                            <h5 class="card-title mt-3">الإيرادات</h5>
                            <p class="card-text">تسجيل ومتابعة الإيرادات المستلمة.</p>
                            <a href="revenues.php" class="btn btn-success">الانتقال إلى الإيرادات</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bx bx-file fs-1 text-warning"></i>
                            <h5 class="card-title mt-3">الفواتير</h5>
                            <p class="card-text">إنشاء وإدارة فواتير العملاء.</p>
                            <a href="invoices.php" class="btn btn-warning">الانتقال إلى الفواتير</a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="bx bxs-bank fs-1 text-info"></i>
                            <h5 class="card-title mt-3">حسابات البنوك</h5>
                            <p class="card-text">إدارة حسابات البنوك الخاصة بالمكتب.</p>
                            <a href="bank_accounts.php" class="btn btn-info">الانتقال إلى الحسابات</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; // Use modern footer ?>
