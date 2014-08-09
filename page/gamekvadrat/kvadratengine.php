<?php
// индекс 5
// Квадрат игра - движок
// версия 0.1
if (!empty($userid) && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    if (!empty($gameid))
    {
        $query_game = "select * from `games` where gameid='".$gameid."' && gametype = '2'";
        $result_game = $db->query ($query_game);
        $num_result_game = $result_game->num_rows;
        if ($num_result_game == 1)
        {
            $rows_game = $result_game->fetch_assoc();

            //настройки
            $gamecountcod = 20;

            //получение информации
            $action = $_GET['action'];
            $action = preg_match('/^[a-z]{3,13}$/', $action) ? $action : '';

            ////

            $query = "select teams.teamid, teams.teamname, gamestaken.takenaccept
								from `gamestaken`, `teams`
								where gamestaken.takengame = '".$gameid."'
								&& teams.teamid = gamestaken.takenteam";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {
                $temp = 0;
                $temp2 = 0;
                for ($i=1; $i<=$num_result; $i++)
                {
                    $rows = $result->fetch_assoc();
                    if ($rows['takenaccept'] == 0)
                    {
                        $temp++;
                        $teamsrequest['id'][$temp] = $rows['teamid'];
                        $teamsrequest['name'][$temp] = $rows['teamname'];
                    }
                    elseif ($rows['takenaccept'] == 1)
                    {
                        $temp2++;
                        $teamstaken['id'][$temp2] = $rows['teamid'];
                        $teamstaken['name'][$temp2] = $rows['teamname'];
                    }
                }
            }

            ////
            if ((time()+$cfg_timeadjustment) < $rows_game['gametimefinish'])
            {
                if ($userid == $rows_game['gameauthor'])
                    // автор игры
                {
                    if ($action == 'accepttaken')
                    {
                        $acceptteam = $_GET['acceptteam'];
                        if (!empty($acceptteam))
                        {
                            $accept = "UPDATE `gamestaken` SET takenaccept = 1 WHERE takengame = '".$gameid."' && takenteam = '".$acceptteam."'";
                            $db->query($accept);
                        }
                        Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                        exit;
                    }
                    elseif ($action == 'deltaken')
                    {
                        $delteam = $_GET['delteam'];
                        if (!empty($delteam))
                        {
                            $delet = "DELETE FROM `gamestaken` WHERE takengame = '".$gameid."' && takenteam = '".$delteam."'";
                            $db->query($delet);
                        }
                        Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                        exit;
                    }
                    elseif ($action == 'logteam')
                    {
                        $teamid = $_GET['tid'];
                        $teamid = preg_match('/^[0-9]{1,11}$/', $teamid) ? $teamid : '';
                        $query = "SELECT glogtime, glogtext, glogugol FROM `kvadratgamelog` WHERE gloggame='".$gameid."' && glogteam='".$teamid."' order by glogtime asc";
                        $result = $db->query($query);
                        $num_result = $result->num_rows;
                        $page['text'] .= '<table><tr><td><a href="http://ferun.org.ru/index.php?pid='.$pageid.'&gid='.$gameid.'">Назад</a></td><td>Время</td><td>Код</td><td>Угол</td></tr>';
                        for ($i = 1; $i <= $num_result; $i++)
                        {
                            $rows = $result->fetch_assoc();
                            $page['text'] .= '<teble><tr><td>'.$i.'</td><td>'.date("H:i:s" , $rows['glogtime']).'</td><td>'.$rows['glogtext'].'</td><td>'.$rows['glogugol'].'</td></tr>';
                        }
                        $page['text'] .= '</table>';

                    }
                    else
                    {
                        if ($rows_game['gametimestart'] < (time()+$cfg_timeadjustment) and $rows_game['gametimefinish'] > (time()+$cfg_timeadjustment) )
                        {
                            // ИГРА
                            //
                            //	СТАТИСТИКА ИГРЫ
                            if (count($teamstaken['id']) > 0)
                            {
                                // статистика заголовок задания
                                $query_tzad = "SELECT zadaniekod, zadaniehelp, zadanieugol FROM `kvadratzadanie` WHERE zadaniegame='".$gameid."' order by zadanieugol asc";// заголовок задания
                                $result_tzad = $db->query($query_tzad);
                                $num_result_tzad = $result_tzad->num_rows;
                                if ($num_result_tzad > 0)
                                {
                                    for ($i = 1; $i <= $num_result_tzad; $i++)
                                    {
                                        $rows_tzad = $result_tzad->fetch_assoc();
                                        $tzad['kod'][$rows_tzad['zadanieugol']] = $rows_tzad['zadaniekod'];
                                        $tzad['help'][$rows_tzad['zadanieugol']] = $rows_tzad['zadaniehelp'];
                                    }
                                }
                                $page['text'] .= '<table border=1><tr><td></td>
																<td>A1<div title="'.$tzad['help']['11'].'">'.$tzad['kod']['11'].'</div></td>
																<td>A2<div title="'.$tzad['help']['12'].'">'.$tzad['kod']['12'].'</div></td>
																<td>A3<div title="'.$tzad['help']['13'].'">'.$tzad['kod']['13'].'</div></td>
																<td>A4<div title="'.$tzad['help']['14'].'">'.$tzad['kod']['14'].'</div></td>
																<td>Б1<div title="'.$tzad['help']['21'].'">'.$tzad['kod']['21'].'</div></td>
																<td>Б2<div title="'.$tzad['help']['22'].'">'.$tzad['kod']['22'].'</div></td>
																<td>Б3<div title="'.$tzad['help']['23'].'">'.$tzad['kod']['23'].'</div></td>
																<td>Б4<div title="'.$tzad['help']['24'].'">'.$tzad['kod']['24'].'</div></td>
																<td>В1<div title="'.$tzad['help']['31'].'">'.$tzad['kod']['31'].'</div></td>
																<td>В2<div title="'.$tzad['help']['32'].'">'.$tzad['kod']['32'].'</div></td>
																<td>В3<div title="'.$tzad['help']['33'].'">'.$tzad['kod']['33'].'</div></td>
																<td>В4<div title="'.$tzad['help']['34'].'">'.$tzad['kod']['34'].'</div></td>
																<td>Г1<div title="'.$tzad['help']['41'].'">'.$tzad['kod']['41'].'</div></td>
																<td>Г2<div title="'.$tzad['help']['42'].'">'.$tzad['kod']['42'].'</div></td>
																<td>Г3<div title="'.$tzad['help']['43'].'">'.$tzad['kod']['43'].'</div></td>
																<td>Г4<div title="'.$tzad['help']['44'].'">'.$tzad['kod']['44'].'</div></td>
																<td>Бонус1<div title="'.$tzad['help']['51'].'">'.$tzad['kod']['51'].'</div></td>
																<td>Бонус2<div title="'.$tzad['help']['52'].'">'.$tzad['kod']['52'].'</div></td>
																<td>Бонус3<div title="'.$tzad['help']['53'].'">'.$tzad['kod']['53'].'</div></td>
																<td>Бонус4<div title="'.$tzad['help']['54'].'">'.$tzad['kod']['54'].'</div></td></tr>';
                                // статистика команды
                                for ($i=1; $i<=count($teamstaken['id']); $i++)
                                {
                                    $query_st = "SELECT gkodugol, gkodtime, gkoduser FROM `kvadratgame`
																		WHERE gkodgame='".$gameid."' && gkodteam='".$teamstaken['id'][$i]."' order by gkodtime asc";
                                    $result_st = $db->query($query_st);
                                    $num_result_st = $result_st->num_rows;
                                    $stat_lv['team'][$i] = $teamstaken['id'][$i]; // данные для вычисления места
                                    $stat_lv['kod'][$i] = 0;
                                    $st = '';
                                    for ($j = 1; $j <= $num_result_st; $j++)
                                    {
                                        $rows_st = $result_st->fetch_assoc();
                                        if ($j == 1) $st_start = $rows_st['gkodugol'];
                                        if (($rows_st['gkodugol'] > 0) and ($rows_st['gkodugol'] < 45)) $stat_lv['kod'][$i]++;
                                        $st['time'][$rows_st['gkodugol']] = $rows_st['gkodtime'];
                                        $st['user'][$rows_st['gkodugol']] = $rows_st['gkoduser'];
                                        if (($rows_st['gkodugol'] > 0) and ($rows_st['gkodugol'] < 45) and ($j == $num_result_st)) $stat_lv['time'][$i] = $rows_st['gkodtime'];
                                    }
                                    $stat_txt[$teamstaken['id'][$i]] .= '<tr><td><a href="index.php?pid=11&tid='.$teamstaken['id'][$i].'"> ['.$teamstaken['name'][$i].']</a>
																		<br /><a href="http://ferun.org.ru/admin.php?pid='.$pageid.'&gid='.$gameid.'&tid='.$teamstaken['id'][$i].'&action=logteam">[лог]</a></td>
																		<td>';
                                    if (!empty($st['time'][11]))
                                    { if ($st_start != 11) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][11].'">'.date ("H:i:s" , $st['time'][11] - $st['time'][41]).'<br />('.date ("H:i:s" , $st['time'][11]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][11].'">'.date ("H:i:s" , $st['time'][11] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][11]).')</div>'; }
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][41])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][12]))
                                    { if ($st_start != 12) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][12].'">'.date ("H:i:s" , $st['time'][12] - $st['time'][11]).'<br />('.date ("H:i:s" , $st['time'][12]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][12].'">'.date ("H:i:s" , $st['time'][12] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][12]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][11])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][13]))
                                    { if ($st_start != 13) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][13].'">'.date ("H:i:s" , $st['time'][13] - $st['time'][12]).'<br />('.date ("H:i:s" , $st['time'][13]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][13].'">'.date ("H:i:s" , $st['time'][13] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][13]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][12])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][14]))
                                    { if ($st_start != 14) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][14].'">'.date ("H:i:s" , $st['time'][14] - $st['time'][13]).'<br />('.date ("H:i:s" , $st['time'][14]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][14].'">'.date ("H:i:s" , $st['time'][14] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][14]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][13])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][21]))
                                    { if ($st_start != 21) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][21].'">'.date ("H:i:s" , $st['time'][21] - $st['time'][11]).'<br />('.date ("H:i:s" , $st['time'][21]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][21].'">'.date ("H:i:s" , $st['time'][21] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][21]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][11])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][22]))
                                    { if ($st_start != 22) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][22].'">'.date ("H:i:s" , $st['time'][22] - $st['time'][21]).'<br />('.date ("H:i:s" , $st['time'][22]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][22].'">'.date ("H:i:s" , $st['time'][22] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][22]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][21])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][23]))
                                    { if ($st_start != 23) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][23].'">'.date ("H:i:s" , $st['time'][23] - $st['time'][22]).'<br />('.date ("H:i:s" , $st['time'][23]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][23].'">'.date ("H:i:s" , $st['time'][23] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][23]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][22])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][24]))
                                    { if ($st_start != 24) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][24].'">'.date ("H:i:s" , $st['time'][24] - $st['time'][23]).'<br />('.date ("H:i:s" , $st['time'][24]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][24].'">'.date ("H:i:s" , $st['time'][24] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][24]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][23])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][31]))
                                    { if ($st_start != 31) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][31].'">'.date ("H:i:s" , $st['time'][31] - $st['time'][21]).'<br />('.date ("H:i:s" , $st['time'][31]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][31].'">'.date ("H:i:s" , $st['time'][31] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][31]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][21])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][32]))
                                    { if ($st_start != 32) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][32].'">'.date ("H:i:s" , $st['time'][32] - $st['time'][31]).'<br />('.date ("H:i:s" , $st['time'][32]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][32].'">'.date ("H:i:s" , $st['time'][32] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][32]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][31])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][33]))
                                    { if ($st_start != 33) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][33].'">'.date ("H:i:s" , $st['time'][33] - $st['time'][32]).'<br />('.date ("H:i:s" , $st['time'][33]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][33].'">'.date ("H:i:s" , $st['time'][33] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][33]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][32])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][34]))
                                    { if ($st_start != 34) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][34].'">'.date ("H:i:s" , $st['time'][34] - $st['time'][33]).'<br />('.date ("H:i:s" , $st['time'][34]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][34].'">'.date ("H:i:s" , $st['time'][34] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][34]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][33])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][41]))
                                    { if ($st_start != 41) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][41].'">'.date ("H:i:s" , $st['time'][41] - $st['time'][31]).'<br />('.date ("H:i:s" , $st['time'][41]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][41].'">'.date ("H:i:s" , $st['time'][41] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][41]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][31])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][42]))
                                    { if ($st_start != 42) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][42].'">'.date ("H:i:s" , $st['time'][42] - $st['time'][41]).'<br />('.date ("H:i:s" , $st['time'][42]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][42].'">'.date ("H:i:s" , $st['time'][42] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][42]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][41])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][43]))
                                    { if ($st_start != 43) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][43].'">'.date ("H:i:s" , $st['time'][43] - $st['time'][42]).'<br />('.date ("H:i:s" , $st['time'][43]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][43].'">'.date ("H:i:s" , $st['time'][43] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][43]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][42])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                    if (!empty($st['time'][44]))
                                    { if ($st_start != 44) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][44].'">'.date ("H:i:s" , $st['time'][44] - $st['time'][43]).'<br />('.date ("H:i:s" , $st['time'][44]).')</div>';
                                    else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][44].'">'.date ("H:i:s" , $st['time'][44] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][44]).')</div>';}
                                    else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][43])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                    $stat_txt[$teamstaken['id'][$i]] .= '</td><td>Бонус1</td><td>Бонус2</td><td>Бонус3</td><td>Бонус4</td></tr>';
                                }

                                // выравнивание таблицы в соответствии с местом
                                for ($i = 1; $i < count($stat_lv['team']); $i++)
                                {
                                    for ($j = $i+1; $j <= count($stat_lv['team']); $j++)
                                    {
                                        if ($stat_lv['kod'][$i] < $stat_lv['kod'][$j])
                                        {
                                            $temp = $stat_lv;
                                            $stat_lv['kod'][$j] = $temp['kod'][$i];
                                            $stat_lv['time'][$j] = $temp['time'][$i];
                                            $stat_lv['team'][$j] = $temp['team'][$i];
                                            $stat_lv['kod'][$i] = $temp['kod'][$j];
                                            $stat_lv['time'][$i] = $temp['time'][$j];
                                            $stat_lv['team'][$i] = $temp['team'][$j];
                                            /*$temp['kod'] = $stat_lv['kod'][$j];		$stat_lv['kod'][$j] = $stat_lv['kod'][$i];	$stat_lv['kod'][$i] = $temp['kod'];
                                            $temp['time'] = $stat_lv['time'][$j];	$stat_lv['time'][$j] = $stat_lv['time'][$i];$stat_lv['time'][$i] = $temp['time'];
                                            $temp['team'] = $stat_lv['team'][$j];	$stat_lv['team'][$j] = $stat_lv['team'][$i];$stat_lv['team'][$i] = $temp['team'];*/







                                        }
                                        elseif (($stat_lv['kod'][$i] == $stat_lv['kod'][$j]) && ($stat_lv['time'][$i] > $stat_lv['time'][$j]))
                                        {
                                            $temp['kod'] = $stat_lv['kod'][$i];
                                            $temp['time'] = $stat_lv['time'][$i];
                                            $temp['team'] = $stat_lv['team'][$i];
                                            $stat_lv['kod'][$i] = $stat_lv['kod'][$j];
                                            $stat_lv['time'][$i] = $stat_lv['time'][$j];
                                            $stat_lv['team'][$i] = $stat_lv['team'][$j];
                                            $stat_lv['kod'][$j] = $temp['kod'];
                                            $stat_lv['time'][$j] = $temp['time'];
                                            $stat_lv['team'][$j] = $temp['team'];
                                        }
                                    }
                                }

                                for ($i = 1; $i <= count($stat_lv['team']); $i++)
                                {
                                    $page['text'] .= $stat_txt[$stat_lv['team'][$i]];
                                }
                                $page['text'] .= '</table>';
                            }
                            //
                            //
                            $page['text'] .= 'Завершить игру<br /> Открыть статистику<br />';
                        }
                        elseif ($rows_game['gametimestart'] > (time()+$cfg_timeadjustment))
                        {
                            // ДО СТАРТА

                        }
                        else
                        {
                            // ПОСЛЕ ФИНИША

                        }
                        /// стандартные функции для автора (вне зависимости от состояния игры)
                        if (count($teamsrequest['id']) > 0)
                        {
                            $page['text'] .= 'Подали заявку:<br />';
                            $page['text'] .= "<table><tr><td>Команда</td><td>Действие</td></tr>";
                            for ($i=1; $i<=count($teamsrequest['id']); $i++)
                            {
                                $page['text'] .= '<tr><td> <a href="index.php?pid=11&tid='.$teamsrequest['id'][$i].'"> ['.$teamsrequest['name'][$i].']</a></td>';
                                $page['text'] .= '<td> <a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=accepttaken&acceptteam='.$teamsrequest['id'][$i].'">принять</a> / <a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=deltaken&delteam='.$teamsrequest['id'][$i].'">отклонить</a> </td></tr>';
                            }
                            $page['text'] .= "</table>";
                        }

                        if (count($teamstaken['id']) > 0)
                        {
                            $page['text'] .= 'Приняты к участию:<br />';
                            $page['text'] .= "<table><tr><td>Команда</td><td>Действие</td></tr>";
                            for ($i=1; $i<=count($teamstaken['id']); $i++)
                            {
                                $page['text'] .= '<tr><td> <a href="index.php?pid=11&tid='.$teamstaken['id'][$i].'"> ['.$teamstaken['name'][$i].']</a> </td>';
                                $page['text'] .= '<td> <a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=deltaken&delteam='.$teamstaken['id'][$i].'">исключить</a> </td></tr>';
                            }
                            $page['text'] .= '</table>';
                        }
                        ///
                    }
                }
                else
                    // игрок
                {
                    if (!empty($userinfo['userteam']))
                        // если игрок имеет команду
                    {


                        $query_team = "select * from `teams` where teamid='".$userinfo['userteam']."'";
                        $result_team = $db->query ($query_team);
                        $num_result_team = $result_team->num_rows;
                        if ($num_result_team == 1)
                        {
                            $rows_team = $result_team->fetch_assoc();
                        }

                        $query = "select * from `gamestaken` where takenteam='".$userinfo['userteam']."' &&  takengame='".$gameid."'";
                        $result = $db->query($query);
                        $num_result = $result->num_rows;
                        if ($num_result == 1)
                            // заявка подана
                        {
                            $rows = $result->fetch_assoc();
                            if ($rows['takenaccept'] == 1)
                                // заявка принята
                            {
                                if ((time()+$cfg_timeadjustment) > $rows_game['gametimestart'] && (time()+$cfg_timeadjustment) < $rows_game['gametimefinish'])
                                    // +++++++++++++++++++++ ИГРА ++++++++++++++++++
                                {
                                    if ($action == 'stat')
                                    {
                                        //	СТАТИСТИКА ИГРЫ
                                        if (count($teamstaken['id']) > 0)
                                        {
                                            // статистика заголовок задания
                                            $page['text'] .= '<table border=1><tr><td><a href="http://ferun.org.ru/index.php?pid='.$pageid.'&gid='.$gameid.'">Назад</a></td>
																						<td>A1</td><td>A2</td><td>A3</td><td>A4</td>
																						<td>Б1</td><td>Б2</td><td>Б3</td><td>Б4</td>
																						<td>В1</td><td>В2</td><td>В3</td><td>В4</td>
																						<td>Г1</td><td>Г2</td><td>Г3</td><td>Г4</td>
																						<td>Бонус1</td><td>Бонус2</td><td>Бонус3</td><td>Бонус4</td></tr>';
                                            // статистика команды
                                            for ($i=1; $i<=count($teamstaken['id']); $i++)
                                            {
                                                $query_st = "SELECT gkodugol, gkodtime, gkoduser FROM `kvadratgame`
																								WHERE gkodgame='".$gameid."' && gkodteam='".$teamstaken['id'][$i]."' order by gkodtime asc";
                                                $result_st = $db->query($query_st);
                                                $num_result_st = $result_st->num_rows;
                                                $stat_lv['team'][$i] = $teamstaken['id'][$i]; // данные для вычисления места
                                                $stat_lv['kod'][$i] = 0;
                                                $st = '';
                                                for ($j = 1; $j <= $num_result_st; $j++)
                                                {
                                                    $rows_st = $result_st->fetch_assoc();
                                                    if ($j == 1) $st_start = $rows_st['gkodugol'];
                                                    if (($rows_st['gkodugol'] > 0) and ($rows_st['gkodugol'] < 45)) $stat_lv['kod'][$i]++;
                                                    $st['time'][$rows_st['gkodugol']] = $rows_st['gkodtime'];
                                                    $st['user'][$rows_st['gkodugol']] = $rows_st['gkoduser'];
                                                    if (($rows_st['gkodugol'] > 0) and ($rows_st['gkodugol'] < 45) and ($j == $num_result_st)) $stat_lv['time'][$i] = $rows_st['gkodtime'];
                                                }
                                                $stat_txt[$teamstaken['id'][$i]] .= '<tr><td><a href="index.php?pid=11&tid='.$teamstaken['id'][$i].'"> ['.$teamstaken['name'][$i].']</a>
																								<br /><a href="http://ferun.org.ru/admin.php?pid='.$pageid.'&gid='.$gameid.'&tid='.$teamstaken['id'][$i].'&action=logteam">[лог]</a></td>
																								<td>';
                                                if (!empty($st['time'][11]))
                                                { if ($st_start != 11) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][11].'">'.date ("H:i:s" , $st['time'][11] - $st['time'][41]).'<br />('.date ("H:i:s" , $st['time'][11]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][11].'">'.date ("H:i:s" , $st['time'][11] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][11]).')</div>'; }
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][41])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][12]))
                                                { if ($st_start != 12) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][12].'">'.date ("H:i:s" , $st['time'][12] - $st['time'][11]).'<br />('.date ("H:i:s" , $st['time'][12]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][12].'">'.date ("H:i:s" , $st['time'][12] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][12]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][11])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][13]))
                                                { if ($st_start != 13) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][13].'">'.date ("H:i:s" , $st['time'][13] - $st['time'][12]).'<br />('.date ("H:i:s" , $st['time'][13]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][13].'">'.date ("H:i:s" , $st['time'][13] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][13]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][12])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][14]))
                                                { if ($st_start != 14) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][14].'">'.date ("H:i:s" , $st['time'][14] - $st['time'][13]).'<br />('.date ("H:i:s" , $st['time'][14]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][14].'">'.date ("H:i:s" , $st['time'][14] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][14]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][13])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][21]))
                                                { if ($st_start != 21) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][21].'">'.date ("H:i:s" , $st['time'][21] - $st['time'][11]).'<br />('.date ("H:i:s" , $st['time'][21]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][21].'">'.date ("H:i:s" , $st['time'][21] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][21]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][11])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][22]))
                                                { if ($st_start != 22) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][22].'">'.date ("H:i:s" , $st['time'][22] - $st['time'][21]).'<br />('.date ("H:i:s" , $st['time'][22]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][22].'">'.date ("H:i:s" , $st['time'][22] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][22]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][21])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][23]))
                                                { if ($st_start != 23) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][23].'">'.date ("H:i:s" , $st['time'][23] - $st['time'][22]).'<br />('.date ("H:i:s" , $st['time'][23]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][23].'">'.date ("H:i:s" , $st['time'][23] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][23]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][22])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][24]))
                                                { if ($st_start != 24) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][24].'">'.date ("H:i:s" , $st['time'][24] - $st['time'][23]).'<br />('.date ("H:i:s" , $st['time'][24]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][24].'">'.date ("H:i:s" , $st['time'][24] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][24]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][23])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][31]))
                                                { if ($st_start != 31) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][31].'">'.date ("H:i:s" , $st['time'][31] - $st['time'][21]).'<br />('.date ("H:i:s" , $st['time'][31]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][31].'">'.date ("H:i:s" , $st['time'][31] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][31]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][21])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][32]))
                                                { if ($st_start != 32) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][32].'">'.date ("H:i:s" , $st['time'][32] - $st['time'][31]).'<br />('.date ("H:i:s" , $st['time'][32]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][32].'">'.date ("H:i:s" , $st['time'][32] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][32]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][31])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][33]))
                                                { if ($st_start != 33) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][33].'">'.date ("H:i:s" , $st['time'][33] - $st['time'][32]).'<br />('.date ("H:i:s" , $st['time'][33]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][33].'">'.date ("H:i:s" , $st['time'][33] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][33]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][32])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][34]))
                                                { if ($st_start != 34) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][34].'">'.date ("H:i:s" , $st['time'][34] - $st['time'][33]).'<br />('.date ("H:i:s" , $st['time'][34]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][34].'">'.date ("H:i:s" , $st['time'][34] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][34]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][33])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][41]))
                                                { if ($st_start != 41) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][41].'">'.date ("H:i:s" , $st['time'][41] - $st['time'][31]).'<br />('.date ("H:i:s" , $st['time'][41]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][41].'">'.date ("H:i:s" , $st['time'][41] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][41]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][31])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][42]))
                                                { if ($st_start != 42) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][42].'">'.date ("H:i:s" , $st['time'][42] - $st['time'][41]).'<br />('.date ("H:i:s" , $st['time'][42]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][42].'">'.date ("H:i:s" , $st['time'][42] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][42]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][41])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][43]))
                                                { if ($st_start != 43) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][43].'">'.date ("H:i:s" , $st['time'][43] - $st['time'][42]).'<br />('.date ("H:i:s" , $st['time'][43]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][43].'">'.date ("H:i:s" , $st['time'][43] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][43]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][42])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>';
                                                if (!empty($st['time'][44]))
                                                { if ($st_start != 44) $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][44].'">'.date ("H:i:s" , $st['time'][44] - $st['time'][43]).'<br />('.date ("H:i:s" , $st['time'][44]).')</div>';
                                                else $stat_txt[$teamstaken['id'][$i]] .= '<div title = "'.$st['user'][44].'">'.date ("H:i:s" , $st['time'][44] - $rows_game['gametimestart']).'<br />('.date ("H:i:s" , $st['time'][44]).')</div>';}
                                                else	$stat_txt[$teamstaken['id'][$i]] .= (!empty($st['time'][43])) ? '<div>На этапе</div>' : '<div>&nbsp;</div>';
                                                $stat_txt[$teamstaken['id'][$i]] .= '</td><td>Бонус1</td><td>Бонус2</td><td>Бонус3</td><td>Бонус4</td></tr>';
                                            }

                                            // выравнивание таблицы в соответствии с местом
                                            for ($i = 1; $i < count($stat_lv['team']); $i++)
                                            {
                                                for ($j = $i+1; $j <= count($stat_lv['team']); $j++)
                                                {
                                                    if ($stat_lv['kod'][$i] < $stat_lv['kod'][$j])
                                                    {
                                                        $temp = $stat_lv;
                                                        $stat_lv['kod'][$j] = $temp['kod'][$i];
                                                        $stat_lv['time'][$j] = $temp['time'][$i];
                                                        $stat_lv['team'][$j] = $temp['team'][$i];
                                                        $stat_lv['kod'][$i] = $temp['kod'][$j];
                                                        $stat_lv['time'][$i] = $temp['time'][$j];
                                                        $stat_lv['team'][$i] = $temp['team'][$j];
                                                    }
                                                    elseif (($stat_lv['kod'][$i] == $stat_lv['kod'][$j]) && ($stat_lv['time'][$i] > $stat_lv['time'][$j]))
                                                    {
                                                        $temp = $stat_lv;
                                                        $stat_lv['kod'][$j] = $temp['kod'][$i];
                                                        $stat_lv['time'][$j] = $temp['time'][$i];
                                                        $stat_lv['team'][$j] = $temp['team'][$i];
                                                        $stat_lv['kod'][$i] = $temp['kod'][$j];
                                                        $stat_lv['time'][$i] = $temp['time'][$j];
                                                        $stat_lv['team'][$i] = $temp['team'][$j];
                                                    }
                                                }
                                            }

                                            for ($i = 1; $i <= count($stat_lv['team']); $i++)
                                            {
                                                $page['text'] .= $stat_txt[$stat_lv['team'][$i]];
                                            }
                                            $page['text'] .= '</table>';
                                        }
                                        //
                                    }
                                    else
                                    {
                                        if (numrowsbase('kvadratgame', 'gkodgame', $gameid, 'gkodteam', $userinfo['userteam']) < $gamecountcod)
                                        {
                                            // проверка найденых кодов
                                            $query = "SELECT gkodugol FROM `kvadratgame` WHERE gkodgame = '".$gameid."' && gkodteam = '".$userinfo['userteam']."' ORDER BY gkodtime ASC";
                                            $result = $db->query($query);
                                            $num_result = $result->num_rows;
                                            if ($num_result > 0)
                                            {
                                                for ($i=1; $i<=$num_result; $i++)
                                                {
                                                    $rows_gku = $result->fetch_assoc();
                                                    if ($i == 1) {$startugol = $rows_gku['gkodugol'];}
                                                    $gku[$rows_gku['gkodugol']] = 1;
                                                }
                                            }

                                            // проверка найденых кодов к подсказкам
                                            $query = "SELECT ghkodugol FROM `kvadratgamehelp` WHERE ghkodgame = '".$gameid."' && ghkodteam = '".$userinfo['userteam']."' ORDER BY ghkodtime ASC";
                                            $result = $db->query($query);
                                            $num_result = $result->num_rows;
                                            if ($num_result > 0)
                                            {
                                                for ($i=1; $i<=$num_result; $i++)
                                                {
                                                    $rows_gku = $result->fetch_assoc();
                                                    if ($i == 1 && empty($startugol)) {$startugol = $rows_gku['ghkodugol'];}
                                                    $ghku[$rows_gku['ghkodugol']] = 1;
                                                }
                                            }

                                            // получение данных о задании
                                            $query = "SELECT zadanietext, zadanieugol, zadaniekod, zadaniehelp, zadaniehelpkod FROM `kvadratzadanie` WHERE zadaniegame = '".$gameid."' ORDER BY zadanieugol ASC";
                                            $result = $db->query($query);
                                            $num_result = $result->num_rows;
                                            if ($num_result > 0)
                                            {
                                                for ($i=1; $i<=$num_result; $i++)
                                                {
                                                    $rows_gz = $result->fetch_assoc();
                                                    $gztext[$rows_gz['zadanieugol']] = $rows_gz['zadanietext'];
                                                    $gzkod[$rows_gz['zadanieugol']] = $rows_gz['zadaniekod'];
                                                    $gzhelp[$rows_gz['zadanieugol']] = $rows_gz['zadaniehelp'];
                                                    $gzhelpkod[$rows_gz['zadanieugol']] = $rows_gz['zadaniehelpkod'];
                                                }
                                            }

                                            // получение данных
                                            $code = $_POST['code'];

                                            if (!empty($code))
                                            {
                                                //   ЛОГ ВВЕДЕНЫХ КОДОВ
                                                $codelog[1] = addslashes(htmlspecialchars($code));
                                                if (!empty($gku['41']) or $startugol==11)
                                                {	if (!empty($gku['11']))
                                                {	if (!empty($gku['12']))
                                                {	if (!empty($gku['13']))
                                                { if (empty($gku['14'])) {$codelog[3] = '14';} }
                                                else {$codelog[3] = '13';}
                                                } else {$codelog[3] = '12';}
                                                } else
                                                { if (!empty($gku['41'])) {$codelog[3] = '11';} }
                                                }
                                                if (!empty($gku['11']) or $startugol==21)
                                                {	if (!empty($gku['21']))
                                                {	if (!empty($gku['22']))
                                                {	if (!empty($gku['23']))
                                                { if (empty($gku['24'])) {$codelog[3] .= '*24';} }
                                                else {$codelog[3] .= '*23';}
                                                } else {$codelog[3] .= '*22';}
                                                } else
                                                { if (!empty($gku['11'])) {$codelog[3] .= '*21';} }
                                                }
                                                if (!empty($gku['21']) or $startugol==31)
                                                {	if (!empty($gku['31']))
                                                {	if (!empty($gku['32']))
                                                {	if (!empty($gku['33']))
                                                { if (empty($gku['34'])) {$codelog[3] .= '*34';} }
                                                else {$codelog[3] .= '*33';}
                                                } else {$codelog[3] .= '*32';}
                                                } else
                                                { if (!empty($gku['21'])) {$codelog[3] .= '*31';} }
                                                }
                                                if (!empty($gku['31']) or $startugol==41)
                                                {	if (!empty($gku['41']))
                                                {	if (!empty($gku['42']))
                                                {	if (!empty($gku['43']))
                                                { if (empty($gku['44'])) {$codelog[3] .= '*44';} }
                                                else {$codelog[3] .= '*43';}
                                                } else {$codelog[3] .= '*42';}
                                                } else
                                                { if (!empty($gku['31'])) {$codelog[3] .= '*41';} }
                                                }
                                                $codelog[2] = time()+$cfg_timeadjustment;
                                                $query = "INSERT INTO `kvadratgamelog` VALUES (NULL, '".$gameid."', '".$userinfo['userteam']."', '".$codelog[2]."', '".$codelog[1]."', '".$codelog[3]."')";
                                                $db->query($query);
                                                $codelog = '';
                                            }

                                            $code = preg_match('/^[0-9a-zA-Zа-яА-Я]{2,20}$/', $code) ? $code : '';
                                            // проверка и обработка полученого кода
                                            if (!empty($code))
                                            {
                                                $codeugol = '';

                                                if (!empty($gku['41']) or $startugol==11 or empty($startugol))
                                                {	if (!empty($gku['11']))
                                                {	if (!empty($gku['12']))
                                                {	if (!empty($gku['13']))
                                                {	if (empty($gku['14']))
                                                {
                                                    if ($code == $gzkod['14']) {$codeugol = 14;}
                                                    elseif ($code == $gzhelpkod['14']) {$codehelp = 14;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['13']) {$codeugol = 13;}
                                                    elseif ($code == $gzhelpkod['13']) {$codehelp = 13;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['12']) {$codeugol = 12;}
                                                    elseif ($code == $gzhelpkod['12']) {$codehelp = 12;}}
                                                }
                                                else
                                                {
                                                    if (!empty($gku['41']) or empty($startugol)) {
                                                        if ($code == $gzkod['11']) {$codeugol = 11;}
                                                        elseif ($code == $gzhelpkod['11']) {$codehelp = 11;}}
                                                }
                                                }

                                                if (!empty($gku['11']) or $startugol==21 or empty($startugol))
                                                {	if (!empty($gku['21']))
                                                {	if (!empty($gku['22']))
                                                {	if (!empty($gku['23']))
                                                {	if (empty($gku['24']))
                                                {
                                                    if ($code == $gzkod['24']) {$codeugol = 24;}
                                                    elseif ($code == $gzhelpkod['24']) {$codehelp = 24;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['23']) {$codeugol = 23;}
                                                    elseif ($code == $gzhelpkod['23']) {$codehelp = 23;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['22']) {$codeugol = 22;}
                                                    elseif ($code == $gzhelpkod['22']) {$codehelp = 22;}}
                                                }
                                                else
                                                {
                                                    if (!empty($gku['11']) or empty($startugol)) {
                                                        if ($code == $gzkod['21']) {$codeugol = 21;}
                                                        elseif ($code == $gzhelpkod['21']) {$codehelp = 21;}}
                                                }
                                                }

                                                if (!empty($gku['21']) or $startugol==31 or empty($startugol))
                                                {	if (!empty($gku['31']))
                                                {	if (!empty($gku['32']))
                                                {	if (!empty($gku['33']))
                                                {	if (empty($gku['34']))
                                                {
                                                    if ($code == $gzkod['34']) {$codeugol = 34;}
                                                    elseif ($code == $gzhelpkod['34']) {$codehelp = 34;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['33']) {$codeugol = 33;}
                                                    elseif ($code == $gzhelpkod['33']) {$codehelp = 33;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['32']) {$codeugol = 32;}
                                                    elseif ($code == $gzhelpkod['32']) {$codehelp = 32;}}
                                                }
                                                else
                                                {
                                                    if (!empty($gku['21']) or empty($startugol)) {
                                                        if ($code == $gzkod['31']) {$codeugol = 31;}
                                                        elseif ($code == $gzhelpkod['31']) {$codehelp = 31;}}
                                                }
                                                }

                                                if (!empty($gku['31']) or $startugol==41 or empty($startugol))
                                                {	if (!empty($gku['41']))
                                                {	if (!empty($gku['42']))
                                                {	if (!empty($gku['43']))
                                                {	if (empty($gku['44']))
                                                {
                                                    if ($code == $gzkod['44']) {$codeugol = 44;}
                                                    elseif ($code == $gzhelpkod['44']) {$codehelp = 44;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['43']) {$codeugol = 43;}
                                                    elseif ($code == $gzhelpkod['43']) {$codehelp = 43;}}
                                                }
                                                else {
                                                    if ($code == $gzkod['42']) {$codeugol = 42;}
                                                    elseif ($code == $gzhelpkod['42']) {$codehelp = 42;}}
                                                }
                                                else
                                                {
                                                    if (!empty($gku['31']) or empty($startugol)) {
                                                        if ($code == $gzkod['41']) {$codeugol = 41;}
                                                        elseif ($code == $gzhelpkod['41']) {$codehelp = 41;}}
                                                }
                                                }

                                                if (!empty($codeugol))
                                                {
                                                    $temp = time()+$cfg_timeadjustment;
                                                    $query = "INSERT INTO `kvadratgame` VALUES (NULL, '".$gameid."', '".$userinfo['userteam']."', '".$codeugol."', '".$temp."', '".$userinfo['userid']."')";
                                                    $db->query($query);


                                                    Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                                                    exit;
                                                }
                                                elseif (!empty($codehelp))
                                                {
                                                    $temp = time()+$cfg_timeadjustment;
                                                    $query = "INSERT INTO `kvadratgamehelp` VALUES (NULL, '".$gameid."', '".$userinfo['userteam']."', '".$codeugol."', '".$temp."', '".$userinfo['userid']."')";
                                                    $db->query($query);


                                                    Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                                                    exit;
                                                }
                                            }

                                            // вывод текущего задания
                                            $page['text'] .= 'Играем <br />';

                                            if (!empty($gku['41']) or $startugol==11)
                                            {	if (!empty($gku['11']))
                                            {	if (!empty($gku['12']))
                                            {	if (!empty($gku['13']))
                                            {	if (!empty($gku['14']))
                                            {
                                                $page['text'] = $page['text'];
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [А4]:</b><br />'.$gztext['14'].'<br />';
                                                if (!empty($ghku['14'])) {$page['text'] .= '<b>Подсказка [А4]:</b><br />'.$gzhelp['14'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [А3]:</b><br />'.$gztext['13'].'<br />';
                                                if (!empty($ghku['13'])) {$page['text'] .= '<b>Подсказка [А3]:</b><br />'.$gzhelp['13'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [А2]:</b><br />'.$gztext['12'].'<br />';
                                                if (!empty($ghku['12'])) {$page['text'] .= '<b>Подсказка [А2]:</b><br />'.$gzhelp['12'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else
                                            {
                                                if (!empty($gku['41'])) {
                                                    $page['text'] .= '<b>Задание [А1]:</b><br />'.$gztext['11'].'<br />';
                                                    if (!empty($ghku['11'])) {$page['text'] .= '<b>Подсказка [А1]:</b><br />'.$gzhelp['11'].'<br />';}
                                                    $page['text'] .= '<hr /><br />';}
                                            }
                                            }

                                            if (!empty($gku['11']) or $startugol==21)
                                            {	if (!empty($gku['21']))
                                            {	if (!empty($gku['22']))
                                            {	if (!empty($gku['23']))
                                            {	if (!empty($gku['24']))
                                            {
                                                $page['text'] = $page['text'];
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [Б4]:</b><br />'.$gztext['24'].'<br />';
                                                if (!empty($ghku['24'])) {$page['text'] .= '<b>Подсказка [Б4]:</b><br />'.$gzhelp['24'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [Б3]:</b><br />'.$gztext['23'].'<br />';
                                                if (!empty($ghku['23'])) {$page['text'] .= '<b>Подсказка [Б3]:</b><br />'.$gzhelp['23'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [Б2]:</b><br />'.$gztext['22'].'<br />';
                                                if (!empty($ghku['22'])) {$page['text'] .= '<b>Подсказка [Б2]:</b><br />'.$gzhelp['22'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else
                                            {
                                                if (!empty($gku['11'])) {
                                                    $page['text'] .= '<b>Задание [Б1]:</b><br />'.$gztext['21'].'<br />';
                                                    if (!empty($ghku['21'])) {$page['text'] .= '<b>Подсказка [Б1]:</b><br />'.$gzhelp['21'].'<br />';}
                                                    $page['text'] .= '<hr /><br />';}
                                            }
                                            }

                                            if (!empty($gku['21']) or $startugol==31)
                                            {	if (!empty($gku['31']))
                                            {	if (!empty($gku['32']))
                                            {	if (!empty($gku['33']))
                                            {	if (!empty($gku['34']))
                                            {
                                                $page['text'] = $page['text'];
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [В4]:</b><br />'.$gztext['34'].'<br />';
                                                if (!empty($ghku['34'])) {$page['text'] .= '<b>Подсказка [В4]:</b><br />'.$gzhelp['34'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [В3]:</b><br />'.$gztext['33'].'<br />';
                                                if (!empty($ghku['33'])) {$page['text'] .= '<b>Подсказка [В3]:</b><br />'.$gzhelp['33'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [В2]:</b><br />'.$gztext['32'].'<br />';
                                                if (!empty($ghku['32'])) {$page['text'] .= '<b>Подсказка [В2]:</b><br />'.$gzhelp['32'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else
                                            {
                                                if (!empty($gku['21'])) {
                                                    $page['text'] .= '<b>Задание [В1]:</b><br />'.$gztext['31'].'<br />';
                                                    if (!empty($ghku['31'])) {$page['text'] .= '<b>Подсказка [В1]:</b><br />'.$gzhelp['31'].'<br />';}
                                                    $page['text'] .= '<hr /><br />';}
                                            }
                                            }

                                            if (!empty($gku['31']) or $startugol==41)
                                            {	if (!empty($gku['41']))
                                            {	if (!empty($gku['42']))
                                            {	if (!empty($gku['43']))
                                            {	if (!empty($gku['44']))
                                            {
                                                $page['text'] = $page['text'];
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [Г4]:</b><br />'.$gztext['44'].'<br />';
                                                if (!empty($ghku['44'])) {$page['text'] .= '<b>Подсказка [Г4]:</b><br />'.$gzhelp['44'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [Г3]:</b><br />'.$gztext['43'].'<br />';
                                                if (!empty($ghku['43'])) {$page['text'] .= '<b>Подсказка [Г3]:</b><br />'.$gzhelp['43'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else {
                                                $page['text'] .= '<b>Задание [Г2]:</b><br />'.$gztext['42'].'<br />';
                                                if (!empty($ghku['42'])) {$page['text'] .= '<b>Подсказка [Г2]:</b><br />'.$gzhelp['42'].'<br />';}
                                                $page['text'] .= '<hr /><br />';}
                                            }
                                            else
                                            {
                                                if (!empty($gku['31'])) {
                                                    $page['text'] .= '<b>Задание [Г1]:</b><br />'.$gztext['41'].'<br />';
                                                    if (!empty($ghku['41'])) {$page['text'] .= '<b>Подсказка [Г1]:</b><br />'.$gzhelp['41'].'<br />';}
                                                    $page['text'] .= '<hr /><br />';}
                                            }
                                            }
                                            $page['text'] .= '<center><form name="gameform" method="post" action="index.php?pid='.$pageid.'&gid='.$gameid.'">
																											<input name="code" type="text" tabindex="1" /><br><a href="index.php?pid='.$pageid.'&gid='.$gameid.'">Обновить</a<br><input name="submit" type="submit" value="Ввод" />
																											</form></center>';

                                        }
                                        else
                                        {
                                            $page['text'] = "Вы завершили игру.";
                                        }
                                    }
                                }
                                // +++++++++++++++++++++ ИГРА ++++++++++++++++++
                                elseif ((time()+$cfg_timeadjustment) < $rows_game['gametimestart'])
                                    // заявка принята - отказаться от игры
                                {
                                    if ($action == 'denyrequest' && $userinfo['userid'] == $rows_team['teamcaptain'])
                                    {
                                        $query_cancel = "DELETE FROM `gamestaken` WHERE takengame = '".$gameid."' && takenteam = '".$userinfo['userteam']."' && takenaccept = '1'";
                                        $db->query($query_cancel);

                                        Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                                        exit;
                                    }
                                    else
                                    {
                                        $page['text'] = '<table class="msgerrorpage"><tr><td>Ваша принята. Ждите начала игры.</td></tr></table>';
                                        if ($userinfo['userid'] == $rows_team['teamcaptain'])
                                        {
                                            $page['text'] .= '<br /><a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=cancelrequest">Отменить заявку</a>';
                                        }
                                    }
                                }
                            }
                            elseif ($rows['takenaccept'] == 0)
                                // заявка не принята
                            {
                                if ($action == 'cancelrequest' && $userinfo['userid'] == $rows_team['teamcaptain'])
                                {
                                    $query_cancel = "DELETE FROM `gamestaken` WHERE takengame = '".$gameid."' && takenteam = '".$userinfo['userteam']."' && takenaccept = '0'";
                                    $db->query($query_cancel);

                                    Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                                    exit;
                                }
                                else
                                {
                                    $page['text'] = '<table class="msgerrorpage"><tr><td>Ваша заявка еще не принята.</td></tr></table>';
                                    if ($userinfo['userid'] == $rows_team['teamcaptain'])
                                    {
                                        $page['text'] .= '<br /><a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=cancelrequest">Отменить заявку</a>';
                                    }
                                }

                            }

                        }
                        else
                            // заявка не подана
                        {
                            if ($action == 'lodgerequest' && $userinfo['userid'] == $rows_team['teamcaptain'])
                            {
                                $query_lodge = "INSERT INTO `gamestaken` VALUES (NULL, ".$gameid.", ".$userinfo['userteam'].", 0)";
                                $db->query($query_lodge);

                                Header("Location: index.php?pid=".$pageid."&gid=".$gameid);
                                exit;
                            }
                            else
                            {
                                $page['text'] = '<table class="msgerrorpage"><tr><td>Ваша команда ('.$rows_team['teamname'].') не подала заявку на участие</td></tr></table>';
                                if ($userinfo['userid'] == $rows_team['teamcaptain'])
                                {
                                    $page['text'] .= '<br /><a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=lodgerequest">Подать заявку</a>';
                                }
                            }
                        }
                    }
                    else
                    {
                        $page['text'] = '<table class="msgerrorpage"><tr><td>Вам необходимо создать команду либо присоедениться к существующей</td></tr></table>';
                    }
                }
            }
            else
            {
                //получение информации
                $action = $_GET['action'];
                $action = preg_match('/^[a-z]{3,13}$/', $action) ? $action : '';

                if ($action == 'stat')
                {

                }
                elseif ($action == 'resh') // СЛОВО ДЛЯ РАСШИФРОВКИ
                {

                }
                else
                {
                    $page['text'] = '<table class="msgerrorpage"><tr><td>Игра уже завершилась.<br /> <a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=stat">СТАТИСТИКА</a>. <a href="index.php?pid='.$pageid.'&gid='.$gameid.'&action=stat">РАСШИФРОВКА</a></td></tr></table>';

                    $page['text'] .= "<tr><td>Название игры: ";
                    $page['text'] .= $rows_game['gamename'];
                    $page['text'] .= "<br />Формат игры";
                    $page['text'] .= $cfg_gametype['name'][$rows_game['gametype']];
                    $page['text'] .= "]<br> Легенда: ";
                    $page['text'] .= nl2br(stripslashes($rows_game['gamelegend'][$i]));
                    $page['text'] .= "<br> Старт: ";
                    $page['text'] .= date ("d/m/Y - H:i:s" , $rows_game['gametimestart']);
                    $page['text'] .= "<br> Финиш: ";
                    $page['text'] .= date ("d/m/Y - H:i:s" , $rows_game['gametimefinish']);
                    $page['text'] .= "<br> Автор: ";
                    $page['text'] .= $rows_game['authorname'][$i];
                    $page['text'] .= "<br>";
                    $page['text'] .= "</td></tr> ";
                }
            }

        }
        else
        {
            $page['text'] = '<table class="msgerrorpage"><tr><td>Нет такой игры</td></tr></table>';
        }
    }
    else
    {
        $page['text'] = 'нет такой игры';
    }
}