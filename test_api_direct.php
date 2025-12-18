<?php
echo "<h2>Direct API Test</h2>";

// Test student products API call
echo "<h3>Student Products API Call:</h3>";
$url = "http://localhost/societrees_web/modules/afprotech/backend/afprotechs_get_reports_data.php?type=student_products";
$response = file_get_contents($url);
echo "Response: <pre>" . htmlspecialchars($response) . "</pre>";

// Test attendance API call
echo "<h3>Attendance API Call:</h3>";
$url = "http://localhost/societrees_web/modules/afprotech/backend/afprotechs_get_reports_data.php?type=attendance";
$response = file_get_contents($url);
echo "Response: <pre>" . htmlspecialchars($response) . "</pre>";
?>