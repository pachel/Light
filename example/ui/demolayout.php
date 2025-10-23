<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{TITLE}}</title>
</head>
<body>
<h1></h1>
<div>
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
<!--[content:content]-->
<!--[content:fromload]-->
</body>
</html>