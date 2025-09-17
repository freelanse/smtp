<?php
 

require_once(__DIR__ . '/../../../wp-load.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Получаем JSON-данные
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
$isJson = strpos($contentType, 'application/json') !== false;
$data = $isJson ? json_decode(file_get_contents('php://input'), true) : $_POST;
if (!is_array($data)) $data = [];

// ====================  НАСТРОЙКИ  ====================
$defaultRecipients = [
    'email@gmail.com',
    
];

$routes = [
    '/uslugi/arenda-legkovyh-avtomobilej-s-voditelem' => ['email2@gmail.com'],
    '/uslugi/passazhirskie-perevozki'                => ['email3@gmail.com'],
 
];
// =====================================================

// Берём страницу напрямую из поля current_page
$sourceUrl = $data['current_page'] ?? '';
$cleanPath = '';
if (!empty($sourceUrl)) {
    $pathInfo = parse_url($sourceUrl, PHP_URL_PATH);
    $cleanPath = strtolower(rtrim($pathInfo, '/'));
    if ($cleanPath === '') $cleanPath = '/';
}

// Формируем список получателей
$recipients = $defaultRecipients;
if (!empty($cleanPath) && isset($routes[$cleanPath])) {
    $recipients = array_merge($recipients, $routes[$cleanPath]);
}
$recipients = array_unique($recipients);
$finalRecipientsString = implode(',', $recipients);

// Формируем тело письма
$emailSubject = 'Новая заявка с сайта ' . get_bloginfo('name');
$emailBody  = "<h1>" . esc_html($emailSubject) . "</h1>";
$emailBody .= "<p><strong>Страница отправки:</strong> " . esc_html($sourceUrl) . "</p>";
$emailBody .= "<p><strong>Определенный маршрут:</strong> " . esc_html($cleanPath) . "</p>";
$emailBody .= "<table style='border-collapse:collapse;width:100%;border:1px solid #ddd;'>
<thead><tr style='background:#f2f2f2'>
<th style='padding:8px;border:1px solid #ddd;'>Поле</th>
<th style='padding:8px;border:1px solid #ddd;'>Значение</th></tr></thead><tbody>";

foreach ($data as $key => $value) {
    if (in_array($key, ['action','nonce','current_page'], true)) continue;
    $fieldValue = is_array($value) ? implode(', ', $value) : $value;
    $emailBody .= "<tr><td style='padding:8px;border:1px solid #ddd;'>"
               . esc_html(ucwords(str_replace('_', ' ', $key))) . "</td><td style='padding:8px;border:1px solid #ddd;'>"
               . nl2br(esc_html($fieldValue)) . "</td></tr>";
}
$emailBody .= "</tbody></table>";

$headers = ['Content-Type: text/html; charset=UTF-8'];

if (wp_mail($finalRecipientsString, $emailSubject, $emailBody, $headers)) {
    http_response_code(200);
    echo json_encode(['status'=>'success','message'=>'Ваше сообщение успешно отправлено!']);
} else {
    http_response_code(500);
    echo json_encode(['status'=>'error','message'=>'Произошла ошибка при отправке.']);
}
exit;

