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

// 2. INPUT VALIDATION & INITIALIZATION
// =============================================================================
$clientId = isset($_GET['cid']) ? (int)$_GET['cid'] : 0;

if ($clientId <= 0) {
    http_response_code(400);
    die('Invalid or missing Client ID.');
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
    die('Client not found.');
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Case Report</title>
    <link href="css/report_styles.css" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Client Case Report / <?php echo safe_output($client['engname']); ?></h1>
    </header>

    <main class="content">
        <p>This report aims to document the time allocated to complete the tasks assigned to our legal team, record the reviews conducted with relevant authorities, and detail the efforts exerted in the cases entrusted to us.</p>
        
        <section>
            <h2>A. Estimation of Legal Fees:</h2>
            <p>Legal fees per hour for each category of legal specialists have been estimated as follows:</p>
            <ul>
                <li>Lawyers: <strong>AED 3,000 per hour.</strong></li>
                <li>Legal Consultants: <strong>AED 2,500 per hour.</strong></li>
                <li>Legal Researchers: <strong>AED 2,000 per hour.</strong></li>
            </ul>
        </section>
        
        <section>
            <h2>B. Cases Covered in the Report:</h2>
            <?php if (empty($files)): ?>
                <p>No cases found for this client.</p>
            <?php else: ?>
                <?php foreach ($files as $index => $file): ?>
                    <?php if ($file['latest_session']): ?>
                        <h3 class="case-title">
                            <?php echo numberToRoman($index + 1); ?>.
                            <a href="#" onclick="window.open('CasePreview.php?fid=<?php echo safe_output($file['file_id']); ?>','','resizable=yes,scrollbars=yes,width=800,height=800'); return false;">
                                <?php echo safe_output($file['eng_file_type']); ?> Case No. <?php echo safe_output($file['latest_session']['case_num'] . '/' . $file['latest_session']['year']); ?>
                                – Charge: <?php echo safe_output($file['file_subject']); ?> (File No. <?php echo safe_output($file['file_id']); ?>)
                            </a>
                        </h3>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </section>

        <!-- Static content for demonstration purposes -->
        <section>
            <h2>C. Detailed Actions and Timeline:</h2>
            <p><strong>Initial Charges Upon Receipt:</strong> Extortion or threats using an information network or technological means.</p>
            <p><strong>Referral Order:</strong> Accusation of committing a felony against a person using information technology (WhatsApp), specifically stating, “I will kill you, you must die.”</p>
            <p><strong>Judgment issued on 10/12/2024 (In Absentia):</strong> The defendant was fined AED 5,000, and the civil claim was referred to the competent civil court. Despite falling under the aforementioned provisions, the trial proceeded under Articles 42/1, 56, and 59/1 (legitimate self-defense rights).</p>
            <p><strong>Appeal No. 13817/2024:</strong> A request was submitted to the Public Prosecution to appeal the issued judgment, registered under Appeal No. 2024/13817, with a hearing scheduled for 20/03/2025.</p>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Date</th>
                        <th>Action Taken</th>
                        <th>Hours</th>
                        <th>Assigned Staff</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>03/11/2024</td>
                        <td>Police Station Review</td>
                        <td>2.5</td>
                        <td>Mr. Ashraf</td>
                        <td>Visited Al Barsha Police Station to attempt to file a criminal report; unsuccessful due to a smart system malfunction.</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>04/11/2024</td>
                        <td>Investigation Hearing</td>
                        <td>3</td>
                        <td>Mr. Suhail</td>
                        <td>Received a message from the client regarding their attendance at the Public Prosecution for an investigation session related to Case No. 21086/2024.</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
    
    <footer>
        <div class="line"></div>
        <div class="footer-content">
            <span>MBH Advocates & Legal Consultants</span>
            <span class="page-number">Page <span class="number"></span></span>
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
