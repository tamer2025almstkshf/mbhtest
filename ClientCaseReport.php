<?php
    include_once 'connection.php';
    include_once 'login_check.php';
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Client Case Report</title>
        <style>
            * {
                font-family: Arial;
            }
            
            body {
                margin: 0 20mm 20mm 20mm; /* Smaller margin: 20mm from all sides */
                color: #000000;
                font-size: 14px;
                position: relative;
                padding-bottom: 60px; /* Reserve space for footer */
            }
            
            h1, h2, h3 {
                font-weight: bold;
            }
            
            h1 {
                text-align: center;
                font-size: 18px;
                margin-bottom: 20px;
            }
            
            h2 {
                font-size: 16px;
                margin-top: 40px;
            }
            
            a {
                color: #007bff;
            }
            
            p {
                margin: 10px 0;
                line-height: 1.6;
            }
            
            ul {
                margin: 10px 20px;
            }
            
            li {
                margin-bottom: 10px;
            }
            
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 30px;
                font-size: 13px;
                page-break-inside: auto;
            }
            
            table, th, td {
                border: 1px solid #ddd;
            }
            
            th, td {
                padding: 8px;
                text-align: left;
                vertical-align: top;
            }
            
            th {
                background-color: #f2f2f2;
            }
            
            footer {
                position: fixed;
                bottom: 5mm; /* Take the footer closer to the bottom (was 10mm) */
                left: 20mm;
                right: 20mm;
                width: calc(100% - 40mm);
                text-align: left;
                font-size: 12px;
            }
            
            footer .line {
                border-top: 1px solid gray;
                margin-bottom: 2mm;
            }
            
            footer .pagenumber {
                letter-spacing: 2px;
                color: gray;
                display: inline-block;
            }
            
            footer .number {
                color: #007bff;
                margin-left: 3px;
            }
            
            @page {
                margin: 20mm 20mm 20mm 20mm; /* Same margins for printing */
            }
            
            @media print {
                html, body {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }
            }
        </style>
    </head>
    <body>
        <div class="content">
            <?php
                $cid = $_GET['cid'];
                $queryclient = "SELECT * FROM client WHERE id='$cid'";
                $resultclient = mysqli_query($conn, $queryclient);
                $rowclient = mysqli_fetch_array($resultclient);
            ?>
            <h1>Client Case Report / <?php echo $rowclient['engname'];?></h1>
            <p>This report aims to document the time allocated to complete the tasks assigned to our legal team, record the reviews conducted with relevant authorities, and detail the efforts exerted in the cases entrusted to us.</p>
            
            <h2>A. Estimation of Legal Fees:</h2>
            <p style="margin-left: 20px">Legal fees per hour for each category of legal specialists have been estimated as follows:</p>
            
            <ul>
                <li>Lawyers: AED 3,000 per hour.</li>
                <li>Legal Consultants: AED 2,500 per hour.</li>
                <li>Legal Researchers: AED 2,000 per hour.</li>
            </ul>
            
            <h2>B. Cases Covered in the Report:</h2>
            
        <?php
            function numberToRoman($number) {
                $map = [
                    'M'  => 1000,
                    'CM' => 900,
                    'D'  => 500,
                    'CD' => 400,
                    'C'  => 100,
                    'XC' => 90,
                    'L'  => 50,
                    'XL' => 40,
                    'X'  => 10,
                    'IX' => 9,
                    'V'  => 5,
                    'IV' => 4,
                    'I'  => 1
                ];
                
                $roman = '';
                
                foreach ($map as $romanChar => $value) {
                    while ($number >= $value) {
                        $roman .= $romanChar;
                        $number -= $value;
                    }
                }
                
                return $roman;
            }
            
            $countrome = 0;
            
            $queryfile = "SELECT * FROM file WHERE file_client='$cid' OR file_client2='$cid' OR file_client3='$cid' OR file_client4='$cid' OR file_client5='$cid' ORDER BY file_id DESC";
            $resultfile = mysqli_query($conn, $queryfile);
            if($resultfile->num_rows > 0){
                while($rowfile = mysqli_fetch_array($resultfile)){
                    $countrome++;
                    
                    $sfid = $rowfile['file_id'];
                    $querysess = "SELECT * FROM session WHERE session_fid='$sfid' AND session_trial!='' ORDER BY session_date DESC, session_id DESC";
                    $resultsess = mysqli_query($conn, $querysess);
                    $rowsess = mysqli_fetch_array($resultsess);
                    
                    $file_type = $rowfile['file_type'];
                    $queryft = "SELECT * FROM file_types WHERE file_type='$file_type'";
                    $resultft = mysqli_query($conn, $queryft);
                    
                    if($resultft->num_rows > 0){
                        $rowft = mysqli_fetch_array($resultft);
                        $engfile_type = $rowft['engfile_type'];
                    } else {
                        if($file_type === 'جزاء'){
                            $engfile_type = 'Criminal';
                        } else if($file_type === 'مدني -عمالى'){
                            $engfile_type = 'Civil';
                        } else if($file_type === 'المنازعات الإيجارية'){
                            $engfile_type = 'Rental disputes';
                        } else if($file_type === 'أحوال شخصية'){
                            $engfile_type = 'Personal status';
                        }
                    }
                    
        ?>
        <h3 style="margin-left: 20px; color: #3c88cc;">
            <?php echo numberToRoman($countrome);?>.
            <a href="#" style="color: #3c88cc;" onclick="MM_openBrWindow('CasePreview.php?fid=<?php echo $rowfile['file_id'];?>','','resizable=yes,status=no,location=no,toolbar=no,menubar=no,fullscreen=no,scrollbars=no,width=800,height=800')">
                <?php echo $engfile_type;?> Case No. <?php echo $rowsess['case_num'];?>/<?php echo $rowsess['year'];?> – Charge: <?php echo $rowfile['file_subject'];?> (File No. <?php echo $rowfile['file_id'];?>)
            </a>
        </h3>
        <?php }}?>
            <p><strong>Initial Charges Upon Receipt:</strong> Extortion or threats using an information network or technological means.</p>
            <p><strong>Referral Order:</strong> Accusation of committing a felony against a person using information technology (WhatsApp), specifically stating, “I will kill you, you must die.”</p>
            <p><strong>Judgment issued on 10/12/2024 (In Absentia):</strong> The defendant was fined AED 5,000, and the civil claim was referred to the competent civil court. Despite falling under the aforementioned provisions, the trial proceeded under Articles 42/1, 56, and 59/1 (legitimate self-defense rights).</p>
            <p><strong>Appeal No. 13817/2024:</strong> A request was submitted to the Public Prosecution to appeal the issued judgment, registered under Appeal No. 2024/13817, with a hearing scheduled for 20/03/2025.</p>
            
            <table>
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
        </div>
        
        <footer>
            <div class="line"></div>
            <div><span class="pagenumber">Page |</span><span class="number"></span></div>
        </footer>
        
        <script src="js/newWindow.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const footerNumber = document.querySelector('footer .number');
                if (footerNumber) {
                    footerNumber.textContent = '1';
                }
            });
        </script>
    </body>
</html>
