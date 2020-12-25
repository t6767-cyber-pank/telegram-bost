<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

class reactionUI extends PDO
{
    // Глобальные переменные
	public $url;
    public $urldoc;
	public $chatID;
	public $dt;
	public $nameuser;
    public $idmsg;
    public $userArray;
    public $ms;
    public $menu;
    public $menu0;
    public $menu1;
    public $menu2;
    public $menu3;
    public $menu4;
    public $menu5;
    public $menu6;
    public $menu7;
    public $menu8;

	// Создадим конструктор ебаный Лего
	public function __construct($data, $file = 'my_setting.ini')
    {
        // парсим файл подключения
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable11 to open ' . $file . '.');
        // Создаем подключение к БД
        $dns = $settings['database']['driver'].':host=' . $settings['database']['host'].((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '').';dbname='.$settings['database']['schema'];
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
        // Объявляем глобальные переменные
        $this->url='https://api.telegram.org/xxxxxxxxxxxxxxxxxxxxxxxxxxxxx/sendMessage';
        $this->urldoc='https://api.telegram.org/xxxxxxxxxxxxxxxxxxxxxxxxxx/sendDocument';
        $this->idmsg=$data['message']['message_id'];
        $this->nameuser=$data['message']['from']['first_name'];
        $this->dt=date("Y-m-d H:i:s", $data['message']['date']);
        $this->ms=$data['message']['text'];
        $this->chatID=$data['message']['chat']['id'];
        $this->userArray=$this->getTelegramUser($this->chatID);
        $this->menu=[["Я ошибся"]];
        $this->menu0=[["Начать"]];
        $this->menu1=[["Да"],["Выход"]];
        $this->menu2=[["Инструкция"],["Выход"]];
        $this->menu3=[["Алматы"], ["Нур-Султан"], ["Шымкент"], ["Актау"], ["Актобе"], ["Атырау"], ["Байконур"], ["Караганда"], ["Кошкетау"], ["Костанай"], ["Кызылорда"], ["Павлодар"], ["Петропавловск"], ["Рудный"], ["Семей"], ["Талдыкорган"], ["Тараз"], ["Темиртау"], ["Уральск"], ["Усть-Каменогорск"], ["Экибастуз"], ["Хочу получить набор через курьера (бесплатная доставка)"], ["Выход"]];
        $this->menu4=[["Да"],["Нет"]];
        $this->menu5=[["Выход"]];
        $this->menu6=[["Аноним"], ["Выход"]];
        $this->menu7=[["Подтвердить и отправить"], ["Выход"]];
        $this->menu8=[["Узнать свой УИК"],["Запросить тест повторно"]];
        if ($this->userArray==null) {
            $this->saveUserTelegram();
            $this->sendMessage($this->chatID, "Добро пожаловать!!!", $this->menu0);
        }
        else {
                $this->StartReaction();
            }
    }

    function StartReaction()
    {
        if ($this->userArray["have18"]>0) { $xman=1; } else { $xman=0; }
        if ($this->userArray["muzh"]>0) { $xman=2; }
        if ($this->userArray["agree"]>0) { $xman=3; }
        if ($this->userArray["sekret"]!="") { $xman=4; }
        if ($this->userArray["city"]!="0") { $xman=5; }
        if ($this->userArray["cityagree"]>0) { $xman=6; }
        if ($this->userArray["office"]!="") { $xman=7; }
        if ($this->userArray["name"]!="") { $xman=8; }
        if ($this->userArray["telephone"]!="") { $xman=9; }
        if ($this->userArray["email"]!="") { $xman=10; }
        if ($this->userArray["reg"]>0) { $xman=11; }
        switch ($xman)
        {
            case 0:
                switch ($this->ms)
                {
                    case "Да":
                        $this->updateAge18();
                        $this->sendMessage($this->chatID, "Я мужчина и хотя бы иногда занимаюсь сексом с мужчинами/Я трансгендерный человек", $this->menu1);
                        break;
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        $this->sendMessage($this->chatID, "Мне уже есть 18 лет", $this->menu1);
                    }
                }
                break;
            case 1:
                switch ($this->ms)
                {
                    case "Да":
                        $this->updateMuzh();
                        $this->sendMessage($this->chatID, "Я соглашаюсь со всеми условиями", $this->menu1);
                        break;
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        $this->sendMessage($this->chatID, "Я мужчина и хотя бы иногда занимаюсь сексом с мужчинами/Я трансгендерный человек", $this->menu1);
                    }
                }
                break;
            case 2:
                switch ($this->ms)
                {
                    case "Да":
                        $this->sendMessage($this->chatID, "Секретный код – твой личный код, который знаешь только ты и по которому ты сможешь получить посылку: *", $this->menu2);
                        $this->sendMessage($this->chatID, "Формируется так:", $this->menu2);
                        $this->sendMessage($this->chatID, "XXXX199", $this->menu2);
                        $this->sendMessage($this->chatID, "Первые две буквы полного имени твоей мамы (кириллицей) + Первые две буквы полного имени твоего отца (кириллицей) + 1 + последние две цифры года твоего рождения. Он очень простой, и если забудешь, мы подскажем, как его вспомнить.", $this->menu2);
                        $this->sendMessage($this->chatID, "Пожалуйста введите свой секретный код", $this->menu2);
                        $this->updateAgree();
                        break;
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        $this->sendMessage($this->chatID, "Я соглашаюсь со всеми условиями", $this->menu1);
                    }
                }
                break;
            case 3:
                switch ($this->ms)
                {
                    case "Инструкция":
                        $this->sendMessage($this->chatID, "Секретный код – твой личный код, который знаешь только ты и по которому ты сможешь получить посылку: *", $this->menu2);
                        $this->sendMessage($this->chatID, "Формируется так:", $this->menu2);
                        $this->sendMessage($this->chatID, "XXXX199", $this->menu2);
                        $this->sendMessage($this->chatID, "Первые две буквы полного имени твоей мамы (кириллицей) + Первые две буквы полного имени твоего отца (кириллицей) + 1 + последние две цифры года твоего рождения. Он очень простой, и если забудешь, мы подскажем, как его вспомнить.", $this->menu2);
                        $this->sendMessage($this->chatID, "Пожалуйста введите свой секретный код", $this->menu2);
                        break;
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        $str=preg_split('//u', $this->ms, null, PREG_SPLIT_NO_EMPTY);
                        $enter=1;
                        for ($i=0; $i<4; $i++) {
                            if (preg_match("/^[А-Яа-я]/i", $str[$i])) {
                            } else {
                                $enter=0;
                            }
                        }
                        if ($str[4]!="1") $enter=0;
                        if (preg_match("/^[0-9]/", $str[5])) {} else { $enter=0; }
                        if (preg_match("/^[0-9]/", $str[6])) {} else { $enter=0; }
                        if(strlen($this->ms)!=11) $enter=0;
                        if ($enter>0) {
                            $this->updateSecret(mb_strtoupper($this->ms));
                            $this->sendMessage($this->chatID, "УИК сохранен (Уникальный Идентификационный Код)", $this->menu3);
                            $this->sendMessage($this->chatID, "Листай список городов и выбери свой", $this->menu3);
                        }
                        else {
                            $this->sendMessage($this->chatID, "<b>Ознакомьтесь с инструкцией внимательнее пожалуйста</b>", $this->menu2);
                            $this->sendMessage($this->chatID, "Секретный код – твой личный код, который знаешь только ты и по которому ты сможешь получить посылку: *", $this->menu2);
                            $this->sendMessage($this->chatID, "Формируется так:", $this->menu2);
                            $this->sendMessage($this->chatID, "XXXX199", $this->menu2);
                            $this->sendMessage($this->chatID, "Первые две буквы полного имени твоей мамы (кириллицей) + Первые две буквы полного имени твоего отца (кириллицей) + 1 + последние две цифры года твоего рождения. Он очень простой, и если забудешь, мы подскажем, как его вспомнить.", $this->menu2);
                            $this->sendMessage($this->chatID, "Пожалуйста введите свой секретный код", $this->menu2);
                        }
                    }
                }
                break;
            case 4:
                switch ($this->ms)
                {
                    case "Алматы":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Нур-Султан":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Шымкент":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Актау":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Актобе":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Атырау":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Байконур":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Караганда":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Кошкетау":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Костанай":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Кызылорда":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Павлодар":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Петропавловск":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Рудный":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Семей":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Талдыкорган":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Тараз":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Темиртау":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Уральск":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Усть-Каменогорск":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Экибастуз":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, "Вы находитесь в ".$this->ms, $this->menu4);
                        break;
                    case "Хочу получить набор через курьера (бесплатная доставка)":
                        $this->updateCity($this->ms);
                        $this->sendMessage($this->chatID, $this->ms, $this->menu4);
                        break;
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        $this->sendMessage($this->chatID, "Такой город не обнаружен. Пожалуйста выберите из списка подходящий.", $this->menu3);
                    }
                }
                break;
            case 5:
                switch ($this->ms)
                {
                    case "Да":
                        $this->sendMessage($this->chatID, "Город принят: ".$this->userArray["city"], $this->menu5);
                        switch ($this->userArray["city"])
                        {
                            case "Алматы":
                                $this->menu5=[["Городская поликлиника №10, мкрн. Аксай-4, д.17."],["Комьюнити-центр Safe Space."],["ОФ «Community-Friends» - ул. Наурызбай батыра, 8."],["Хочу получить набор через курьера (бесплатная доставка)."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            case "Нур-Султан":
                                $this->menu5=[["Офис Human Health Institute – ул. Желтоксан, 33/1, oфис 102а."],["Хочу получить набор через курьера (бесплатная доставка)."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            case "Шымкент":
                                $this->menu5=[["Хочу получить набор через курьера (бесплатная доставка)."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            case "Караганда":
                                $this->menu5=[["ОФ ГАЛА."],["Хочу получить набор через курьера (бесплатная доставка)."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            case "Павлодар":
                                $this->menu5=[["ОФ «Герлита»."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            case "Тараз":
                                $this->menu5=[["Ул. Толе би, 64 Г (пересечение с проспектом Жамбыла), каб. №7"],["Хочу получить набор через курьера (бесплатная доставка)."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            case "Талдыкорган":
                                $this->menu5=[["ОО «Амелия», ул. Толебаева 100, офис 54."],["Хочу получить набор через курьера (бесплатная доставка)."]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                break;
                            default:
                            {
                                $this->updateOffice("0");
                                $this->menu5=[["Выход"]];
                                $this->sendMessage($this->chatID, "После заказа ты получишь номер телефона координатора. ", $this->menu5);
                                $this->sendMessage($this->chatID, "Пожалуйста, укажи, как к тебе обращаться? Ты можешь указать свое настоящее имя, а можешь придумать другое. И оставь свои контакты, чтобы мы смогли сообщить тебе о прибытии посылки с тестом:", $this->menu6);
                            }
                        }
                        $this->updateCityagree();
                        break;
                    case "Нет":
                        $this->sendMessage($this->chatID, "Попробуйте выбрать снова город из списка", $this->menu3);
                        $this->updateCity("0");
                        break;
                }
                break;
            case 6:
                $this->updateOffice($this->ms);
                $this->sendMessage($this->chatID, "Пожалуйста, укажи, как к тебе обращаться? Ты можешь указать свое настоящее имя, а можешь придумать другое. И оставь свои контакты, чтобы мы смогли сообщить тебе о прибытии посылки с тестом:", $this->menu6);
                break;
            case 7:
                switch ($this->ms)
                {
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        $this->updateName($this->ms);
                        $this->sendMessage($this->chatID, "Спасибо", $this->menu6);
                        $this->sendMessage($this->chatID, "Пожалуйста введите свой контактный номер. Формат записи следующий:", $this->menu5);
                        $this->sendMessage($this->chatID, "+7XXXXXXXXXX", $this->menu5);
                        $this->sendMessage($this->chatID, "или", $this->menu5);
                        $this->sendMessage($this->chatID, "8XXXXXXXXXX", $this->menu5);
                    }
                }
                break;
            case 8:
                switch ($this->ms)
                {
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        if (preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $this->ms))
                            {
                                $this->updateTel($this->ms);
                                $this->sendMessage($this->chatID, "Спасибо", $this->menu5);
                                $this->sendMessage($this->chatID, "Пожалуйста введите свой email", $this->menu5);
                                $this->sendMessage($this->chatID, "Формат ввода следующий: XXX@XXX.XX", $this->menu5);
                            }
                        else
                            {
                                $this->sendMessage($this->chatID, "Пожалуйста проверьте правьльность ввода номера согласно формату:", $this->menu5);
                                $this->sendMessage($this->chatID, "+7XXXXXXXXXX", $this->menu5);
                                $this->sendMessage($this->chatID, "или", $this->menu5);
                                $this->sendMessage($this->chatID, "8XXXXXXXXXX", $this->menu5);
                            }
                    }
                }
                break;
            case 9:
                switch ($this->ms)
                {
                    case "Выход":
                        $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                        $this->updateToNul();
                        break;
                    default:
                    {
                        if (preg_match("/.+@.+\..+/i", $this->ms)) {
                            $this->updateMail($this->ms);
                            $this->sendMessage($this->chatID, "Спасибо", $this->menu7);
                            $this->sendMessage($this->chatID, "Нажмите <b>Подтвердить и отправить</b> для завершения Формы заказа теста", $this->menu7);
                            $this->sendMessage($this->chatID, "Нажмите <b>Выход</b> если передумали", $this->menu7);
                        } else {
                            $this->sendMessage($this->chatID, "Пожалуйста проверьте правьльность ввода номера согласно формату:", $this->menu5);
                            $this->sendMessage($this->chatID, "XXX@XXX.XX", $this->menu5);
                        }
                    }
                }
                break;
            case 10:
            switch ($this->ms)
            {
                case "Выход":
                    $this->sendMessage($this->chatID, "Приносим извинения, но ваш возраст не позволяет воспользоваться данной услугой", $this->menu);
                    $this->updateToNul();
                    break;
                case "Подтвердить и отправить":
                    $this->updateDate();
                    $this->updateReg();
                    $this->sendMail();
                    $this->sendMessage($this->chatID, "Ваш запрос обработан ", $this->menu8);
                    $this->sendMessage($this->chatID, "Спасибо мы свяжемся с вами в ближающее время. Запомните пожалуйства свой УИК <b>".$this->userArray["sekret"]."</b>", $this->menu8);
                    break;
                case "Запросить тест повторно":
                    $d1=$this->userArray["daterequest"];
                    $d2=date("Y-m-d");
                    if (strtotime($d1)>strtotime($d2))
                    {
                        $this->sendMessage($this->chatID, "В данный мимент вы не можите отправить запрос повторно.", $this->menu8);
                        $this->sendMessage($this->chatID, "Функция будет доступна ".$d1, $this->menu8);
                    } else {
                        $this->sendMail();
                        $this->sendMessage($this->chatID, "Ваш запрос обработан ", $this->menu8);
                        $this->updateDate();
                    }
                    break;
                case "Узнать свой УИК":
                    $this->sendMessage($this->chatID, "Ваш УИК <b>".$this->userArray["sekret"]."</b>", $this->menu8);
                    break;
                default:
                {
                    $this->sendMessage($this->chatID, "Нажмите <b>Подтвердить и отправить</b> для завершения Формы заказа теста", $this->menu7);
                    $this->sendMessage($this->chatID, "Нажмите <b>Выход</b> если передумали", $this->menu7);
                }
            }
            break;
            case 11:
                switch ($this->ms)
                {
                    case "Запросить тест повторно":
                        $d1=$this->userArray["daterequest"];
                        $d2=date("Y-m-d");
                        if (strtotime($d1)>strtotime($d2))
                        {
                            $this->sendMessage($this->chatID, "В данный мимент вы не можите отправить запрос повторно.", $this->menu8);
                            $this->sendMessage($this->chatID, "Функция будет доступна ".$d1, $this->menu8);
                        } else {
                            $this->sendMail();
                            $this->sendMessage($this->chatID, "Ваш запрос обработан ", $this->menu8);
                            $this->updateDate();
                        }
                        break;
                    case "Узнать свой УИК":
                        $this->sendMessage($this->chatID, "Ваш УИК <b>".$this->userArray["sekret"]."</b>", $this->menu8);
                        break;
                    default:
                    {
                        $this->sendMessage($this->chatID, "Выберите действие", $this->menu8);
                    }
                }
                break;
        }
        return 0;
    }

	function saveToBase($name, $res)
    {
        $sql = "INSERT INTO log (name, text) VALUES ('$name','$res')";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function saveUserTelegram()
    {
        $sql = "INSERT INTO userstelegram (telegramid, 	have18, muzh, agree, sekret, city, cityagree, office, name, email, telephone, telegramname, daterequest, reg) VALUES ('".$this->chatID."',0, 0, 0, '', '0', 0, '', '', '', '','".$this->nameuser."', '".date("Y-m-d")."', 0)";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateToNul()
    {
        $sql = "update userstelegram set have18=0, muzh=0, agree=0, sekret='', city='0', cityagree=0, office='', name='', email='', telephone='' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateAge18()
    {
        $sql = "update userstelegram set have18=1 where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateCityagree()
    {
        $sql = "update userstelegram set cityagree=1 where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateOffice($ms)
    {
        $sql = "update userstelegram set office='$ms' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateMuzh()
    {
        $sql = "update userstelegram set muzh=1 where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateAgree()
    {
        $sql = "update userstelegram set agree=1 where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateSecret($ms)
    {
        $sql = "update userstelegram set sekret='$ms' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateName($ms)
    {
        $sql = "update userstelegram set name='$ms' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateTel($ms)
    {
        $sql = "update userstelegram set telephone='$ms' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateMail($ms)
    {
        $sql = "update userstelegram set email='$ms' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateCity($ms)
    {
        $sql = "update userstelegram set city='$ms' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateDate()
    {
        $sql = "update userstelegram set daterequest='".date("Y-m-d", strtotime("+1 day"))."' where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function updateReg()
    {
        $sql = "update userstelegram set reg=1 where telegramid='".$this->chatID."'";
        $query = $this->prepare($sql);
        $query->execute();
    }

    function getTelegramUser($user)
    {
        $stmt = $this->query("SELECT * FROM userstelegram where telegramid='$user'");
        return $stmt->fetch();
    }

    function sendMessage($uid, $message,$buttons = null) {
        $data = array(
            'text' => $message,
            'parse_mode' => 'HTML',
            'chat_id' => $uid
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

    public function sendMail()
    {
        $mail = new PHPMailer(true);
        try {

            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host = 'xxxxxxxxxxxxxxxxx';                    // Set the SMTP server to send through
            $mail->SMTPAuth = true;                                   // Enable SMTP authentication
            $mail->Username = 'xxxxxxxxxxxxxxx';                     // SMTP username
            $mail->Password = 'xxxxxxxxxxxxx';                               // SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
            $mail->Port = 25;    // TCP port to connect to
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->setFrom('xxxxxxxxxxxxxxxxx', $this->userArray["sekret"]." - запрос на тест от ".date("d-m-Y"));
            $mail->addAddress('xxxxxxxxxxxxxxxxx', 'sMM');     // Add a recipient
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $this->userArray["sekret"]." - запрос на тест от ".date("d-m-Y");
            $str="--- Пожалуйста, поставь галочки и подтверди, что: ---";
            $str.="Мне уже есть 18 лет";
            $str.="--- Field ID #2 ---";
            $str.="Я мужчина и хотя бы иногда занимаюсь сексом с мужчинами/Я трансгендерный человек";
            $str.="--- Field ID #18 ---";
            $str.="Я соглашаюсь со всеми условиями";
            $str.="--- Секретный код – твой личный код, который знаешь только ты и по которому ты сможешь получить посылку: ---";
            $str.=$this->userArray["sekret"];
            $str.="--- Выбери свой город: ---";
            $str.=$this->userArray["city"];
            $str.="--- Выбери офис, где тебе удобнее забрать тест: ---";
            $str.=$this->userArray["office"];
            $str.="--- Пожалуйста, укажи, как к тебе обращаться? Ты можешь указать свое настоящее имя, а можешь придумать другое. И оставь свои контакты, чтобы мы смогли сообщить тебе о прибытии посылки с тестом: ---";
            $str.=$this->userArray["name"];
            $str.="--- Оставь свой контактный номер: ---";
            $str.=$this->userArray["telephone"];
            $str.="--- Электронная почта: ---";
            $str.=$this->userArray["email"];
            $mail->Body = $str;
            $mail->AltBody = '';

            $mail->send();
            //   echo "Сообщение успешно отправлено!";
        } catch (Exception $e) {
            //   echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
?>
