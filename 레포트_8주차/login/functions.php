<?php
session_start();

// 데이터베이스에 연결
$db = mysqli_connect('localhost', 'tmdzm', 'Popo121!', 'tmdzm');//host(ip나 이름),MySQL이름,비밀번호,데이터베이스이름을 넣어야 한다.

// 연결 확인
//if (!$db) {
    //die("Connection failed: " . mysqli_connect_error());
//}

// 변수 선언
$username = "";
$email    = "";
$errors   = array();

// register_btn이 클릭되면 register() 함수 호출
if (isset($_POST['register_btn'])) {
    register();
}

// 사용자 등록
function register()
{
    // 이 함수 내에서 사용할 변수들을 global 키워드를 사용하여 전역 변수로 만듦
    global $db, $errors, $username, $email;

    // 폼에서 모든 입력 값을 받음. 값을 이스케이프하기 위해 아래에 정의된 e() 함수 호출
    $username    =  e($_POST['username']);
    $email       =  e($_POST['email']);
    $password_1  =  e($_POST['password_1']);
    $password_2  =  e($_POST['password_2']);

    // 폼 유효성 검사: 폼이 올바르게 채워져 있는지 확인
    if (empty($username)) {
        array_push($errors, "사용자명이 필요합니다");
    }
    if (empty($email)) {
        array_push($errors, "이메일이 필요합니다");
    }
    if (empty($password_1)) {
        array_push($errors, "비밀번호가 필요합니다");
    }
    if ($password_1 != $password_2) {
        array_push($errors, "두 비밀번호가 일치하지 않습니다");
    }

    // 폼에 오류가 없다면 사용자 등록
    if (count($errors) == 0) {
        $password = md5($password_1); // 데이터베이스에 저장하기 전에 비밀번호를 암호화

        if (isset($_POST['user_type'])) {
            $user_type = e($_POST['user_type']);
            $query = "INSERT INTO users (username, email, user_type, password) 
                      VALUES('$username', '$email', '$user_type', '$password')";
            mysqli_query($db, $query);
            $_SESSION['success']  = "새로운 사용자가 성공적으로 생성되었습니다!";
            header('location: home.php');
        } else {
            $query = "INSERT INTO users (username, email, user_type, password) 
                      VALUES('$username', '$email', 'user', '$password')";
            mysqli_query($db, $query);

            // 생성된 사용자의 ID를 가져옴
            $logged_in_user_id = mysqli_insert_id($db);

            $_SESSION['user'] = getUserById($logged_in_user_id); // 로그인된 사용자를 세션에 저장
            $_SESSION['success']  = "로그인되었습니다";
            header('location: index.php');
        }
    }
}

// 사용자 ID로부터 사용자 배열 반환
function getUserById($id)
{
    global $db;
    $query = "SELECT * FROM users WHERE id=" . $id;
    $result = mysqli_query($db, $query);

    $user = mysqli_fetch_assoc($result);
    return $user;
}

// 문자열 이스케이프
function e($val)
{
    global $db;
    return mysqli_real_escape_string($db, trim($val));
}

// 오류 메시지 표시
function display_error()
{
    global $errors;

    if (count($errors) > 0) {
        echo '<div class="error">';
        foreach ($errors as $error) {
            echo $error . '<br>';
        }
        echo '</div>';
    }
}

// 사용자가 로그인되어 있는지 확인
function isLoggedIn()
{
    if (isset($_SESSION['user'])) {
        return true;
    } else {
        return false;
    }
}

// 로그아웃 버튼이 클릭되면 사용자 로그아웃
if (isset($_GET['logout'])) {
    session_destroy();
    unset($_SESSION['user']);
    header("location: login.php");
}

// login_btn이 클릭되면 login() 함수 호출
if (isset($_POST['login_btn'])) {
    login();
}

// 사용자 로그인
function login()
{
    global $db, $username, $errors;

    // 폼 값 가져오기
    $username = e($_POST['username']);
    $password = e($_POST['password']);

    // 폼이 올바르게 채워져 있는지 확인
    if (empty($username)) {
        array_push($errors, "사용자명이 필요합니다");
    }
    if (empty($password)) {
        array_push($errors, "비밀번호가 필요합니다");
    }

    // 폼에 오류가 없다면 로그인 시도
    if (count($errors) == 0) {
        $password = md5($password);

        $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
        $results = mysqli_query($db, $query);

        if (mysqli_num_rows($results) == 1) { // 사용자 찾음
            // 사용자가 어드민인지 또는 일반 사용자인지 확인
            $logged_in_user = mysqli_fetch_assoc($results);
            if ($logged_in_user['user_type'] == 'admin') {

                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "로그인되었습니다";
                header('location: admin/home.php');
            } else {
                $_SESSION['user'] = $logged_in_user;
                $_SESSION['success']  = "로그인되었습니다";

                header('location: index.php');
            }
        } else {
            array_push($errors, "잘못된 사용자명 또는 비밀번호 조합");
        }
    }
}

// ...

// 사용자가 어드민인지 확인
function isAdmin()
{
    if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin') {
        return true;
    } else {
        return false;
    }
}