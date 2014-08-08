<?php
/**
 *
 */
session_start();
$sid=$_COOKIE['sid'];
$pageid = $_GET['pid'];
$pageid = htmlspecialchars(trim($pageid));
$pageid = preg_match('/^[0-9]{1,3}$/', $pageid) ? $pageid : '';
$gameid = $_GET['gid'];
$gameid = htmlspecialchars(trim($gameid));
$gameid = preg_match('/^[0-9]{1,11}$/', $gameid) ? $gameid : '';
$page['id'] = "admin";

require "config.php"; //ПОДКЛЮЧЕНИЕ НАСТРОЕК
require "ferunclass.php";
require "auth/auth.php";

@ $db = new mysqli($db_server, $db_user, $db_pass, $db_name); //соединение с базой данных

if (!empty($userid) && $access['admin']==1)
{



    if ($pageid>0)
    {
        $query = "SELECT * FROM `sitepage` WHERE pageid = '".$pageid."'";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if ($num_result == 1)
        {
            $rows = $result->fetch_assoc();
            require $rows['pageurl'];
        }
        else
        {
            require "page/0/index.php";
        }
    }
    else
    {
        require "page/0/index.php";
    }




    ?><html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo($site['name']); ?></title>
    <link href="http://<?php echo($site['domen']); ?>/css/css.css" rel="stylesheet" type=text/css>
</head>

<body style="margin: 0; background-color: #ffffff; color: #000000;">

<table style="width: 100%" cellspacing="0" cellpadding="0">
    <tr>
        <td align="left"><a href="index.php">Сайт</a> | <a href="admin.php">Админка</a>
        </td>
        <td align="right"><?php echo $form_auth;/*('<table border="0" cellspacing="1">
				<tr>
					<td class="style1">Команда: '.$username.'</td>
					<td class="style1"  valign="bottom"> <a href="auth/logout.php">Выход</a> </td>
				</tr>
		</table>');*/ ?></td>
    </tr>
</table>
<hr />
<?php







echo $page['text'];




?>
</body>
    </html>
<?php
}
else
{
    Header("Location: index.php");
}
$db->close();
?>