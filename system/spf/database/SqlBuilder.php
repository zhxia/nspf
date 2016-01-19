<?php
/**
 * Created by PhpStorm.
 * User: zhxia84@gmail.com
 * Date: 2016/1/16
 * Time: 10:54
 */

namespace Spf\Database;


class SqlBuilder
{
    /**
     * @param $table
     * @param $row
     * @param $update
     * @return string
     */
    public static function buildInsertOnUpdateSql($table, $row, $update)
    {
        $sql = self::buildInsertSql($table, $row);
        if (is_array($update)) {
            $arr_fields = array_keys($update);
            $str_update = '`' . implode('`=?,`', $arr_fields) . '`=?';
        } else {
            $str_update = strval($update);
        }
        $sql .= ' ON DUPLICATE KEY UPDATE ' . $str_update;
        return $sql;
    }

    /**
     * @param $table
     * @param $where
     * @return string
     */
    public static function buildSelectCountSql($table, $where)
    {
        $sql = "SELECT COUNT(*) AS total FROM `{$table}`";
        $strWhere = self::buildWhere($where);
        if ($strWhere) {
            $sql .= " {$strWhere}";
        }
        return $sql;
    }

    /**
     * @param $table
     * @param $where
     * @param $option
     * @return string
     */
    public static function  buildDeleteSql($table, $where, $option='')
    {
        $sql = "DELETE FROM `{$table}`";
        $strWhere = self::buildWhere($where);
        if ($strWhere) {
            $sql .= " {$strWhere}";
        }
        if ($option) {
            $sql .= " {$option}";
        }
        return $sql;
    }

    /**
     * @param $table
     * @param $data
     * @param $where
     * @param $option
     * @return string
     */
    public static function buildUpdateSql($table, $data, $where, $option)
    {
        $sql = "UPDATE `{$table}` SET ";
        if (is_array($data)) {
            $arrFields = array_keys($data);
            $sql .= '`' . implode('`=?,`', $arrFields) . '`=?';
        }
        $strWhere = self::buildWhere($where);
        if ($strWhere) {
            $sql .= " {$strWhere}";
        }
        if ($option) {
            $sql .= " {$option}";
        }
        return $sql;
    }

    /**
     * @param $table
     * @param array $row
     * @return string
     */
    public static function buildInsertSql($table, array &$row)
    {
        $sql = "INSERT INTO `{$table}`";
        $arrFields = array_keys($row);
        $sql .= '(`' . implode('`,`', $arrFields) . '`)';
        $arrValues = array_fill(0, count($arrFields), '?');
        $sql .= 'VALUES(' . implode(',', $arrValues) . ')';
        return $sql;
    }

    /**
     * @param $table
     * @param $where
     * @param $limit
     * @param $offset
     * @param $fields
     * @return string
     */
    public static function buildQuerySql($table, $where, $order, $limit, $offset, $fields)
    {
        $sql = 'SELECT ';
        if (is_array($fields)) {
            $fields = implode(',', $fields);
        }
        $sql .= "{$fields} FROM `$table`";
        $str_where = self::buildWhere($where);
        if ($str_where) {
            $sql .= $str_where;
        }
        if (is_array($order)) {
            $strOrder = implode(',', $order);
        } else {
            $strOrder = strval($order);
        }
        if ($strOrder) {
            $sql .= " ORDER BY {$strOrder}";
        }
        $sql .= " LIMIT {$offset},{$limit}";
        return $sql;
    }

    /**
     * @param $where
     * @return string
     */
    private static function buildWhere($where)
    {
        if (is_array($where)) {
            foreach ($where as $key => $val) {
                $key = preg_replace('/\s+/', '', $key);
                if (strpos($key, '?') !== false) {
                    $fields[] = "({$key})";
                } elseif (preg_match('/[><=!]/', $key)) {
                    $fields[] = "({$key}?)";
                } else {
                    $fields[] = "(`{$key}`=?)";
                }
                $_values[] = $val;
            }
            $strWhere = implode(' AND ', $fields);
        } else {
            $strWhere = strval($where);
        }
        return $strWhere ? ' WHERE ' . $strWhere : '';
    }
}