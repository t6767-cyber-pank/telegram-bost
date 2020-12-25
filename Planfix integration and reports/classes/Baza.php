<?php
class Baza extends PDO
{
    public function __construct($file = 'my_setting.ini')
    {
        // парсим файл подключения
        if (!$settings = parse_ini_file($file, TRUE)) throw new exception('Unable11 to open ' . $file . '.');
        // Создаем подключение к БД
        $dns = $settings['database']['driver'].':host=' . $settings['database']['host'].((!empty($settings['database']['port'])) ? (';port=' . $settings['database']['port']) : '').';dbname='.$settings['database']['schema'];
        parent::__construct($dns, $settings['database']['username'], $settings['database']['password']);
    }

    function getAllFields($id, $sort)
    {
        if ($sort=='week')
        {
            $today = date('w');
            $mon  = mktime(0, 0, 0, date("m")  , date("d")-$today+1, date("Y"));
            $sun  = mktime(0, 0, 0, date("m")  , date("d")+(8-$today), date("Y"));
            $dt1=date('Y-m-d',$mon);
            $dt2=date('Y-m-d',$sun);
        }
        else
        {
            $mon  = mktime(0, 0, 0, date("m")  , "01", date("Y"));
            $sun  = mktime(0, 0, 0, date("m")+1  , "01", date("Y"));
            $dt1=date('Y-m-d',$mon);
            $dt2=date('Y-m-d',$sun);
        }
        $stmt = $this->query("SELECT * FROM resultsWork where partnerID='$id' and date_send between '$dt1' and '$dt2' order by date_send");
        $arr=array();
        while ($row = $stmt->fetch())
        {
            array_push($arr, $row);
        }
        return $arr;
    }
}
?>