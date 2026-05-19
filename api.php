<?php
require_once 'config.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit();
}

$action = $_GET['action'] ?? '';

if ($action === 'get_questions') {
    $questions = [];

    // Fast random selection: get IDs per section then fetch only those rows
    function get_random_questions($conn, $section, $limit) {
        $ids = [];
        $res = $conn->query("SELECT id FROM questions WHERE section = '$section'");
        while ($row = $res->fetch_assoc()) {
            $ids[] = $row['id'];
        }
        if (empty($ids)) return [];
        shuffle($ids);
        $selected = array_slice($ids, 0, $limit);
        $id_list = implode(',', $selected);
        $result = $conn->query("SELECT id, question, option_a, option_b, option_c, option_d, correct_option, section FROM questions WHERE id IN ($id_list)");
        $rows = [];
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        shuffle($rows); // shuffle the result order too
        return $rows;
    }

    $questions = array_merge(
        get_random_questions($conn, 'A', 10),
        get_random_questions($conn, 'B', 15),
        get_random_questions($conn, 'C', 15)
    );

    if (empty($questions)) {
        echo json_encode(['status' => 'error', 'message' => 'No questions found. Please import inserts.sql into your database via phpMyAdmin.']);
        exit();
    }

    echo json_encode(['status' => 'success', 'data' => $questions]);
    exit();
}

if ($action === 'submit_score') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['score']) && isset($data['total'])) {
        $score = (int)$data['score'];
        $total = (int)$data['total'];
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("UPDATE users SET score = ?, total = ? WHERE id = ?");
        $stmt->bind_param("iii", $score, $total, $user_id);
        if ($stmt->execute()) {
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database error']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    }
    exit();
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
?>
