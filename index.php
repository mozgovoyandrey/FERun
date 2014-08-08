<?php
/**
 * Основной модуль программы
 */
session_start();
$sid=$_COOKIE['sid'];
$pageid = $_GET['pid'];
$pageid = htmlspecialchars(trim($pageid));
$pageid = preg_match('/^[0-9]{1,3}$/', $pageid) ? $pageid : '';
$page['id'] = "index";
$gameid = $_GET['gid'];
$gameid = htmlspecialchars(trim($gameid));
$gameid = preg_match('/^[0-9]{1,11}$/', $gameid) ? $gameid : '';
$errorcode = $_GET['ec'];
$errorcode = htmlspecialchars(trim($errorcode));
$errorcode = preg_match('/^[0-9]{1,11}$/', $errorcode) ? $errorcode : '';

require "config.php"; //ПОДКЛЮЧЕНИЕ НАСТРОЕК
require "ferunclass.php";
require "auth/auth.php";

@ $db = new mysqli($db_server, $db_user, $db_pass, $db_name); //соединение с базой данных


if ($pageid>0) {
    $query = "SELECT * FROM `sitepage` WHERE pageid = '".$pageid."'";
    $result = $db->query($query);
    $num_result = $result->num_rows;
    if ($num_result == 1) {
        $rows = $result->fetch_assoc();
        require $rows['pageurl'];
    } else {
        require "page/0/index.php";
    }
} else {
    require "page/0/index.php";
}






?><html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo($site['name']); ?></title>
    <link href="http://<?php echo($_SERVER['HTTP_HOST']); ?>/css/style.css" rel="stylesheet" type=text/css>
</head>

<body style="margin: 0; background-color: #ffffff; color: #000000;">

<table style="width: 100%" cellspacing="0" cellpadding="0">
    <tr>
        <td class="style1" style="width: 50%" align="left"><div style="width: 200px"><!--ADR</a> | <a href="index.php">КВADRAT</a> | <a href="index.php">DC --><a href="index.php">Главная</a> <!--| <a href="index.php?pid=8">INFO</a>-->
                <?php if($access['admin']==1) { echo ("| <a href='admin.php'>ADMIN</a>");} ?></div>
        </td>
        <td align="right" style="width: 50%"><?php echo ($form_auth); ?></td>
    </tr>
</table>
<hr />
<?php

if (!empty($errorcode)) echo $page['errorcode'][$errorcode].'<br />';

echo $page['text'];

?>
</body>
</html>
<?php
$db->close();