<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait Bilingual
{
    public function getNameAttribute($value): mixed
    {
        return $this->localize('name', $value);
    }

    public function getDescriptionAttribute($value): mixed
    {
        return $this->localize('description', $value);
    }

    protected function localize(string $field, mixed $value): mixed
    {
        // Only localize for API requests (mobile app), not admin panel
        $request = request();
        if (!$request || !str_starts_with($request->path(), 'api/')) {
            return $value;
        }

        $locale = App::getLocale();
        $enField = $field . '_en';
        $arField = $field . '_ar';

        if ($locale === 'ar' && isset($this->attributes[$arField]) && !empty($this->attributes[$arField])) {
            return $this->attributes[$arField];
        }

        // If English, prefer _en field, fallback to original value
        if (isset($this->attributes[$enField])) {
            return $this->attributes[$enField];
        }

        return $value;
    }
}
