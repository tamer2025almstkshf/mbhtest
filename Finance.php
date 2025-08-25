<?php
// FILE: Finance.php

/**
 * This page serves as the main dashboard for the Finance section,
 * providing navigation links to various financial modules like expenses,
 * revenues, invoices, and bank accounts.
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
// Include the central bootstrap file
require_once __DIR__ . '/bootstrap.php';

$pageTitle = __('finance_department'); // Set the page title for the header
include_once 'layout/header.php'; // Modern, Bootstrap-based header

// 2. PERMISSIONS CHECK
// =============================================================================
$can_view_finance = ($row_permcheck['accfinance_rperm'] === '1');

if (!$can_view_finance) {
    // Display a clear access denied message if the user lacks permission
    echo '<div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <strong><i class="bx bxs-lock-alt"></i>' . __('access_denied') . ':</strong> ' . __('no_permission_to_view') . '
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
            <h3><i class="bx bx-money-withdraw"></i> <?php echo __('finance_department'); ?></h3>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php"><?php echo __('home'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo __('finance'); ?></li>
                </ol>
            </nav>
        </div>
        <div class="card-body">
            <p class="lead"><?php echo __('finance_intro_text'); ?></p>
            <hr>
            <div class="row g-4">
                
                <!-- Expenses Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bx-wallet fs-1 text-primary"></i>
                            <h5 class="card-title mt-3"><?php echo __('expenses'); ?></h5>
                            <p class="card-text"><?php echo __('manage_office_expenses'); ?></p>
                            <a href="expenses.php" class="btn btn-primary stretched-link"><?php echo __('go_to_expenses'); ?></a>
                        </div>
                    </div>
                </div>

                <!-- Revenues Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bx-line-chart fs-1 text-success"></i>
                            <h5 class="card-title mt-3"><?php echo __('revenues'); ?></h5>
                            <p class="card-text"><?php echo __('track_received_revenues'); ?></p>
                            <a href="revenues.php" class="btn btn-success stretched-link"><?php echo __('go_to_revenues'); ?></a>
                        </div>
                    </div>
                </div>

                <!-- Invoices Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bx-file fs-1 text-warning"></i>
                            <h5 class="card-title mt-3"><?php echo __('invoices'); ?></h5>
                            <p class="card-text"><?php echo __('manage_client_invoices'); ?></p>
                            <a href="invoices.php" class="btn btn-warning stretched-link"><?php echo __('go_to_invoices'); ?></a>
                        </div>
                    </div>
                </div>
                
                <!-- Bank Accounts Card -->
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 text-center feature-card">
                        <div class="card-body">
                            <i class="bx bxs-bank fs-1 text-info"></i>
                            <h5 class="card-title mt-3"><?php echo __('bank_accounts'); ?></h5>
                            <p class="card-text"><?php echo __('manage_office_bank_accounts'); ?></p>
                            <a href="BankAccs.php" class="btn btn-info stretched-link"><?php echo __('go_to_accounts'); ?></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php include_once 'layout/footer.php'; // Modern, Bootstrap-based footer ?>
