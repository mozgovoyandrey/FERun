<?php
// индекс 6 (7)
// Квадрат игра - Задание
// версия 0.2
if (!empty($userid) && $access['admin']==1 && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    if (!empty($gameid))
    {
        // получение данных
        $zadanieid = $_GET['zid'];
        //$action = ereg('^[0-9]{1,11}$', $action) ? $action : '';
        $action = $_GET['action'];
        $action = preg_match('/^[a-z]{3,4}$/', $action) ? $action : '';

        if ($action == 'add' && empty($zadanieid))
        {
            //Новое здание - получение
            $adm_gamekvadratzadanie_add_zadanietext = $_POST['adm_gamekvadratzadanie_add_zadanietext'];
            $adm_gamekvadratzadanie_add_zadanietext = addslashes(htmlspecialchars(trim($adm_gamekvadratzadanie_add_zadanietext)));
            $adm_gamekvadratzadanie_add_zadaniekod = $_POST['adm_gamekvadratzadanie_add_zadaniekod'];
            $adm_gamekvadratzadanie_add_zadaniekod = preg_match('/^[a-zA-Zа-яА-Я0-9]{2,25}$/',trim($adm_gamekvadratzadanie_add_zadaniekod)) ? trim($adm_gamekvadratzadanie_add_zadaniekod) : '';
            $adm_gamekvadratzadanie_add_zadanieugol = $_POST['adm_gamekvadratzadanie_add_zadanieugol'];
            $adm_gamekvadratzadanie_add_zadanieugol = preg_match('/^[0-9]{2}$/',$adm_gamekvadratzadanie_add_zadanieugol) ? $adm_gamekvadratzadanie_add_zadanieugol : '';
            $adm_gamekvadratzadanie_add_zadanieanswer = $_POST['adm_gamekvadratzadanie_add_zadanieanswer'];
            $adm_gamekvadratzadanie_add_zadanieanswer = addslashes(htmlspecialchars(trim($adm_gamekvadratzadanie_add_zadanieanswer)));
            $adm_gamekvadratzadanie_add_zadaniehelp = $_POST['adm_gamekvadratzadanie_add_zadaniehelp'];
            $adm_gamekvadratzadanie_add_zadaniehelp = addslashes(htmlspecialchars(trim($adm_gamekvadratzadanie_add_zadaniehelp)));
            $adm_gamekvadratzadanie_add_zadaniehelpkod = $_POST['adm_gamekvadratzadanie_add_zadaniehelpkod'];
            $adm_gamekvadratzadanie_add_zadaniehelpkod = preg_match('/^[a-zA-Zа-яА-Я0-9]{2,25}$/',trim($adm_gamekvadratzadanie_add_zadaniehelpkod)) ? trim($adm_gamekvadratzadanie_add_zadaniehelpkod) : '';

            //Новое здание - добавление
            if (!empty($adm_gamekvadratzadanie_add_zadanietext) && !empty($adm_gamekvadratzadanie_add_zadaniekod) && !empty($adm_gamekvadratzadanie_add_zadanieugol) && !empty($adm_gamekvadratzadanie_add_zadanieanswer) && !empty($adm_gamekvadratzadanie_add_zadaniehelp) && !empty($adm_gamekvadratzadanie_add_zadaniehelpkod))
            {
                $query = "INSERT INTO `kvadratzadanie` VALUES (NULL, '".$adm_gamekvadratzadanie_add_zadanietext."', '".$gameid."', '".$adm_gamekvadratzadanie_add_zadanieugol."', '".$adm_gamekvadratzadanie_add_zadaniekod."', '".$adm_gamekvadratzadanie_add_zadaniehelp."', '".$adm_gamekvadratzadanie_add_zadaniehelpkod."', '".$adm_gamekvadratzadanie_add_zadanieanswer."')";
                $db->query($query);

                Header("Location: admin.php?pid=".$cfg_gametype['edit'][2]."&gid=".$gameid);
                exit;
            }

            //Новое здание - форма
            $page['text'] .= '<form name="addgamekvadratzadanienewform" method="post" action="admin.php?pid='.$pageid.'&gid='.$gameid.'&action=add">';
            $page['text'] .= '<table style="width: 100%"><tr><td>Текст задания:</td>
												<td><textarea name="adm_gamekvadratzadanie_add_zadanietext" style="width: 400px; height: 100px"></textarea></td>
											</tr><tr><td>Код:</td>
												<td><input name="adm_gamekvadratzadanie_add_zadaniekod" type="text" style="width: 150px" /></td>
											</tr><tr><td>Угол:</td>
												<td><select name="adm_gamekvadratzadanie_add_zadanieugol">
														<option></option>';
            $query = "SELECT zadanieugol FROM `kvadratzadanie` WHERE zadaniegame='".$gameid."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            $temp = array( 	11 => 0, 12 => 0, 13 => 0, 14 => 0,
                21 => 0, 22 => 0, 23 => 0, 24 => 0,
                31 => 0, 32 => 0, 33 => 0, 34 => 0,
                41 => 0, 42 => 0, 43 => 0, 44 => 0,
                51 => 0, 52 => 0, 53 => 0, 54 => 0);
            if ($num_result > 0)
            {
                for ($i=1; $i<=$num_result; $i++)
                {
                    $rows = $result->fetch_assoc();
                    $temp[$rows['zadanieugol']] = 1;
                }
            }

            if (empty($temp[11])) $page['text'] .= '<option value="11">11</option>';
            if (empty($temp[12])) $page['text'] .= '<option value="12">12</option>';
            if (empty($temp[13])) $page['text'] .= '<option value="13">13</option>';
            if (empty($temp[14])) $page['text'] .= '<option value="14">14</option>';
            if (empty($temp[21])) $page['text'] .= '<option value="21">21</option>';
            if (empty($temp[22])) $page['text'] .= '<option value="22">22</option>';
            if (empty($temp[23])) $page['text'] .= '<option value="23">23</option>';
            if (empty($temp[24])) $page['text'] .= '<option value="24">24</option>';
            if (empty($temp[31])) $page['text'] .= '<option value="31">31</option>';
            if (empty($temp[32])) $page['text'] .= '<option value="32">32</option>';
            if (empty($temp[33])) $page['text'] .= '<option value="33">33</option>';
            if (empty($temp[34])) $page['text'] .= '<option value="34">34</option>';
            if (empty($temp[41])) $page['text'] .= '<option value="41">41</option>';
            if (empty($temp[42])) $page['text'] .= '<option value="42">42</option>';
            if (empty($temp[43])) $page['text'] .= '<option value="43">43</option>';
            if (empty($temp[44])) $page['text'] .= '<option value="44">44</option>';
            if (empty($temp[51])) $page['text'] .= '<option value="51">51</option>';
            if (empty($temp[52])) $page['text'] .= '<option value="52">52</option>';
            if (empty($temp[53])) $page['text'] .= '<option value="53">53</option>';
            if (empty($temp[54])) $page['text'] .= '<option value="54">54</option>';
            $page['text'] .= '</select></td></tr><tr><td>Текст подсказки:</td>
												<td><textarea name="adm_gamekvadratzadanie_add_zadaniehelp" style="width: 400px; height: 100px"></textarea></td>
											</tr><tr><td>Код подсказки:</td>
												<td><input name="adm_gamekvadratzadanie_add_zadaniehelpkod" type="text" style="width: 150px" /></td>
											</tr><tr><td>Решение задания:</td>
												<td><textarea name="adm_gamekvadratzadanie_add_zadanieanswer" style="width: 400px; height: 100px"></textarea></td>
											</tr><tr><td>&nbsp;</td>
												<td><input name="Submit1" type="submit" value="Добавить" /></td>
											</tr></table></form>';
        }
        elseif ($action == 'del' && !empty($zadanieid))
        {
            // Удаление задания
            $query = "select * from `kvadratzadanie` where zadanieid = '".$zadanieid."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $delete = "delete from `kvadratzadanie` where zadanieid = '".$zadanieid."'";
                $db->query($delete);

                Header("Location: admin.php?pid=".$cfg_gametype['edit'][2]."&gid=".$gameid);
                exit;
            }
        }
        elseif ($action == 'edit' && !empty($zadanieid))
        {
            // Редактировать задание - получение данных
            $adm_gamekvadratzadanie_edit_zadanietext = $_POST['adm_gamekvadratzadanie_edit_zadanietext'];
            $adm_gamekvadratzadanie_edit_zadaniekod = $_POST['adm_gamekvadratzadanie_edit_zadaniekod'];
            $adm_gamekvadratzadanie_edit_zadanieugol = $_POST['adm_gamekvadratzadanie_edit_zadanieugol'];
            $adm_gamekvadratzadanie_edit_zadanieanswer = $_POST['adm_gamekvadratzadanie_edit_zadanieanswer'];
            $adm_gamekvadratzadanie_edit_zadaniehelp = $_POST['adm_gamekvadratzadanie_edit_zadaniehelp'];
            $adm_gamekvadratzadanie_edit_zadaniehelpkod = $_POST['adm_gamekvadratzadanie_edit_zadaniehelpkod'];

            // Редактировать задание - обновление
            if (!empty($adm_gamekvadratzadanie_edit_zadanietext) && !empty($adm_gamekvadratzadanie_edit_zadaniekod))
            {
                $query = "select * from `kvadratzadanie` where zadanieid = '".$zadanieid."'";
                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result == 1)
                {
                    $query = "UPDATE `kvadratzadanie` SET
										`zadanietext` = '".$adm_gamekvadratzadanie_edit_zadanietext."',
										`zadaniekod` = '".$adm_gamekvadratzadanie_edit_zadaniekod."',
										`zadanieugol` = '".$adm_gamekvadratzadanie_edit_zadanieugol."',
										`zadanieanswer` = '".$adm_gamekvadratzadanie_edit_zadanieanswer."',
										`zadaniehelp` = '".$adm_gamekvadratzadanie_edit_zadaniehelp."',
										`zadaniehelpkod` = '".$adm_gamekvadratzadanie_edit_zadaniehelpkod."'
										WHERE  `zadanieid` = '".$zadanieid."'";
                    $db->query($query);

                    Header("Location: admin.php?pid=".$cfg_gametype['edit'][2]."&gid=".$gameid);
                    exit;
                }
            }
            // Редактировать задание - форма
            $query = "select * from `kvadratzadanie` where zadanieid = '".$zadanieid."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $rows_zad = $result->fetch_assoc();
            }

            $page['text'] .= '<form name="editgamekvadratzadanieform" method="post" action="admin.php?pid='.$pageid.'&gid='.$gameid.'&zid='.$zadanieid.'&action=edit">';
            $page['text'] .= '<table style="width: 100%"><tr><td>Текст задания:</td>
												<td><textarea name="adm_gamekvadratzadanie_edit_zadanietext" style="width: 400px; height: 100px">'.$rows_zad['zadanietext'].'</textarea></td>
											</tr><tr><td>Код:</td>
												<td><input name="adm_gamekvadratzadanie_edit_zadaniekod" type="text" style="width: 150px" value="'.$rows_zad['zadaniekod'].'" /></td>
											</tr><tr><td>Угол:</td>
												<td><select name="adm_gamekvadratzadanie_edit_zadanieugol">
														<option></option>';
            $query = "SELECT zadanieugol FROM `kvadratzadanie` WHERE zadaniegame='".$gameid."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            $temp = '';
            if ($num_result > 0)
            {
                for ($i=1; $i<=$num_result; $i++)
                {
                    $rows = $result->fetch_assoc();
                    $temp[$rows['zadanieugol']] = 1;
                }
            }

            if (empty($temp['11'])) {$page['text'] .= '<option value="11">11</option>';} elseif ($rows_zad['zadanieugol'] == 11 ) {$page['text'] .= '<option value="11" selected>11</option>';}
            if (empty($temp['12'])) {$page['text'] .= '<option value="12">12</option>';} elseif ($rows_zad['zadanieugol'] == 12 ) {$page['text'] .= '<option value="12" selected>12</option>';}
            if (empty($temp['13'])) {$page['text'] .= '<option value="13">13</option>';} elseif ($rows_zad['zadanieugol'] == 13 ) {$page['text'] .= '<option value="13" selected>13</option>';}
            if (empty($temp['14'])) {$page['text'] .= '<option value="14">14</option>';} elseif ($rows_zad['zadanieugol'] == 14 ) {$page['text'] .= '<option value="14" selected>14</option>';}
            if (empty($temp['21'])) {$page['text'] .= '<option value="21">21</option>';} elseif ($rows_zad['zadanieugol'] == 21 ) {$page['text'] .= '<option value="21" selected>21</option>';}
            if (empty($temp['22'])) {$page['text'] .= '<option value="22">22</option>';} elseif ($rows_zad['zadanieugol'] == 22 ) {$page['text'] .= '<option value="22" selected>22</option>';}
            if (empty($temp['23'])) {$page['text'] .= '<option value="23">23</option>';} elseif ($rows_zad['zadanieugol'] == 23 ) {$page['text'] .= '<option value="23" selected>23</option>';}
            if (empty($temp['24'])) {$page['text'] .= '<option value="24">24</option>';} elseif ($rows_zad['zadanieugol'] == 24 ) {$page['text'] .= '<option value="24" selected>24</option>';}
            if (empty($temp['31'])) {$page['text'] .= '<option value="31">31</option>';} elseif ($rows_zad['zadanieugol'] == 31 ) {$page['text'] .= '<option value="31" selected>31</option>';}
            if (empty($temp['32'])) {$page['text'] .= '<option value="32">32</option>';} elseif ($rows_zad['zadanieugol'] == 32 ) {$page['text'] .= '<option value="32" selected>32</option>';}
            if (empty($temp['33'])) {$page['text'] .= '<option value="33">33</option>';} elseif ($rows_zad['zadanieugol'] == 33 ) {$page['text'] .= '<option value="33" selected>33</option>';}
            if (empty($temp['34'])) {$page['text'] .= '<option value="34">34</option>';} elseif ($rows_zad['zadanieugol'] == 34 ) {$page['text'] .= '<option value="34" selected>34</option>';}
            if (empty($temp['41'])) {$page['text'] .= '<option value="41">41</option>';} elseif ($rows_zad['zadanieugol'] == 41 ) {$page['text'] .= '<option value="41" selected>41</option>';}
            if (empty($temp['42'])) {$page['text'] .= '<option value="42">42</option>';} elseif ($rows_zad['zadanieugol'] == 42 ) {$page['text'] .= '<option value="42" selected>42</option>';}
            if (empty($temp['43'])) {$page['text'] .= '<option value="43">43</option>';} elseif ($rows_zad['zadanieugol'] == 43 ) {$page['text'] .= '<option value="43" selected>43</option>';}
            if (empty($temp['44'])) {$page['text'] .= '<option value="44">44</option>';} elseif ($rows_zad['zadanieugol'] == 44 ) {$page['text'] .= '<option value="44" selected>44</option>';}
            if (empty($temp['51'])) {$page['text'] .= '<option value="51">51</option>';} elseif ($rows_zad['zadanieugol'] == 51 ) {$page['text'] .= '<option value="51" selected>51</option>';}
            if (empty($temp['52'])) {$page['text'] .= '<option value="52">52</option>';} elseif ($rows_zad['zadanieugol'] == 52 ) {$page['text'] .= '<option value="52" selected>52</option>';}
            if (empty($temp['53'])) {$page['text'] .= '<option value="53">53</option>';} elseif ($rows_zad['zadanieugol'] == 53 ) {$page['text'] .= '<option value="53" selected>53</option>';}
            if (empty($temp['54'])) {$page['text'] .= '<option value="54">54</option>';} elseif ($rows_zad['zadanieugol'] == 54 ) {$page['text'] .= '<option value="54" selected>54</option>';}
            $page['text'] .= '</select></td></tr><tr><td>Текст подсказки:</td>
												<td><textarea name="adm_gamekvadratzadanie_edit_zadaniehelp" style="width: 400px; height: 100px">'.$rows_zad['zadaniehelp'].'</textarea></td>
											</tr><tr><td>Код подсказки:</td>
												<td><input name="adm_gamekvadratzadanie_edit_zadaniehelpkod" type="text" style="width: 150px" value="'.$rows_zad['zadaniehelpkod'].'" /></td>
											</tr><tr><td>Решение задания:</td>
												<td><textarea name="adm_gamekvadratzadanie_edit_zadanieanswer" style="width: 400px; height: 100px">'.$rows_zad['zadanieanswer'].'</textarea></td>
											</tr><tr><td>&nbsp;</td>
												<td><input name="submit" type="submit" value="Обновить" /></td>
											</tr></table></form>';
        }
        else
        {

        }
    }
    else
    {
        Header("Location: http://".$_SERVER['HTTP_HOST']."/admin.php?pid=5");
        exit;
    }
}