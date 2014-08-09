<?php
// индекс 4
// Квадрат игра - редактирование
// версия 0.1
if (!empty($userid) && $access['admin']==1 && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $action = $_GET['action'];
    $action = preg_match('/^[a-z]{3,10}$/', $action) ? $action : '';

    if (empty($gameid))
        // Игра не выбрана
    {
        $query = "select * from `games` where gametype = '2' && gameauthor = '".$userinfo['userid']."'";
        $result = $db->query ($query);
        $num_result = $result->num_rows;
        if ($num_result > 0)
        {
            $page['text'] = '<table>';
            for ($i=1;$i<=$num_result;$i++)
            {
                $rows = $result->fetch_assoc();
                $page['text'] .= '<tr><td><a href="admin.php?pid='.$pageid.'&gid='.$rows['gameid'].'">['.$rows['gamename'].']</a></td></tr>';
            }
            $page['text'] .= '</table>';
        }
    }
    else
        // Игра выбрана
    {
        $query = "select * from `games` where gameid='".$gameid."' and gametype = '2'";
        $result = $db->query ($query);
        $num_result = $result->num_rows;
        if ($num_result == 1)
            // Игра существует
        {
            $gameinfo = $result->fetch_assoc();
            if ($gameinfo['gameauthor'] == $userinfo['userid'])
                // Пользователь является автором этой игры
            {
                if ($action == 'edit')
                    // Редактировать данные по игре
                {
                    $adm_gamenew_edit_gamename = $_POST['adm_gamenew_edit_gamename'];
                    $adm_gamenew_edit_gamename = preg_match('/^[0-9a-zA-Zа-яА-ЯЁё _\-\.]{5,50}$/', trim($adm_gamenew_edit_gamename)) ? addslashes(trim($adm_gamenew_edit_gamename)) : '';
                    $adm_gamenew_edit_gamelegend = $_POST['adm_gamenew_edit_gamelegend'];
                    $adm_gamenew_edit_gamelegend = addslashes(htmlspecialchars(trim($adm_gamenew_edit_gamelegend)));
                    $adm_gamenew_edit_gametimestart['1'] = $_POST['adm_gamenew_edit_gametimestart1'];
                    $adm_gamenew_edit_gametimestart['1'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimestart['1']) ? $adm_gamenew_edit_gametimestart['1'] : '';
                    $adm_gamenew_edit_gametimestart['2'] = $_POST['adm_gamenew_edit_gametimestart2'];
                    $adm_gamenew_edit_gametimestart['2'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimestart['2']) ? $adm_gamenew_edit_gametimestart['2'] : '';
                    $adm_gamenew_edit_gametimestart['3'] = $_POST['adm_gamenew_edit_gametimestart3'];
                    $adm_gamenew_edit_gametimestart['3'] = preg_match('/^[0-9]{4}$/', $adm_gamenew_edit_gametimestart['3']) ? $adm_gamenew_edit_gametimestart['3'] : '';
                    $adm_gamenew_edit_gametimestart['4'] = $_POST['adm_gamenew_edit_gametimestart4'];
                    $adm_gamenew_edit_gametimestart['4'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimestart['4']) ? $adm_gamenew_edit_gametimestart['4'] : '';
                    $adm_gamenew_edit_gametimestart['5'] = $_POST['adm_gamenew_edit_gametimestart5'];
                    $adm_gamenew_edit_gametimestart['5'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimestart['5']) ? $adm_gamenew_edit_gametimestart['5'] : '';
                    $adm_gamenew_edit_gametimefinish['1'] = $_POST['adm_gamenew_edit_gametimefinish1'];
                    $adm_gamenew_edit_gametimefinish['1'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimefinish['1']) ? $adm_gamenew_edit_gametimefinish['1'] : '';
                    $adm_gamenew_edit_gametimefinish['2'] = $_POST['adm_gamenew_edit_gametimefinish2'];
                    $adm_gamenew_edit_gametimefinish['2'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimefinish['2']) ? $adm_gamenew_edit_gametimefinish['2'] : '';
                    $adm_gamenew_edit_gametimefinish['3'] = $_POST['adm_gamenew_edit_gametimefinish3'];
                    $adm_gamenew_edit_gametimefinish['3'] = preg_match('/^[0-9]{4}$/', $adm_gamenew_edit_gametimefinish['3']) ? $adm_gamenew_edit_gametimefinish['3'] : '';
                    $adm_gamenew_edit_gametimefinish['4'] = $_POST['adm_gamenew_edit_gametimefinish4'];
                    $adm_gamenew_edit_gametimefinish['4'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimefinish['4']) ? $adm_gamenew_edit_gametimefinish['4'] : '';
                    $adm_gamenew_edit_gametimefinish['5'] = $_POST['adm_gamenew_edit_gametimefinish5'];
                    $adm_gamenew_edit_gametimefinish['5'] = preg_match('/^[0-9]{2}$/', $adm_gamenew_edit_gametimefinish['5']) ? $adm_gamenew_edit_gametimefinish['5'] : '';

                    // получение unix времени старта
                    if (!empty($adm_gamenew_edit_gametimestart['1']) && !empty($adm_gamenew_edit_gametimestart['2']) && !empty($adm_gamenew_edit_gametimestart['3']) && !empty($adm_gamenew_edit_gametimestart['4']) && !empty($adm_gamenew_edit_gametimestart['5']))
                    {
                        $adm_gamenew_edit_gametimestart['unix'] = mktime($adm_gamenew_edit_gametimestart['4'], $adm_gamenew_edit_gametimestart['5'], '00', $adm_gamenew_edit_gametimestart['2'], $adm_gamenew_edit_gametimestart['1'], $adm_gamenew_edit_gametimestart['3']);
                    }

                    // получение unix времени финиша
                    if (!empty($adm_gamenew_edit_gametimefinish['1']) && !empty($adm_gamenew_edit_gametimefinish['2']) && !empty($adm_gamenew_edit_gametimefinish['3']) && !empty($adm_gamenew_edit_gametimefinish['4']) && !empty($adm_gamenew_edit_gametimefinish['5']))
                    {
                        $adm_gamenew_edit_gametimefinish['unix'] = mktime ($adm_gamenew_edit_gametimefinish['4'], $adm_gamenew_edit_gametimefinish['5'], '00', $adm_gamenew_edit_gametimefinish['2'], $adm_gamenew_edit_gametimefinish['1'], $adm_gamenew_edit_gametimefinish['3']);
                    }

                    if (!empty($adm_gamenew_edit_gamename) && !empty($adm_gamenew_edit_gamelegend) && !empty($adm_gamenew_edit_gametimestart['unix']) && !empty($adm_gamenew_edit_gametimefinish['unix']))
                    {
                        $query = "UPDATE `games` SET
														`gamename` = '".$adm_gamenew_edit_gamename."',
														`gamelegend` = '".$adm_gamenew_edit_gamelegend."',
														`gametimestart` = '".$adm_gamenew_edit_gametimestart['unix']."',
														`gametimefinish` = '".$adm_gamenew_edit_gametimefinish['unix']."' 
														WHERE `gameid` = ".$gameid." LIMIT 1";
                        $db->query($query);

                        Header("Location: admin.php?pid=".$pageid."&gid=".$gameid);
                        exit;
                    }
                    else
                    {
                        $page['text'] .= 'Не правильно заполнены поля<br />';
                    }

                    $page['text'] .= '<table><tr><td><form name="editgameform" method="post" action="admin.php?pid=';
                    $page['text'] .= $pageid;
                    $page['text'] .= '&gid='.$gameid.'&action=edit"><table border="0" cellspacing="1">
											<tr>
												<td>Название: </td>
												<td><input name="adm_gamenew_edit_gamename" type="text" value="'.$gameinfo['gamename'].'" /></td>
											</tr>
											<tr>
												<td>Легенда:</td>
												<td><textarea name="adm_gamenew_edit_gamelegend" style="width: 308px; height: 86px" rows="1" cols="20">'.$gameinfo['gamelegend'].'</textarea></td>
											</tr>
											<tr>
												<td>Начало игры:</td>
												<td>';

                    $gameinfo['gametimestart'] = split ('/', date ("d/m/Y/H/i/s" , $gameinfo['gametimestart']));

                    $page['text'] .= 'День <select name="adm_gamenew_edit_gametimestart1">';
                    for ($i=1; $i<=31; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimestart'][0]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$temp.'</option>';
                    }

                    $page['text'] .= '</select> Месяц <select name="adm_gamenew_edit_gametimestart2">';
                    for ($i=1; $i<=12; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimestart'][1]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$cfg_default['month'][$temp].'</option>';
                    }

                    $page['text'] .= '</select> Год <select name="adm_gamenew_edit_gametimestart3">';
                    for ($i=2009; $i<=2011; $i++)
                    {
                        $page['text'] .= '<option value="'.$i.'"';
                        $page['text'] .= $gameinfo['gametimestart'][2]==$i ?  ' selected ' : '';
                        $page['text'] .= '>'.$i.'</option>';
                    }

                    $page['text'] .= '</select> Час <select name="adm_gamenew_edit_gametimestart4">';
                    for ($i=0; $i<=23; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimestart'][3]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$temp.'</option>';
                    }

                    $page['text'] .= '</select>Минуты<select name="adm_gamenew_edit_gametimestart5">';
                    for ($i=0; $i<=59; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimestart'][4]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$temp.'</option>';
                    }
                    $page['text'] .= '</select>';

                    $page['text'] .= '</td></tr><tr><td>Завершение игры:</td><td>';

                    $gameinfo['gametimefinish'] = split ('/', date ("d/m/Y/H/i/s" , $gameinfo['gametimefinish']));

                    $page['text'] .= 'День <select name="adm_gamenew_edit_gametimefinish1">';
                    for ($i=1; $i<=31; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimefinish'][0]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$temp.'</option>';
                    }

                    $page['text'] .= '</select> Месяц <select name="adm_gamenew_edit_gametimefinish2">';
                    for ($i=1; $i<=12; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimefinish'][1]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$cfg_default['month'][$temp].'</option>';
                    }

                    $page['text'] .= '</select> Год <select name="adm_gamenew_edit_gametimefinish3">';
                    for ($i=2009; $i<=2011; $i++)
                    {
                        $page['text'] .= '<option value="'.$i.'"';
                        $page['text'] .= $gameinfo['gametimefinish'][2]==$i ?  ' selected ' : '';
                        $page['text'] .= '>'.$i.'</option>';
                    }

                    $page['text'] .= '</select> Час <select name="adm_gamenew_edit_gametimefinish4">';
                    for ($i=0; $i<=23; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimefinish'][3]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$temp.'</option>';
                    }

                    $page['text'] .= '</select> Минуты <select name="adm_gamenew_edit_gametimefinish5">';
                    for ($i=0; $i<=59; $i++)
                    {
                        if ($i <10)
                        {
                            $temp = '0'.$i;
                        }
                        else
                        {
                            $temp = $i;
                        }
                        $page['text'] .= '<option value="'.$temp.'"';
                        $page['text'] .= $gameinfo['gametimefinish'][4]==$temp ?  ' selected ' : '';
                        $page['text'] .= '>'.$temp.'</option>';
                    }
                    $page['text'] .= '</select>';

                    $page['text'] .= '</td>
												</tr>
												<tr>
													<td>Автор:</td>
													<td>'.$username.'</td>
												</tr>
												<tr>
													<td>&nbsp;</td>
													<td><input name="submit" type="submit" value="Oбновить" /></td>
												</tr></table></form><br />
												<a href="admin.php?pid='.$pageid.'&gid='.$gameid.'">Назад</a>
												<hr />';
                }
                elseif ($action == 'del' && $gameinfo['gametimestart'] > (time()+$cfg_timeadjustment))
                {
                    $query = "DELETE FROM `gamestaken` WHERE takengame = '".$gameid."'";
                    $db->query($query);

                    $query = "DELETE FROM `kvadratzadanie` WHERE zadaniegame = '".$gameid."'";
                    $db->query($query);

                    $query = "DELETE FROM `games` WHERE gameid = '".$gameid."' && gamedisplay = 0";
                    $db->query($query);

                    Header("Location: admin.php?pid=".$pageid);
                    exit;
                }
                elseif ($action == 'display'/* && numrowsbase('kvadratzadanie', 'zadaniegame', $gameid) == 16*/ && $gameinfo['gametimestart'] > (time()+$cfg_timeadjustment))
                {
                    if ($gameinfo['gamedisplay'] == 0)
                    {
                        $query = "UPDATE  `games` SET  `gamedisplay` =  '1' WHERE  `gameid` = '".$gameid."'";
                        $db->query($query);
                    }
                    elseif ($gameinfo['gamedisplay'] == 1)
                    {
                        $query = "UPDATE  `games` SET  `gamedisplay` =  '0' WHERE  `gameid` = '".$gameid."'";
                        $db->query($query);
                    }
                    Header("Location: admin.php?pid=".$pageid.'&gid='.$gameid);
                    exit;
                }
                else
                {

                    $page['text'] = '<table style="width: 100%">
														<tr>
															<td>Название: '.$gameinfo['gamename'].'</td>
														</tr>
														<tr>
															<td>Формат игры: KBADRAT</td>
														</tr>
														<tr>
															<td>Легенда:<br />'.nl2br(stripslashes($gameinfo['gamelegend'])).'</td>
														</tr>
														<tr>
															<td>Старт: '.date("d/m/Y H:i:s" , $gameinfo['gametimestart']).'</td>
														</tr>
														<tr>
															<td>Финиш: '.date("d/m/Y H:i:s" , $gameinfo['gametimefinish']).'</td>
														</tr>
														<tr>
															<td>Автор: </td>
														</tr>
														<tr>
															<td>Публикация: ';
                    if (true/*numrowsbase('kvadratzadanie', 'zadaniegame', $gameid) == 16*/)
                    {
                        $page['text'] .= '<a href="admin.php?pid='.$pageid.'&gid='.$gameid.'&action=display">';
                        $page['text'] .= !empty($gameinfo['gamedisplay']) ? 'Да' : 'Нет';
                        $page['text'] .= '</a><br />';
                    }
                    else
                    {
                        $page['text'] .= 'Недостаточно заданий для публикации';
                    }


                    $page['text'] .= '</td></tr></table>';
                    $page['text'] .= '<a href="admin.php?pid='.$pageid.'&gid='.$gameid.'&action=del">Удалить игру</a><br />
														<a href="admin.php?pid='.$pageid.'&gid='.$gameid.'&action=edit">Редактировать игру</a>';


                    $page['text'] .= '<hr />';

                    //задания

                    $query = "select * from `kvadratzadanie` where zadaniegame='".$gameid."' order by zadanieugol asc";
                    $result = $db->query($query);
                    $num_result = $result->num_rows;
                    if ($num_result > 0)
                    {
                        $page['text'] .= "<table>";
                        for ($i=1;$i<=$num_result;$i++)
                        {
                            $rows = $result->fetch_assoc();
                            $zadanie['id'][$i] =		$rows['zadanieid'];
                            $zadanie['text'][$i] =		$rows['zadanietext'];
                            $zadanie['ugol'][$i] =		$rows['zadanieugol'];
                            //$tempchek[$rows['zadanieugol']] = 1;
                            $zadanie['kod'][$i] =		$rows['zadaniekod'];
                            $zadanie['answer'][$i] =	$rows['zadanieanswer'];

                            $page['text'] .= '<table style="width: 100%"><tr><td colspan="2">Задание: ';
                            $page['text'] .= nl2br(stripslashes($rows['zadanietext']));
                            $page['text'] .= '</td></tr><tr><td>Угол: ';
                            $page['text'] .= $rows['zadanieugol'];
                            $page['text'] .= '</td><td>Код: ';
                            $page['text'] .= $rows['zadaniekod'];
                            $page['text'] .= '</td></tr><tr><td colspan="2">Решение: ';
                            $page['text'] .= nl2br(stripslashes($rows['zadanieanswer']));
                            $page['text'] .= '</td></tr><tr>
																				<td><a href="admin.php?pid=7&gid='.$gameid.'&zid='.$rows['zadanieid'].'&action=edit">Редактировать</a></td>
																				<td><a href="admin.php?pid=7&gid='.$gameid.'&zid='.$rows['zadanieid'].'&action=del">Удалить</a></td>
																			</tr>
																		</table><hr />';

                        }
                    }



                    $page['text'] .= '<br><a href="admin.php?pid=7&gid='.$gameid.'&action=add">Добавить задание</a>';

                }
            }
        }
        else
        {
            Header("Location: admin.php?pid=5");
            exit;
        }

        /*

        if (!empty($adm_gamenew_edit_gamename) && !empty($adm_gamenew_edit_gametype) && !empty($adm_gamenew_add_gamelegend) && !empty($adm_gamenew_add_gametimestart) && !empty($adm_gamenew_add_gametimefinish))
            {
                $query = "INSERT INTO `games` VALUES (NULL, '".$adm_gamenew_add_gamename."', '".$adm_gamenew_add_gametype."', '".$adm_gamenew_add_gamelegend."', '".$adm_gamenew_add_gametimestart."', '".$adm_gamenew_add_gamettimefinish."', '".$userid."')";
                $db->query($query);

                $query = "select * from `games` where gamename = '".$adm_gamenew_add_gamename."' and gametype = '".$adm_gamenew_add_gametype."' and gameautor = '".$userid."'";
                $result = $db->query ($query);
                $num_result = $result->num_rows;
                if ($num_result == "1")
                    {
                        $rows = $result->fetch_assoc();
                        switch ($rows['gametype'])
                            {
                                case '1':
                                    $temp = '';
                                    break;
                                case '2':
                                    $temp = '';
                                    break;
                                case '3':
                                    $temp = '';
                                    break;
                                case '4':
                                    $temp = '';
                                    break;
                            }
                        Header("Location: admin.php?pid=".$temp."&gid=".$rows['gameid']);
                    }
                else
                    {
                        Header("Location: admin.php");
                    }
            }

        $query = "select * from `games` where gameid gametype = '2'";

        */
    }
}