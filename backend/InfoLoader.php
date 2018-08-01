<?php

class InfoLoader
{
    const UPLOAD_DIR = 'uploads';

    private $dirWithFileList = 'filelist';

    public function __construct()
    {
        if (!file_exists($this->dirWithFileList)) {
            mkdir($this->dirWithFileList, 0774);

            $handleFile = fopen($this->dirWithFileList.DIRECTORY_SEPARATOR.'index.php', "w");
            fwrite($handleFile, '<?php header("HTTP/1.0 404 Not Found");');
            fclose($handleFile);
        }

        if (!file_exists(self::UPLOAD_DIR)) {
            mkdir(self::UPLOAD_DIR, 0774);

            $handleFile = fopen(self::UPLOAD_DIR.DIRECTORY_SEPARATOR.'index.php', "w");
            fwrite($handleFile, '<?php header("HTTP/1.0 404 Not Found");');
            fclose($handleFile);
        }

    }

    private function getNameInfoFile($linkToFile): string
    {
        return "$this->dirWithFileList". DIRECTORY_SEPARATOR . "{$linkToFile}.txt";
    }

    public function setInfoAboutFile($linkToFile, $userFileName, $uploadFileName): bool
    {
        if ($handleFile = fopen($this->getNameInfoFile($linkToFile), "w")) {

            $string = json_encode([
                'userFileName' => $userFileName,
                'uploadFileName' => $uploadFileName,
            ], 0, 1);

            if (fwrite($handleFile, $string)) {
                fclose($handleFile);
                return true;

            } else {
                // если записать инфо не получилось,
                // то закрыть инфофайл и удалить его
                fclose($handleFile);
                unlink($this->getNameInfoFile($linkToFile));

                // так же удаляем загруженный файл, так как к нему не будет доступа
                unlink(self::UPLOAD_DIR . DIRECTORY_SEPARATOR . $userFileName);

                return false;
            }

        } else {
            // ошибка при открытии/создании инфофайла

            // так же удаляем загруженный файл, так как к нему не будет доступа
            unlink(self::UPLOAD_DIR . DIRECTORY_SEPARATOR . $userFileName);

            return false;
        }
    }

    public function getInfoAboutFile($linkToFile): array
    {
        $fileWithInfo = $this->getNameInfoFile($linkToFile);

        if (file_exists($fileWithInfo)) {

            if ($handleFile = fopen($fileWithInfo, "r")) {

                $buffer = fgets($handleFile);

                if ($arr = json_decode($buffer, true, 2)) {
                    fclose($handleFile);

                    return $arr;
                } else {
                    fclose($handleFile);

                    return [
                        'error' => '<h1 class="h2 text-warning">Ошибка при формировании ссылки для скачивания - некорректный файл!</h1>'
                    ];
                }

            } else {
                return [
                    'error' => '<h1 class="h2 text-warning">Ошибка при формировании ссылки для скачивания - попробуйте позже!</h1>'
                ];
            }

        } else {
            return [
                'error' => '<h1 class="h2 text-danger">Файл отсутствует - неправильная ссылка!</h1>'
            ];
        }
    }
}