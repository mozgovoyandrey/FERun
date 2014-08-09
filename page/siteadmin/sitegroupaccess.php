<?php
// индекс 9(9)
// Права пользователей.
// версия 0.2

///////////////////////////////////////////
//        Права пользователей.
///////////////////////////////////////////


if (!empty($userid) && $access['admin']==1 && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $groupid = 	$_GET['groupid'];
    $action = 	$_GET['action'];
    if (empty($groupid))
    {
        $adm_usergroup_del_groupid = $_GET['adm_usergroup_del_groupid'];
        $adm_usergroup_edit_groupid = $_GET['adm_usergroup_edit_groupid'];
        $adm_usergroup_add_groupname = $_POST['adm_usergroup_add_groupname'];

        if ($action == 'add' && !empty($adm_usergroup_add_groupname))
        {
            $query = "INSERT INTO `groups` VALUES (NULL, '".$adm_usergroup_add_groupname."')";
            $db->query($query);
        }
        elseif ($action == 'edit' && !empty($adm_usergroup_edit_groupid))
        {

        }
        elseif ($action == 'del' && !empty($adm_usergroup_add_groupname))
        {

        }

        $query = "select * from `groups` where 1";
        $result = $db->query ($query);
        $num_result = $result->num_rows;
        if ($num_result > 0)
        {
            $page['text'] = "<table>";
            for ($i=1;$i<=$num_result;$i++)
            {
                $rows = $result->fetch_assoc();
                $page['text'] .= "<tr><td> <a href='admin.php?pid=";
                $page['text'] .= $pageid;
                $page['text'] .= "&groupid=";
                $page['text'] .= $rows['groupid'];
                $page['text'] .= "'>[";
                $page['text'] .= $rows['groupname'];
                $page['text'] .= "] </a></td><td><a href='admin.php?pid=";
                $page['text'] .= $pageid;
                $page['text'] .= "&adm_usergroup_del_groupid=";
                $page['text'] .= $rows['groupid'];
                $page['text'] .= "'>Удалить</a></td></tr>";
            }
            $page['text'] .= "</table>";
        }
        $page['text'] .= '<form name="addgamenewform" method="post" action="admin.php?action=add&pid=';
        $page['text'] .= $pageid;
        $page['text'] .= '">';
        $page['text'] .= 'Название: <input name="adm_usergroup_add_groupname" type="text" /><input name="Submit" type="submit" value="Добавить" />';
        $page['text'] .= '</form>';
    }
    else
    {
        $query = "select * from `groups` where groupid='".$groupid."'";
        $result = $db->query ($query);
        $num_result = $result->num_rows;
        if ($num_result == 1)
        {
            $groupinfo = $result->fetch_assoc();

            $allpage = $_POST['allpage'];

            // Получение имеющегося списка прав в таблице
            $query = "select * from `groupaccess` where accessgroup='".$groupid."'";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {
                for($i=1; $i<=$num_result; $i++)
                {
                    $rows = $result->fetch_assoc();
                    $tempaccess[$rows['accesspage']] = 1;
                }
            }
            //Проверка были ли переданы данные в POST
            if ($allpage > 0)
            {
                // Получение нового списка прав из запроса
                for ($i=1; $i<=$allpage; $i++)
                {
                    $temp = 'check'.$i;
                    $temp = $_POST[$temp];
                    if (!empty($temp))
                    {
                        $checked[$temp] = 1;
                    }
                }

                $query = "select * from `sitepage` where 1";
                $result = $db->query($query);
                $num_result = $result->num_rows;
                if ($num_result > 0)
                {
                    for($i=1; $i<=$num_result; $i++)
                    {
                        $rows = $result->fetch_assoc();

                        if ($checked[$rows['pageid']] == 1 && empty($tempaccess[$rows['pageid']]))
                        {
                            $query = "INSERT INTO `groupaccess` VALUES (NULL, '".$groupid."', '".$rows['pageid']."')";
                            $db->query($query);
                        }
                        elseif ($tempaccess[$rows['pageid']] == 1 && empty($checked[$rows['pageid']]))
                        {
                            $query = "delete from `groupaccess` where accessgroup = '".$groupid."' && accesspage = '".$rows['pageid']."'";
                            $db->query($query);
                        }
                    }
                }
                Header("Location: admin.php?pid=".$pageid);
                exit;
            }

            $page['text'] .= '<a href="admin.php?pid='.$pageid.'">Назад</a><br />';
            $page['text'] .= 'Редактирование прaв группы - '.$groupinfo['groupname'].'<br />';

            $query = "select * from sitepage where 1";
            $result = $db->query($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {

                $page['text'] .= '<form name="editgroupaccessform" method="post" action="admin.php?pid=';
                $page['text'] .= $pageid;
                $page['text'] .= '&groupid=';
                $page['text'] .= $groupid;
                $page['text'] .= '"><input name="allpage" type="hidden" value="'.$num_result.'"  />';
                for($i=1;$i<=$num_result;$i++)
                {
                    $rows = $result->fetch_assoc();
                    $page['text'] .= '<input name="check';
                    $page['text'] .= $i;
                    $page['text'] .= '" type="checkbox"';
                    $page['text'] .= ($tempaccess[$rows['pageid']] == 1) ? ' checked="checked"' : '';
                    $page['text'] .= ' value="';
                    $page['text'] .= $rows['pageid'];
                    $page['text'] .= '"  />';
                    $page['text'] .= $rows['pagename'];
                    $page['text'] .= "<br/ >";
                }
                $page['text'] .= '<input type="submit" value="Изменить"></form>';
            }
        }
        else
        {
            Header("Location: http://".$_SERVER['HTTP_HOST']."/admin.php?pid=".$pageid);
            exit;
        }
    }
}