<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <title>{{title}}</title>
    <base href="{{URL}}">
    <link rel="stylesheet" href="ui/style.css">
</head>
<body>
<div class="load">
<!--[load:loadtolayout.php]-->
</div>
<div class="php">
<?php
echo "LAYOUT";
$vars = get_defined_vars();
foreach ($vars AS $name => $var){
    if($name == "content"){
        continue;
    }
    echo "<br>var <b>\$".$name."</b> = ".$var;
}
?>
</div>
<div class="content">
<!--[content:content]-->
</div>
<div class="content">
<!--[content:js]-->
</div>
</body>
</html>
