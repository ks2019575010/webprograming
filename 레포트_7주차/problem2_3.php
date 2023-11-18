<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>exam.txt</title>
</head>
<body>
<h3>exam.txt</h3>
    <hr>
    <?php
    $file = fopen('exam.txt', 'r');//fopen(파일경로, 파일모드), r 은 읽기 모드
    if ($file) {
        $lineCount = 0;
        $wordCount = 0;
        $charCount = 0;

        while (($line = fgets($file)) !== false) {
            $lineCount++;
            $wordCount += str_word_count($line);
            $charCount += strlen($line);
        }

        fclose($file);

        echo "줄수: $lineCount\n <br>";
        echo "단어수: $wordCount\n <br>";
        echo "글자수: $charCount\n <br>";//공백포함
    } else {
        echo "Error opening file.\n";
    }
    ?>
</body>
</html>