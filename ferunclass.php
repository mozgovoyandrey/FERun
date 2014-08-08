<?php
/**
 *
 * ferunclass.php
 *
 */

// ѕроверка прав на доступ к странице
function accesscheck($acgid, $acpid)
{
    $query = "select * from `groupaccess` where accessgroup='".$acgid."' && accesspage='".$acpid."'";
    $result = $GLOBALS['db']->query($query);
    $num_result = $result->num_rows;
    return $num_result;
}


// ѕроверка количества строк в таблице с заданным критерием
function numrowsbase($table, $coll, $varname, $cool2 = '', $varname2 = '')
{
    if (!empty($table) && !empty($coll) && !empty($varname) && !empty($cool2) && !empty($varname2))
    {
        $query = "select * from `".$table."` where ".$coll."='".$varname."' && ".$cool2."='".$varname2."'";
        $result = $GLOBALS['db']->query($query);
        $num_result = $result->num_rows;
        return $num_result;
    }
    elseif (!empty($table) && !empty($coll) && !empty($varname) && empty($cool2) && empty($varname2))
    {
        $query = "select * from `".$table."` where ".$coll."='".$varname."'";
        $result = $GLOBALS['db']->query($query);
        $num_result = $result->num_rows;
        return $num_result;
    }
    else
    {
        return '-1';
    }
}