<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'permissions_check.php';
    include_once 'safe_output.php';
    include_once 'golden_pass.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <title>محمد بني هاشم للمحاماة و الاستشارات القانونية</title>
        <meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
        <meta name="google-site-verification" content="_xmqQ0kTuDS9ta1v4E4je5rweWQ4qtH1l8_cnWro7Tk" />
        <meta name="robots" content="noindex, nofollow">
        <meta name="googlebot" content="noindex">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="shortcut icon" href="files/images/instance/favicon.ico?v=35265" type="image/icon">
        <link rel="SHORTCUT ICON" href="img/favicon.ico">
        <style>
            @page {
                size: A4;
                margin: 0;
            }
            
            .maininfo {
                font-weight: bold;
            }
            
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }
            
            .page {
                background-image: url('img/Picture1.png');
                background-repeat: no-repeat;
                background-size: cover;
                width: 210mm;
                height: 297mm;
                padding: 40mm 20mm 20mm 20mm;
                box-sizing: border-box;
                page-break-after: always;
            }
            
            label {
                font-weight: bold;
            }
            
            input, textarea {
                width: 100%;
                padding: 5px;
                margin-top: 5px;
                box-sizing: border-box;
            }
            
            @media print {
                body, .page {
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                    background-image: url('img/Picture1.png');
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: top left;
                }
                
                .no-print {
                    display: none;
                }
                
                input, textarea {
                    border: none;
                    background: transparent;
                    outline: none;
                }
                
                textarea::-webkit-scrollbar {
                    display: none;
                }
            }
            
            .row {
                display: grid; 
                grid-template-columns: 1fr 1fr;
            }
            
            .engdiv{
                text-align: left;
            }
            
            .ardiv{
                text-align: right;
            }
            
            table {
                border-collapse: collapse;
            }
            
            th, td {
                border: 2px solid #000001;
                padding: 8px;
                text-align: center;
            }
            
            th {
                background-color: #f4b183;
            }
        </style>
    </head>
    <body>
        <?php if($row_permcheck['sdocs_pperm'] == 1){?>
        <div class="no-print" style="position: sticky; top: 0; width: 100%;">
            <input type="button" style="width: 210mm; background: none; outline: none; border: none; border-radius: 5px; cursor: pointer; padding: 3px; background-color: #f8ccac; font-size: 20px; font-weight: bold;" value="طباعة" onclick="print();">
        </div>
        <?php } if($row_permcheck['sdocs_rperm'] == 1){?>
        <div class="page">
            <?php 
                $d = date("d");
                $mEN = date("M");
                $y = date("Y");
                $todayDateEN = "$d / $mEN / $y";
                
                $months_ar = [
                    'Jan' => 'يناير',
                    'Feb' => 'فبراير',
                    'Mar' => 'مارس',
                    'Apr' => 'أبريل',
                    'May' => 'مايو',
                    'Jun' => 'يونيو',
                    'Jul' => 'يوليو',
                    'Aug' => 'أغسطس',
                    'Sep' => 'سبتمبر',
                    'Oct' => 'أكتوبر',
                    'Nov' => 'نوفمبر',
                    'Dec' => 'ديسمبر'
                ];
                $mAR = $months_ar[$mEN];
                $todayDateAR = "$d / $mAR / $y";
                
                $cid = $_GET['cid'];
                $stmt = $conn->prepare("SELECT * FROM client WHERE id=?");
                $stmt->bind_param("i", $cid);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
                $stmt->close();
                
                $arname = $row['arname'];
                $engname = $row['engname'];
            ?>
            <div class="row">
                <div class="engdiv">
                    <h3 style="text-decoration: underline; text-align: center;">Fees Agreement</h3>
                </div>
                <div class="ardiv" dir="rtl">
                    <h3 style="text-decoration: underline; text-align: center;">إتفاقية الأتعاب</h3>
                </div>
            </div>
            <table style="width: 100%;">
                <tbody id="myTable">
                    <tr class="no-print">
                        <td colspan="2"><input type="button" style="background: none; outline: none; border: none; border-radius: 5px; cursor: pointer; padding: 3px; background-color: #f8ccac" value="+" onclick="addTr();"></td>
                    </tr>
                </tbody>
                
                <tr>
                    <td>
                        <input rows="1" style="width: 100%; resize: none; overflow: hidden; border: none; outline: none; background: transparent; font-family: inherit; font-size: inherit; padding: 4px;">
                    </td>
                    <td>
                        <input rows="1" style="width: 100%; resize: none; overflow: hidden; border: none; outline: none; background: transparent; font-family: inherit; font-size: inherit; padding: 4px;">
                    </td>
                </tr>
            </table>
        </div>
        <script>
            window.onload = function () {
            }
        </script>
        <script>
let pages = [document.querySelector(".page")];
const MAX_TABLE_HEIGHT = 600; // px

function addTr() {
    const currentPage = pages[pages.length - 1];
    const tableBody = currentPage.querySelector("tbody");

    const newRow = createRow();
    tableBody.appendChild(newRow);

    monitorTableOverflow(currentPage);
}

function createRow() {
    const newRow = document.createElement("tr");

    for (let i = 0; i < 2; i++) {
        const td = document.createElement("td");
        const textarea = document.createElement("textarea");

        textarea.setAttribute("rows", "1");
        textarea.style = `
            width: 100%;
            resize: none;
            overflow: hidden;
            border: none;
            outline: none;
            background: transparent;
            font-family: inherit;
            font-size: 14px;
            padding: 4px;
        `;
        textarea.addEventListener("input", function () {
            this.style.height = "auto";
            this.style.height = this.scrollHeight + "px";
            monitorTableOverflow(pages[pages.length - 1]); // check during typing
        });

        td.appendChild(textarea);
        newRow.appendChild(td);
    }

    return newRow;
}

function monitorTableOverflow(page) {
    const table = page.querySelector("table");
    const tableBody = page.querySelector("tbody");

    setTimeout(() => {
        while (table.offsetHeight > MAX_TABLE_HEIGHT && tableBody.rows.length > 1) {
            // Move last row to new page
            const lastRow = tableBody.lastElementChild;
            if (!lastRow || lastRow.classList.contains('no-print')) break;

            const nextPage = ensureNextPage();
            nextPage.querySelector("tbody").prepend(lastRow);
        }
    }, 0);
}

function ensureNextPage() {
    let nextPage = pages[pages.length - 1];

    const table = nextPage.querySelector("table");
    if (table.offsetHeight <= MAX_TABLE_HEIGHT) {
        return nextPage;
    }

    // Create new page
    const newPage = document.createElement("div");
    newPage.className = "page new-page";
    newPage.innerHTML = `
        <div class="row">
            <div class="engdiv">
                <h3 style="text-decoration: underline; text-align: center;">Fees Agreement</h3>
            </div>
            <div class="ardiv" dir="rtl">
                <h3 style="text-decoration: underline; text-align: center;">إتفاقية الأتعاب</h3>
            </div>
        </div>
        <table style="width: 100%;">
            <tbody></tbody>
        </table>
    `;
    document.body.appendChild(newPage);
    pages.push(newPage);
    return newPage;
}
</script>




        <?php }?>
    </body>
</html>
