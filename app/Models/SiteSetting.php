<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    public static function locale(): string
    {
        $locale = self::query()
            ->where('key', 'site_locale')
            ->value('value');

        return in_array($locale, ['fr', 'ar'], true) ? $locale : 'fr';
    }
}
