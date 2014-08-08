<?php
///////////////////////////////////////////
//        ПРОФИЛЬ КОМАНДЫ
///////////////////////////////////////////

// индекс 10
// Профиль команды
// версия 0.1


if (!empty($userid) && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $action = $_GET['action'];
    $teamid = $_GET['tid'];
    $action = ereg('^[a-z]{3,20}$', $action) ? $action : '';
    $teamid = ereg('^[0-9]{1,11}$', $teamid) ? $teamid : '';

    if ($action == 'newteam' && empty($userinfo['userteam']))
        // Создание новой команды
    {
        $newteamname = $_POST['newteamname'];
        $newteamname = ereg('^[a-zA-Zа-яА-Я0-9]{3,20}$', $newteamname) ? $newteamname : '';
        //$action = ereg('^[a-z]{3,20}$', $action) ? $action : '';
        if (!empty($newteamname) && numrowsbase('teams', 'teamname', $newteamname) == 0)
        {
            $query = "INSERT INTO `teams` VALUES (NULL, '".$newteamname."', '".$userinfo['userid']."')";
            $db->query($query);

            $query = "select * from `teams` where teamname = '".$newteamname."' && teamcaptain = '".$userinfo['userid']."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $rows = $result->fetch_assoc();
                $query = "UPDATE  `users` SET  `userteam` =  '".$rows['teamid']."' WHERE  `userid` = '".$userinfo['userid']."'";
                $db->query($query);
                $query = "delete from `teamrequest` where requestuser = '".$userinfo['userid']."'";
                $db->query($query);
            }
        }
        Header("Location: index.php?pid=".$pageid);
        exit;
    }
    elseif ($action == 'delteam' && !empty($userinfo['userteam']))
        // Удаление/роспуск свое команды
    {
        $query = "select * from `teams` where teamid = '".$userinfo['userteam']."' && teamcaptain = '".$userinfo['userid']."'";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if ($num_result == 1)
        {
            $query = "delete from `teams` where teamid = '".$userinfo['userteam']."'";
            $db->query($query);
            $query = "delete from `teamrequest` where requestteam = '".$userinfo['userteam']."'";
            $db->query($query);
            $query = "select * from `users` where userteam = '".$userinfo['userteam']."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {
                for ($i=1;$i<=$num_result;$i++)
                {
                    $rows = $result->fetch_assoc();
                    $query = "UPDATE  `users` SET  `userteam` =  '0' WHERE  `userid` = '".$rows['userid']."'";
                    $db->query($query);
                }
            }
        }

        Header("Location: admin.php?pid=".$pageid);
        exit;
    }
    elseif ($action == 'editteam' && !empty($userinfo['userteam']))
        // редактирование данных команды (название)
    {
        $query = "select * from `teams` where teamid = '".$userinfo['userteam']."' && teamcaptain = '".$userinfo['userid']."'";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if (numrowsbase('teams', 'teamid', $userinfo['userteam'], 'teamcaptain', $userinfo['userid']) == 1)
        {
            $editteamname = $_POST['editteamname'];
            $rows = $result->fetch_assoc();
            if (!empty($editteamname) && numrowsbase('teams', 'teamname', $editteamname) == 0)
            {
                $query = "UPDATE  `teams` SET  `teamname` =  '".$editteamname."' WHERE  `teamid` = '".$rows['teamid']."'";
                $db->query($query);

                Header("Location: index.php?pid=".$pageid."&tid=".$rows['teamid']);
                exit;
            }
            else
            {
                $page['text'] .= '<form name="editteamform" method="post" action="index.php?pid='.$pageid.'&action=editteam"><input name="editteamname" type="text" value="'.$rows['teamname'].'"/><input name="submit" type="submit" value="Изменить" /></form>';
            }
        }
    }
    elseif ($action == 'addrequest' && !empty($teamid) && empty($userinfo['userteam']))
        // подача заявки на вступление в команду
    {
        $query = "select * from `teams` where teamid = '".$teamid."'";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if ($num_result == 1)
        {
            $query = "delete from `teamrequest` where requestuser = '".$userinfo['userid']."'";
            $db->query($query);
            $query = "INSERT INTO `teamrequest` VALUES (NULL, '".$teamid."', '".$userinfo['userid']."')";
            $db->query($query);
        }
        Header("Location: index.php?pid=".$pageid."&tid=".$teamid);
        exit;
    }
    elseif ($action == 'delrequest')
        // удаление заявки на вступление
    {
        $query = "select * from `teamrequest` where requestuser = '".$userinfo['userid']."'";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if ($num_result > 0)
        {
            $query = "delete from `teamrequest` where requestuser = '".$userinfo['userid']."'";
            $db->query($query);
        }
        Header("Location: index.php?pid=".$pageid."&tid=".$teamid);
        exit;
    }
    elseif ($action == 'reject' && !empty($userinfo['userteam']))
    {
        $rejectuser = $_GET['rejectuser'];
        if (!empty($rejectuser))
        {
            $query = "select * from `teamrequest` where requestteam = '".$userinfo['userteam']."' && requestuser = '".$rejectuser."'";

            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $query = "delete from `teamrequest` where requestuser = '".$rejectuser."'";
                $db->query($query);
            }
        }
        Header("Location: index.php?pid=".$pageid."&tid=".$userinfo['userteam']);
        exit;
    }
    elseif ($action == 'addmember' && !empty($userinfo['userteam']))
    {
        $adduser = $_GET['adduser'];
        if (!empty($adduser))
        {
            $query = "select * from `teamrequest` where requestteam = '".$userinfo['userteam']."' && requestuser = '".$adduser."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $query = "delete from `teamrequest` where requestuser = '".$adduser."'";
                $db->query($query);
                $query = "UPDATE  `users` SET  `userteam` =  '".$userinfo['userteam']."' WHERE  `userid` = '".$adduser."'";
                $db->query($query);
            }
        }
        Header("Location: index.php?pid=".$pageid."&tid=".$userinfo['userteam']);
        exit;
    }
    /*elseif ($action == 'delmember')
        {
            $rejectuser = $_GET['rejectuser'];
            if (!empty($rejectuser))
            {
                $query = "select * from `teamrequest` where requestteam = '".$userinfo['userteam']."' && requestuser = '".$rejectuser."'";

                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result == 0)
                    {
                        $query = "delete from `teamrequest` where requestuser = '".$rejectuser."'";
                        $db->query($query);
                    }
            }
            Header("Location: index.php?pid=".$pageid."&tid=".$teamid);
            exit;
        }*/
    /*elseif ($action == 'search')
        {
            $rejectuser = $_GET['rejectuser'];
            if (!empty($rejectuser))
            {
                $query = "select * from `teamrequest` where requestteam = '".$userinfo['userteam']."' && requestuser = '".$rejectuser."'";

                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result == 0)
                    {
                        $query = "delete from `teamrequest` where requestuser = '".$rejectuser."'";
                        $db->query($query);
                    }
            }
            Header("Location: index.php?pid=".$pageid."&tid=".$teamid);
            exit;
        }*/
    elseif ($action == 'list')
    {
        $query = "SELECT * FROM `teams` WHERE 1";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if ($num_result > 0)
        {
            $page['text'] .= '<table><tr><td>№</td><td>NAME</td></tr>';
            for($i=1;$i<=$num_result;$i++)
            {
                $rows = $result->fetch_assoc();
                $page['text'] .= '<tr><td>'.$i.'</td><td><a href="index.php?pid='.$pageid.'&tid='.$rows['teamid'].'">'.$rows['teamname'].'</a></td></tr>';
            }
            $page['text'] .= '<tr><td></td><td></td></tr></table>';
        }
    }
    else
    {

        if (!empty($teamid))
        {
            $query = "SELECT * FROM `teams` WHERE teamid='".$teamid."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $rows_team = $result->fetch_assoc();

                if ($rows_team['teamcaptain'] == $userinfo['userid'])
                {
                    $page['text'] = '<a href="index.php?pid='.$pageid.'&action=list">Все</a> | ';
                    $page['text'] .= 'Команда: '.$rows_team['teamname'].'<br />';
                    $page['text'] .= 'Состав: <br />';

                    $query = "SELECT * FROM `users` WHERE userteam = '".$rows_team['teamid']."'";
                    $result = $db->query($query);
                    $num_result = $result->num_rows;
                    if ($num_result > 0)
                    {
                        $page['text'] .= '<table><tr><td>№</td>
																	<td>NAME</td>
																	<td>Статус</td></tr>';
                        for($i=1;$i<=$num_result;$i++)
                        {
                            $rows = $result->fetch_assoc();

                            $page['text'] .= '<tr><td>'.$i.'</td><td>'.$rows['username'].'</td><td>';
                            $page['text'] .= $rows['userid'] != $rows_team['teamcaptain'] ? 'Игрок' : 'Капитан';
                            $page['text'] .= '</td></tr>';
                        }
                        $page['text'] .= '<tr><td></td><td></td><td></td></tr></table><br /><a href="index.php?pid='.$pageid.'&action=editteam">Переименовать команду</a><br /><a href="index.php?pid='.$pageid.'&action=delteam">Удалить команду</a>';
                    }

                    $query = "SELECT users.userid, users.username
													FROM `teamrequest`, `users` 
													WHERE teamrequest.requestteam = '".$rows_team['teamid']."' 
													and users.userid = teamrequest.requestuser";
                    $result = $db->query($query);
                    $num_result = $result->num_rows;
                    if ($num_result > 0)
                    {
                        $page['text'] .= '<br />Заявки на вступление: <br /><table><tr><td>№</td>
																	<td>NAME</td>
																	<td>Действие</td></tr>';
                        for($i=1;$i<=$num_result;$i++)
                        {
                            $rows = $result->fetch_assoc();

                            $page['text'] .= '<tr><td>'.$i.'</td><td>'.$rows['username'].'</td><td>';
                            $page['text'] .= '<a href="index.php?pid='.$pageid.'&action=addmember&adduser='.$rows['userid'].'">Принять</a>/<a href="index.php?pid='.$pageid.'&action=reject&rejectuser='.$rows['userid'].'">Отклонить</a>';
                            $page['text'] .= '</td></tr>';
                        }
                        $page['text'] .= '<tr><td></td><td></td><td></td></tr></table>';
                    }
                }
                else
                {
                    $page['text'] = '<a href="index.php?pid='.$pageid.'&action=list">Все</a> | ';
                    $page['text'] .= 'Команда: '.$rows_team['teamname'].'<br />';
                    $page['text'] .= 'Состав: <br />';

                    $query = "SELECT * FROM `users` WHERE userteam = '".$rows_team['teamid']."'";
                    $result = $db->query($query);
                    $num_result = $result->num_rows;
                    if ($num_result > 0)
                    {
                        $page['text'] .= '<table><tr><td> № </td>
																	<td>NAME</td>
																	<td>Статус</td></tr>';
                        for($i=1;$i<=$num_result;$i++)
                        {
                            $rows = $result->fetch_assoc();

                            $page['text'] .= '<tr><td>'.$i.'</td><td>'.$rows['username'].'</td><td>';
                            $page['text'] .= $rows['userid'] != $rows_team['teamcaptain'] ? 'Игрок' : 'Капитан';
                            $page['text'] .= '</td></tr>';
                        }
                        $page['text'] .= '<tr><td></td><td></td><td></td></tr></table>';
                    }

                    if (empty($userinfo['userteam']))
                    {
                        $query = "SELECT * FROM `teamrequest` WHERE requestteam = '".$teamid."' &&  requestuser = '".$userinfo['userid']."'";
                        $result = $db->query($query);
                        $num_result = $result->num_rows;
                        if ($num_result > 0)
                        {
                            $page['text'] .= '<br /><a href="index.php?pid='.$pageid.'&action=delrequest&tid='.$teamid.'">Отменить заявку</a>';
                        }
                        else
                        {
                            $page['text'] .= '<br /><a href="index.php?pid='.$pageid.'&action=addrequest&tid='.$teamid.'">Подать заявку</a>';
                        }
                        $page['text'] .= '<br />';
                    }

                }
            }
            else
            {
                Header("Location: index.php?pid=".$pageid);
                exit;
            }
        }
        else
        {
            if (!empty($userinfo['userteam']))
            {
                $page['text'] = '<a href="index.php?pid='.$pageid.'&action=list">Все</a> | ';
                $page['text'] .= '<a href="index.php?pid='.$pageid.'&tid='.$userinfo['userteam'].'">Моя команда</a>';
            }
            else
            {
                $page['text'] = '<a href="index.php?pid='.$pageid.'&action=list">Все</a> <br /> ';
                $page['text'] .= '<form name="newteamform" method="post" action="index.php?pid='.$pageid.'&action=newteam"><input name="newteamname" type="text" /><input name="submit" type="submit" value="Создать" /></form>';
            }
        }
    }
}