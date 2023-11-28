<?php
session_start();
include 'config.php';

if (isset($_GET['app_id'])) {
    $appID = $_GET['app_id'];
    $mode = 'main'; // Define the mode

    $thesisQuery = "SELECT ThesisTitle FROM mainapplication WHERE MAppID = ?";
    $studentUploadDir = 'student_file/';

    if (isset($_GET['mode']) && $_GET['mode'] === 'branch') {
        $thesisQuery = "SELECT ThesisTitle FROM campusbranchesapplication WHERE CBAppID = ?";
        $studentUploadDir = 'student_filecb/';
        $mode = 'branch'; // Set the mode to "branch"
    }

    $stmt = $conn->prepare($thesisQuery);
    $stmt->bind_param("i", $appID);
    $stmt->execute();
    $stmt->bind_result($thesisTitle);
    $stmt->fetch();
    $stmt->close();

    $zipFileName = $thesisTitle . '.zip';
    $zip = new ZipArchive();

    if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
        $subfolderPath = $studentUploadDir . $thesisTitle . '/';
        $files = scandir($subfolderPath);

        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $filePath = $subfolderPath . $file;
                $zip->addFile($filePath, "$thesisTitle/$file");
            }
        }

        $zip->close();

        if (file_exists($zipFileName)) {
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
            header('Content-Length: ' . filesize($zipFileName));
            readfile($zipFileName);

            unlink($zipFileName);
        } else {
            echo "Error: Could not create ZIP file.";
        }
    } else {
        echo "Error: Could not open ZIP archive.";
    }
} else {
    // Handle invalid request
    echo "Invalid request.";
}
?>
