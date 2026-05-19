<?php
require_once 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $username = trim($_POST['username']);
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $error = "Username already taken! Please choose another one.";
    } else {
        // Create user
        $stmt = $conn->prepare("INSERT INTO users (username) VALUES (?)");
        $stmt->bind_param("s", $username);
        if ($stmt->execute()) {
            $_SESSION['user_id'] = $conn->insert_id;
            $_SESSION['username'] = $username;
            header("Location: exam.php");
            exit();
        } else {
            $error = "An error occurred. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IPDC-2 Exam Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="logo">IPDC Exam Portal</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="materials.php">Materials</a>
            <a href="history.php">History</a>
        </nav>
    </header>

    <div class="container">
        <div class="glass-card">
            <h1>Start Your IPDC-2 Exam</h1>
            <p class="subtitle">Enter a unique username to begin the test. Your results will be recorded.</p>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter a unique username">
                    <?php if (isset($error)): ?>
                        <div style="display:block;" class="error-msg"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn">Start Exam</button>
            </form>
        </div>
    </div>
</body>
</html>
