<?php
$nAppStartTime = array_sum(explode(' ', microtime()));

require_once("./Controlls/System/Core/Application/Application.php");

$Application = new Application();
$Application->Start();

echo $Application->Check->Compare(APP_START);

?>