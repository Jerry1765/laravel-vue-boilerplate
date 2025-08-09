<?php

namespace App\Services;

use App\Models\Setting;
use App\Traits\CacheTrait;
use App\Traits\CanInstantiate;

class SettingsService
{
    use CanInstantiate, CacheTrait;

    /**
     * @return bool
     */
    public function isRegistrationEnabled(): bool
    {
        return $this->cacheRememberForever(CacheKeyService::instance()->getRegistrationEnabledKey(), function () {
            return Setting::query()->where('key', Setting::REG_ENABLED_KEY)->first()->value === '1';
        });
    }

    public function getUploadFileSize(): int
    {
        return $this->cacheRememberForever(CacheKeyService::instance()->getUploadFileSizeKey(), function () {
            return Setting::query()->where('key', Setting::UPLOAD_MAX_SIZE_KEY)->first()->value;
        });
    }
}
