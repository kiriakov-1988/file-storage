<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <title>Загрузка файлов на сервер</title>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container text-center pt-5">

        <h1 class="h3">Выберите файл для загрузки на сервер</h1>
        <p class="text-monospace text-info">Максимальный размер загружаемого файла - 20 Мб.</p>

        <hr>

        <form enctype="multipart/form-data" action="upload.php" method="POST" onsubmit="return checkForm(this)">

            <input type="hidden" name="MAX_FILE_SIZE" value="20971520" /> <!-- 20 MB -->

            <p class="pl-4" style="line-height: 3">
                Отправить этот файл: <input name="userfile" type="file" id="file" />

                <input class="btn btn-outline-success" type="submit" value="Загрузить файл !" />
            </p>

        </form>

<?php
    // Добавлено небольшое удобство для пользователя
    if (isset($_SESSION['uploadFiles']) && !empty($_SESSION['uploadFiles'])) {

            echo '<hr class="mt-5">';

            echo '<h2 class="h4 pt-1">Эти файлы были загружены Вами ранее:</h2>';

            echo '<ul>';
            foreach ($_SESSION['uploadFiles'] as $uploadFile) {
                if ($uploadFile = json_decode($uploadFile, true, 2)) {
                    echo '<li><a href="files.php?file=' . $uploadFile['linkToFile']. '">' . $uploadFile['inputFileName'] . '</a></li>';
                } else {
                    echo '<li class="text-warning">Файл был неправильно сохранен.</li>';
                }
            }
            echo '</ul>';
    }

?>

    </div>

    <script type="text/javascript">
        function checkForm(form){
            if (document.getElementById('file').value=="") {
                document.getElementById('file').classList.add('text-danger');
                document.getElementById('file').classList.add('font-weight-bold');
                return false;
            };
            return true;
        };
    </script>


</body>
</html>