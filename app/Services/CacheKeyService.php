<?php

namespace App\Services;

use App\Traits\CanInstantiate;

class CacheKeyService
{
    use CanInstantiate;

    public function getEventKey(int $eventId): string
    {
        return 'event:' . $eventId;
    }

    public function getUpcomingEventsKey(): string
    {
        return 'upcoming_events';
    }

    public function getAllEventsKey(): string
    {
        return 'all_events';
    }

    public function getRegistrationEnabledKey(): string
    {
        return $this->getSettingsKey() . ':registration_enabled';
    }

    public function getUploadFileSizeKey(): string
    {
        return $this->getSettingsKey() . ':upload_file_size';
    }

    public function getSettingsKey(): string
    {
        return 'app_settings';
    }

    public function getFailedAttemptsKey(string $email): string
    {
        return 'failed_attempts:'.$email;
    }

}
