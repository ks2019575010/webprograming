<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2주차</title>
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

    <h3>내림차순 정렬</h3>
    <hr>
    <?php
    function revsort(&$arr) {
        sort($arr);
        $arr = array_reverse($arr);
    }

    $numbers = array(4, 2, 8, 1, 6);
    revsort($numbers);

    echo "내림차순으로 정렬: ";
    print_r($numbers);//r은 return의 r,기본값은 false , true면 리턴되어 출력되지 않음
    ?>

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

    <h3>연상 배열</h3>
    <hr>
    <?php
    $people = array(
        'Kim' => 'Seoul',
        'Lee' => array('Pusan', 'Daegu'),
        'Choi' => 'Inchon',
        'Park' => array('Suwon', 'Daejon'),
        'Jung' => array('Kwangju', 'Chunchon', 'Wonju')
    );

    // Choi 항목 삭제
    unset($people['Choi']);

    // 배열 내용 출력
    foreach ($people as $name => $cities) {
        echo "$name: ";
        if (is_array($cities)) {
            echo implode(', ', $cities);
        } else {
            echo $cities;
        }
        echo "<br>";
    }
    ?>

    <h3>client.txt</h3>
    <hr>
    <?php
    $file = fopen('client.txt', 'r');

    if ($file) {
        while (($line = fgets($file)) !== false) {
            $data = explode("\t", $line,4);//explode()는 문자열을 분할하여 배열로 저장하는 함수, implode()는 배열을 문자열로
            $name = $data[0];
            $age  = $data[1];
    
                if (intval($age) >= 30) {
                    echo "이름: $name, 나이: $age<br>";
                }
             /*else {
                // 예상한 배열 키가 없는 경우 오류 메시지를 표시합니다.
                echo "잘못된 데이터 형식: $age <br>";
            }*/
        }
    
        fclose($file);
    } else {
        echo "파일을 열 수 없습니다.\n";
    }
    /*
    $file = fopen('client.txt', 'r');
    if ($file) {
        while (($line = fgets($file)) !== false) {
            $data = explode("\t", $line);//explode()는 문자열을 분할하여 배열로 저장하는 함수입니다. explode("문자열 분할기준",$분할할문자열,분할할갯수)
            $name = $data[0];
            $age = intval($data[1]);//intval 모든것을 정수로 만들어 준다

            if ($age >= 30) {
                echo "Name: $name, Age: $age\n";
            }
        }

        fclose($file);
    } else {
        echo "Error opening file.\n";
    }
    */
    ?>

    
</body>
</html>