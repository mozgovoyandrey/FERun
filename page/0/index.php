<?php
// индекс 0
// Главная страница
// версия 0.1

///////////////////////////////////////////
//        ГЛАВНАЯ САЙТА
///////////////////////////////////////////

if ($page['id'] == "index")
{
    $page['text'] = "Главная<br>";

    // поиск игр
    $query = "select games.gameid, games.gamename, games.gametype, games.gamelegend, games.gametimestart, games.gametimefinish, users.userid, users.username
				from games, users
				where games.gameid IS NOT NULL 
				and users.userid = games.gameauthor
				order by games.gametimestart asc";
    $result = $db->query ($query);
    $num_result = $result->num_rows;
    if ($num_result > 0)
    {
        $temp = 0;
        $temp2 = 0;
        for ($i = 1; $i <= $num_result; $i++)
        {
            $rows = $result->fetch_assoc();
            if ($rows['gametimefinish'] > (time()+$cfg_timeadjustment))
            {
                $temp++;
                $gamecoming['id'][$temp] = $rows['gameid'];
                $gamecoming['name'][$temp] = $rows['gamename'];
                $gamecoming['type'][$temp] = $rows['gametype'];
                $gamecoming['legend'][$temp] = $rows['gamelegend'];
                $gamecoming['timestart'][$temp] = $rows['gametimestart'];
                $gamecoming['timefinish'][$temp] = $rows['gametimefinish'];
                $gamecoming['authorid'][$temp] = $rows['userid'];
                $gamecoming['authorname'][$temp] = $rows['username'];
            }
            else
            {
                $temp2++;
                $gamepast['id'][$temp2] = $rows['gameid'];
                $gamepast['name'][$temp2] = $rows['gamename'];
                $gamepast['type'][$temp2] = $rows['gametype'];
                $gamepast['authorid'][$temp2] = $rows['userid'];
                $gamepast['authorname'][$temp2] = $rows['username'];
            }
        }
        $temp = 0;
    }

    if (count($gamecoming['id']) > 0)
    {
        $page['text'] .= "Скоро:<br>";
        $page['text'] .= "<table>";
        for ($i = 1; $i <= count($gamecoming['id']); $i++)
        {
            $page['text'] .= "<tr><td> ";
            $page['text'] .= $cfg_gametype['name'][$gamecoming['type'][$i]];
            $page['text'] .= "- [";
            $page['text'] .= $gamecoming['name'][$i];
            $page['text'] .= "]<br> Легенда: ";
            $page['text'] .= nl2br(stripslashes($gamecoming['legend'][$i]));
            $page['text'] .= "<br> Старт: ";
            $page['text'] .= date ("d/m/Y - H:i:s" , $gamecoming['timestart'][$i]);
            $page['text'] .= "<br> Финиш: ";
            $page['text'] .= date ("d/m/Y - H:i:s" , $gamecoming['timefinish'][$i]);
            $page['text'] .= "<br> Автор: ";
            $page['text'] .= $gamecoming['authorname'][$i];
            $page['text'] .= "<br>";
            if (!empty($userid))
            {
                $page['text'] .= "<a href='index.php?pid=";
                $page['text'] .= $cfg_gametype['engine'][$gamecoming['type'][$i]];
                $page['text'] .= "&gid=";
                $page['text'] .= $gamecoming['id'][$i];
                $page['text'] .= "'>Вход в игру</a>";
            }
            $page['text'] .= "</td></tr> ";
            if ($i < count($gamecoming['id'])) {$page['text'] .= "<tr><td>--------------------</td></tr> ";}
        }
        $page['text'] .= "</table>";

        $page['text'] .= "<br /><br />Прошедшие игры:<br />";
        $page['text'] .= "<table>";
        for ($i=1; $i<=count($gamepast['id']); $i++)
        {
            $page['text'] .= "<tr><td> ";
            $page['text'] .= $cfg_gametype['name'][$gamepast['type'][$i]];
            $page['text'] .= "- [";
            $page['text'] .= $gamepast['name'][$i];
            $page['text'] .= "] (";
            $page['text'] .= $gamepast['authorname'][$i];
            $page['text'] .= ")";
            if (!empty($userid))
            {
                $page['text'] .= "<a href='index.php?pid=";
                $page['text'] .= $cfg_gametype['engine'][$gamepast['type'][$i]];
                $page['text'] .= "&gid=";
                $page['text'] .= $gamepast['id'][$i];
                $page['text'] .= "'>&gt;&gt;</a>";
            }
            $page['text'] .= "</td></tr> ";
        }
        $page['text'] .= "</table>";
    }
}
///////////////////////////////////////////
//        АДМИНКА
///////////////////////////////////////////
elseif ($page['id'] == "admin")
{
    $page['text'] = "Главная";
    $page['text'] .= " - Админка";


    $page['text'] .= "<br><br>Организаторский раздел";
    $page['text'] .= "<br><a href='admin.php?pid=4'>Новая игра</a>";
    $page['text'] .= "<br><a href='admin.php?pid=5'>Квадрат - редактирование игр</a>";

    $page['text'] .= "<br><br>Настройки движка";
    $page['text'] .= "<br><a href='admin.php?pid=1'>Страницы сайта</a>";
    $page['text'] .= "<br><a href='admin.php?pid=2'>Пользователи сайта</a>";
    $page['text'] .= "<br><a href='admin.php?pid=9'>Права пользователей</a>";
}