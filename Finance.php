<?php
// FILE: Finance.php

/**
 * This page serves as the main dashboard for the Finance section,
 * providing navigation links to various financial modules like expenses,
 * revenues, invoices, and bank accounts.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
$pageTitle = 'قسم المالية'; // Set the page title for the header
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'layout/header.php'; // Modern, Bootstrap-based header

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view_finance = ($row_permcheck['accfinance_rperm'] === '1');

if (!$can_view_finance) {
    // Display a clear access denied message if the user lacks permission
    echo '<div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <strong><i class="bx bxs-lock-alt"></i> وصول مرفوض:</strong> ليس لديك الصلاحية اللازمة لعرض هذه الصفحة.
            </div>
          </div>';
    include_once 'layout/footer.php';
    exit(); // Stop further script execution
}

// 3. RENDER PAGE
// =============================================================================
?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header">
            <h3><i class="bx bx-money-withdraw"></i> قسم المالية</h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">الرئيسية</a></li>
                    <li class="breadcrumb-item active" aria-current="page">المالية</li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            <p class="lead">اختر أحد الأقسام أدناه لإدارة الجوانب المالية للمكتب.</p>
            <hr>
            <div class="row g-4">
                
                <!-- Expenses Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bx-wallet fs-1 text-primary"></i>
                            <h5 class="card-title mt-3">المصروفات</h5>
                            <p class="card-text">إدارة وتتبع مصروفات المكتب.</p>
                            <a href="expenses.php" class="btn btn-primary stretched-link">الانتقال إلى المصروفات</a>
                        </div>
                    </div>
                </div>

                <!-- Revenues Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bx-line-chart fs-1 text-success"></i>
                            <h5 class="card-title mt-3">الإيرادات</h5>
                            <p class="card-text">تسجيل ومتابعة الإيرادات المستلمة.</p>
                            <a href="revenues.php" class="btn btn-success stretched-link">الانتقال إلى الإيرادات</a>
                        </div>
                    </div>
                </div>

                <!-- Invoices Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bx-file fs-1 text-warning"></i>
                            <h5 class="card-title mt-3">الفواتير</h5>
                            <p class="card-text">إنشاء وإدارة فواتير العملاء.</p>
                            <a href="invoices.php" class="btn btn-warning stretched-link">الانتقال إلى الفواتير</a>
                        </div>
                    </div>
                </div>
                
                <!-- Bank Accounts Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bxs-bank fs-1 text-info"></i>
                            <h5 class="card-title mt-3">حسابات البنوك</h5>
                            <p class="card-text">إدارة حسابات البنوك الخاصة بالمكتب.</p>
                            <a href="BankAccs.php" class="btn btn-info stretched-link">الانتقال إلى الحسابات</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; // Modern, Bootstrap-based footer ?>
