<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Global History - IPDC Exam</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <div class="logo">IPDC Exam Portal</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="materials.php">Materials</a>
            <a href="history.php" style="color: var(--primary);">History</a>
        </nav>
    </header>

    <div class="container" style="align-items: flex-start; margin-top: 2rem;">
        <div class="glass-card" style="max-width: 800px;">
            <h1>Global Results History</h1>
            <p class="subtitle">See how everyone performed in the IPDC-2 Exam.</p>
            
            <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Username</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Date Taken</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $result = $conn->query("SELECT username, score, total, created_at FROM users WHERE total > 0 ORDER BY score DESC, created_at ASC");
                        if ($result->num_rows > 0) {
                            $rank = 1;
                            while ($row = $result->fetch_assoc()) {
                                $percentage = round(($row['score'] / $row['total']) * 100, 2);
                                $date = date('M d, Y h:i A', strtotime($row['created_at']));
                                echo "<tr>
                                        <td>#{$rank}</td>
                                        <td style='font-weight: 600;'>" . htmlspecialchars($row['username']) . "</td>
                                        <td>{$row['score']} / {$row['total']}</td>
                                        <td>
                                            <span style='color: " . ($percentage >= 50 ? 'var(--success)' : 'var(--danger)') . "'>
                                                {$percentage}%
                                            </span>
                                        </td>
                                        <td style='color: var(--text-muted); font-size: 0.9rem;'>{$date}</td>
                                      </tr>";
                                $rank++;
                            }
                        } else {
                            echo "<tr><td colspan='5' style='text-align: center;'>No history available yet.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 2rem; text-align: center;">
                <a href="index.php" class="btn" style="width: auto;">Take Exam</a>
            </div>
        </div>
    </div>
</body>
</html>
