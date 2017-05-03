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
<div align="center">
    <h1>数独破解</h1>
    （请在对应文本框中输入已经提示的数字，请保证输入的只有数字，然后点击提交）
    <form action="sudoku_with.php" method="post">
        <table border="0" >
            <?php
            $sum = 0;
            for ($i=0;$i<9;$i++){
                echo "<tr>";
                for ($j=0;$j<9;$j++){
                    $sum = $sum + 1;
                    echo "<td><input type=\"text\" name=\"fig".$sum."\" max=\"9\" min=\"1\" maxlength=\"1\" size=\"1\" value=\"\"></td>";
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
        <input type="submit" value="提交" style="width: 50px;height: 30px;">&nbsp;&nbsp;&nbsp;<input type="reset" value="重置" style="width: 50px;height: 30px;">
    </form>

</div>


</body>

</html>
