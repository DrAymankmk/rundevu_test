<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class WorldLocationsSeeder extends Seeder
{
    private const STATE_NAME_LIMIT = 100;
    private const CITY_NAME_LIMIT = 100;

    public function run()
    {
        DB::disableQueryLog();

        $stateIds = $this->seedStates();
        $this->seedCities($stateIds);
    }

    private function seedStates()
    {
        $path = storage_path('app/seed-data/world-states.csv');
        $countryIds = $this->countryIdsByCode();
        $existingStates = $this->existingRowsByNameAndParent('cities', 'country_id');
        $stateIds = [];

        $this->readCsv($path, function ($row) use ($countryIds, &$existingStates, &$stateIds) {
            $countryCode = strtoupper($row['country_code'] ?? '');

            if (! isset($countryIds[$countryCode])) {
                return;
            }

            $countryId = $countryIds[$countryCode];
            $nameEn = $this->limit($row['name'] ?? '', self::STATE_NAME_LIMIT);
            $nameAr = $this->limit(($row['native'] ?? '') ?: $nameEn, self::STATE_NAME_LIMIT);

            if ($nameEn === '') {
                return;
            }

            $key = $this->rowKey($countryId, $nameEn);
            $values = [
                'name_en' => $nameEn,
                'name_ar' => $nameAr,
                'country_id' => $countryId,
                'status' => 1,
            ];

            if (! empty($existingStates[$key])) {
                $id = array_shift($existingStates[$key]);
                DB::table('cities')->where('id', $id)->update($values);
                $stateIds[$row['id']] = $id;

                return;
            }

            $id = DB::table('cities')->insertGetId($values);
            $stateIds[$row['id']] = $id;
        });

        return $stateIds;
    }

    private function seedCities(array $stateIds)
    {
        $path = storage_path('app/seed-data/world-cities.csv.gz');
        $existingCities = $this->existingRowsByNameAndParent('regions', 'city_id');
        $rows = [];
        $now = now();

        $this->readCsv($path, function ($row) use ($stateIds, &$existingCities, &$rows, $now) {
            $sourceStateId = $row['state_id'] ?? null;

            if (! $sourceStateId || ! isset($stateIds[$sourceStateId])) {
                return;
            }

            $stateId = $stateIds[$sourceStateId];
            $nameEn = $this->limit($row['name'] ?? '', self::CITY_NAME_LIMIT);
            $nameAr = $this->limit(($row['native'] ?? '') ?: $nameEn, self::CITY_NAME_LIMIT);

            if ($nameEn === '') {
                return;
            }

            $key = $this->rowKey($stateId, $nameEn);

            $values = [
                'name_en' => $nameEn,
                'name_ar' => $nameAr,
                'city_id' => $stateId,
                'status' => 1,
                'deleted_at' => null,
                'updated_at' => $now,
            ];

            if (! empty($existingCities[$key])) {
                $id = array_shift($existingCities[$key]);
                DB::table('regions')->where('id', $id)->update($values);

                return;
            }

            $existingCities[$key][] = true;
            $rows[] = [
                'name_en' => $values['name_en'],
                'name_ar' => $values['name_ar'],
                'city_id' => $values['city_id'],
                'status' => $values['status'],
                'deleted_at' => $values['deleted_at'],
                'created_at' => $now,
                'updated_at' => $values['updated_at'],
            ];

            if (count($rows) >= 1000) {
                DB::table('regions')->insert($rows);
                $rows = [];
            }
        });

        if ($rows) {
            DB::table('regions')->insert($rows);
        }
    }

    private function countryIdsByCode()
    {
        return DB::table('countries')
            ->pluck('id', 'code')
            ->mapWithKeys(function ($id, $code) {
                return [strtoupper($code) => $id];
            })
            ->all();
    }

    private function existingRowsByNameAndParent($table, $parentColumn)
    {
        $rows = DB::table($table)->select('id', 'name_en', $parentColumn)->get();
        $existing = [];

        foreach ($rows as $row) {
            if ($row->{$parentColumn} === null) {
                continue;
            }

            $existing[$this->rowKey($row->{$parentColumn}, $row->name_en)][] = $row->id;
        }

        return $existing;
    }

    private function readCsv($path, callable $callback)
    {
        if (! file_exists($path)) {
            throw new RuntimeException("Missing world locations data file: {$path}");
        }

        $isGzip = substr($path, -3) === '.gz';
        $handle = $isGzip ? gzopen($path, 'r') : fopen($path, 'r');

        if (! $handle) {
            throw new RuntimeException("Unable to open world locations data file: {$path}");
        }

        $headers = $isGzip ? fgetcsv($handle) : fgetcsv($handle);

        while (($values = ($isGzip ? fgetcsv($handle) : fgetcsv($handle))) !== false) {
            if (count($values) !== count($headers)) {
                continue;
            }

            $callback(array_combine($headers, $values));
        }

        $isGzip ? gzclose($handle) : fclose($handle);
    }

    private function rowKey($parentId, $name)
    {
        return $parentId . '|' . mb_strtolower(trim($name), 'UTF-8');
    }

    private function limit($value, $limit)
    {
        return mb_substr(trim((string) $value), 0, $limit, 'UTF-8');
    }
}
