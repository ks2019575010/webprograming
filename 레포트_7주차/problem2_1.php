<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>for문</title>
</head>
<body>
<h3>for문</h3>
    <hr>
    <?php
    $rows = 5;
    
    for ($i = 1; $i <= $rows; $i++) {
        for ($j = 1; $j <= $i; $j++) {
            echo chr(64 + $j) . " ";
        }
        echo "<br>";//"\n은 소스코드에서만 줄이 바뀌고 출력은 안바뀜"
    }
    
    for ($i = $rows - 1; $i >= 1; $i--) {
        for ($j = 1; $j <= $i; $j++) {
            echo chr(64 + $j) . " ";
        }
        echo "<br>";
    }
    ?>
</body>
</html>