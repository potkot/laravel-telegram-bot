<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Region;
use App\Models\WaterBase;
use App\Models\WaterVolume;
use Illuminate\Database\Seeder;

class AddInitDataSeeder extends Seeder
{

    public function run()
    {
        $regionsJson = json_decode(file_get_contents(resource_path('data/regions.json')), true);
        $waterbasesJson = json_decode(file_get_contents(resource_path('data/waterbases.json')), true);
        $volumesJson = json_decode(file_get_contents(resource_path('data/volumes.json')), true);

        foreach ($regionsJson['data'] as $index => $datum) {

            /**
             * Сохраняем регион
             */
            $region = Region::create([
                'uuid' => $datum['uuid'],
                'name' => $datum['name']
            ]);

            /**
             * Сохраняем его пространства
             */
            $areas = [];
            foreach ($datum['area_names'] as $area_name) {
                $areas[] = new Area(['name' => $area_name]);
            }
            $region->areas()->saveMany($areas);

        }

        /**
         * Пересортируем водобазы по регинам
         */
        $prepareData = [];
        foreach ($waterbasesJson['data'] as $waterbase) {
            $prepareData[$waterbase['region_uuid']][] = $waterbase;
        }

        /**
         * Сохраняем водобазы для каждого региона
         */
        foreach ($prepareData as $regionUUID => $prepareDatum) {
            $region = Region::where('uuid', $regionUUID)->first();
            if ($region) {
                $waterBases = [];

                foreach ($prepareDatum as $index => $item) {
                    $waterBases[] = new WaterBase([
                        'uuid' => $item['uuid'],
                        'name' => $item['name'],
                    ]);
                }

                $region->watersBases()->saveMany($waterBases);
            }
        }

        foreach ($volumesJson['data'] as $index => $datum) {
            WaterVolume::create([
                'water_base_uuid' => $datum['waterbase_uuid'],
                'volume' => $datum['volume'],
            ]);
        }
    }

}
