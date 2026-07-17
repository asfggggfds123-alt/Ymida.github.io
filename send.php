<?php
header('Content-Type: application/json');

// ========== НАСТРОЙКИ ==========
$to_email = 'Umida.centr@gmail.com'; // ← ВАША ПОЧТА

// =================================================

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$message = trim($_POST['message'] ?? '');

if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Имя и Email обязательны']);
    exit;
}

$text = "📩 *Новая заявка*\n\n";
$text .= "👤 Имя: $name\n";
$text .= "📧 Email: $email\n";
$text .= "📞 Телефон: " . ($phone ?: 'не указан') . "\n";
$text .= "💬 Сообщение: " . ($message ?: 'не указано') . "\n";

// Отправка на Email
mail($to_email, 'Заявка с сайта Юмида', $text, "From: $email\r\nContent-Type: text/plain; charset=utf-8");

// Отправка в Telegram
if (!empty($telegram_token) && !empty($telegram_chat_id)) {
    $url = "https://api.telegram.org/bot$telegram_token/sendMessage";
    $data = ['chat_id' => $telegram_chat_id, 'text' => $text, 'parse_mode' => 'Markdown'];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_exec($ch);
    curl_close($ch);
}

// (Здесь можно добавить WhatsApp и VK по аналогии)

echo json_encode(['success' => true]);
?>