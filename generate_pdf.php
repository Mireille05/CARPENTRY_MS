<?php
// Ensure no output is sent before PDF generation
ob_start();

// Include the FPDF library
require('fpdf186/fpdf.php');

// Database configuration
require_once 'config.php'; // Assuming config.php contains DB credentials
try {
    $conn = new PDO("pgsql:host=" . DB_HOST . ";port=5432;dbname=" . DB_NAME, DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    ob_end_clean();
    die("Database connection failed: " . htmlspecialchars($e->getMessage()));
}

// Fetch data from the 'orders' table
$sql = "SELECT id, name, items, total FROM orders";
$stmt = $conn->query($sql);

// Extend FPDF to add header and footer
class PDF extends FPDF {
    function Header() {
        // Logo (adjust path as needed)
        if (file_exists('images/logo.jpg')) {
            $this->Image('images/logo.jpg', 10, 10, 30);
        }
        // Company Name
        $this->SetFont('Arial', 'B', 16);
        $this->SetTextColor(51, 51, 51); // Dark Gray (#333333)
        $this->Cell(0, 10, 'MasterCraft Woodworks', 0, 1, 'C');
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Order Summary', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(51, 51, 51); // Dark Gray (#333333)
        $this->Cell(0, 10, '© 2025 MasterCraft Woodworks. All rights reserved. | Page ' . $this->PageNo(), 0, 0, 'C');
    }
}

// Create a new PDF instance
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetAutoPageBreak(true, 20);

// Setting margins for better layout
$pdf->SetMargins(10, 10, 10);

// Table header with Forest Green background
$pdf->SetFillColor(46, 125, 50); // Forest Green (#2E7D32)
$pdf->SetTextColor(255, 255, 255); // White
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(30, 10, 'Order ID', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Customer Name', 1, 0, 'C', true);
$pdf->Cell(80, 10, 'Items Ordered', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Total', 1, 1, 'C', true);

// Table rows with data
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(85, 85, 85); // Medium Gray (#555555)

if ($stmt->rowCount() > 0) {
    $row_count = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Alternate row background
        $pdf->SetFillColor($row_count % 2 == 0 ? 255 : 245, $row_count % 2 == 0 ? 255 : 245, $row_count % 2 == 0 ? 255 : 245); // White or light gray
        $fill = $row_count % 2 == 0 ? false : true;

        // Parse items (assuming JSON from cart)
        $items_display = '';
        if (is_string($row['items'])) {
            $items = json_decode($row['items'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($items)) {
                $item_names = array_map(function($item) {
                    // Truncate long names to 30 characters
                    $name = $item['name'] ?? 'Unknown Item';
                    return strlen($name) > 30 ? substr($name, 0, 27) . '...' : $name;
                }, $items);
                $items_display = implode(', ', $item_names);
            } else {
                $items_display = 'Invalid items data';
            }
        } else {
            $items_display = 'N/A';
        }

        // Calculate cell height based on items text
        $lines = $pdf->NbLines(80, $items_display); // Custom function to estimate lines
        $cell_height = 10 * max(1, $lines);

        // Print row
        $pdf->Cell(30, $cell_height, $row['id'], 1, 0, 'C', $fill);
        $pdf->Cell(50, $cell_height, $row['name'], 1, 0, 'C', $fill);
        $pdf->MultiCell(80, 10, $items_display, 1, 'L', $fill);
        $pdf->SetXY(170, $pdf->GetY() - $cell_height); // Adjust position for Total column
        $pdf->Cell(30, $cell_height, '$' . number_format($row['total'], 2), 1, 1, 'C', $fill);

        $row_count++;
    }
} else {
    $pdf->SetFillColor(255, 255, 255);
    $pdf->Cell(190, 10, 'No orders found', 1, 1, 'C', true);
}

// Custom function to estimate number of lines for MultiCell
function NbLines($pdf, $width, $text) {
    $cw = &$pdf->CurrentFont['cw'];
    if ($width == 0) {
        $width = $pdf->w - $pdf->rMargin - $pdf->x;
    }
    $wmax = ($width - 2 * $pdf->cMargin) * 1000 / $pdf->FontSize;
    $s = str_replace("\r", '', $text);
    $nb = strlen($s);
    if ($nb > 0 && $s[$nb - 1] == "\n") {
        $nb--;
    }
    $sep = -1;
    $i = 0;
    $j = 0;
    $l = 0;
    $nl = 1;
    while ($i < $nb) {
        $c = $s[$i];
        if ($c == "\n") {
            $i++;
            $sep = -1;
            $j = $i;
            $l = 0;
            $nl++;
            continue;
        }
        if ($c == ' ') {
            $sep = $i;
        }
        $l += $cw[$c];
        if ($l > $wmax) {
            if ($sep == -1) {
                if ($i == $j) {
                    $i++;
                }
            } else {
                $i = $sep + 1;
            }
            $sep = -1;
            $j = $i;
            $l = 0;
            $nl++;
        } else {
            $i++;
        }
    }
    return $nl;
}

// Add the custom function to the PDF instance
$pdf->NbLines = function($width, $text) use ($pdf) {
    return NbLines($pdf, $width, $text);
};

// Clear output buffer and send the PDF
ob_end_clean();
$pdf->Output('D', 'orders_summary.pdf');
?>