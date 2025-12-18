<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Test endpoint to check what login would return
    require_once 'db_connection.php';
    
    $student_id = $_GET['student_id'] ?? '202310655'; // Default test ID
    
    try {
        $stmt = $pdo->prepare('SELECT id, student_id, first_name, middle_name, last_name, course, year_level, section, role, department, position FROM users WHERE student_id = ? LIMIT 1');
        $stmt->execute([$student_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $name_parts = array_filter([$user['first_name'], $user['middle_name'], $user['last_name']]);
            $full_name = $name_parts ? implode(' ', $name_parts) : '';
            
            $response = [
                'success' => true,
                'message' => 'Test data retrieved',
                'user_id' => (int)$user['id'],
                'student_id' => $user['student_id'],
                'role' => $user['role'] ?: 'student',
                'department' => $user['department'] ?: '',
                'position' => $user['position'] ?: '',
                'first_name' => $user['first_name'] ?? '',
                'middle_name' => $user['middle_name'] ?? '',
                'last_name' => $user['last_name'] ?? '',
                'full_name' => $full_name,
                'year_level' => $user['year_level'] ?? '',
                'section' => $user['section'] ?? '',
                'course' => $user['course'] ?? '',
            ];
            
            echo json_encode($response, JSON_PRETTY_PRINT);
        } else {
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Use GET method with ?student_id=202310655']);
}
?>