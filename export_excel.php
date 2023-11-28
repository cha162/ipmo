<?php
session_start();
require 'config.php';
require 'phpspreadsheet/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Retrieve filtered data based on the date range
if (isset($_GET['startDate']) && isset($_GET['endDate']) && (isset($_GET['collegesDropdown']) && isset($_GET['programsDropdown']))) {
    $startDate = $_GET['startDate'];
    $endDate = $_GET['endDate'];
    $collegesDropdown = $_GET['collegesDropdown'];
    $programsDropdown = $_GET['programsDropdown'];

        $query = "SELECT r.`MRecordID`, r.`RegistrationNumber`, a.`Author`, a.`ThesisTitle`, a.`Adviser`, a.`DateOfSubmission`, c.`College_Name` AS College, p.`Program_Name` AS Program
              FROM `mainapplicationrecords` r
              JOIN `mainapplication` a ON r.`MFileID` = a.`MAppID`
              JOIN `college` c ON a.`CollegeID` = c.`CollegeID`
              JOIN `program` p ON a.`ProgramID` = p.`ProgramID`
              WHERE a.`DateOfSubmission` BETWEEN ? AND ? AND a.`CollegeID` = ? AND a.`ProgramID` = ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ssss', $startDate, $endDate, $collegesDropdown, $programsDropdown);

        $stmt->execute();
    $result = $stmt->get_result();

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Registration Number');
    $sheet->setCellValue('B1', 'Author');
    $sheet->setCellValue('C1', 'Thesis Title');
    $sheet->setCellValue('D1', 'College');
    $sheet->setCellValue('E1', 'Program');
    $sheet->setCellValue('F1', 'Adviser');
    $sheet->setCellValue('G1', 'Date Of Submission');

    // Set column width
    $sheet->getColumnDimension('A')->setWidth(21);
    $sheet->getColumnDimension('B')->setWidth(35);
    $sheet->getColumnDimension('C')->setWidth(55);
    $sheet->getColumnDimension('D')->setWidth(54);
    $sheet->getColumnDimension('E')->setWidth(56);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(18);

    $style = [
        'font' => [
            'bold' => true
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Horizontal alignment
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Vertical alignment
        ]
    ];

    $sheet->getStyle('A1:G1')->applyFromArray($style);

    $row = 2; // Start at row 2
    while ($row_data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $row_data['RegistrationNumber']);
        $sheet->setCellValue('B' . $row, $row_data['Author']);
        $sheet->setCellValue('C' . $row, $row_data['ThesisTitle']);
        $sheet->setCellValue('D' . $row, $row_data['College']);
        $sheet->setCellValue('E' . $row, $row_data['Program']);
        $sheet->setCellValue('F' . $row, $row_data['Adviser']);
        $sheet->setCellValue('G' . $row, $row_data['DateOfSubmission']);
        $row++;
    }

    // Save the Excel file
    ob_start();
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    $excelData = ob_get_contents();
    ob_end_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="exported_data.xlsx"');
    header('Cache-Control: max-age=0');

    echo $excelData;
    exit();


} elseif (isset($_GET['startDate']) && isset($_GET['endDate']) && (!isset($_GET['collegesDropdown']) && !isset($_GET['programsDropdown']))) {
        $startDate = $_GET['startDate'];
        $endDate = $_GET['endDate'];
        $query = "SELECT r.`MRecordID`, r.`RegistrationNumber`, a.`Author`, a.`ThesisTitle`, a.`Adviser`, a.`DateOfSubmission`, c.`College_Name` AS College, p.`Program_Name` AS Program
              FROM `mainapplicationrecords` r
              JOIN `mainapplication` a ON r.`MFileID` = a.`MAppID`
              JOIN `college` c ON a.`CollegeID` = c.`CollegeID`
              JOIN `program` p ON a.`ProgramID` = p.`ProgramID`
              WHERE a.`DateOfSubmission` BETWEEN ? AND ?";

        $stmt = $conn->prepare($query);
        $stmt->bind_param('ss', $startDate, $endDate);

        $stmt->execute();
    $result = $stmt->get_result();

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Set headers
    $sheet->setCellValue('A1', 'Registration Number');
    $sheet->setCellValue('B1', 'Author');
    $sheet->setCellValue('C1', 'Thesis Title');
    $sheet->setCellValue('D1', 'College');
    $sheet->setCellValue('E1', 'Program');
    $sheet->setCellValue('F1', 'Adviser');
    $sheet->setCellValue('G1', 'Date Of Submission');

    // Set column width
    $sheet->getColumnDimension('A')->setWidth(21);
    $sheet->getColumnDimension('B')->setWidth(35);
    $sheet->getColumnDimension('C')->setWidth(55);
    $sheet->getColumnDimension('D')->setWidth(54);
    $sheet->getColumnDimension('E')->setWidth(56);
    $sheet->getColumnDimension('F')->setWidth(20);
    $sheet->getColumnDimension('G')->setWidth(18);

    $style = [
        'font' => [
            'bold' => true
        ],
        'alignment' => [
            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, // Horizontal alignment
            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER, // Vertical alignment
        ]
    ];

    $sheet->getStyle('A1:G1')->applyFromArray($style);

    $row = 2; // Start at row 2
    while ($row_data = $result->fetch_assoc()) {
        $sheet->setCellValue('A' . $row, $row_data['RegistrationNumber']);
        $sheet->setCellValue('B' . $row, $row_data['Author']);
        $sheet->setCellValue('C' . $row, $row_data['ThesisTitle']);
        $sheet->setCellValue('D' . $row, $row_data['College']);
        $sheet->setCellValue('E' . $row, $row_data['Program']);
        $sheet->setCellValue('F' . $row, $row_data['Adviser']);
        $sheet->setCellValue('G' . $row, $row_data['DateOfSubmission']);
        $row++;
    }

    // Save the Excel file
    ob_start();
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    $excelData = ob_get_contents();
    ob_end_clean();

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="exported_data.xlsx"');
    header('Cache-Control: max-age=0');

    echo $excelData;
    exit();

} else {
        error_log("Not downloaded");
}

// Close the statement and database connection
$stmt->close();
$conn->close();

header("Location: main_record.php");
exit();



?>