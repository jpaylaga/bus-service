<?php

namespace App\Repositories;

interface CacheRecordRepositoryInterface
{
    public function getKeyPrefix(): string;
    public function getCacheKey($key): string;
    public function getExistingKeys(): array;
    public function clearAll($onSpecificIp = true);
}
