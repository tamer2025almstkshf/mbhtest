<?php
// FILE: ClientCaseReport.php

/**
 * Generates a printable case report for a specific client.
 *
 * This script fetches all cases associated with a client and presents them
 * in a structured report format, including details about legal fees, case
 * summaries, and actions taken.
 *
 * GET Params:
 * - cid: The client ID (required, integer).
 */

// 1. INCLUDES & BOOTSTRAPPING
// =============================================================================
include_once 'connection.php';
include_once 'login_check.php';
include_once 'safe_output.php';
include_once 'src/I18n.php';

$i18n = new I18n('translations/ClientCaseReport.yaml');

// 2. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$clientId = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;

if ($clientId <= 0) {
    http_response_code(400);
    die($i18n->get('invalid_client_id'));
}

// 3. DATA FETCHING
// =============================================================================

// Fetch Client Details
$stmt = $conn->prepare("SELECT id, engname FROM client WHERE id = ?");
$stmt->bind_param("i", $clientId);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$client) {
    http_response_code(404);
    die($i18n->get('client_not_found'));
}

// Fetch all files associated with the client
$files = [];
$stmt = $conn->prepare("
    SELECT file_id, file_type, file_subject
    FROM file 
    WHERE file_client = ? OR file_client2 = ? OR file_client3 = ? OR file_client4 = ? OR file_client5 = ?
    ORDER BY file_id DESC
");
$stmt->bind_param("iiiii", $clientId, $clientId, $clientId, $clientId, $clientId);
$stmt->execute();
$result = $stmt->get_result();

while ($file = $result->fetch_assoc()) {
    // For each file, get the latest session details and English file type
    $file['latest_session'] = getLatestSessionForFile($conn, $file['file_id']);
    $file['eng_file_type'] = getEnglishFileType($conn, $file['file_type']);
    $files[] = $file;
}
$stmt->close();

// 4. HELPER FUNCTIONS
// =============================================================================
function getLatestSessionForFile($conn, $fileId) {
    $stmt = $conn->prepare("
        SELECT case_num, year FROM session 
        WHERE session_fid = ? AND session_trial != '' 
        ORDER BY session_date DESC, session_id DESC 
        LIMIT 1
    ");
    $stmt->bind_param("i", $fileId);
    $stmt->execute();
    $session = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $session;
}

function getEnglishFileType($conn, $fileTypeAr) {
    $stmt = $conn->prepare("SELECT engfile_type FROM file_types WHERE file_type = ?");
    $stmt->bind_param("s", $fileTypeAr);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $type = $result->fetch_assoc();
        $stmt->close();
        return $type['engfile_type'];
    }
    $stmt->close();
    
    // Fallback for types not in the database table
    $map = [
        'جزاء' => 'Criminal',
        'مدني -عمالى' => 'Civil',
        'المنازعات الإيجارية' => 'Rental Disputes',
        'أحوال شخصية' => 'Personal Status',
    ];
    return $map[$fileTypeAr] ?? 'General';
}

function numberToRoman($number) {
    $map = ['M'=>1000, 'CM'=>900, 'D'=>500, 'CD'=>400, 'C'=>100, 'XC'=>90, 'L'=>50, 'XL'=>40, 'X'=>10, 'IX'=>9, 'V'=>5, 'IV'=>4, 'I'=>1];
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $i18n->getLocale(); ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo $i18n->get('client_case_report'); ?></title>
    <link href="css/report_styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1><?php echo $i18n->get('client_case_report'); ?> / <?php echo safe_output($client['engname']); ?></h1>
    </header>

    <main class="content">
        <p><?php echo $i18n->get('report_documentation_aim'); ?></p>
        
        <section>
            <h2><?php echo $i18n->get('estimation_of_legal_fees'); ?></h2>
            <p><?php echo $i18n->get('legal_fees_estimation_details'); ?></p>
            <ul>
                <li><?php echo $i18n->get('lawyers'); ?>: <strong>3,000 <?php echo $i18n->get('aed_per_hour'); ?></strong></li>
                <li><?php echo $i18n->get('legal_consultants'); ?>: <strong>2,500 <?php echo $i18n->get('aed_per_hour'); ?></strong></li>
                <li><?php echo $i18n->get('legal_researchers'); ?>: <strong>2,000 <?php echo $i18n->get('aed_per_hour'); ?></strong></li>
            </ul>
        </section>
        
        <section>
            <h2><?php echo $i18n->get('cases_covered_in_report'); ?></h2>
            <?php if (empty($files)): ?>
                <p><?php echo $i18n->get('no_cases_found'); ?></p>
            <?php else: ?>
                <?php foreach ($files as $index => $file): ?>
                    <?php if ($file['latest_session']): ?>
                        <h3 class="case-title">
                            <?php echo numberToRoman($index + 1); ?>.
                            <a href="#" onclick="window.open('CasePreview.php?fid=<?php echo safe_output($file['file_id']); ?>','','resizable=yes,scrollbars=yes,width=800,height=800'); return false;">
                                <?php 
                                    echo str_replace(
                                        ['{case_type}', '{case_number}', '{charge}', '{file_number}'],
                                        [safe_output($file['eng_file_type']), safe_output($file['latest_session']['case_num'] . '/' . $file['latest_session']['year']), safe_output($file['file_subject']), safe_output($file['file_id'])],
                                        $i18n->get('case_title_format')
                                    );
                                ?>
                            </a>
                        </h3>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- Static content for demonstration purposes -->
        <section>
            <h2><?php echo $i18n->get('detailed_actions_and_timeline'); ?></h2>
            <p><strong><?php echo $i18n->get('initial_charges_upon_receipt'); ?></strong> <?php echo $i18n->get('extortion_threats_details'); ?></p>
            <p><strong><?php echo $i18n->get('referral_order'); ?></strong> <?php echo $i18n->get('felony_accusation_details'); ?></p>
            <p><strong><?php echo $i18n->get('judgment_in_absentia'); ?></strong> <?php echo $i18n->get('judgment_details'); ?></p>
            <p><strong><?php echo str_replace('{appeal_number}', '13817/2024', $i18n->get('appeal_no')); ?></strong> <?php echo str_replace('{appeal_number}', '2024/13817', $i18n->get('appeal_request_details')); ?></p>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th><?php echo $i18n->get('no'); ?></th>
                        <th><?php echo $i18n->get('date'); ?></th>
                        <th><?php echo $i18n->get('action_taken'); ?></th>
                        <th><?php echo $i18n->get('hours'); ?></th>
                        <th><?php echo $i18n->get('assigned_staff'); ?></th>
                        <th><?php echo $i18n->get('notes'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>03/11/2024</td>
                        <td><?php echo $i18n->get('police_station_review'); ?></td>
                        <td>2.5</td>
                        <td>Mr. Ashraf</td>
                        <td><?php echo $i18n->get('police_station_review_details'); ?></td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>04/11/2024</td>
                        <td><?php echo $i18n->get('investigation_hearing'); ?></td>
                        <td>3</td>
                        <td>Mr. Suhail</td>
                        <td><?php echo str_replace('{case_number}', '21086/2024', $i18n->get('investigation_hearing_details')); ?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
    
    <footer>
        <div class="line"></div>
        <div class="footer-content">
            <span><?php echo $i18n->get('footer_firm_name'); ?></span>
            <span class="page-number"><?php echo $i18n->get('page'); ?> <span class="number"></span></span>
        </div>
    </footer>
    
    <script>
        // Simple script for page numbering in print view
        document.addEventListener('DOMContentLoaded', function () {
            const pageNumberSpan = document.querySelector('footer .number');
            if (pageNumberSpan) {
                // This is a simple implementation. For multi-page reports in browser print,
                // this will only show '1'. A more complex solution with print event listeners
                // or a library like Paged.js would be needed for accurate page numbers.
                pageNumberSpan.textContent = '1'; 
            }
        });
    </script>
</body>
</html>
