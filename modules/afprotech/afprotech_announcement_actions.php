<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../db_connection.php';

$action = $_REQUEST['action'] ?? 'list';

try {
    if ($action === 'create') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $datetime = trim($_POST['datetime'] ?? '');

        if ($title === '' || $content === '') {
            throw new Exception('Title and content are required');
        }

        // If no datetime provided, use current
        if ($datetime === '') {
            $datetime = date('Y-m-d H:i:s');
        } else {
            // incoming from datetime-local: convert to MySQL datetime
            $dt = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
            if ($dt) $datetime = $dt->format('Y-m-d H:i:s');
            else $datetime = date('Y-m-d H:i:s');
        }

        $stmt = $pdo->prepare('INSERT INTO afprotechs_announcement (announcement_title, announcement_content, announcement_datetime) VALUES (:title, :content, :datetime)');
        $stmt->execute([':title' => $title, ':content' => $content, ':datetime' => $datetime]);

        echo json_encode(['success' => true, 'id' => $pdo->lastInsertId()]);
        exit;
    }

    if ($action === 'list') {
        $stmt = $pdo->query('SELECT announcement_id, announcement_title, announcement_content, announcement_datetime FROM afprotechs_announcement ORDER BY announcement_datetime DESC');
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['success' => true, 'data' => $rows]);
        exit;
    }

    if ($action === 'delete') {
        $id = intval($_POST['id'] ?? 0);
        if ($id <= 0) throw new Exception('Invalid id');
        $stmt = $pdo->prepare('DELETE FROM afprotechs_announcement WHERE announcement_id = :id');
        $stmt->execute([':id' => $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    if ($action === 'update') {
        $id = intval($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $datetime = trim($_POST['datetime'] ?? '');
        if ($id <= 0 || $title === '' || $content === '') throw new Exception('Invalid input');

        if ($datetime === '') {
            $datetime = date('Y-m-d H:i:s');
        } else {
            $dt = DateTime::createFromFormat('Y-m-d\TH:i', $datetime);
            if ($dt) $datetime = $dt->format('Y-m-d H:i:s');
            else $datetime = date('Y-m-d H:i:s');
        }

        $stmt = $pdo->prepare('UPDATE afprotechs_announcement SET announcement_title = :title, announcement_content = :content, announcement_datetime = :datetime WHERE announcement_id = :id');
        $stmt->execute([':title' => $title, ':content' => $content, ':datetime' => $datetime, ':id' => $id]);
        echo json_encode(['success' => true]);
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unknown action']);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>
