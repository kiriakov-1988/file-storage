<?php

class Version
{
    const CHARS = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvWwXxYyZz';

    // В принципе этот срок должен быть чуть больше чем срок хранения загруженого файла на сервере
    // при наличии такой опции (автоматического удаления), здесь просто период - 10 дней.
    // Но можно и не округлять - тогда названия файлов вообще врятли будут конфликтовать
    private $periodOfTime;

    // Для еще большей минимизации конфликта названий файлов - добавляется случайная текстовая строка.
    private $lengthOfUniqueStr;

    public function __construct($lengthOfUniqueStr = 5, $periodOfTime = 60*60*24*10)
    {
        $this->lengthOfUniqueStr = $lengthOfUniqueStr;
        $this->periodOfTime = $periodOfTime;
    }

    private function getUniqueId(): string
    {
        $uniqueId = 1;

        // дробная часть "переносится" в целое число.
        $uniqueId *= microtime(true) * 10000;

        // уникальное число в заданный период
        $uniqueId %= (10000 * $this->periodOfTime);

        return (string)($uniqueId);
    }

    private function getUniqStr(): string
    {
        $numChars = mb_strlen(self::CHARS);

        $uniqueStr = '';

        for ($i = 0; $i < $this->lengthOfUniqueStr; $i++) {
            $uniqueStr .= mb_substr(self::CHARS, mt_rand(1, $numChars) - 1, 1);
        }

        return $uniqueStr;
    }

    public function getUniqueVersion(): string
    {
        return $this->getUniqueId() .'-' . $this->getUniqStr() . '-';
    }

}