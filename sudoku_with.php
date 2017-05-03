<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>数独破解</title>
</head>
<body>
<style>
    .form{
        width: 395px;
        height: 200px;
    }
    input{
        width: 30px;
        height: 30px;
        font-size: 18px;
        text-align: center;
    }
    td{

    }
</style>
<?php
/**
 * Created by PhpStorm.
 * User: Rain
 * Date: 2017/5/2
 * Time: 21:29
 */
$old_host =  $_SERVER['HTTP_REFERER'];
if($old_host != 'http://www.rain1024.com/more/sudoku_web.php'){
    $url = 'http://www.rain1024.com/more/sudoku_web.php';
    header("Locatio:$url");
    exit();
}
//$host = $_SERVER['HTTP_HOST'];
//if($host=='manage.rain1024.com'){
//    $url = 'manage.rain1024.com/manage/index.php';
//    header("Locatio:$url");
//    exit();
//}
$sudoku_arr = array();
for ($i = 0; $i < 20; $i++) {
    for ($j = 0; $j < 20; $j++) {
        $sudoku_arr[$i][$j] = 0;
    }
}
$sum = 0;
for ($i = 0; $i < 9; $i++) {
    for ($j = 0; $j < 9; $j++) {

        $sum = $sum + 1;
        /**
         * 要先判断输入的类型，只能是1-9的数字
         */
        if (empty($_POST['fig' . $sum])) {
            $sudoku_arr[$i][$j] = 0;
        } else {
            $sudoku_arr[$i][$j] = intval($_POST['fig' . $sum]);
        }
    }
}
$new_arr = $sudoku_arr;
//var_dump($sudoku_arr);
/* 数独求解程序
 * Created on 2017-4-18
 *
 */
class Sudoku {
    var $matrix;
    function __construct($arr = null) {
        if ($arr == null) {
            $this->clear();
        } else {
            $this->matrix = $arr;
        }
    }
    function clear() {
        for($i=0; $i<9; $i++) {
            for($j=0; $j<9; $j++) {
                $this->matrix[$i][$j] = array();
                for ($k = 1; $k <= 9; $k++) {
                    $this->matrix[$i][$j][$k] = $k;
                }
            }
        }
    }
    function setCell($row, $col, $value){
        $this->matrix[$row][$col] = array($value => $value);
        //row
        for($i = 0; $i < 9; $i++){
            if($i != $col){
                if(! $this->removeValue($row, $i, $value)) {
                    return false;
                }
            }
        }
        //col
        for($i = 0; $i < 9; $i++){
            if($i != $row){
                if(! $this->removeValue($i, $col, $value)) {
                    return false;
                }
            }
        }
        //square
        $rs=intval($row / 3) * 3;
        $cs=intval($col / 3) * 3;
        for($i = $rs; $i < $rs + 3; $i++){
            for($j = $cs; $j < $cs + 3; $j++){
                if($i != $row && $j != $col){
                    if(! $this->removeValue($i, $j, $value))
                        return false;
                }
            }
        }
        return true;
    }
    function removeValue($row, $col, $value) {
        $count = count($this->matrix[$row][$col]);
        if($count == 1){
            $ret = !isset($this->matrix[$row][$col][$value]);
            return $ret;
        }
        if (isset($this->matrix[$row][$col][$value])) {
            unset($this->matrix[$row][$col][$value]);
            if($count - 1 == 1) {
                return $this->setCell($row, $col, current($this->matrix[$row][$col]));
            }
        }
        return true;
    }
    function set($arr) {
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                if ($arr[$i][$j] > 0) {
                    $this->setCell($i, $j, $arr[$i][$j]);
                }
            }
        }
    }
    function dump() {
        for($i = 0; $i < 9; $i++){
            for($j = 0; $j < 9; $j++){
                $c = count($this->matrix[$i][$j]);
                if($c == 1){
                    echo " ".current($this->matrix[$i][$j])." ";
                } else {
                    echo "(".$c.")";
                }
            }
            echo "<br>";
        }
        echo "<br>";
    }
    function result() {
        global $new_arr;
        for($i = 0; $i < 9; $i++){
            for($j = 0; $j < 9; $j++){
                $c = count($this->matrix[$i][$j]);
                if($c == 1){
                    $new_arr[$i][$j] = current($this->matrix[$i][$j]);
                } else {
                    $new_arr[$i][$j] = $c;
                }
            }
//            echo "<br>";
        }
//        echo "<br>";
    }
    function dumpAll() {
        for($i = 0; $i < 9; $i++){
            for($j = 0; $j < 9; $j++){
                echo implode('', $this->matrix[$i][$j]), "&nbsp;&nbsp;";
            }
            echo "<br>";
        }
        echo "<br>";
    }
    function calc($data) {
        $this->clear();
        $this->set($data);
        $this->_calc();
        $this->result();
//        $this->dump();
    }
    function _calc() {
        for($i = 0; $i < 9; $i++){
            for($j = 0; $j < 9; $j++){
                if(count($this->matrix[$i][$j]) == 1) {
                    continue;
                }
                foreach($this->matrix[$i][$j] as $v){
                    $flag = false;
                    $t = new Sudoku($this->matrix);
                    if(!$t->setCell($i, $j, $v)){
                        continue;
                    }
                    if(!$t->_calc()){
                        continue;
                    }
                    $this->matrix = $t->matrix;
                    return true;
                }
                return false;
            }
        }
        return true;
    }
}
$sudoku = new Sudoku();
$sudoku->calc($sudoku_arr);
//for ($i = 0; $i < 9; $i++) {
//    for ($j = 0; $j < 9; $j++) {
//        echo $new_arr[$i][$j]."  ";
//    }
//    echo "<br>";
//}
?>

<div align="center">
    <h1>破解结果</h1>
    （灰色输入框为自己输入的值，点击返回按钮可重新修改内容）
    <table border="0" >
        <?php
        $sum = 0;
        for ($i=0;$i<9;$i++){
            echo "<tr>";
            for ($j=0;$j<9;$j++){
                $sum = $sum + 1;
                if($sudoku_arr[$i][$j]==0){
                    echo "<td><input  type=\"text\" value=\"".$new_arr[$i][$j]."\" max=\"9\" min=\"1\" maxlength=\"1\" size=\"1\" ></td>";
                }else{
                    echo "<td><input style='background-color: #cac5c9' type=\"text\" max=\"9\" min=\"1\" maxlength=\"1\" size=\"1\" value=\"".$sudoku_arr[$i][$j]."\"></td>";
                }
                if($sum%3==0){
                    echo "<td></td>";
                }
            }
            echo "</tr>";
            if($sum%27==0){
                echo "<tr></tr><tr></tr>";
            }
        }

        ?>
    </table>
    <input type="button" value="返回" style="width: 50px;height: 30px;" onclick="goback()">
</div>
<script>
    function goback(){
        history.go(-1);
    }
</script>
</body>
</html>

