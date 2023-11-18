<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>client.txt</title>
</head>
<body>
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