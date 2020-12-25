<?php
class planFX
{
    public $api_server = 'https://api.planfix.ru/xml/';
    public $api_key = 'xxxxxxxxxxxxxxxxxx';
    public $api_secret = 'xxxxxxxxxxxxxxxxx';
    public $api_token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';

    public function __construct()
    {
    }

    public function getGetPartner($pik)
    {
        //test1
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request method="contact.getList"><filters><filter><type>4101</type><field>25724</field><operator>equal</operator><value>'.$pik.'</value></filter></filters></request>');
        return $this->curlEditor($requestXml);
    }

    public function getUIK($uik)
    {
        //QWEB199
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request method="contact.getList"><filters><filter><type>4101</type><field>14926</field><operator>equal</operator><value>'.$uik.'</value></filter></filters></request>');
        return $this->curlEditor($requestXml);
    }

    public function getTask($uid)
    {
        //QWEB199
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request method="task.getList"><account></account><sid></sid><user><id></id></user><owner><id></id></owner> <parent><id></id></parent><status></status><pageCurrent></pageCurrent><pageSize></pageSize><filters><filter><type>1</type><operator>equal</operator><value>'.$uid.'</value></filter></filters></request>');
        return $this->curlEditor($requestXml);
    }

    public function updateTask($tid, $stat)
    {
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request method="task.changeStatus"><account></account><sid></sid><task><id>'.$tid.'</id></task><status>'.$stat.'</status></request>');
        return $this->curlEditor($requestXml);
    }

    public function updateTaskWorkers($tid, $workers)
    {
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request method="task.update"><account></account><sid></sid><silent></silent><task><id>'.$tid.'</id><general></general><workers>'.$workers.'</workers></task></request>');
        return $this->curlEditor($requestXml);
    }

    public function addTaskToKontactById($uid)
    {
        $requestXml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><request method="task.add"><account></account><sid></sid><task><template>1270242</template><title>9oweb тестирует создание задач</title><description>Тестируем создание задач</description><importance>AVERAGE</importance><status>1</status><statusSet>15944</statusSet><checkResult>1</checkResult><owner><id>'.$uid.'</id></owner><parent><id>0</id></parent><project><id>0</id></project><client><id>'.$uid.'</id></client><workers><users><id>616378</id></users><groups><id></id></groups></workers></task></request>');
        return $this->curlEditor($requestXml);
    }

    public function curlEditor($requestXml)
    {
        $requestXml->account = 'amanbolkz';
        $requestXml->pageCurrent = 1;
        $ch = curl_init($this->api_server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // не выводи ответ на stdout
        curl_setopt($ch, CURLOPT_HEADER, 1);   // получаем заголовки
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_key . ':' . $this->api_token);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestXml->asXML());
        $response = curl_exec($ch);
        $error = curl_error($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $responseBody = substr($response, $header_size);
        curl_close($ch);
        $temp=trim($responseBody);
        $xml    = simplexml_load_string($temp);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        return $array;
    }
}

class reactionUI extends PDO
{
    // Глобальные переменные
	public $user;
	public $iduser;
	public $idmsg;
	public $dt;
	public $ms;
	public $access;
	public $url;
	public $menu1;
    public $menu2;
    public $menu3;
    public $menu4;
    public $menu5;
    public $menu6;
    public $menu7;
    public $menu8;
    public $planFX;
    public $uik;
    public $chatID;
    public $partnerID;
    public $partnerName;

