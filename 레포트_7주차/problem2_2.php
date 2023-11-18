<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>내림차순 정렬</title>
</head>
<body>
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
</body>
</html>