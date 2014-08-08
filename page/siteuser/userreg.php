<?php
// индекс 8(10)
// Регистрация
// версия 0.3

///////////////////////////////////////////
//        РЕГИСТРАЦИЯ НА САЙТЕ
///////////////////////////////////////////

if (empty($userid))
{

    $register['login'] = 	$_POST['register_login'];
    $register['nikname'] = 	$_POST['register_nikname'];
    $register['password'] = $_POST['register_password'];
    $register['email'] = 	$_POST['register_email'];
    $register['post'] = 	$_POST['register_post'];
    $register['login'] = 	strtolower(trim($register['login']));
    $register['email'] = 	strtolower(trim($register['email']));

    if ($register['post']==1)
    {
        if (!empty($register['login']))
        {
            if (ereg('^[a-z]{3,20}$', $register['login']))
            {
                $query = "select * from `users` where userlogin='".$register['login']."'";
                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result == 0)
                {
                    $error['login'] = 0;
                }
                else
                {
                    $page['text'] .= '<font color="#FF0000">Такой логин уже занят.</font><br />';
                    $register['login'] = '';
                }
            }
            else
            {
                $page['text'] .= '<font color="#FF0000">Вы использовали недопустимый логин.</font><br />';
                $register['login'] = '';
            }
        }
        else
        {
            $page['text'] .= '<font color="#FF0000">Вы не заполнили поле "Логин".</font><br />';
        }

        if (!empty($register['nikname']))
        {
            if (ereg('^[a-zA-Zа-яА-Я0-9]{3,20}$', $register['nikname']))
            {
                $query = "select * from `users` where username='".$register['nikname']."'";
                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result == 0)
                {
                    $error['nikname'] = 0;
                }
                else
                {
                    $page['text'] .= '<font color="#FF0000">Такой никнэйм уже занят.</font><br />';
                    $register['nikname'] = '';
                }
            }
            else
            {
                $page['text'] .= '<font color="#FF0000">Вы использовали недопустимый никнэйм.</font><br />';
                $register['nikname'] = '';
            }
        }
        else
        {
            $page['text'] .= '<font color="#FF0000">Вы не заполнили поле "Никнэйм".</font><br />';
        }

        if (!empty($register['password']))
        {
            if (ereg('^[a-zA-Z0-9]{3,20}$', $register['password']))
            {
                $error['password'] = 0;
                $register['password'] =  md5($register['password']);
            }
            else
            {
                $page['text'] .= '<font color="#FF0000">Вы использовали недопустимый пароль.</font><br />';
                $register['password'] = '';
            }
        }
        else
        {
            $page['text'] .= '<font color="#FF0000">Вы не заполнили поле "Пароль".</font><br />';
        }

        if (!empty($register['email']))
        {
            if (ereg('^[a-z0-9_\.\-]{3,}@[a-z]{4,}\.[a-z]{2,3}$', $register['email']))
            {
                $error['email'] = 0;
            }
            else
            {
                $page['text'] .= '<font color="#FF0000">Вы использовали недопустимый email.</font><br />';
                $register['email'] = '';
            }
        }
        else
        {
            $page['text'] .= '<font color="#FF0000">Вы не заполнили поле "E-mail".</font><br />';
        }

        if (!empty($register['login']) && !empty($register['nikname']) && !empty($register['password']) && !empty($register['email']))
        {
            $query = "INSERT INTO `users` VALUES (NULL, '".$register['login']."', '".$register['password']."', '".$register['nikname']."', '".$register['email']."', '0', '0', '0')";
            $db->query($query);
            Header("Location: index.php");
            exit;
        }
    }

    $page['text'] .= '<form name="registerform" method="post" action="index.php?pid=';
    $page['text'] .= $pageid;
    $page['text'] .= '"><input name="register_post" type="hidden" value="1" /><table style="width: 100%">
				<tr>
					<td>Логин</td>
					<td><input name="register_login" type="text"';
    if ($error['login'] == 0)
    {
        $page['text'] .= ' value="';
        $page['text'] .= $register['login'];
        $page['text'] .= '"';
    }
    $page['text'] .= ' />Разрешены символы латинского алфавита в нижнем регистре. От 3 до 20 символов.</td>
				</tr>
				<tr>
					<td>Никнэйм</td>
					<td><input name="register_nikname" type="text"';
    if ($error['nikname'] == 0)
    {
        $page['text'] .= ' value="';
        $page['text'] .= $register['nikname'];
        $page['text'] .= '"';
    }
    $page['text'] .= ' />Разрешены символы латинского алфавита, цифры, дефис, точка, и нижнее подчеркивание. От 3 до 20 символов.</td>
				</tr>
				<tr>
					<td>Пароль</td>
					<td><input name="register_password" type="text" />Разрешены символы латинского алфавита и цифры. От 3 до 20 символов.</td>
				</tr>
				<tr>
					<td>E-mail</td>
					<td><input name="register_email" type="text"';
    if ($error['email'] == 0)
    {
        $page['text'] .= ' value="';
        $page['text'] .= $register['email'];
        $page['text'] .= '"';
    }
    $page['text'] .= ' />Разрешены символы латинского алфавита, цифры, дефис, точка, и нижнее подчеркивание.</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><input name="register_submit" type="submit" value="Ввод" /></td>
				</tr>
			</table>';
}
else
{
    $page['text'] = 'Вы уже зарегистрированы!!!';
}