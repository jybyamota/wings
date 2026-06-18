<?php

declare(strict_types=1);

const SITE_NAME = 'Wing Master';
const SITE_TAGLINE = 'Wing Master Silog Samal';
const SITE_PHONE = '0909 163 9984';
const SITE_EMAIL = 'wingmastersilogsamal@gmail.com';
const SITE_FACEBOOK = 'Wing Master Silog Samal';

const SITE_HOURS = 'Mon–Thu 11am–10pm · Fri–Sun 11am–12am';

const DB_HOST = '127.0.0.1';
const DB_PORT = 3307;
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'wing_master';

function db_connect(): mysqli
{
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $db = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    $db->set_charset('utf8mb4');
    return $db;
}

function page_url(string $page = 'index.php', string $hash = ''): string
{
    $hashPart = $hash !== '' ? '#' . ltrim($hash, '#') : '';

    return $page . $hashPart;
}
