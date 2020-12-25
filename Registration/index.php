<?php 
// зона времени
date_default_timezone_set("Asia/Almaty");
// Собираем посты и парсим json в массив
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
// Блок переменных массив делим в переменные
require_once("classes/reactionUI.php");
$reactionUI = new reactionUI($data);
?>