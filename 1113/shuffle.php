<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php

    $array = range(1, 45);
    shuffle($array);
    $slice = array_slice($array,0,6);
    sort($slice);
    echo "숫자:".implode(",",$slice);//implode() / 배열을 하나의 문자열로 만드는 함수
    
    ?>
</body>
</html>
<!--1~45 배열을 만들고 셔플
/*
        $arr = array()
        $i = 0;
        while($i<45){
            $arr[$i++] = ;
        }
        */
-->