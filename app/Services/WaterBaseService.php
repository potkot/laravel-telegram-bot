<?php

namespace App\Services;

use App\Models\Region;
use App\Models\WaterBase;
use App\Models\WaterVolume;
use Illuminate\Support\Collection;

/**
 * Класс для работы с данными по водобазам.
 *
 */
class WaterBaseService
{
    /**
     * Возвращает данные в формате кнопок для телеграм бота
     * @return array
     */
    public function getRegions(): array
    {
        $regions = Region::with(['areas'])->orderBy('name')
            ->get()
            ->map(function (Region $region) {
                return [
                    "text" => $region->areas->pluck('name')->implode(', '),
                    "callback_data" => $region->uuid
                ];
            });

        return $this->byColumns($regions);
    }

    /**
     * Возвращает данные в формате кнопок для телеграм бота
     * @param string $uuid
     * @return array
     */
    public function getWatersBasesByRegion(string $uuid): array
    {
        $watersBases = WaterBase::whereHas('region', function ($q) use ($uuid) {
            $q->where('uuid', $uuid);
        })
            ->get()
            ->map(function (WaterBase $waterBase) {
                return [
                    "text" => $waterBase->name,
                    "callback_data" => $waterBase->uuid
                ];
            });

        return $this->byColumns($watersBases);
    }

    public function getVolumeByWaterBase(string $uuid): string
    {
        $watersVolumes = WaterVolume::where('water_base_uuid', $uuid)->get();
        return $watersVolumes->pluck('volume')->implode(', ');
    }

    /**
     * @param string $uuid
     * @return array
     */
    public function getWaterBaseByUUID(string $uuid): array
    {
        $waterBase = WaterBase::with(['region' => function ($q) {
            $q->with(['areas']);
        }])
            ->where('uuid', $uuid)
            ->first();

        return [
            'name' => $waterBase?->name,
            'region' => $waterBase?->region?->areas?->pluck('name')?->implode(', ')
        ];
    }

    private function byColumns(Collection $data): array
    {
        return array_chunk($data->toArray(), 2);
    }
}
