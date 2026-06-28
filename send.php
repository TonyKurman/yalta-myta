<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit(json_encode(['ok' => false]));
}

$name  = trim(strip_tags($_POST['name']  ?? ''));
$phone = trim(strip_tags($_POST['phone'] ?? ''));
$goal  = trim(strip_tags($_POST['goal']  ?? ''));

if (!$name || !$phone) {
    http_response_code(400);
    exit(json_encode(['ok' => false, 'error' => 'missing fields']));
}

$goals = [
    'price'   => 'Узнать стоимость апартаментов',
    'tour'    => 'Записаться на просмотр',
    'consult' => 'Получить консультацию',
];
$goalText = $goals[$goal] ?? $goal;

$to      = 'kurman@otp.msk.ru';
$subject = '=?UTF-8?B?' . base64_encode('Новая заявка — MYTA Ялта') . '?=';
$message = "Новая заявка с сайта yalta-myta.ru\n\n"
         . "Имя:     $name\n"
         . "Телефон: $phone\n"
         . "Цель:    $goalText\n"
         . "\n---\n"
         . date('d.m.Y H:i') . " (МСК)";

$headers  = "From: =?UTF-8?B?" . base64_encode('MYTA Ялта') . "?= <noreply@yalta-myta.ru>\r\n";
$headers .= "Reply-To: $to\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "Content-Transfer-Encoding: base64\r\n";

$ok = mail($to, $subject, base64_encode($message), $headers);

echo json_encode(['ok' => $ok]);
