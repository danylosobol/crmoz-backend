<?php

namespace App\Enums\Setting;

enum Key: string
{
    case CLIENT_ID = 'client_id';
    case CLIENT_SECRET = 'client_secret';
    case REDIRECT_URI = 'redirect_uri';
    case ACCOUNT_URL = 'account_url';
    case API_DOMAIN = 'api_domain';
    case ACCESS_TOKEN = 'access_token';
    case REFRESH_TOKEN = 'refresh_token';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
