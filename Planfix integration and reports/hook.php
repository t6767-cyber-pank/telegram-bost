<?php 
// зона времени
date_default_timezone_set("Asia/Almaty");
// Собираем посты и парсим json в массив
$postData = file_get_contents('php://input');
$data = json_decode($postData, true);
// Блок переменных массив делим в переменные
$idmsg=$data['message']['message_id'];
$uid=$data['message']['from']['id'];
$unm=$data['message']['from']['first_name'];
$dt=date("Y-m-d H:i:s", $data['message']['date']);
$ms=$data['message']['text'];
$chatID=$data['message']['chat']['id'];
// подключаем наш единственный класс. Однояйцевый недоблизнец
echo "77777";
require_once("classes/reactionUI.php");
//require_once('./classes/vendor/autoload.php');
$reactionUI = new reactionUI($unm, $uid, $idmsg, $dt, $ms, $chatID);
// Запускаем динамо машину
$reactionUI->StartReaction();
//$reactionUI->saveToBase(print_r($data, true));
?>