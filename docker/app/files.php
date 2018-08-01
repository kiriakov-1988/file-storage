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

    if (!isset($_GET['file']) || empty($_GET['file'])) {
        echo '<h1 class="text-danger">Ошибка - укажите адрес файла для скачивания!</h1>';
    } else {

        $linkToFile = trim($_GET['file']);

        if (!preg_match('/^[0-9abcdef]+$/', $linkToFile)) {
            echo '<h1 class="text-danger">Ошибка - запрещенные символы в адресе файла!</h1>';
        } else {

            require_once('InfoLoader.php');
            $loader = new InfoLoader();

            $uploadDir = InfoLoader::UPLOAD_DIR . DIRECTORY_SEPARATOR;

            $file = $loader->getInfoAboutFile($linkToFile);

            if (isset($file['error'])) {
                echo $file['error'];

            } else {
                $fileUrl = $uploadDir . $file['userFileName'];
                $fileName = $file['uploadFileName'];

                echo '<h1 class="h2 text-success">Запрашиваемый файл существует!</h1>';

                $_SESSION['fileUrl'] = $fileUrl;
                $_SESSION['fileName'] = $fileName;

                $nSize = filesize($fileUrl);
                $createTime = date("F d Y H:i:s.", filectime($fileUrl));

                echo "<p>Скачать файл - <a href='download.php'>$fileName</a></p>";
                echo "<p class='text-monospace'>Файл загружен - <i>$createTime</i> Размер - <b>$nSize</b> <i>B</i>.</p>";

                echo "<p>Вернуться на - <a href='/'>Главную страницу</a></p>";

            }

        }

    }

?>

    </div>

</body>
</html>