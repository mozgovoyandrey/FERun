<?php
// индекс 1
// Страницы сайта
// версия 0.1
if (!empty($userid) && $access['admin']==1 && accesscheck($userinfo['usergroup'], $pageid) == 1)
{
    $adm_sitepage_del_pageid = $_GET['adm_sitepage_del_pageid'];
    $adm_sitepage_add_pagename = $_POST['adm_sitepage_add_pagename'];
    $adm_sitepage_add_pageurl = $_POST['adm_sitepage_add_pageurl'];
    if (!empty($adm_sitepage_add_pagename) && !empty($adm_sitepage_add_pageurl))
    {
        $query = "INSERT INTO `sitepage` VALUES (NULL, '".$adm_sitepage_add_pagename."', '".$adm_sitepage_add_pageurl."')";
        $db->query($query);
    }
    elseif (!empty($adm_sitepage_del_pageid))
    {
        $query = "select * from `sitepage` where pageid = '".$adm_sitepage_del_pageid."'";
        $result = $db->query ($query);
        $num_result = $result->num_rows;
        if ($num_result=="1")
        {
            $delete = "delete from `sitepage` where pageid = '".$adm_sitepage_del_pageid."'";
            $db->query($delete);
        }
    }


    $query = "SELECT * FROM sitepage WHERE 1";
    $result = $db->query($query);
    $num_result = $result->num_rows;
    if ($num_result > 0)
    {
        $page['text'] = "<table>";
        for($i=1;$i<=$num_result;$i++)
        {
            $rows = $result->fetch_assoc();
            $page['text'] .= "<tr><td> [";
            $page['text'] .= $rows['pageid'];
            $page['text'] .= "] - ";
            $page['text'] .= $rows['pagename'];
            $page['text'] .= " (";
            $page['text'] .= $rows['pageurl'];
            $page['text'] .= ") <a href='admin.php?pid=";
            $page['text'] .= $pageid;
            $page['text'] .= "&adm_sitepage_del_pageid=";
            $page['text'] .= $rows['pageid'];
            $page['text'] .= "'>удалить</a></td></tr>";
        }
        $page['text'] .= '<tr><td><form name="adduserform" method="post" action="admin.php?pid=';
        $page['text'] .= $pageid;
        $page['text'] .= '"><table border="0" cellspacing="1"><tr>
							<td >Название страницы:<br><input type="text" style="width:80px;" name="adm_sitepage_add_pagename" title="Название страницы" size="20" maxlength="20"></td>
							<td >URL страницы:<br><input type="text" style="width:80px;" name="adm_sitepage_add_pageurl" title="URL страницы" size="50" maxlength="50"></td>
							<td valign="bottom"><input type="submit" value="Добавить"></td>
						</tr></table></form></td></tr></table>';
    }
}