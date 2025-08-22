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
                    <h3 style="text-decoration: underline; text-align: center;">Quotation</h3>
                </div>
                <div class="ardiv" dir="rtl">
                    <h3 style="text-decoration: underline; text-align: center;">عرض أسعار</h3>
                </div>
                <div class="engdiv">
                    <p class="maininfo">Date: <?php echo $todayDateEN;?></p>
                </div>
                <div class="ardiv" dir="rtl">
                    <p class="maininfo">التاريخ: <?php echo $todayDateAR;?></p>
                </div>
                <div class="engdiv">
                    <p class="maininfo">Dear <input class="maininfo" style="width: 30px; padding: 0; background: none; border: none;" type="text" value="Mr."><?php echo safe_output($engname);?><br><br>Greatings,</p>
                </div>
                <div class="ardiv" dir="rtl">
                    <p class="maininfo"><input class="maininfo" style="width: 30px; padding: 0; background: none; border: none;" type="text" value="السيد"> / <?php echo safe_output($arname);?> المحترم <br><br>تحية طيبة,, ,, وبعد</p>
                </div>
                <div class="engdiv">
                    <p>We, Mohammed Bani Hashem Advocates and Legal Consultants, are pleased to offer you the annual prices for our legal services as follows:</p>
                </div>
                <div class="ardiv" dir="rtl">
                    <p>نتشرف نحن محمد بني هاشم للمحاماة والإستشارات القانونية  بأن نقدم لكم عرض الأسعار السنوية لخدماتنا القانونية كما هو مبين أدناه:</p>
                </div>
                <table style="grid-column: span 2;">
                    <thead>
                        <tr style="background-color: #f8ccac;">
                            <th style="width: 5%"><span id="Translate1">Serial No</span></th>
                            <th style="width: 20%"><span id="Translate2">Details</span></th>
                            <th style="width: 20%"><span id="Translate3">Date</span></th>
                            <th style="width: 15%"><span id="Translate4">Amount execluding vat</span></th>
                            <th style="width: 20%"><span id="Translate5">5% Vat</span></th>
                            <th style="width: 20%"><span id="Translate6">Total amount</span></th>
                        </tr>
                    </thead>
                    
                    <tbody id="myTable">
                        <tr class="no-print">
                            <td colspan="5"><input type="button" style="background: none; outline: none; border: none; border-radius: 5px; cursor: pointer; padding: 3px; background-color: #f8ccac; font-weight: bold" value="Translate" onclick="translateTHs();"></td>
                            <td colspan="1"><input type="button" style="background: none; outline: none; border: none; border-radius: 5px; cursor: pointer; padding: 3px; background-color: #f8ccac" value="+" onclick="addTr();"></td>
                        </tr>
                    </tbody>
                    
                    <tr>
                        <td colspan="5" style="background-color: #f8ccac;"><span id="Translate7" style="font-weight: bold">Total :</span></td>
                        <td>
                            <input rows="1" style="width: 100%; resize: none; overflow: hidden; border: none; outline: none; background: transparent; font-family: inherit; font-size: inherit; padding: 4px;">
                        </td>
                    </tr>
                </table>
                <div class="engdiv">
                    <p>This price is determined by: <strong>Mr. MBH</strong></p>
                </div>
                <div class="ardiv" dir="rtl">
                    <p>تم التحديد من قبل الأستاذ / <strong>محمد بني هاشم</strong></p>
                </div>
                <div id="secondmove" style="grid-column: span 2;" class="row">
                    <div class="engdiv">
                        <p>The Professional fee mentioned is liable to the Arts below:<br><strong>1. Profissional fee are 5% Vat inclusive<br>2. The above shall be obligated according to the payment plan<br>3. The receipts and invoices will be provided by both parties upon payment<br><br>Regards,</strong></p>
                    </div>
                    <div class="ardiv" dir="rtl">
                        <p>حيث أن أتعاب المحاماة خاضعة إلى البنود المبنية أدناه:<br><strong>1. اتعاب المحاماة شاملة ضريبة القيمة المضافة بمقدار 5%<br>2. يتم التقيد بما تم ذكره أعلاه بناءًا على خطة السداد<br>3. سيتم تسليم الايصالات و الفواتير من كلا الطرفين عند السداد و الاستلام<br><br>بكل تحفظ و إحترام ,, ,,</strong></p>
                    </div>
                </div>
                <div id="firstmove" style="grid-column: span 2;" class="row">
                    <div class="engdiv">
                        <p>If you have any questions concerning this quotation, please do not hesitate to contact us<br><strong>Phone No.</strong> +971(6)731 8777 / +971 50 646 2864<br><strong>Email:</strong> info@mbhadvocates.com / mbh@mbhadvocates.com</p>
                    </div>
                    <div class="ardiv" dir="rtl">
                        <p>في حالة وجود اي استفسار متعلقة بهذا العرض ، نرجو التواصل معنا على<br><strong>رقم الهاتف:</strong> +971(6)731 8777 / +971 50 646 2864<br><strong>البريد الإلكتروني:</strong> info@mbhadvocates.com / mbh@mbhadvocates.com</p>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            function translateTHs() {
                if(document.getElementById("Translate1").textContent === 'Serial No'){
                    document.getElementById("Translate1").textContent = 'الرقم التسلسلي';
                    document.getElementById("Translate2").textContent = 'التفاصيل';
                    document.getElementById("Translate3").textContent = 'التاريخ';
                    document.getElementById("Translate4").textContent = 'اجمالي المبلغ (غير شامل لقيمة الضريبة المضافة)';
                    document.getElementById("Translate5").textContent = 'قيمة الضريبة المضافة 5%';
                    document.getElementById("Translate6").textContent = 'اجمالي المبلغ';
                    document.getElementById("Translate7").textContent = 'المجموع :';
                } else{
                    document.getElementById("Translate1").textContent = 'Serial No';
                    document.getElementById("Translate2").textContent = 'Details';
                    document.getElementById("Translate3").textContent = 'Date';
                    document.getElementById("Translate4").textContent = 'Amount execluding vat';
                    document.getElementById("Translate5").textContent = '5% Vat';
                    document.getElementById("Translate6").textContent = 'Total amount';
                    document.getElementById("Translate7").textContent = 'Total :';
                }
            }
        </script>
        <script>
            window.onload = function () {
            }
        </script>
        <script>
            let trCount = 0;
            let newPageCreated = false;
            
            function addTr() {
                if (trCount >= 7) {
                    alert("You cant create more than 7 rows");
                    return;
                }
                
                const table = document.getElementById("myTable");
                const newRow = document.createElement("tr");
                trCount++;
                
                const counterTd = document.createElement("td");
                counterTd.textContent = trCount;
                counterTd.style.backgroundColor = "#f8ccac";
                newRow.appendChild(counterTd);
                
                for (let i = 0; i < 5; i++) {
                    const td = document.createElement("td");
                    if (i === 0) td.style.backgroundColor = "#f8ccac";
                    
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
                    });
                    
                    td.appendChild(textarea);
                    newRow.appendChild(td);
                }
                
                table.appendChild(newRow);
                
                if (trCount === 1 && !newPageCreated) {
                    createNewPageAndMove('firstmove');
                }
                
                if (trCount === 4) {
                    createNewPageAndMove('secondmove');
                }
            }
            
            function createNewPageAndMove(elementId) {
                let newPage = document.querySelector(".page.new-page");
                if (!newPage) {
                    newPage = document.createElement("div");
                    newPage.className = "page new-page";
                    
                    const row = document.createElement("div");
                    row.className = "row";
                    newPage.appendChild(row);
                    
                    document.body.appendChild(newPage);
                    newPageCreated = true;
                }
                
                const element = document.getElementById(elementId);
                if (element) {
                    newPage.querySelector(".row").appendChild(element);
                }
            }
        </script>
        <?php }?>
    </body>
</html>
