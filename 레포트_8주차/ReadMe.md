http://tmdzm.dothome.co.kr/login/login.php
id 123, pw abc
![스크린샷(680)](https://github.com/ks2019575010/webprograming/assets/48661594/5de37132-e7a0-41a8-bfb0-485ae6408af6)


![스크린샷(679)](https://github.com/ks2019575010/webprograming/assets/48661594/140a4553-c4ad-4675-93ee-d0afbb6e9c2e)

dothome(호스팅 사이트) 설명
애니시큐어 주식회사에서 운영중인 웹사이트 호스팅 사이트. 도메인, 웹 호스팅, 리눅스 호스팅, 웹 메일 등을 제공한다.
아이디당 2개까지 무료 호스팅이 가능하며 , 무료 호스팅은 6개월 단위로 연장만 해주면 반영구적으로 사용가능하다.
DB가 무제한이다.


function 주석

폼: HTML 에서 사용자가 입력한 데이터. 주로 사용자가 등록 또는 로그인 폼에서 입력한 값들
register() 함수를 이용해 데이터베이스에 로그인에 필요한 정보를 저장하고
login() 함수를 이용해 불러온다.

$_SESSION['user'] = $logged_in_user;
$_SESSION['success']  = "로그인되었습니다";
header('location: admin/home.php'); // 세션을 이용해 페이지를 바꾸는 코드

```
<?php

session_start();

// 데이터베이스에 연결

$db = mysqli_connect('localhost', 'tmdzm', 'Popo121!', 'tmdzm');//host,MySQL이름,비밀번호,데이터베이스이름을 넣어야 한다.

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
    

    // 폼(즉,페이지내)에서 모든 입력 값을 받음. 값을 이스케이프하기 위해 아래에 정의된 e() 함수 호출
    
    
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
                
                header('location: admin/home.php'); // 세션을 이용해서 페이지를 바꾸는 코드
                
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
```

<div class="colorscripter-code" style="color:#f0f0f0;font-family:Consolas, 'Liberation Mono', Menlo, Courier, monospace !important; position:relative !important;overflow:auto"><table class="colorscripter-code-table" style="margin:0;padding:0;border:none;background-color:#272727;border-radius:4px;" cellspacing="0" cellpadding="0"><tr><td style="padding:6px;border-right:2px solid #4f4f4f"><div style="margin:0;padding:0;word-break:normal;text-align:right;color:#aaa;font-family:Consolas, 'Liberation Mono', Menlo, Courier, monospace !important;line-height:130%"><div style="line-height:130%">1</div><div style="line-height:130%">2</div><div style="line-height:130%">3</div><div style="line-height:130%">4</div><div style="line-height:130%">5</div><div style="line-height:130%">6</div><div style="line-height:130%">7</div><div style="line-height:130%">8</div><div style="line-height:130%">9</div><div style="line-height:130%">10</div><div style="line-height:130%">11</div><div style="line-height:130%">12</div><div style="line-height:130%">13</div><div style="line-height:130%">14</div><div style="line-height:130%">15</div><div style="line-height:130%">16</div><div style="line-height:130%">17</div><div style="line-height:130%">18</div><div style="line-height:130%">19</div><div style="line-height:130%">20</div><div style="line-height:130%">21</div><div style="line-height:130%">22</div><div style="line-height:130%">23</div><div style="line-height:130%">24</div><div style="line-height:130%">25</div><div style="line-height:130%">26</div><div style="line-height:130%">27</div><div style="line-height:130%">28</div><div style="line-height:130%">29</div><div style="line-height:130%">30</div><div style="line-height:130%">31</div><div style="line-height:130%">32</div><div style="line-height:130%">33</div><div style="line-height:130%">34</div><div style="line-height:130%">35</div><div style="line-height:130%">36</div><div style="line-height:130%">37</div><div style="line-height:130%">38</div><div style="line-height:130%">39</div><div style="line-height:130%">40</div><div style="line-height:130%">41</div><div style="line-height:130%">42</div><div style="line-height:130%">43</div><div style="line-height:130%">44</div><div style="line-height:130%">45</div><div style="line-height:130%">46</div><div style="line-height:130%">47</div><div style="line-height:130%">48</div><div style="line-height:130%">49</div><div style="line-height:130%">50</div><div style="line-height:130%">51</div><div style="line-height:130%">52</div><div style="line-height:130%">53</div><div style="line-height:130%">54</div><div style="line-height:130%">55</div><div style="line-height:130%">56</div><div style="line-height:130%">57</div><div style="line-height:130%">58</div><div style="line-height:130%">59</div><div style="line-height:130%">60</div><div style="line-height:130%">61</div><div style="line-height:130%">62</div><div style="line-height:130%">63</div><div style="line-height:130%">64</div><div style="line-height:130%">65</div><div style="line-height:130%">66</div><div style="line-height:130%">67</div><div style="line-height:130%">68</div><div style="line-height:130%">69</div><div style="line-height:130%">70</div><div style="line-height:130%">71</div><div style="line-height:130%">72</div><div style="line-height:130%">73</div><div style="line-height:130%">74</div><div style="line-height:130%">75</div><div style="line-height:130%">76</div><div style="line-height:130%">77</div><div style="line-height:130%">78</div><div style="line-height:130%">79</div><div style="line-height:130%">80</div><div style="line-height:130%">81</div><div style="line-height:130%">82</div><div style="line-height:130%">83</div><div style="line-height:130%">84</div><div style="line-height:130%">85</div><div style="line-height:130%">86</div><div style="line-height:130%">87</div><div style="line-height:130%">88</div><div style="line-height:130%">89</div><div style="line-height:130%">90</div><div style="line-height:130%">91</div><div style="line-height:130%">92</div><div style="line-height:130%">93</div><div style="line-height:130%">94</div><div style="line-height:130%">95</div><div style="line-height:130%">96</div><div style="line-height:130%">97</div><div style="line-height:130%">98</div><div style="line-height:130%">99</div><div style="line-height:130%">100</div><div style="line-height:130%">101</div><div style="line-height:130%">102</div><div style="line-height:130%">103</div><div style="line-height:130%">104</div><div style="line-height:130%">105</div><div style="line-height:130%">106</div><div style="line-height:130%">107</div><div style="line-height:130%">108</div><div style="line-height:130%">109</div><div style="line-height:130%">110</div><div style="line-height:130%">111</div><div style="line-height:130%">112</div><div style="line-height:130%">113</div><div style="line-height:130%">114</div><div style="line-height:130%">115</div><div style="line-height:130%">116</div><div style="line-height:130%">117</div><div style="line-height:130%">118</div><div style="line-height:130%">119</div><div style="line-height:130%">120</div><div style="line-height:130%">121</div><div style="line-height:130%">122</div><div style="line-height:130%">123</div><div style="line-height:130%">124</div><div style="line-height:130%">125</div><div style="line-height:130%">126</div><div style="line-height:130%">127</div><div style="line-height:130%">128</div><div style="line-height:130%">129</div><div style="line-height:130%">130</div><div style="line-height:130%">131</div><div style="line-height:130%">132</div><div style="line-height:130%">133</div><div style="line-height:130%">134</div><div style="line-height:130%">135</div><div style="line-height:130%">136</div><div style="line-height:130%">137</div><div style="line-height:130%">138</div><div style="line-height:130%">139</div><div style="line-height:130%">140</div><div style="line-height:130%">141</div><div style="line-height:130%">142</div><div style="line-height:130%">143</div><div style="line-height:130%">144</div><div style="line-height:130%">145</div><div style="line-height:130%">146</div><div style="line-height:130%">147</div><div style="line-height:130%">148</div><div style="line-height:130%">149</div><div style="line-height:130%">150</div><div style="line-height:130%">151</div><div style="line-height:130%">152</div><div style="line-height:130%">153</div><div style="line-height:130%">154</div><div style="line-height:130%">155</div><div style="line-height:130%">156</div><div style="line-height:130%">157</div><div style="line-height:130%">158</div><div style="line-height:130%">159</div><div style="line-height:130%">160</div><div style="line-height:130%">161</div><div style="line-height:130%">162</div><div style="line-height:130%">163</div><div style="line-height:130%">164</div><div style="line-height:130%">165</div><div style="line-height:130%">166</div><div style="line-height:130%">167</div><div style="line-height:130%">168</div><div style="line-height:130%">169</div><div style="line-height:130%">170</div><div style="line-height:130%">171</div><div style="line-height:130%">172</div><div style="line-height:130%">173</div><div style="line-height:130%">174</div><div style="line-height:130%">175</div><div style="line-height:130%">176</div><div style="line-height:130%">177</div><div style="line-height:130%">178</div><div style="line-height:130%">179</div><div style="line-height:130%">180</div><div style="line-height:130%">181</div><div style="line-height:130%">182</div><div style="line-height:130%">183</div><div style="line-height:130%">184</div><div style="line-height:130%">185</div><div style="line-height:130%">186</div><div style="line-height:130%">187</div><div style="line-height:130%">188</div><div style="line-height:130%">189</div><div style="line-height:130%">190</div><div style="line-height:130%">191</div><div style="line-height:130%">192</div><div style="line-height:130%">193</div><div style="line-height:130%">194</div><div style="line-height:130%">195</div><div style="line-height:130%">196</div><div style="line-height:130%">197</div><div style="line-height:130%">198</div><div style="line-height:130%">199</div><div style="line-height:130%">200</div><div style="line-height:130%">201</div><div style="line-height:130%">202</div><div style="line-height:130%">203</div><div style="line-height:130%">204</div><div style="line-height:130%">205</div><div style="line-height:130%">206</div><div style="line-height:130%">207</div><div style="line-height:130%">208</div><div style="line-height:130%">209</div><div style="line-height:130%">210</div><div style="line-height:130%">211</div><div style="line-height:130%">212</div><div style="line-height:130%">213</div><div style="line-height:130%">214</div><div style="line-height:130%">215</div><div style="line-height:130%">216</div><div style="line-height:130%">217</div><div style="line-height:130%">218</div><div style="line-height:130%">219</div><div style="line-height:130%">220</div><div style="line-height:130%">221</div><div style="line-height:130%">222</div><div style="line-height:130%">223</div><div style="line-height:130%">224</div><div style="line-height:130%">225</div><div style="line-height:130%">226</div><div style="line-height:130%">227</div><div style="line-height:130%">228</div><div style="line-height:130%">229</div><div style="line-height:130%">230</div><div style="line-height:130%">231</div><div style="line-height:130%">232</div><div style="line-height:130%">233</div><div style="line-height:130%">234</div><div style="line-height:130%">235</div><div style="line-height:130%">236</div><div style="line-height:130%">237</div><div style="line-height:130%">238</div><div style="line-height:130%">239</div><div style="line-height:130%">240</div><div style="line-height:130%">241</div><div style="line-height:130%">242</div><div style="line-height:130%">243</div><div style="line-height:130%">244</div><div style="line-height:130%">245</div><div style="line-height:130%">246</div><div style="line-height:130%">247</div><div style="line-height:130%">248</div><div style="line-height:130%">249</div><div style="line-height:130%">250</div><div style="line-height:130%">251</div><div style="line-height:130%">252</div><div style="line-height:130%">253</div><div style="line-height:130%">254</div><div style="line-height:130%">255</div><div style="line-height:130%">256</div><div style="line-height:130%">257</div><div style="line-height:130%">258</div><div style="line-height:130%">259</div><div style="line-height:130%">260</div><div style="line-height:130%">261</div><div style="line-height:130%">262</div><div style="line-height:130%">263</div><div style="line-height:130%">264</div><div style="line-height:130%">265</div><div style="line-height:130%">266</div><div style="line-height:130%">267</div><div style="line-height:130%">268</div><div style="line-height:130%">269</div><div style="line-height:130%">270</div><div style="line-height:130%">271</div><div style="line-height:130%">272</div><div style="line-height:130%">273</div><div style="line-height:130%">274</div><div style="line-height:130%">275</div><div style="line-height:130%">276</div><div style="line-height:130%">277</div><div style="line-height:130%">278</div><div style="line-height:130%">279</div><div style="line-height:130%">280</div><div style="line-height:130%">281</div><div style="line-height:130%">282</div><div style="line-height:130%">283</div><div style="line-height:130%">284</div><div style="line-height:130%">285</div><div style="line-height:130%">286</div><div style="line-height:130%">287</div><div style="line-height:130%">288</div><div style="line-height:130%">289</div><div style="line-height:130%">290</div><div style="line-height:130%">291</div><div style="line-height:130%">292</div><div style="line-height:130%">293</div><div style="line-height:130%">294</div><div style="line-height:130%">295</div><div style="line-height:130%">296</div><div style="line-height:130%">297</div><div style="line-height:130%">298</div><div style="line-height:130%">299</div><div style="line-height:130%">300</div><div style="line-height:130%">301</div><div style="line-height:130%">302</div><div style="line-height:130%">303</div><div style="line-height:130%">304</div><div style="line-height:130%">305</div><div style="line-height:130%">306</div><div style="line-height:130%">307</div><div style="line-height:130%">308</div><div style="line-height:130%">309</div><div style="line-height:130%">310</div><div style="line-height:130%">311</div><div style="line-height:130%">312</div><div style="line-height:130%">313</div><div style="line-height:130%">314</div><div style="line-height:130%">315</div><div style="line-height:130%">316</div></div></td><td style="padding:6px 0;text-align:left"><div style="margin:0;padding:0;color:#f0f0f0;font-family:Consolas, 'Liberation Mono', Menlo, Courier, monospace !important;line-height:130%"><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff7e00">&lt;?php</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">session_start();</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;데이터베이스에&nbsp;연결</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#4be6fa">$db</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;mysqli_connect(<span style="color:#ffd500">'localhost'</span>,&nbsp;<span style="color:#ffd500">'tmdzm'</span>,&nbsp;<span style="color:#ffd500">'Popo121!'</span>,&nbsp;<span style="color:#ffd500">'tmdzm'</span>);<span style="color:#999999">//host,MySQL이름,비밀번호,데이터베이스이름을&nbsp;넣어야&nbsp;한다.</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;변수&nbsp;선언</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#4be6fa">$username</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">""</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#4be6fa">$email</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">""</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#4be6fa">$errors</span>&nbsp;&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#4be6fa">array</span>();</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;register_btn이&nbsp;클릭되면&nbsp;register()&nbsp;함수&nbsp;호출</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">isset</span>(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'register_btn'</span>]))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;register();</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;사용자&nbsp;등록</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;register()</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;이&nbsp;함수&nbsp;내에서&nbsp;사용할&nbsp;변수들을&nbsp;global&nbsp;키워드를&nbsp;사용하여&nbsp;전역&nbsp;변수로&nbsp;만듦</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;global&nbsp;<span style="color:#4be6fa">$db</span>,&nbsp;<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#4be6fa">$username</span>,&nbsp;<span style="color:#4be6fa">$email</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;폼(즉,페이지내)에서&nbsp;모든&nbsp;입력&nbsp;값을&nbsp;받음.&nbsp;값을&nbsp;이스케이프하기&nbsp;위해&nbsp;아래에&nbsp;정의된&nbsp;e()&nbsp;함수&nbsp;호출</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$username</span>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'username'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$email</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'email'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$password_1</span>&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'password_1'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$password_2</span>&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'password_2'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;폼&nbsp;유효성&nbsp;검사:&nbsp;폼이&nbsp;올바르게&nbsp;채워져&nbsp;있는지&nbsp;확인</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">empty</span>(<span style="color:#4be6fa">$username</span>))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"사용자명이&nbsp;필요합니다"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">empty</span>(<span style="color:#4be6fa">$email</span>))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"이메일이&nbsp;필요합니다"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">empty</span>(<span style="color:#4be6fa">$password_1</span>))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"비밀번호가&nbsp;필요합니다"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">$password_1</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">!</span><span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#4be6fa">$password_2</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"두&nbsp;비밀번호가&nbsp;일치하지&nbsp;않습니다"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;폼에&nbsp;오류가&nbsp;없다면&nbsp;사용자&nbsp;등록</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">count</span>(<span style="color:#4be6fa">$errors</span>)&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span><span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#c10aff">0</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$password</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#4be6fa">md5</span>(<span style="color:#4be6fa">$password_1</span>);&nbsp;<span style="color:#999999">//&nbsp;데이터베이스에&nbsp;저장하기&nbsp;전에&nbsp;비밀번호를&nbsp;암호화</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">isset</span>(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'user_type'</span>]))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$user_type</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'user_type'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$query</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"INSERT&nbsp;INTO&nbsp;users&nbsp;(username,&nbsp;email,&nbsp;user_type,&nbsp;password)&nbsp;</span></div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ffd500">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VALUES('$username',&nbsp;'$email',&nbsp;'$user_type',&nbsp;'$password')"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mysqli_query(<span style="color:#4be6fa">$db</span>,&nbsp;<span style="color:#4be6fa">$query</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'success'</span>]&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"새로운&nbsp;사용자가&nbsp;성공적으로&nbsp;생성되었습니다!"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">header</span>(<span style="color:#ffd500">'location:&nbsp;home.php'</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;<span style="color:#ff3399">else</span>&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$query</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"INSERT&nbsp;INTO&nbsp;users&nbsp;(username,&nbsp;email,&nbsp;user_type,&nbsp;password)&nbsp;</span></div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ffd500">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;VALUES('$username',&nbsp;'$email',&nbsp;'user',&nbsp;'$password')"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;mysqli_query(<span style="color:#4be6fa">$db</span>,&nbsp;<span style="color:#4be6fa">$query</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;생성된&nbsp;사용자의&nbsp;ID를&nbsp;가져옴</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$logged_in_user_id</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;mysqli_insert_id(<span style="color:#4be6fa">$db</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>]&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;getUserById(<span style="color:#4be6fa">$logged_in_user_id</span>);&nbsp;<span style="color:#999999">//&nbsp;로그인된&nbsp;사용자를&nbsp;세션에&nbsp;저장</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'success'</span>]&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"로그인되었습니다"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">header</span>(<span style="color:#ffd500">'location:&nbsp;index.php'</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;사용자&nbsp;ID로부터&nbsp;사용자&nbsp;배열&nbsp;반환</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;getUserById(<span style="color:#4be6fa">$id</span>)</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;global&nbsp;<span style="color:#4be6fa">$db</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$query</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"SELECT&nbsp;*&nbsp;FROM&nbsp;users&nbsp;WHERE&nbsp;id="</span>&nbsp;.&nbsp;<span style="color:#4be6fa">$id</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$result</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;mysqli_query(<span style="color:#4be6fa">$db</span>,&nbsp;<span style="color:#4be6fa">$query</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$user</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;mysqli_fetch_assoc(<span style="color:#4be6fa">$result</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">return</span>&nbsp;<span style="color:#4be6fa">$user</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;문자열&nbsp;이스케이프</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;e(<span style="color:#4be6fa">$val</span>)</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;global&nbsp;<span style="color:#4be6fa">$db</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">return</span>&nbsp;mysqli_real_escape_string(<span style="color:#4be6fa">$db</span>,&nbsp;trim(<span style="color:#4be6fa">$val</span>));</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;오류&nbsp;메시지&nbsp;표시</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;display_error()</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;global&nbsp;<span style="color:#4be6fa">$errors</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">count</span>(<span style="color:#4be6fa">$errors</span>)&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">&gt;</span>&nbsp;<span style="color:#c10aff">0</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">echo</span>&nbsp;<span style="color:#ffd500">'&lt;div&nbsp;class="error"&gt;'</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;foreach&nbsp;(<span style="color:#4be6fa">$errors</span>&nbsp;<span style="color:#ff3399">as</span>&nbsp;<span style="color:#4be6fa">$error</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">echo</span>&nbsp;<span style="color:#4be6fa">$error</span>&nbsp;.&nbsp;<span style="color:#ffd500">'&lt;br&gt;'</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">echo</span>&nbsp;<span style="color:#ffd500">'&lt;/div&gt;'</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;사용자가&nbsp;로그인되어&nbsp;있는지&nbsp;확인</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;isLoggedIn()</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">isset</span>(<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>]))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">return</span>&nbsp;<span style="color:#c10aff">true</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;<span style="color:#ff3399">else</span>&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">return</span>&nbsp;<span style="color:#c10aff">false</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;로그아웃&nbsp;버튼이&nbsp;클릭되면&nbsp;사용자&nbsp;로그아웃</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">isset</span>(<span style="color:#4be6fa">$_GET</span>[<span style="color:#ffd500">'logout'</span>]))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;session_destroy();</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;unset(<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">header</span>(<span style="color:#ffd500">"location:&nbsp;login.php"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;login_btn이&nbsp;클릭되면&nbsp;login()&nbsp;함수&nbsp;호출</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">isset</span>(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'login_btn'</span>]))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;login();</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;사용자&nbsp;로그인</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;login()</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;global&nbsp;<span style="color:#4be6fa">$db</span>,&nbsp;<span style="color:#4be6fa">$username</span>,&nbsp;<span style="color:#4be6fa">$errors</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;폼&nbsp;값&nbsp;가져오기</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$username</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'username'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$password</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;e(<span style="color:#4be6fa">$_POST</span>[<span style="color:#ffd500">'password'</span>]);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;폼이&nbsp;올바르게&nbsp;채워져&nbsp;있는지&nbsp;확인</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">empty</span>(<span style="color:#4be6fa">$username</span>))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"사용자명이&nbsp;필요합니다"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">empty</span>(<span style="color:#4be6fa">$password</span>))&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"비밀번호가&nbsp;필요합니다"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;폼에&nbsp;오류가&nbsp;없다면&nbsp;로그인&nbsp;시도</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">count</span>(<span style="color:#4be6fa">$errors</span>)&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span><span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#c10aff">0</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$password</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#4be6fa">md5</span>(<span style="color:#4be6fa">$password</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$query</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"SELECT&nbsp;*&nbsp;FROM&nbsp;users&nbsp;WHERE&nbsp;username='$username'&nbsp;AND&nbsp;password='$password'&nbsp;LIMIT&nbsp;1"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$results</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;mysqli_query(<span style="color:#4be6fa">$db</span>,&nbsp;<span style="color:#4be6fa">$query</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(mysqli_num_rows(<span style="color:#4be6fa">$results</span>)&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span><span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#c10aff">1</span>)&nbsp;{&nbsp;<span style="color:#999999">//&nbsp;사용자&nbsp;찾음</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#999999">//&nbsp;사용자가&nbsp;어드민인지&nbsp;또는&nbsp;일반&nbsp;사용자인지&nbsp;확인</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$logged_in_user</span>&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;mysqli_fetch_assoc(<span style="color:#4be6fa">$results</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">$logged_in_user</span>[<span style="color:#ffd500">'user_type'</span>]&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span><span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">'admin'</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>]&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#4be6fa">$logged_in_user</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'success'</span>]&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"로그인되었습니다"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">header</span>(<span style="color:#ffd500">'location:&nbsp;admin/home.php'</span>);&nbsp;<span style="color:#999999">//&nbsp;세션을&nbsp;이용해서&nbsp;페이지를&nbsp;바꾸는&nbsp;코드</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;<span style="color:#ff3399">else</span>&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>]&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#4be6fa">$logged_in_user</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'success'</span>]&nbsp;&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">"로그인되었습니다"</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">header</span>(<span style="color:#ffd500">'location:&nbsp;index.php'</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;<span style="color:#ff3399">else</span>&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#4be6fa">array_push</span>(<span style="color:#4be6fa">$errors</span>,&nbsp;<span style="color:#ffd500">"잘못된&nbsp;사용자명&nbsp;또는&nbsp;비밀번호&nbsp;조합"</span>);</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;...</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#999999">//&nbsp;사용자가&nbsp;어드민인지&nbsp;확인</span></div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%"><span style="color:#ff3399">function</span>&nbsp;isAdmin()</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">if</span>&nbsp;(<span style="color:#4be6fa">isset</span>(<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>])&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">&amp;</span><span style="color:#0086b3"></span><span style="color:#ff3399">&amp;</span>&nbsp;<span style="color:#4be6fa">$_SESSION</span>[<span style="color:#ffd500">'user'</span>][<span style="color:#ffd500">'user_type'</span>]&nbsp;<span style="color:#0086b3"></span><span style="color:#ff3399">=</span><span style="color:#0086b3"></span><span style="color:#ff3399">=</span>&nbsp;<span style="color:#ffd500">'admin'</span>)&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">return</span>&nbsp;<span style="color:#c10aff">true</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}&nbsp;<span style="color:#ff3399">else</span>&nbsp;{</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:#ff3399">return</span>&nbsp;<span style="color:#c10aff">false</span>;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;}</div><div style="padding:0 6px; white-space:pre; line-height:130%">&nbsp;&nbsp;&nbsp;&nbsp;</div><div style="padding:0 6px; white-space:pre; line-height:130%">}</div></div><div style="text-align:right;margin-top:-13px;margin-right:5px;font-size:9px;font-style:italic"><a href="http://colorscripter.com/info#e" target="_blank" style="color:#4f4f4ftext-decoration:none">Colored by Color Scripter</a></div></td><td style="vertical-align:bottom;padding:0 2px 4px 0"><a href="http://colorscripter.com/info#e" target="_blank" style="text-decoration:none;color:white"><span style="font-size:9px;word-break:normal;background-color:#4f4f4f;color:white;border-radius:10px;padding:1px">cs</span></a></td></tr></table></div>
