<?php

namespace App\Components;

class QueryBuilderComponent{
    
    public function select($table, $option_1 = [], $option_2 = []) {
        if (gettype($option_1) === 'integer' && !count($option_2)) {
            return "select * from $table limit $option_1";
        }
        if (count($option_1) && !count($option_2)
            && is_array($option_1[0]) && is_array($option_1[1])) {
            return "select * from $table order by ". implode(' ', $option_1[0]).', '.implode(' ', $option_1[1]);
        }
        if (count($option_1) && count($option_2) && strtoupper($option_2[1]) == $option_2[1]) {
            return "SELECT ". implode(', ', $option_1)." FROM $table ORDER BY ". implode(' ',$option_2);
        }
        if (count($option_1) && count($option_2)) {
            return "select ". implode(', ', $option_1)." from $table order by ". implode(' ',$option_2);
        }
        if (count($option_1) && gettype($option_1[0]) === 'integer') {
            return "select * from $table limit $option_1[0] offset $option_1[1]";
        }
        if (count($option_1) && $option_1[0] == 'count') {
            return "select *, count(\"$option_1[1]\") from $table";
        }
        if (count($option_1) && $option_1[0] == 'max') {
            return "select max('$option_1[1]') from $table";
        }
        if (count($option_1) && $option_1[0] == 'distinct') {
            return "select distinct $option_1[1] from $table";
        }
        if (count($option_1) && $option_1[0] == 'group by') {
            return "select max('$option_1[1]') from $table group by $option_1[1]";
        }
        if (count($option_1)) {
            return "select ". implode(', ', $option_1)." from $table";
        }
        return "select * from $table";
    }

    public function selectJoin($main_table, $join_table, $join_columns=[], $selection=[])
    {
        return "select ".($selection?implode(', ', $selection):'*')." from $main_table join $join_table on $main_table.$join_columns[0]=$join_table.$join_columns[1]";
    }

    public function insert($table, $columns = [], $values=[])
    {
       $sql = "INSERT INTO $table(" .implode(', ', $columns). ")"." VALUES";

       foreach ($values as $key => $value) {
           $rows[] = "(" .$this->implodeCustom(', ', $value). ")";
       }
       return $sql.implode(', ', $rows);
    }

    private function implodeCustom($glue, $values)
    {
        $data='';
        foreach ($values as $key => $value) {
            $data .= (is_string($value) ? '"'.$value.'"':$value).((sizeof($values)-1 != $key)?$glue:'');
        }
        return $data??$values;
    }

    public function update($table, $option1, $option2)
    {
        $output = "UPDATE $table SET $option1[0]=";
        if($option1[0] == 'cost'){
            $output .= $option1[1];
        }else{
            $output .= '"'.$option1[1].'"';
        }
        $output .= " WHERE $option2[0] = ";
        if($option2[0] == 'cost'){
            $output .= $option2[1];
        }else{
            $output .= '"'.$option2[1].'"';
        }
        return $output;
    }
    public function delete($table, $option = "")
    {
        $output = "DELETE FROM $table";
        if(is_array($option)){
            $output .= " WHERE $option[0]";
            if(count($option) == 3){
                $output .=  ">".(int)$option[2]*5;
            }else{
                $output .=  '="'.$option[1].'"';
            }
        }
        return $output;
    }
}