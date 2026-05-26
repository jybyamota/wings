<?php

declare(strict_types=1);

const SITE_NAME = 'Wing Master';
const SITE_TAGLINE = 'Wing Master Silog Samal';
const SITE_PHONE = '0909 163 9984';
const SITE_EMAIL = 'wingmastersilogsamal@gmail.com';
const SITE_FACEBOOK = 'Wing Master Silog Samal';

const SITE_HOURS = 'Mon–Thu 11am–10pm · Fri–Sun 11am–12am';

function page_url(string $page = 'index.php', string $hash = ''): string
{
    $hashPart = $hash !== '' ? '#' . ltrim($hash, '#') : '';

    return $page . $hashPart;
}
