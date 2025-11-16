<?php

function renderError($type, $message, $file, $line)
{
    $snippet = getCodeSnippet($file, $line, 4);

    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Xion Debug Engine</title>
<link rel="shortcut icon" type="text/css" href="favicon.ico">
<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

<style>
    body {
        margin: 0;
        background: #050505;
        color: #e5e5e5;
        font-family: "JetBrains Mono", monospace;
        overflow-x: hidden;
    }

    .container {
        padding: 35px;
        max-width: 1200px;
        margin: auto;
        animation: fadeIn 0.7s ease;
    }

    /* Title */
    .title {
        font-size: 32px;
        font-weight: 700;
        color: #ff3e3e;
        margin-bottom: 20px;
        text-shadow: 0 0 15px rgba(255,50,50,0.8),
                     0 0 25px rgba(255,0,0,0.4);
        letter-spacing: 2px;
    }

    /* Error box */
    .info-box {
        background: rgba(255, 60, 60, 0.08);
        border: 1px solid rgba(255, 60, 60, 0.3);
        padding: 20px;
        border-radius: 12px;
        margin-bottom: 25px;
        box-shadow: 0 0 12px rgba(255, 30, 30, 0.3);
        backdrop-filter: blur(6px);
    }

    .subtitle {
        font-size: 16px;
        margin-bottom: 8px;
        color: #ff9f9f;
    }

    /* Code Snippet Box */
    .snippet-box {
        background: rgba(15, 15, 15, 0.7);
        border: 1px solid #222;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 25px;
        box-shadow: 0 0 10px rgba(0,255,255,0.15);
    }

    .snippet-header {
        padding: 12px 15px;
        font-size: 16px;
        background: #0e0e0e;
        cursor: pointer;
        border-bottom: 1px solid #222;
        display: flex;
        justify-content: space-between;
        color: #33e3ff;
        text-shadow: 0 0 10px rgba(0,255,255,0.5);
        user-select: none;
    }

    .code {
        max-height: 0px;
        overflow: hidden;
        transition: max-height 0.4s ease;
    }

    .code.open {
        max-height: 700px;
    }

    .line {
        padding: 7px 20px;
        display: flex;
        gap: 15px;
        color: #cfcfcf;
        border-bottom: 1px solid #1a1a1a;
        font-size: 14px;
    }

    .number {
        width: 50px;
        color: #666;
    }

    .highlight {
        background: rgba(255, 0, 0, 0.25);
        color: #fff;
        border-left: 5px solid #ff3e3e;
        text-shadow: 0 0 8px rgba(255,80,80,0.7);
    }

    .footer {
        margin-top: 35px;
        text-align: center;
        font-size: 14px;
        color: #555;
    }

    /* Fade effect */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
</head>

<body>
<div class="container">

    <div class="title">⚠ XION RUNTIME ERROR</div>

    <div class="info-box">
        <div class="subtitle"><b>Type:</b> <?= $type ?></div>
        <div class="subtitle"><b>Message:</b> <?= htmlspecialchars($message) ?></div>
        <div class="subtitle"><b>File:</b> <?= $file ?></div>
        <div class="subtitle"><b>Line:</b> <?= $line ?></div>
    </div>

    <div class="snippet-box">
        <div class="snippet-header" onclick="toggleSnippet()">
            View Code Snippet
            <span id="arrow">▼</span>
        </div>

        <div class="code" id="codeBox">
            <?php foreach ($snippet as $num => $row): ?>
                <div class="line <?= ($num == $line ? 'highlight' : '') ?>">
                    <div class="number"><?= $num ?></div>
                    <div><?= htmlspecialchars($row) ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="footer"> Xion Debug Engine © <?= date('Y') ?></div>
</div>

<script>
    let isOpen = false;

    function toggleSnippet() {
        const box = document.getElementById("codeBox");
        const arrow = document.getElementById("arrow");

        if (isOpen) {
            box.classList.remove("open");
            arrow.textContent = "▼";
        } else {
            box.classList.add("open");
            arrow.textContent = "▲";
        }
        isOpen = !isOpen;
    }
</script>

</body>
</html>

<?php
    exit;
}

function getCodeSnippet($file, $line, $range = 4)
{
    if (!file_exists($file)) return [];

    $lines = file($file);
    $start = max($line - $range, 1);
    $end   = min($line + $range, count($lines));

    $out = [];
    for ($i = $start; $i <= $end; $i++) {
        $out[$i] = rtrim($lines[$i - 1], "\n");
    }
    return $out;
}
?>
