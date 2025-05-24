<?php
// Настройки подключения к MySQL
$db_host = 'localhost';
$db_user = 'freepbxuser';
$db_pass = 'your_password_to_freepbx_db';
$db_name = 'asterisk';

// Загружаем config.ini
$config_ini = __DIR__ . '/config.ini';
$group_name = 'Phonebook';

if (file_exists($config_ini)) {
    $cfg = parse_ini_file($config_ini, true);
    if (!empty($cfg['settings']['group_name'])) {
        $group_name = $cfg['settings']['group_name'];
    }
}

// Загружаем exclude.ini
$exclude_file = __DIR__ . '/exclude.ini';
$excluded = [];

if (file_exists($exclude_file)) {
    $config = parse_ini_file($exclude_file, true);
    if (isset($config['exclude']['numbers'])) {
        $excluded = $config['exclude']['numbers'];
    }
}

// Подключение к БД
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}

// Получаем пользователей
$sql = "SELECT extension, name FROM users";
$result = $mysqli->query($sql);

$entries = [];
while ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    $ext = $row['extension'];

    // Исключения
    if (in_array($ext, $excluded)) {
        continue;
    }
    if (stripos($name, 'free') !== false) {
        continue;
    }

    $entries[] = [
        'extension' => $ext,
        'name' => $name
    ];
}

// Сортировка по номеру
usort($entries, function ($a, $b) {
    return (int)$a['extension'] <=> (int)$b['extension'];
});

// Создаём XML
$dom = new DOMDocument('1.0', 'UTF-8');
$dom->formatOutput = true;

// Создаём корневой <contacts> с group_name
$contacts = $dom->createElement('contacts');
$contacts->setAttribute('group_name', $group_name);
$contacts->setAttribute('editable', '0');
$contacts->setAttribute('id', 'internal');
$dom->appendChild($contacts);

// Контакты
foreach ($entries as $entry) {
    $ext = $entry['extension'];
    $name = htmlspecialchars($entry['name']);

    $contact = $dom->createElement('contact');
    $contact->setAttribute('id', $ext);
    $contact->setAttribute('prefix', '');
    $contact->setAttribute('first_name', $name);
    $contact->setAttribute('second_name', '');
    $contact->setAttribute('last_name', '');
    $contact->setAttribute('suffix', '');
    $contact->setAttribute('organization', '');
    $contact->setAttribute('job_title', '');
    $contact->setAttribute('location', '');
    $contact->setAttribute('notes', '');
    $contact->setAttribute('contact_type', 'sip');
    $contact->setAttribute('account_id', $ext);
    $contact->setAttribute('subscribe_to', "$ext");

    $actions = $dom->createElement('actions');
    $action = $dom->createElement('action');
    $action->setAttribute('id', 'primary');
    $action->setAttribute('dial', $ext);
    $action->setAttribute('label', 'CL_ACTN_SIP');
    $action->setAttribute('name', 'CN_ACTN_DIAL');
    $action->setAttribute('transfer_name', 'CN_ACTN_TRANSFER');
    $actions->appendChild($action);
    $contact->appendChild($actions);

    $contacts->appendChild($contact);
}

// Сохраняем
$xml_file = '/var/www/html/digium_phones/all-contacts.xml';
$dom->save($xml_file);

echo "Файл создан: $xml_file (группа: $group_name)\n";
$mysqli->close();
exec("asterisk -rx 'digium_phones reconfigure all'");

?>
