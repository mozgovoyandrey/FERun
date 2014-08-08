<?php
// индекс 11
// Профиль игрока
// версия 0.2

///////////////////////////////////////////
//        ПРОФИЛЬ ИГРОКА
///////////////////////////////////////////

if (!empty($userid) && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $action = $_GET['action'];
    $action = ereg('^[a-z]{3,10}$', $action) ? $action : '';
    $viewuser = $_GET['uid'];
    $viewuser = ereg('^[0-9]{1,11}$', $viewuser) ? $viewuser : '';

    if (!empty($viewuser) && $userinfo['userid'] == $viewuser)
    {
        if ($action == 'edit')
        {

        }
        elseif ($action == 'editpass')
        {
            $editpass = $_POST['newpassword'];
            $editpass = ereg('^[a-zA-Z0-9]{3,20}$', $editpass) ? $editpass : '';
            if (!empty($editpass['pass']))
            {
                $query = "UPDATE  `users` SET  `userpassword` =  '".md5($editpass)."' WHERE  `userid` = '".$userinfo['userid']."'";
                $db->query($query);

                Header("Location: index.php?pid=".$pageid."&uid=".$userinfo['userid']);
                exit;
            }
            else
            {
                $page['text'] .= '<form name="editpassform" method="post" action="index.php?pid='.$pageid.'&uid='.$userinfo['userid'].'&action=editpass"><input name="newpassword" type="text" value=""/><input name="submit" type="submit" value="Изменить" /></form>';
            }
        }
        else
        {
            $page['text'] .= '<a href="index.php?pid='.$pageid.'&action=list">Все пользователи</a><br />
						Игрок: '.$userinfo['username'].'<br />
						Логин: '.$userinfo['userlogin'].'<br />
						Пароль: <a href="index.php?pid='.$pageid.'&uid='.$userinfo['userid'].'&action=editpass">Сменить пароль</a><br />
						E-mail: '.$userinfo['usermail'].'<br />';
            $query = "SELECT teams.teamid, teams.teamname FROM `teams` WHERE teamid='".$userinfo['userteam']."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $rows = $result->fetch_assoc();
                if (!empty($rows['teamid']) && !empty($rows['teamname']))
                {
                    $page['text'] .= 'Команда: <a href="index.php?pid=11&tid='.$rows['teamid'].'">'.$rows['teamname'].'</a><br />';
                }
            }
            else
            {
                $page['text'] .= 'Команда: Нет<br />';
            }

            // информация о играх (автор)
            $query = "SELECT gameid, gamename, gametype FROM `games` WHERE gameauthor='".$viewuser."' and gamedisplay='0'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {
                $page['text'] .= '<table><tr><td collspan=2>Автор игр</td></tr><tr><td>Формат</td><td>Название</td></tr>';
                for ($i=1; $i <= $num_result; $i++)
                {
                    $rows = $result->fetch_assoc();
                    $page['text'] .= '<tr><td>'.$cfg_gametype['name'][$rows['gametype']].'</td><td><a href="index.php?pid='.$cfg_gametype['engine'][$rows['gametype']].'&gid='.$rows['gameid'].'">'.$rows['gamename'].'</a></td></tr>';
                }
            }
        }
    }
    elseif (!empty($viewuser) && $userinfo['userid'] != $viewuser)
    {
        if ($action == 'msg')
        {

        }
        else
        {
            $query = "SELECT userid, username, userteam FROM `users` WHERE userid='".$viewuser."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                // информация о пользователе
                $rows = $result->fetch_assoc();
                $page['text'] .= '<a href="index.php?pid='.$pageid.'&action=list">Все пользователи</a><br /> Игрок: '.$rows['username'].'<br />';

                // информация о команде
                $query = "SELECT teamid, teamname FROM `teams` WHERE teamid='".$rows['userteam']."'";
                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result == 1)
                {
                    $rows = $result->fetch_assoc();
                    if (!empty($rows['teamid']) && !empty($rows['teamname']))
                    {
                        $page['text'] .= 'Команда: <a href="index.php?pid=11&tid='.$rows['teamid'].'">'.$rows['teamname'].'</a><br />';
                    }
                }
                else
                {
                    $page['text'] .= 'Команда: Нет<br />';
                }

                // информация о играх (автор)
                $query = "SELECT gameid, gamename, gametype FROM `games` WHERE gameauthor='".$viewuser."' and gamedisplay='0'";
                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result > 0)
                {
                    $page['text'] .= '<table><tr><td collspan=2>Автор игр</td></tr><tr><td>Формат</td><td>Название</td></tr>';
                    for ($i=1; $i <= $num_result; $i++)
                    {
                        $rows = $result->fetch_assoc();
                        $page['text'] .= '<tr><td>'.$cfg_gametype['name'][$rows['gametype']].'</td><td><a href="index.php?pid='.$cfg_gametype['engine'][$rows['gametype']].'&gid='.$rows['gameid'].'">'.$rows['gamename'].'</a></td></tr>';
                    }
                }
            }

        }
    }
    else
    {
        if ($action == 'list')
        {
            $query = "SELECT userid, username FROM `users` where 1 order by username asc";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {
                $page['text'] .= '<table><tr><td> № </td><td>NAME</td></tr>';
                for($i=1;$i<=$num_result;$i++)
                {
                    $rows = $result->fetch_assoc();
                    $page['text'] .= '<tr><td>'.$i.'</td><td><a href="index.php?pid='.$pageid.'&uid='.$rows['userid'].'">'.$rows['username'].'</a></td></tr>';
                }
                $page['text'] .= '<tr><td></td><td></td></tr></table>';
            }
        }
        else
        {
            Header("Location: index.php?pid=".$pageid."&uid=".$userinfo['userid']);
            exit;
        }
    }
}