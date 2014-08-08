<?php


$userid='';
$username='';

@ $db = new mysqli($db_server, $db_user, $db_pass, $db_name); //соединение с базой данных

if (empty($sid))
{
    $userid='0';
}
else
{
    $query_sid = "select * from `session` where sid='".$sid."'";
    $result_sid = $db->query($query_sid);
    $num_result_sid = $result_sid->num_rows;
    if ($num_result_sid="1")
    {
        $rows_sid = $result_sid->fetch_assoc();
        $userid = $rows_sid['userid'];
    }
}

if (!empty($userid))
{

    $query_user = "select * from `users` where userid = '".$userid."'";
    $result_user = $db->query($query_user);
    $userinfo = $result_user->fetch_assoc();
    $username = $userinfo['username'];
    $access['admin'] = ($userinfo['useraccess']==1)?1:0;
    if (!empty($userinfo['userteam']))
    {
        $query = "select * from `teams` where teamid = '".$userinfo['userteam']."'";
        $result = $db->query($query);
        $temp = $result->fetch_assoc();
        $temp = '&tid='.$userinfo['userteam'].'">'.$temp['teamname'];
    }
    else
    {
        $temp = '">---';
    }

    $form_auth = '<table border="0" cellspacing="1" style="width: 230px">
				<tr>
					<td class="style1" style="width: 200px; text-align: right">Пользователь: <a href="index.php?pid=12&uid='.$userinfo['userid'].'">'.$username.'</a>[<a href="index.php?pid=11'.$temp.'</a>]</td>
					<td class="style1" style="width: 30px" valign="bottom"> <a href="auth/logout.php">Выход</a> </td>
				</tr>
		</table>';
}
else
{
    $form_auth = '<table border="0" cellspacing="1">
					<tr><form name="loginform" method="post" action="auth/login.php">
					<td class="style1"><a href="index.php?pid=10">Регистрация</a></td>
					<td class="style1">пользователь:<input class="loginField" type="text" style="width:80px;" name="user" title="пользователь" size="20" maxlength="15"></td>
					<td class="style1">пароль:<input class="loginField" type="password" style="width:80px;" name="password" title="пароль" size="20" maxlength="15"></td> 
					<td class="style1" valign="bottom"><input class="loginField" type="submit" value="Вход"></td></form>
				</tr> 
			</table>';
}

$db->close();