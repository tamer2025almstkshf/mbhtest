<?php
require __DIR__ . '/vendor/autoload.php';

use GuzzleHttp\Client;
use Smalot\PdfParser\Parser;

// 1) fetch the Open-Data listing page
$client = new Client([
    'timeout' => 10,
    'headers' => [
        'User-Agent' => 'MySite PDF Sync/1.0'
    ]
]);
$res = $client->get('https://www.uaeiec.gov.ae/ar-ae/open-data-list?searchId=344c128a-b45a-4012-94b7-be8190d50e04');
$html = (string) $res->getBody();

// 2) parse it and extract all PDF links
$dom   = new \DOMDocument;
@$dom->loadHTML($html);
$xpath = new \DOMXPath($dom);

// adjust this XPath to match where PDFs live in that page
$nodes = $xpath->query("//a[contains(@href, '.pdf')]");
$pdfUrls = [];
foreach ($nodes as $a) {
    $href = $a->getAttribute('href');
    // make absolute if needed
    if (strpos($href, 'http') !== 0) {
        $href = 'https://www.uaeiec.gov.ae' . $href;
    }
    $pdfUrls[] = $href;
}

// 3) for each PDF: download + parse
$parser = new Parser;
$allText = '';
foreach ($pdfUrls as $pdfUrl) {
    try {
        $pdfRes = $client->get($pdfUrl);
        $pdfData = $pdfRes->getBody()->getContents();

        // parse PDF
        $pdf = $parser->parseContent($pdfData);
        $text = $pdf->getText();

        // you could further split into pages, lines, tables, etc.
        $allText .= "<h2>Source: " . htmlspecialchars($pdfUrl) . "</h2>\n";
        // simple HTML escape & preserve line breaks
        $allText .= '<pre style="white-space: pre-wrap;">' . htmlspecialchars($text) . "</pre>\n";

    } catch (\Exception $e) {
        // if one PDF fails, show an error but continue
        $allText .= "<p style='color:red;'>Failed to load {$pdfUrl}: "
                  . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

// 4) output to user
?>
<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>تقرير الإرهاب (محدث)</title>
  <style>
    body { font-family: Arial, sans-serif; direction: rtl; }
    pre  { background: #f3f3f3; padding: 10px; }
  </style>
</head>
<body>
  <h1>آخر تحديث لبيانات الإرهاب</h1>
  <?php echo $allText; ?>
</body>
</html>
