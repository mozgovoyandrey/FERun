<?php
// индекс 2
// Пользователи сайта
// версия 0.2

///////////////////////////////////////////
//        ПОЛЬЗОВАТЕЛИ САЙТА
///////////////////////////////////////////

if (!empty($userid) && $access['admin']==1 && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $action = $_GET['action'];

    if ($action == 'add')
    {
        $adm_siteusers_add_username = $_POST['adm_siteusers_add_username'];
        $adm_siteusers_add_userlogin = $_POST['adm_siteusers_add_userlogin'];
        $adm_siteusers_add_userpassword = $_POST['adm_siteusers_add_userpassword'];
        $adm_siteusers_add_userpassword = md5($adm_siteusers_add_userpassword);

        if (!empty($adm_siteusers_add_username) && !empty($adm_siteusers_add_userlogin) && !empty($adm_siteusers_add_userpassword))
        {
            $query = "INSERT INTO `users` VALUES (NULL, '".$adm_siteusers_add_userlogin."', '".$adm_siteusers_add_userpassword."', '".$adm_siteusers_add_username."', NULL, NULL, NULL, NULL)";
            $db->query($query);

            Header("Location: admin.php?pid=".$pageid);
            exit;
        }
        $page['text'] = '<form name="adduserform" method="post" action="admin.php?pid=';
        $page['text'] .= $pageid;
        $page['text'] .= '&action=add"><table border="0" cellspacing="1">
								<tr>
									<td >пользователь:<br><input type="text" style="width:80px;" name="adm_siteusers_add_username" title="пользователь" size="20" maxlength="20"></td>
									<td >логин:<br><input type="text" style="width:80px;" name="adm_siteusers_add_userlogin" title="login" size="20" maxlength="20"></td>
									<td >пароль:<br><input type="password" style="width:80px;" name="adm_siteusers_add_userpassword" title="пароль" size="20" maxlength="20"></td> 
									<td valign="bottom"><input type="submit" value="Добавить"></td>
								</tr></table></form>';
    }
    elseif ($action == 'edit')
    {
        $adm_siteusers_edit_userid = $_GET['adm_siteusers_edit_userid'];

        if (!empty($adm_siteusers_add_username) && !empty($adm_siteusers_add_userlogin) && !empty($adm_siteusers_add_userpassword))
        {
            $query = "INSERT INTO `users` VALUES (NULL, '".$adm_siteusers_add_userlogin."', '".$adm_siteusers_add_userpassword."', '".$adm_siteusers_add_username."', NULL, NULL, NULL, NULL)";
            $db->query($query);

            Header("Location: admin.php?pid=".$pageid);
            exit;
        }

    }
    elseif ($action == 'editgroup')
    {
        $adm_siteusers_editgroup_userid = $_GET['adm_siteusers_editgroup_userid'];
        $adm_siteusers_editgroup_groupid = $_POST['adm_siteusers_editgroup_groupid'];

        if (!empty($adm_siteusers_editgroup_userid))
        {
            if (!empty($adm_siteusers_editgroup_groupid) || $adm_siteusers_editgroup_groupid == '0')
            {
                $query = "UPDATE `users` SET usergroup = '".$adm_siteusers_editgroup_groupid."' WHERE userid = '".$adm_siteusers_editgroup_userid."'";
                $db->query($query);

                Header("Location: admin.php?pid=".$pageid);
                exit;
            }

            $page['text'] .= 'Пользователь <br />';
            $page['text'] .= '<form name="editusergroupform" method="post" action="admin.php?action=editgroup&adm_siteusers_editgroup_userid='.$adm_siteusers_editgroup_userid.'&pid='.$pageid.'">';
            $page['text'] .= '<select name="adm_siteusers_editgroup_groupid"><option value="0" selected>-------</option>';

            $query = "select * from `groups` where 1";
            $result = $db->query ($query);
            $num_result = $result->num_rows;
            if ($num_result > 0)
            {
                for ($i=1;$i<=$num_result;$i++)
                {
                    $rows = $result->fetch_assoc();
                    $page['text'] .= '<option value="'.$rows['groupid'].'">'.$rows['groupname'].'</option>';
                }
            }
            $page['text'] .= '</select><input type="submit" value="Изменить"></form>';
        }
    }
    elseif ($action == 'editaccess')
    {
        $adm_siteusers_editaccess_userid = $_GET['adm_siteusers_editaccess_userid'];

        if (!empty($adm_siteusers_editaccess_userid))
        {
            $query = "select * from `users` where userid='".$adm_siteusers_editaccess_userid."'";
            $result = $db->query ($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $rows = $result->fetch_assoc();
                if ($rows['useraccess'] == 0)
                {
                    $query = "UPDATE `users` SET useraccess = '1' WHERE userid = '".$adm_siteusers_editaccess_userid."'";
                    $db->query($query);
                }
                else
                {
                    $query = "UPDATE `users` SET useraccess = '0' WHERE userid = '".$adm_siteusers_editaccess_userid."'";
                    $db->query($query);
                }
                Header("Location: admin.php?pid=".$pageid);
                exit;
            }
        }
    }
    elseif ($action == 'del')
    {
        $adm_siteusers_del_userid = $_GET['adm_siteusers_del_userid'];

        if (!empty($adm_siteusers_del_userid))
        {
            $query = "select * from `users` where userid = '".$adm_siteusers_del_userid."'";
            $result = $db->query ($query);
            $num_result = $result->num_rows;
            if ($num_result == 1)
            {
                $delete = "delete from `users` where userid = '".$adm_siteusers_del_userid."'";
                $db->query($delete);
            }
        }
        Header("Location: admin.php?pid=".$pageid);
        exit;
    }
    else
    {
        $query = "SELECT * FROM `users` WHERE 1";
        $result = $db->query($query);
        $num_result = $result->num_rows;
        if ($num_result > 0)
        {
            $page['text'] = '<table><tr><td>[ID] - NAME(LOGIN)</td>
												<td>E-mail</td>
												<td>Доступ в админку</td>
												<td>Группа</td>
												<td></td>
												<td></td></tr>';
            for($i=1;$i<=$num_result;$i++)
            {
                $rows = $result->fetch_assoc();
                $page['text'] .= '<tr><td> ['.$rows['userid'].'] - '.$rows['username'].' ('.$rows['userlogin'].') </td>';
                $page['text'] .= '<td>'.$rows['usermail'].'</td>';
                $temp = $rows['useraccess'] == 1 ? 'Да' : 'Нет';
                $page['text'] .= '<td><a href="admin.php?pid='.$pageid.'&action=editaccess&adm_siteusers_editaccess_userid='.$rows['userid'].'">'.$temp.'</a></td>';

                if (!empty($rows['usergroup']))
                {
                    $query_group = "select * from `groups` WHERE groupid = '".$rows['usergroup']."'";
                    $result_group = $db->query($query_group);
                    $num_result_group = $result_group->num_rows;
                    if ($num_result_group == 1)
                    {
                        $rows_group = $result_group->fetch_assoc();
                        $temp = $rows_group['groupname'];
                    }
                }
                else
                {
                    $temp = 'Нет группы';
                }

                $page['text'] .= '<td><a href="admin.php?pid='.$pageid.'&action=editgroup&adm_siteusers_editgroup_userid='.$rows['userid'].'">'.$temp.'</a></td>';
                $page['text'] .= '<td><a href="admin.php?pid='.$pageid.'&action=edit&adm_siteusers_edit_userid='.$rows['userid'].'">измениить</a></td>';
                $page['text'] .= '<td><a href="admin.php?pid='.$pageid.'&action=del&adm_siteusers_del_userid='.$rows['userid'].'">удалить</a></td></tr>';
            }
            $page['text'] .= '<tr><td><a href="admin.php?pid='.$pageid.'&action=add"> Добавить пользователя</a></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td></tr></table>';
        }
    }
}