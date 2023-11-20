<?php
$id = $_POST['userid'];
$pw = $_POST['userpw'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인</title>
</head>
<body>
    <?php
    echo "ID는 {$id}<br> 비밀번호는 {$pw}"
    ?>
</body>
</html>