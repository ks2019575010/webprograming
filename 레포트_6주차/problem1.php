<!-- index.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP in HTML</title>
</head>
<body>
    <h2>홀짝</h2>
    <hr>
    <?php

    function printNextNumber($a) {
        if ($a % 2 == 1) {
            // $a가 홀수인 경우
            echo $a + 1;
        } else {
            // $a가 짝수인 경우
            echo $a;
        }
    }
    
    // 함수 테스트
    $a = 7; // 홀수
    echo "<p>입력값은 $a</p>";
    printNextNumber($a); // 출력: 8
    echo "<br>";

    $a = 12; // 짝수
    echo "<p>입력값은 $a</p>";
    printNextNumber($a); // 출력: 12
    echo "<br>";
    ?>

    <h2>팩토리얼</h2>
    <hr>
    <?php
    function factorial($n) {
        $result = 1;
        $i = 1;
    
        while ($i <= $n) {
            $result *= $i;
            $i++;
        }
    
        return $result;
    }
    
    // 테스트
    $n = 5;
    echo "<p> $n 의 팩토리얼 값은: " . factorial($n) . "</p>";
    ?>

    <h2>홀짝 삼항연산자</h2>
    <hr>
    <?php
    $value = 12;
    $result = ($value % 2 == 1) ? "홀수" : "짝수";
    echo "<p>$value 는 $result.</p>";
    ?>

</body>
</html>