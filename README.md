# Digium XML Phonebook Generator

This PHP script automatically generates an XML phonebook file for Digium IP phones using data from an Asterisk/FreePBX database. It creates a structured and editable contact list that supports SIP/BLF features.

## Features

- Pulls user data from the Asterisk MySQL database
- Excludes specific extensions and names via config files, also exclude contacts which contain name *FREE* or *free*
- Customizable contact group name (`config.ini`)
- Automatically saves the resulting XML file to Digium's directory
- Reloads Digium phone settings via Asterisk CLI

## Files

- `contacts.php` — main script
- `config.ini` — optional custom group name
- `exclude.ini` — optional list of extensions to exclude
- `all-contacts.xml` — generated contact file

## Requirements

- PHP 5.6 or later
- MySQL (used by FreePBX)
- Asterisk with Digium Phones Addon Module
- Apache or any web server with PHP

## License

This project is licensed under the terms of the [GNU GPL v3](LICENSE).

---

**Author:** Alexey Astashov  
**Year:** 2025

---
# Генератор XML телефонной книги для Digium

Этот PHP-скрипт автоматически генерирует XML-файл телефонной книги для IP-телефонов Digium, используя данные из базы данных Asterisk/FreePBX. Он создаёт структурированный и редактируемый список контактов с поддержкой SIP и BLF (индикации занятости).

## Возможности

- Извлечение данных пользователей из MySQL-базы FreePBX
- Исключение определённых внутренних номеров и имён через конфигурационные файлы, а также номеров с именем FREE
- Поддержка кастомного имени группы контактов (`config.ini`)
- Сохранение готового XML-файла в директорию Digium-телефонов
- Автоматическая перезагрузка настроек телефонов через Asterisk CLI

## Используемые файлы

- `contacts.php` — основной скрипт
- `config.ini` — необязательное имя группы контактов
- `exclude.ini` — список внутренних номеров для исключения
- `all-contacts.xml` — сгенерированный файл с контактами

## Требования

- PHP 5.6 или новее
- MySQL (используется в FreePBX)
- Asterisk с модулем Digium Phones Addon
- Веб-сервер с поддержкой PHP (например, Apache)

## Запуск из консоли
```
php contacts.php
```

Также можно запускать по *cron*



## Лицензия

Проект распространяется на условиях лицензии [GNU GPL v3](LICENSE).

---

**Автор:** Алексей Асташов  
**Год:** 2025
