<?php
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Статус загрузки файла</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="text-center pt-5">

<?php

    if (!isset($_FILES['userfile']) || empty($_FILES['userfile'])) {

        echo '<h1 class="text-danger">Не был выбран файл для загрузки <br> или файл превышает допустимый размер !</h1>';

    } else {

        if ($_FILES['userfile']['error']) {
            echo '<h2 class="text-danger">Возникла ошибка при загрузке файла, скорее всего превышен максимальный размер файла.</h2>';
        } else {

            require_once('Version.php');
            require_once('Transliterate.php');
            require_once('InfoLoader.php');
            require_once('Token.php');

            $version = new Version();
            $transliterate = new Transliterate();
            $loader = new InfoLoader();

            $uploadDir = InfoLoader::UPLOAD_DIR . DIRECTORY_SEPARATOR;

            $inputFileName = htmlspecialchars(trim($_FILES['userfile']['name']));

            $uniqueFileName = $version->getUniqueVersion() .
                $transliterate->getTransliterateStr($inputFileName);

            $token = new Token($uniqueFileName);
            $linkToFile = $token->getAuthToken();

            $uploadFile = $uploadDir . $uniqueFileName;


            if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadFile)) {

                chmod($uploadFile, 0444);

                if ($loader->setInfoAboutFile($linkToFile , $uniqueFileName, $inputFileName)) {
                    echo '<h2 class="text-success">Файл был успешно загружен.</h2>';

                    echo "<p>Сссылка для скачивания Вашего файла - <a href='files.php?file=$linkToFile'>$inputFileName</a></p>";

                    // если вдруг этот файл будет находиться не в корне сайта
                    $protocol = 'http://';
                    if ($_SERVER['HTTPS']) {
                        $protocol = 'https://';
                    }
                    $html_path = $protocol . $_SERVER['HTTP_HOST'];
                    $html_path .= substr($_SERVER['REQUEST_URI'], 0, strripos($_SERVER['REQUEST_URI'],'/')) ;

                    echo "<p class='text-monospace p-2 mx-2' style='background-color:silver;'>
                        Или полный адрес: <br> 
                        <a href='files.php?file={$linkToFile}'>{$html_path}/files.php?file={$linkToFile}</a>
                      </p>";

                    echo "<p class='text-monospace'>Вернуться на - <a href='/'>Главную страницу</a></p>";

                    // Небольшое удобство для пользователя - на главной отображаются текущие загруженные им файлы
                    $forCookie = json_encode([
                        'linkToFile' => $linkToFile,
                        'inputFileName' => $inputFileName
                    ], 0, 1);
                    $_SESSION['uploadFiles'][] = $forCookie;

                } else {
                    echo '<h2 class="text-warning">Возникла ошибка при формировании обратной ссылки для скачивания файла.</h2>';
                }

            } else {

                echo '<h2 class="text-warning">Возникла ошибка при загрузке файла.</h2>';

            }

        }
    }

?>
    </div>

</body>
</html>


