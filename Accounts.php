<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'src/I18n.php';

    /** @var mysqli $conn */

    $i18n = new I18n();
    $i18n->loadTranslations('translations/Accounts.yaml');
    // Use a prepared statement to securely fetch user permission data.
    $user_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_permissions = $result->fetch_assoc();
    $stmt->close();

    if (!$user_permissions) {
        // Handle case where user is not found, e.g., redirect to logout.
        header("Location: logout.php");
        exit();
    }
?>
<!DOCTYPE html>
<html dir="<?php echo $i18n->getDirection(); ?>">
<head>
    <title><?php echo $i18n->translate('title'); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="robots" content="noindex, nofollow">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
    <link href="css/styles.css" rel="stylesheet">
    <link rel="SHORTCUT ICON" href="img/favicon.ico">
</head>
<body>
    <div class="container">
        <?php include_once 'sidebar.php'; ?>
        <div class="website">
            <?php 
                include_once 'header.php';
                // Check if user has permission to view any of the account sections.
                if (
                    !empty($user_permissions['accfinance_rperm']) ||
                    !empty($user_permissions['accsecterms_rperm']) ||
                    !empty($user_permissions['accbankaccs_rperm']) ||
                    !empty($user_permissions['acccasecost_rperm']) ||
                    !empty($user_permissions['accrevenues_rperm']) ||
                    !empty($user_permissions['accexpenses_rperm'])
                ) {
            ?>
            <div class="web-page">
                <br><br>
                <div style="height: 80vh; display: flex; justify-content: center; align-items: center;">
                    <div class="advinputs-container" style="max-height: 80vh; overflow-y: auto; width: fit-content">
                        <h2 class="advinputs-h2"><?php echo $i18n->translate('accounts'); ?></h2>
                        <div class="links-container">
                            <div class="links3">
                                <?php if (!empty($user_permissions['accfinance_rperm'])) { ?>
                                    <div class="link-align">
                                        <a href="Finance.php" target="_blank" class="link link2">
                                            <div class="images-style" style="background-image: url('img/finance.png');"></div>
                                            <p class="link-topic"><?php echo $i18n->translate('finance_department'); ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($user_permissions['accsecterms_rperm'])) { ?>
                                    <div class="link-align">
                                        <a href="SubCategory.php" target="_blank" class="link link2">
                                            <div class="images-style" style="background-image: url('img/subcategory.png');"></div>
                                            <p class="link-topic"><?php echo $i18n->translate('sub_items'); ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($user_permissions['accbankaccs_rperm'])) { ?>
                                    <div class="link-align">
                                        <a href="BankAccs.php" target="_blank" class="link link2">
                                            <div class="images-style" style="background-image: url('img/bankaccs.png');"></div>
                                            <p class="link-topic"><?php echo $i18n->translate('bank_accounts'); ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($user_permissions['acccasecost_rperm'])) { ?>
                                    <div class="link-align">
                                        <a href="CasesFees.php" target="_blank" class="link link2">
                                            <div class="images-style" style="background-image: url('img/fees.png');"></div>
                                            <p class="link-topic"><?php echo $i18n->translate('case_fees'); ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($user_permissions['accrevenues_rperm'])) { ?>
                                    <div class="link-align">
                                        <a href="income.php" target="_blank" class="link link2">
                                            <div class="images-style" style="background-image: url('img/income.png');"></div>
                                            <p class="link-topic"><?php echo $i18n->translate('revenues'); ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                                <?php if (!empty($user_permissions['accexpenses_rperm'])) { ?>
                                    <div class="link-align">
                                        <a href="expenses.php" target="_blank" class="link link2">
                                            <div class="images-style" style="background-image: url('img/expenses.png');"></div>
                                            <p class="link-topic"><?php echo $i18n->translate('expenses'); ?></p>
                                        </a>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
                <div class="web-page">
                    <p style="text-align: center; margin-top: 50px;"><?php echo $i18n->translate('no_permission'); ?></p>
                </div>
            <?php } ?>
        </div>
    </div>
    
    <script src="js/translate.js"></script>
    <script src="js/toggleSection.js"></script>
    <!-- Add other scripts as needed -->
</body>
</html>