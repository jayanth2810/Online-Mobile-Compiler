<?php

$name=$_POST['name'];
$addr=$_POST['address'];

$ans="\\\"";
$ans1="\"";

$ans2="\\'";
$ans3="'";

$ans4="\\\\";
$ans5="\\";

echo $ans4;
echo "<br>";
echo $ans5;
echo "<br>";

$name=str_replace($ans,$ans1,$name);
$name=str_replace($ans2,$ans3,$name);
$name=str_replace($ans4,$ans5,$name);
echo "address is ".$addr;
echo "<br>";
echo "name is ".$name;

//echo $ans;
//echo "<br>";
//echo $ans1;

?>
