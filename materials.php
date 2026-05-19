<?php
// Scan for PDF files in the current directory
$files = scandir(__DIR__);
$pdfs = [];
foreach ($files as $file) {
    if (pathinfo($file, PATHINFO_EXTENSION) === 'pdf') {
        $pdfs[] = $file;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Study Materials - IPDC</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">IPDC Exam Portal</div>
        <nav>
            <a href="index.php">Home</a>
            <a href="materials.php" style="color: var(--primary);">Materials</a>
            <a href="history.php">History</a>
        </nav>
    </header>

    <div class="container" style="align-items: flex-start; margin-top: 2rem;">
        <div class="glass-card" style="max-width: 900px;">
            <h1>Study Materials & Resources</h1>
            <p class="subtitle">Access the syllabus, past papers, question banks, and notes below.</p>
            
            <div class="material-grid">
                <?php foreach ($pdfs as $pdf): ?>
                    <?php
                        // Determine type of file for better display
                        $title = str_replace('.pdf', '', $pdf);
                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                 </svg>';
                    ?>
                    <a href="<?php echo htmlspecialchars($pdf); ?>" target="_blank" style="text-decoration: none; color: inherit;">
                        <div class="material-card">
                            <?php echo $icon; ?>
                            <h3 style="font-size: 1.1rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($title); ?></h3>
                            <p style="color: var(--text-muted); font-size: 0.9rem;">PDF Document</p>
                        </div>
                    </a>
                <?php endforeach; ?>
                
                <?php if (empty($pdfs)): ?>
                    <p style="grid-column: 1 / -1; text-align: center; color: var(--text-muted);">No study materials found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
