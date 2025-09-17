<?php
// Memulai sesi untuk menyimpan status toggle
session_start();

// Menangani perubahan level jika toggle ditekan
if (isset($_POST['toggle'])) {
    // Mengubah status level
    if ($_SESSION['level'] == 1) {
        $_SESSION['level'] = 2;  // Set level ke 2
    } else {
        $_SESSION['level'] = 1;  // Set level ke 1
    }
}

// Menentukan level berdasarkan sesi
$level = isset($_SESSION['level']) ? $_SESSION['level'] : 1;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toggle Level</title>
    <style>
        /* Gaya untuk switch toggle */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            border-radius: 50%;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
        }

        input:checked + .slider {
            background-color: #4CAF50;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        /* Gaya untuk level text */
        #levelText {
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container" style="text-align: center; padding-top: 50px;">
        <form method="post">
            <label class="switch">
                <input type="checkbox" name="toggle" <?php echo $level == 2 ? 'checked' : ''; ?>>
                <span class="slider"></span>
            </label>
        </form>
        <div id="levelText">Level: <?php echo $level; ?></div>
    </div>
</body>
</html>
