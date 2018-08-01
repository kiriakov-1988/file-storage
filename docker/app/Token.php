<?php

class Token
{
    private $uniqueFileName;

    private $lengthOfHex;

    public function __construct($uniqueFileName, $lengthOfHex = 64)
    {
        $this->uniqueFileName = $uniqueFileName;
        $this->lengthOfHex = $lengthOfHex;
    }

    public function getAuthToken()
    {
        $n = $this->lengthOfHex - mb_strlen($this->uniqueFileName);

        if ($n <= 0)
        {
            // будет хоть один дополнительный байт
            $n = 1;
        }

        $salt = random_bytes($n);

        // символы в строке перемешиваются, иначе одинаковая последовательность
        // символов (часть) в названии "нового" файла будут одинаково кодироваться
        $string = str_shuffle($this->uniqueFileName . $salt);

        return bin2hex($string);
    }
}