<?php
// индекс 3
// Новая игра
// версия 0.1
if (!empty($userid) && $access['admin'] == 1 && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $adm_gamenew_add_gamename = $_POST['adm_gamenew_add_gamename'];
    $adm_gamenew_add_gamename = ereg('^[0-9a-zA-Zа-яА-ЯЁё _\-\.]{5,50}$', trim($adm_gamenew_add_gamename)) ? addslashes(trim($adm_gamenew_add_gamename)) : '';
    $page['text'] .= $adm_gamenew_add_gamename.'<br />';
    $adm_gamenew_add_gametype = $_POST['adm_gamenew_add_gametype'];
    $adm_gamenew_add_gametype = ereg('^[0-9]{1,3}$', $adm_gamenew_add_gametype) ? $adm_gamenew_add_gametype : '';
    $page['text'] .= $adm_gamenew_add_gametype.'<br />';
    $adm_gamenew_add_gamelegend = $_POST['adm_gamenew_add_gamelegend'];
    $adm_gamenew_add_gamelegend = addslashes(htmlspecialchars(trim($adm_gamenew_add_gamelegend)));
    $adm_gamenew_add_gametimestart['1'] = $_POST['adm_gamenew_add_gametimestart1'];
    $adm_gamenew_add_gametimestart['1'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimestart['1']) ? $adm_gamenew_add_gametimestart['1'] : '';
    $adm_gamenew_add_gametimestart['2'] = $_POST['adm_gamenew_add_gametimestart2'];
    $adm_gamenew_add_gametimestart['2'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimestart['2']) ? $adm_gamenew_add_gametimestart['2'] : '';
    $adm_gamenew_add_gametimestart['3'] = $_POST['adm_gamenew_add_gametimestart3'];
    $adm_gamenew_add_gametimestart['3'] = ereg('^[0-9]{4}$', $adm_gamenew_add_gametimestart['3']) ? $adm_gamenew_add_gametimestart['3'] : '';
    $adm_gamenew_add_gametimestart['4'] = $_POST['adm_gamenew_add_gametimestart4'];
    $adm_gamenew_add_gametimestart['4'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimestart['4']) ? $adm_gamenew_add_gametimestart['4'] : '';
    $adm_gamenew_add_gametimestart['5'] = $_POST['adm_gamenew_add_gametimestart5'];
    $adm_gamenew_add_gametimestart['5'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimestart['5']) ? $adm_gamenew_add_gametimestart['5'] : '';
    $adm_gamenew_add_gametimefinish['1'] = $_POST['adm_gamenew_add_gametimefinish1'];
    $adm_gamenew_add_gametimefinish['1'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimefinish['1']) ? $adm_gamenew_add_gametimefinish['1'] : '';
    $adm_gamenew_add_gametimefinish['2'] = $_POST['adm_gamenew_add_gametimefinish2'];
    $adm_gamenew_add_gametimefinish['2'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimefinish['2']) ? $adm_gamenew_add_gametimefinish['2'] : '';
    $adm_gamenew_add_gametimefinish['3'] = $_POST['adm_gamenew_add_gametimefinish3'];
    $adm_gamenew_add_gametimefinish['3'] = ereg('^[0-9]{4}$', $adm_gamenew_add_gametimefinish['3']) ? $adm_gamenew_add_gametimefinish['3'] : '';
    $adm_gamenew_add_gametimefinish['4'] = $_POST['adm_gamenew_add_gametimefinish4'];
    $adm_gamenew_add_gametimefinish['4'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimefinish['4']) ? $adm_gamenew_add_gametimefinish['4'] : '';
    $adm_gamenew_add_gametimefinish['5'] = $_POST['adm_gamenew_add_gametimefinish5'];
    $adm_gamenew_add_gametimefinish['5'] = ereg('^[0-9]{2}$', $adm_gamenew_add_gametimefinish['5']) ? $adm_gamenew_add_gametimefinish['5'] : '';

    // получение unix времени старта
    if (!empty($adm_gamenew_add_gametimestart['1']) && !empty($adm_gamenew_add_gametimestart['2']) && !empty($adm_gamenew_add_gametimestart['3']) && !empty($adm_gamenew_add_gametimestart['4']) && !empty($adm_gamenew_add_gametimestart['5']))
    {
        $adm_gamenew_add_gametimestart['unix'] = mktime ($adm_gamenew_add_gametimestart['4'], $adm_gamenew_add_gametimestart['5'], '00', $adm_gamenew_add_gametimestart['2'], $adm_gamenew_add_gametimestart['1'], $adm_gamenew_add_gametimestart['3']);
        $page['text'] .= 'Start<br />';
    }

    // получение unix времени финиша
    if (!empty($adm_gamenew_add_gametimefinish['1']) && !empty($adm_gamenew_add_gametimefinish['2']) && !empty($adm_gamenew_add_gametimefinish['3']) && !empty($adm_gamenew_add_gametimefinish['4']) && !empty($adm_gamenew_add_gametimefinish['5']))
    {
        $adm_gamenew_add_gametimefinish['unix'] = mktime ($adm_gamenew_add_gametimefinish['4'], $adm_gamenew_add_gametimefinish['5'], '00', $adm_gamenew_add_gametimefinish['2'], $adm_gamenew_add_gametimefinish['1'], $adm_gamenew_add_gametimefinish['3']);
        $page['text'] .= 'Finish<br />';
    }

    if (!empty($adm_gamenew_add_gamename) && !empty($adm_gamenew_add_gametype) && !empty($adm_gamenew_add_gamelegend) && !empty($adm_gamenew_add_gametimestart['unix']) && !empty($adm_gamenew_add_gametimefinish['unix']))
    {
        $query = "INSERT INTO `games` VALUES (NULL, '".$adm_gamenew_add_gamename."', '".$adm_gamenew_add_gametype."', '".$adm_gamenew_add_gamelegend."', '".$adm_gamenew_add_gametimestart['unix']."', '".$adm_gamenew_add_gametimefinish['unix']."', '".$userid."', '0')";
        $db->query($query);

        $query = "select * from `games` where gamename = '".$adm_gamenew_add_gamename."' and gametype = '".$adm_gamenew_add_gametype."' and gameauthor = '".$userid."'";
        $result = $db->query ($query);
        $num_result = $result->num_rows;
        if ($num_result == "1")
        {
            $rows = $result->fetch_assoc();
            Header("Location: admin.php?pid=".$cfg_gametype['edit'][$rows['gametype']]."&gid=".$rows['gameid']);
            exit;
        }
        else
        {
            Header("Location: admin.php");
            exit;
        }
    }


    $page['text'] .= '<table><tr><td><form name="addgamenewform" method="post" action="admin.php?pid=';
    $page['text'] .= $pageid;
    $page['text'] .= '"><table border="0" cellspacing="1">
		<tr>
			<td>Название: </td>
			<td><input name="adm_gamenew_add_gamename" type="text" /></td>
		</tr>
		<tr>
			<td>Формат игры:</td>
			<td><select name="adm_gamenew_add_gametype" style="width: 138px">
			<option value="">---------</option><!--
			<option value="1">ADRеналин</option>-->
			<option value="2">KBADRAT</option><!--
			<option value="3">WW</option>
			<option value="4">DC</option>-->
			</select></td>
		</tr>
		<tr>
			<td>Легенда:</td>
			<td><textarea name="adm_gamenew_add_gamelegend" style="width: 308px; height: 86px" rows="1" cols="20"></textarea></td>
		</tr>
		<tr>
			<td>Начало игры:</td>
			<td>';

    $time = split ('/', date ("d/m/Y/H/i/s" , time()/*+$cfg_timeadjustment*/));

    $page['text'] .= 'День <select name="adm_gamenew_add_gametimestart1">';
    for ($i=1; $i<=31; $i++)
    {
        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="';
        $page['text'] .= $temp;
        $page['text'] .= '"';
        $page['text'] .= $time[0]==$temp ?  ' selected ' : '';
        $page['text'] .= '>';
        $page['text'] .= $temp;
        $page['text'] .= '</option>';
    }

    $page['text'] .= '</select> Месяц <select name="adm_gamenew_add_gametimestart2">';
    for ($i=1; $i<=12; $i++)
    {
        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="'.$temp.'"';
        $page['text'] .= $time[1]==$temp ?  ' selected ' : '';
        $page['text'] .= '>'.$cfg_default['month'][$temp].'</option>';
    }

    $page['text'] .= '</select> Год <select name="adm_gamenew_add_gametimestart3">';
    for ($i=2009; $i<=2011; $i++)
    {
        $page['text'] .= '<option value="'.$i.'"';
        $page['text'] .= $time[2]==$i ? ' selected ' : '';
        $page['text'] .= '>'.$i.'</option>';
    }

    $page['text'] .= '</select> Час <select name="adm_gamenew_add_gametimestart4">';
    for ($i=0; $i<=23; $i++)
    {
        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="'.$temp.'"';
        $page['text'] .= $time[3]==$temp ?  ' selected ' : '';
        $page['text'] .= '>'.$temp.'</option>';
    }

    $page['text'] .= '</select> Минуты <select name="adm_gamenew_add_gametimestart5">';
    for ($i=0; $i<=59; $i++)
    {
        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="'.$temp.'"';
        $page['text'] .= $time[4]==$temp ?  ' selected ' : '';
        $page['text'] .= '>'.$temp.'</option>';
    }

    $page['text'] .= '</select></td></tr><tr>
			<td>Завершение игры:</td>
			<td> День <select name="adm_gamenew_add_gametimefinish1">';
    for ($i=1; $i<=31; $i++)
    {

        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="';
        $page['text'] .= $temp;
        $page['text'] .= '"';
        $page['text'] .= $time[0]==$temp ?  ' selected ' : '';
        $page['text'] .= '>';
        $page['text'] .= $temp;
        $page['text'] .= '</option>';
    }

    $page['text'] .= '</select>
			Месяц
			<select name="adm_gamenew_add_gametimefinish2">';
    for ($i=1; $i<=12; $i++)
    {

        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="';
        $page['text'] .= $temp;
        $page['text'] .= '"';
        $page['text'] .= $time[1]==$temp ?  ' selected ' : '';
        $page['text'] .= '>';
        $page['text'] .= $cfg_default['month'][$temp];
        $page['text'] .= '</option>';
    }

    $page['text'] .= '</select>
			Год
			<select name="adm_gamenew_add_gametimefinish3">';
    for ($i=2009; $i<=2011; $i++)
    {
        $page['text'] .= '<option value="'.$i.'"';
        $page['text'] .= $time[2]==$i ? ' selected ' : '';
        $page['text'] .= '>'.$i.'</option>';
    }

    $page['text'] .= '</select>
			Час
			<select name="adm_gamenew_add_gametimefinish4">';
    for ($i=0; $i<=23; $i++)
    {
        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="'.$temp.'"';
        $page['text'] .= $time[3]==$temp ?  ' selected ' : '';
        $page['text'] .= '>'.$temp.'</option>';
    }

    $page['text'] .= '</select>
			Минуты
			<select name="adm_gamenew_add_gametimefinish5">';
    for ($i=0; $i<=59; $i++)
    {
        if ($i <10)
        {
            $temp = '0';
            $temp .= $i;
        }
        else
        {
            $temp = $i;
        }
        $page['text'] .= '<option value="'.$temp.'"';
        $page['text'] .= $time[4]==$temp ?  ' selected ' : '';
        $page['text'] .= '>'.$temp.'</option>';
    }

    $page['text'] .= '</select></td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><input name="submit" type="submit" value="Создать" /></td>
		</tr></table></form>
';
}