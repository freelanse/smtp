<?php
 
 $data = $_POST;
if (empty($data)) {
    $inputJSON = file_get_contents('php://input');
    $data = json_decode($inputJSON, true);
}

if (!empty($data)) {

    $to = "";
    $subject = "Новое сообщение с формы сайта";

    $message = "Получено новое сообщение с сайта:\n\n";

    foreach ($data as $key => $value) {
        $key = htmlspecialchars($key);
        $value = htmlspecialchars($value);
        $message .= "$key: $value\n";
    }

    $headers = "From: no-reply@" . $_SERVER['SERVER_NAME'] . "\r\n";
    $headers .= "Reply-To: no-reply@" . $_SERVER['SERVER_NAME'] . "\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo json_encode(["status" => "success", "message" => "Сообщение отправлено!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Ошибка отправки сообщения."]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "Данные формы не получены."]);
}
