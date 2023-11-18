<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>연상배열</title>
</head>
<body>
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
</body>
</html>