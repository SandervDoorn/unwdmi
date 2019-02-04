<?php
/**
 * Created by PhpStorm.
 * User: redmarbakker
 * Date: 31-10-17
 * Time: 16:05
 */

namespace App\Service;

class QueryBuilder
{

    public function select(string $select, string $table, array $where = [])
    {
        $sql = sprintf(
            'SELECT %s FROM %s',
            $select,
            $table
        );

        if (empty($where) !== true) {
            $sql .= ' WHERE ' . $this->arrayToCondition($where, ' AND ');
        }

        return $sql;
    }

    public function create(string $table, $data)
    {
        $columns = '(' . implode(',', array_keys($data)) . ')';
        $values = '(' . implode(',', array_values($data)) . ')';

        $sql = sprintf(
            'INSERT INTO %s VALUES %s',
            $table,
            $columns,
            $values
        );

        return $sql;
    }

    public function update(string $table, array $where, $data)
    {

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            $this->arrayToCondition($data),
            $this->arrayToCondition($where, ' AND ')
        );

        return $sql;
    }

    public function delete(string $table, array $where)
    {
        $sql = sprintf(
            'DELETE FROM %s WHERE %s',
            $table,
            $this->arrayToCondition($where)
        );

        return $sql;
    }

    private function arrayToCondition(array $data, $glue = ', ')
    {
        $values = [];
        foreach ($data as $key => $value) {
            $values[] = $key . '="' . $value . '"';
        }

        return implode($glue, $values);
    }

}