<?php

session_start();

if ( isset($_SESSION['fileUrl']) && !empty($_SESSION['fileUrl']) && isset($_SESSION['fileName']) && !empty($_SESSION['fileName']) ) {
    $fileUrl = $_SESSION['fileUrl'];
    $fileName = $_SESSION['fileName'];

    $file = ($fileUrl);
    header ("Content-Type: application/octet-stream");
    header ("Accept-Ranges: bytes");
    header ("Content-Length: ".filesize($file));
    // пользователю отдается файл с исходным названием, правда с выполненой транслетирацией букв
    header ("Content-Disposition: attachment; filename=".$fileName);
    readfile($file);

    unset($_SESSION['fileUrl']);
    unset($_SESSION['fileName']);

} else {
    header("HTTP/1.0 404 Not Found");
}