	// Создадим конструктор ебаный Лего
	public function __construct($unm, $uid, $idmsg, $dt, $ms, $chatID, $file = 'my_setting.ini')
    {
        // парсим файл подключения
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable11 to open ' . $file . '.');
        // Создаем подключение к БД
        $dns = $settings['database']['driver'].':host=' . $settings['database']['host'].((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '').';dbname='.$settings['database']['schema'];
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
        // Объявляем глобальные переменные
        $this->access=0;
        $this->url='https://api.telegram.org/xxxxxxxxxxxxxxxxxxx/sendMessage';
        $this->urldoc='https://api.telegram.org/xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx/sendDocument';
        $this->menu1=[["\xF0\x9F\x91\x8D Выдать"],["Назад"],["\xE2\x9D\x8C Выход"]];
        $this->menu2=[["Инструкция"],["Отчет за неделю"],["Отчет за месяц"],["Назад"]];
        $this->menu3=[["Инструкция"]];
        $this->menu4=[["Авторизация"]];
        $this->menu5=[["Выдать набор"],["Результат"],["\xE2\x9D\x8C Выход"]];
        $this->menu6=[["Инструкция"],["Назад"],["\xE2\x9D\x8C Выход"]];
        $this->menu7=[["Инструкция"],["Назад"],["\xE2\x9D\x8C Выход"]];
        $this->menu8=[["Да, результат положительный"],["Да, результат отрицательный"],["Тест не сработал"],["Нет, не видел"],["Назад"]];
        $this->user=$unm;
        $this->iduser=$uid;
        $this->idmsg=$idmsg;
        $this->dt=$dt;
        $this->ms=$ms;
        $this->chatID=$chatID;
        $this->planFX=new planFX();
        // Проверка на пользователя. Оставь надежду всяк сюда входящий
        $xxx=$this->planFX->getGetPartner($this->ms);
        $countRes=$xxx['contacts']['@attributes']['totalCount'];
        if ($countRes>1) {
            $login=$xxx['contacts']['contact'][0]['customData']['customValue']['text'];
            $this->partnerID=$xxx['contacts']['contact'][0]['userid'];
            $this->partnerName=$xxx['contacts']['contact'][0]['name'];
            if ($login==$ms) $this->access=1;
        }
        else
        {
            $login = $xxx['contacts']['contact']['customData']['customValue']['text'];
            $this->partnerID=$xxx['contacts']['contact']['userid'];
            $this->partnerName=$xxx['contacts']['contact']['name'];
            if ($login==$ms) $this->access=1;
        }
    }

    // Взять id авторизации
	function getAuth()
    {
        $id=$this->iduser;
        $stmt = $this->query("SELECT * FROM auth where iduser=$id and active=1 and dt> NOW() - INTERVAL 1440 MINUTE");
        $row = $stmt->fetch();
        $str=$row['id'];
        return $str;
    }

    // Взять id contack
    function getKontactID()
    {
        $id=$this->iduser;
        $stmt = $this->query("SELECT * FROM auth where iduser=$id and active=1 and dt> NOW() - INTERVAL 1440 MINUTE");
        $row = $stmt->fetch();
        $str=$row['uik'];
        return $str;
    }

    function getAllFields()
    {
        $id=$this->iduser;
        $stmt = $this->query("SELECT * FROM auth where iduser=$id and active=1 and dt> NOW() - INTERVAL 1440 MINUTE");
        $row = $stmt->fetch();
        return $row;
    }

    function DeleteAuth()
    {
        $sql = "delete from auth where id=".$this->getAuth();
        $query = $this->prepare($sql);
        $query->execute();
    }

	function saveToBase($res)
    {
        $sql = "INSERT INTO test (text) VALUES ('$res')";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function saveAuth()
    { //$this->partnerID
        $id_user = $this->iduser;
        $dt=$this->dt;
        $sql = "INSERT INTO auth (iduser, dt, active, uik, uikname, nameuser, telephone, status, email, taskID, statusmenu) VALUES ('$id_user', '$dt', 1, '','', '".$this->partnerName."', '".$this->partnerID."', '0', '', '', 0)";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateAuth($uik, $uikName, $status, $email, $taskID)
    {
        $id=$this->getAuth();
        $sql = "update auth set uik='$uik', uikname='$uikName', status='$status', email='$email', taskID='$taskID'  where id=$id";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateAuthstatusmenu($status)
    {
        $id=$this->getAuth();
        $sql = "update auth set statusmenu=$status  where id=$id";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function saveLog()
    {
        $id_message = $this->idmsg;
        $id_user = $this->iduser;
        $uname = $this->user;
        $date = $this->dt;
        $msg = $this->ms;
        $sql = "INSERT INTO lids (id_message, id_user, uname, date, msg) VALUES ($id_message, '$id_user', '$uname', '$date', '$msg')";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function saveResult($uik, $idpart, $partname)
    {
        $dt=date("Y-m-d H:i:s");
        $sql = "INSERT INTO resultsWork (date_send, UIK, status, partnerID, PartnerName) VALUES ('$dt', '$uik', 'Клиент получил посылку', '$idpart', '$partname')";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function StartReaction()
    {
            if ((int)$this->getAuth()>0)
            {
                $this->saveLog();
                switch ($this->ms)
                {
                    case "\xF0\x9F\x91\x8D Выдать":
                        $this->sendMessage('👍🏻🏻 Набор для пользователя выдан и зарегистрирован. Спасибо!',null);
                        $arr=$this->getAllFields();
                        $PartID=$arr['telephone'];
                        $msx2=$this->planFX->getUIK( $arr['uikname'] );//'QWEB199');
                        $ms2=$msx2['contacts']['@attributes']['totalCount'];
                        if ($ms2>1) {
                            $userID2=$msx2['contacts']['contact'][0]['userid'];
                        } else {
                            $userID2 = $msx2['contacts']['contact']['userid'];
                        }

                        $taskArray=$this->planFX->getTask($userID2);
                        $strf="<users>";
                        if (isset($taskArray['tasks']["task"][0])) {
                            if ($taskArray['tasks']["task"][0]['workers']['users']["user"]["id"] != "") {
                                $strf .= "<id>" . $taskArray['tasks']["task"][0]['workers']['users']["user"]["id"] . "</id>";
                                if ($taskArray['tasks']["task"][0]['workers']['users']["user"]["id"] != $PartID) $strf .= "<id>" . $PartID . "</id>";
                            } else {
                                $x = 1;
                                foreach ($taskArray['tasks']["task"][0]['workers']['users']["user"] as $userxxx) {
                                    $strf .= "<id>" . $userxxx["id"] . "</id>";
                                    if ($userxxx["id"] == $PartID) $x = 0;
                                }
                                if ($x > 0) $strf .= "<id>" . $PartID . "</id>";
                            }
                        }
                        else
                        {
                            if ($taskArray['tasks']["task"]['workers']['users']["user"]["id"] != "") {
                                $strf .= "<id>" . $taskArray['tasks']["task"]['workers']['users']["user"]["id"] . "</id>";
                                if ($taskArray['tasks']["task"]['workers']['users']["user"]["id"] != $PartID) $strf .= "<id>" . $PartID . "</id>";
                            } else {
                                $x = 1;
                                foreach ($taskArray['tasks']["task"]['workers']['users']["user"] as $userxxx) {
                                    $strf .= "<id>" . $userxxx["id"] . "</id>";
                                    if ($userxxx["id"] == $PartID) $x = 0;
                                }
                                if ($x > 0) $strf .= "<id>" . $PartID . "</id>";
                            }
                        }
                        $strf.="</users>";
                        $this->saveToBase(print_r($taskArray['tasks']["task"], true));
                        $this->saveToBase($strf);



                        $taskIDDD=$arr['taskID'];


                        $this->planFX->updateTask($taskIDDD, "103");
                        $this->planFX->updateTaskWorkers($taskIDDD, $strf);
                        $this->saveResult($arr['uikname'], $arr['telephone'], $arr['nameuser']);
                        //$this->planFX->addTaskToKontactById($this->getKontactID());
                        //$this->DeleteAuth();
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) и ожидай мой ответ. ', $this->menu2);
                        break;
                    case "Да, результат положительный":
                        $this->sendMessage('Статус Изменен. Спасибо!',null);
                        $arr=$this->getAllFields();
                        $taskIDDD=$arr['taskID'];
                        $this->planFX->updateTask($taskIDDD, "106");
                        $this->updateAuthstatusmenu(0);
                        $this->sendMessage('Выберите действия из предложенных в меню.', $this->menu5);
                        break;
                    case "Да, результат отрицательный":
                        $this->sendMessage('Статус Изменен. Спасибо!',null);
                        $arr=$this->getAllFields();
                        $taskIDDD=$arr['taskID'];
                        $this->planFX->updateTask($taskIDDD, "105");
                        $this->updateAuthstatusmenu(0);
                        $this->sendMessage('Выберите действия из предложенных в меню.', $this->menu5);
                        break;
                    case "Тест не сработал":
                        $this->sendMessage('Статус Изменен. Спасибо!',null);
                        $arr=$this->getAllFields();
                        $taskIDDD=$arr['taskID'];
                        $this->planFX->updateTask($taskIDDD, "109");
                        $this->updateAuthstatusmenu(0);
                        $this->sendMessage('Выберите действия из предложенных в меню.', $this->menu5);
                        break;
                    case "Нет, не видел":
                        $this->sendMessage('Статус Изменен. Спасибо!',null);
                        $arr=$this->getAllFields();
                        $taskIDDD=$arr['taskID'];
                        $this->planFX->updateTask($taskIDDD, "107");
                        $this->updateAuthstatusmenu(0);
                        $this->sendMessage('Выберите действия из предложенных в меню.', $this->menu5);
                        break;
                    case "\xE2\x9D\x8C Выход":
                        $this->sendMessage("\xE2\x9A\xA0 Запрос отклонен!",$this->menu4);
                        $this->DeleteAuth(); break;
                    case '/start':
                        $this->sendMessage('🙋🏻‍♂️ Привет. Я помогу тебе узнать, заказывал ли клиент набор Aman Bol на нашем сайте.', $buttons = null);
                        $this->sendMessage('⌨️ Просто введи свой Login для авторизации и следуй дальнейшим инструкциям.', $buttons = null);
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) и ожидай мой ответ. ', $this->menu2);
                        break;
                    case '/restart':
                        $this->sendMessage('🙋🏻‍♂️ Привет. Я помогу тебе узнать, заказывал ли клиент набор Aman Bol на нашем сайте.', $buttons = null);
                        $this->sendMessage('⌨️ Просто введи свой Login для авторизации и следуй дальнейшим инструкциям.', $buttons = null);
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) и ожидай мой ответ. ', $this->menu2);
                        break;
                    case "Выдать набор":
                        $this->updateAuthstatusmenu(1);
                        $this->sendMessage('⌨️Пожалуйста, введи его УИК (секретный код) <b>для выдачи набора</b> и ожидай мой ответ', $this->menu2);
                        break;
                    case "Результат":
                        $this->updateAuthstatusmenu(2);
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) <b>для ввода результата тестирования</b> и ожидай мой ответ', $this->menu6);
                        break;
                    case "Назад":
                        $this->updateAuthstatusmenu(0);
                        $this->sendMessage('Выберите действия из предложенных в меню.', $this->menu5);
                        break;
                    case "Инструкция":
                        $this->sendMessage("<b>Зачем мне этот бот?</b>\nБот поможет тебе узнать, заказывал ли клиент набор на сайте и можно ли ему набор выдать. Также бот автоматически сохранит УИК клиента в твоем персональном отчете и мы это сразу увидим.", null);
                        $this->sendMessage("<b>Как пользоваться ботом?</b>\nВведите /start и следуй подсказкам бота", null);
                        $this->sendMessage("<b>Я ввожу УИК из письма после заказа или из переписки с менеджером, но бот отвечает, что не может найти клиента. Почему?</b>\nЗдесь три причины.\n1. Возможно, клиент при формировании УИКа (секретного кода) использовал кириллицу, латиницу или обе раскладки сразу. Пожалуйста, уточни у клиента, какой секретный код он указывал при заказе, и введи именно его. Ты можешь посмотреть письмо после заказа, которое приходит на почту клиента, а также переписку клиента с менеджером проекта (вотсапп, телеграм, почта, смс). Правило проекта: вводимый тобой УИК должен полностью совпадать с тем, который указал клиент при заказе.\n2. Возможно, клиент уже получил набор в другом пункте выдачи. Пожалуйста, уточни этот момент у клиента. Правило проекта: один УИК – один набор на 3 три месяца.\n3. Самый маловероятный вариант. Возможно, клиент вообще не заказывал и использует чужое письмо после заказа или чужой УИК. Пожалуйста, попроси клиента заказать набор на сайте amanbol.kz", null);
                        $this->sendMessage("<b>Я случайно ввел/ввела неправильный УИК, но бот подтвердил, что такой клиент есть. Как такое возможно?</b>\nПолное совпадение УИКов встречается крайне редко. Поэтому, если ты случайно подтверждаешь неверный, но существующий УИК, пожалуйста, напиши нам как можно скорее, чтобы мы исправили ошибку. Иначе коллеги в другом пункте выдачи не смогут выдать клиенту набор.", null);
                        $this->sendMessage("<b>Я ничего не понимаю. Что мне делать?</b>\nТакое тоже бывает, поэтому смело свяжись с нами", null);
                        $this->sendMessage("<b>Как связаться с нами?</b>\nПо номеру телефона, который у тебя наверняка есть\nПо почте: amanbol@amanbol.kz\nЧерез форму обратной связи на сайте amanbol.kz\nИли пиши личные сообщения в соц.сети. Ссылки смотри на сайте amanbol.kz", null);
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) и ожидай мой ответ. ', $this->menu2);
                        break;
                    case "Отчет за неделю":
                        $arr=$this->getAllFields();
                        $this->sendFile($arr['telephone'], 'week');
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) и ожидай мой ответ. ', $this->menu2);
                        break;
                    case "Отчет за месяц":
                        $arr=$this->getAllFields();
                        $this->sendFile($arr['telephone'], 'month');
                        $this->sendMessage('⌨️ Пожалуйста, введи его УИК (секретный код) и ожидай мой ответ. ', $this->menu2);
                        break;
                    default:
                        {
                        $arr=$this->getAllFields();
                        $PartID=$arr['telephone'];
                            $statusmenu=$arr['statusmenu'];
                            switch ($statusmenu)
                            {
                            case "0":
                                $this->sendMessage('Выберите действия из предложенных в меню.', $this->menu5);
                            break;
                            case "1":
                            $this->ms=mb_strtoupper($this->ms);
                            $msx=$this->planFX->getUIK( $this->ms );//'QWEB199');
                            $ms=$msx['contacts']['@attributes']['totalCount'];
                            if ($ms>1) {
                                $userID=$msx['contacts']['contact'][0]['userid'];
                                $uikName=$msx['contacts']['contact'][0]['customData']['customValue']['text'];
                            } else {
                                $userID = $msx['contacts']['contact']['userid'];
                                $uikName = $msx['contacts']['contact']['customData']['customValue']['text'];
                            }
                            if ($this->ms!=$uikName) $ms=0;
                            if ($ms>0)
                            {
                                $taskArray=$this->planFX->getTask($userID);
                                $strf="<users>";
                                if ($taskArray['tasks']["task"]['workers']['users']["user"]["id"]!="")
                                {
                                $strf.="<id>".$taskArray['tasks']["task"]['workers']['users']["user"]["id"]."</id>";
                                if ($taskArray['tasks']["task"]['workers']['users']["user"]["id"]!=$PartID) $strf.="<id>".$PartID."</id>";
                                }
                                else
                                {
                                $x=1;
                                foreach ($taskArray['tasks']["task"]['workers']['users']["user"] as $userxxx) {
                                    $strf.="<id>".$userxxx["id"]."</id>";
                                    if ($userxxx["id"]==$PartID) $x=0;
                                }
                                if($x>0) $strf.="<id>".$PartID."</id>";
                                }
                                $strf.="</users>";
                                $mstask=$taskArray['tasks']['@attributes']['totalCount'];
                                if ($mstask>1) {
                                $idTask = $taskArray["tasks"]["task"][0]["id"];
                                $taskStatus = $taskArray["tasks"]["task"][0]["status"];
                                } else
                                {
                                $idTask = $taskArray["tasks"]["task"]["id"];
                                $taskStatus = $taskArray["tasks"]["task"]["status"];
                                }
                            }
                            switch ($taskStatus)
                            {
                                case 1: break;
                                case 2: break;
                                case 101: break;
                                case 102: break;
                                default:
                                    {
                                        $ms=0;
                                    }
                            }
                            if ($ms>0) {
                                $this->updateAuth($userID, $uikName,'Клиент получил посылку', $strf, $idTask);
                                $this->sendMessage('🆗 Да, все верно, пользователь с УИК '.$uikName.' заказал набор, его можно выдать. Нажми кнопку ВЫДАТЬ НАБОР, чтобы сохранить его в отчете',$buttons = $this->menu1);
                            } else
                            {
                                    $this->sendMessage('🙁 Не удалось найти этого пользователя с УИК '.$this->ms.'.', $buttons = $this->menu2);
                                    $this->sendMessage('Пожалуйста, попроси клиента показать письмо после заказа или переписку с нашим менеджером, посмотри его УИК (секретный код) в письме или в переписке и введи УИК еще раз. Если нет ни письма, ни переписки, то попроси клиента заказать набор на сайте amanbol.kz', $buttons = $this->menu2);
                            }
                            break;
                            case "2":
                                $this->ms=mb_strtoupper($this->ms);
                                $this->sendMessage('УИК введен '.$this->ms.'.', $buttons = $this->menu2);
                                $msx=$this->planFX->getUIK( $this->ms );//'QWEB199');
                                $ms=$msx['contacts']['@attributes']['totalCount'];
                                if ($ms>1) {
                                    $userID=$msx['contacts']['contact'][0]['userid'];
                                    $uikName=$msx['contacts']['contact'][0]['customData']['customValue']['text'];
                                } else {
                                    $userID = $msx['contacts']['contact']['userid'];
                                    $uikName = $msx['contacts']['contact']['customData']['customValue']['text'];
                                }
                                if ($this->ms!=$uikName) $ms=0;
                                if ($ms>0)
                                {
                                    $taskArray=$this->planFX->getTask($userID);
                                    $strf="<users>";
                                    if ($taskArray['tasks']["task"]['workers']['users']["user"]["id"]!="")
                                    {
                                        $strf.="<id>".$taskArray['tasks']["task"]['workers']['users']["user"]["id"]."</id>";
                                        if ($taskArray['tasks']["task"]['workers']['users']["user"]["id"]!=$PartID) $strf.="<id>".$PartID."</id>";
                                    }
                                    else
                                    {
                                        $x=1;
                                        foreach ($taskArray['tasks']["task"]['workers']['users']["user"] as $userxxx) {
                                            $strf.="<id>".$userxxx["id"]."</id>";
                                            if ($userxxx["id"]==$PartID) $x=0;
                                        }
                                        if($x>0) $strf.="<id>".$PartID."</id>";
                                    }
                                    $strf.="</users>";
                                    $mstask=$taskArray['tasks']['@attributes']['totalCount'];
                                    if ($mstask>1) {
                                        $idTask = $taskArray["tasks"]["task"][0]["id"];
                                        $taskStatus = $taskArray["tasks"]["task"][0]["status"];
                                    } else
                                    {
                                        $idTask = $taskArray["tasks"]["task"]["id"];
                                        $taskStatus = $taskArray["tasks"]["task"]["status"];
                                    }
                                }
                                switch ($taskStatus)
                                {
                                    case 103: break;
                                    default:
                                    {
                                        $ms=0;
                                    }
                                }
                                if ($ms>0) {
                                    $this->updateAuth($userID, $uikName,'Клиент получил посылку', $strf, $idTask);
                                    $this->sendMessage('🆗 Да, все верно, пользователь с УИК '.$uikName.' получал набор набор. Видели ли вы как клиент прошел тест?',$this->menu8);
                                } else
                                {
                                    $this->sendMessage('🙁 Не удалось найти этого пользователя с УИК '.$this->ms.'. Возможно он еще не получал посылку.', $this->menu7);
                                    $this->sendMessage('Пожалуйста, уточните информацию или попробуйте ввести УИК еще раз.', $this->menu7);
                                }
                            break;
                            }
                        }
                }
            }
            else {
                if ($this->access==1) {
                    $this->saveLog();
                    $this->saveAuth();
                    $this->sendMessage('Авторизация прошла успешно! Выберите действия из предложенных в меню.', $this->menu5);
                }
                else {
                    switch ($this->ms) {
                        case '/start':
                            $this->sendMessage('🙋🏻‍♂️ Привет. Я помогу тебе узнать, заказывал ли клиент набор Aman Bol на нашем сайте.', $buttons = null);
                            $this->sendMessage('⌨️ Просто введи свой Login для авторизации и следуй дальнейшим инструкциям.', $buttons = null);
                            break;
                        case '/restart':
                            $this->sendMessage('🙋🏻‍♂️ Привет. Я помогу тебе узнать, заказывал ли клиент набор Aman Bol на нашем сайте.', $buttons = null);
                            $this->sendMessage('⌨️ Просто введи свой Login для авторизации и следуй дальнейшим инструкциям.', $buttons = null);
                            break;
                        case 'Авторизация':
                            $this->sendMessage($this->user . ' введите пожалуйста свой логин', $this->menu4);
                            break;
                         default:
                        {
                            $this->sendMessage($this->user . ' бот не слушает ваши команды. Авторизуйтесь и выполните их снова', $this->menu4);
                        }
                    }
                }
            }
    }

    function sendMessage($message,$buttons = null) {
        $data = array(
            'text' => $message,
            'parse_mode' => 'HTML',
            'chat_id' => $this->iduser
        );

        if($buttons != null) {
            $data['reply_markup'] = [
                'keyboard' => $buttons,
                'resize_keyboard' => true,
                'one_time_keyboard' => true,
                'parse_mode' => 'HTML',
                'selective' => true
            ];
        } else {
        }
        $data_string = json_encode($data);
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function securityStrip($str)
    {
        //$str=trim($str);
        //$str=strip_tags($str);
        $str=str_replace(chr(13),'',$str);
        $str=str_replace(chr(10),'',$str);
        $str=str_replace(" ",'%20',$str);
        $str=str_replace("+",'%2b',$str);
        return $str;
    }

    public function sendFile($IDName, $sort) {
        $file_url=$this->securityStrip("https://amanbol.kz/api/exel.php?user=$IDName&sort=$sort");
        $ch = curl_init($file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $html = curl_exec($ch);
        curl_close($ch);
        file_put_contents(basename($file_url), $html);
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL =>  $this->urldoc.'?caption='.date("d.m.Y").'&chat_id='.$this->chatID,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: multipart/form-data'
            ],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => [
                'document' => curl_file_create(basename($file_url), mime_content_type(basename($file_url)), "$sort.".date('d-m-Y').".xlsx")
            ]
        ]);
        $data = curl_exec($curl);
        curl_close($curl);
        unlink(basename($file_url));
        return $data;
    }
}
?>
