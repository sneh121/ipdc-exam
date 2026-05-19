<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam in Progress - IPDC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">IPDC Exam Portal</div>
        <nav>
            <span style="color: var(--text-muted);">Candidate: <?php echo htmlspecialchars($_SESSION['username']); ?></span>
        </nav>
    </header>

    <div class="container">
        <div class="glass-card" id="exam-card">
            <div id="loading">Loading questions...</div>
            <div id="quiz-container" style="display: none;">
                <div id="section-indicator" style="background: rgba(255, 107, 0, 0.15); color: #ff8c00; font-weight: bold; border-left: 4px solid #ff8c00; padding: 12px 18px; border-radius: 4px; margin-bottom: 1.5rem; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1.4;"></div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div class="progress" id="progress-text">Question 1 of 40</div>
                    <div id="question-value" style="font-size: 0.9rem; padding: 4px 8px; background: rgba(255,255,255,0.08); border-radius: 4px; color: var(--text-muted);">Marks: 1</div>
                </div>
                
                <h2 id="question-text" style="margin-top: 1rem; font-size: 1.25rem;"></h2>
                
                <div class="options" id="options-container">
                    <!-- Options will be injected here -->
                </div>
                
                <div id="feedback"></div>
                
                <div class="controls">
                    <div></div>
                    <button id="next-btn" class="btn" style="width: auto; display: none;">Next Question</button>
                    <button id="finish-btn" class="btn" style="width: auto; display: none; background: var(--success);">Finish Exam</button>
                </div>
            </div>
            
            <div id="result-container" style="display: none; text-align: center;">
                <h2>Exam Completed!</h2>
                <p class="subtitle" style="margin-top: 1rem; font-size: 1.2rem;">Your Score: <span id="final-score" style="color: var(--primary); font-weight: bold;"></span></p>
                <div style="margin-top: 2rem;">
                    <a href="history.php" class="btn">View Global History</a>
                </div>
            </div>
        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>
