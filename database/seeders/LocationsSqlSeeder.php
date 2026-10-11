<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class LocationsSqlSeeder extends Seeder
{
    public function run()
    {
        $this->seedCities();
        $this->seedRegions();
    }

    private function seedCities()
    {
        $rows = $this->readInsertRows(database_path('seeders/data/cities.sql'), 'cities');

        DB::table('cities')->upsert(
            array_map(function ($row) {
                return [
                    'id' => (int) $row[0],
                    'name_en' => $row[1],
                    'name_ar' => $row[2],
                    'country_id' => $this->nullableInt($row[3]),
                    'status' => (int) $row[4],
                    'created_by' => $this->nullableInt($row[5]),
                    'deleted_at' => $this->nullableDate($row[6]),
                    'created_at' => $this->nullableDate($row[7]),
                    'updated_at' => $this->nullableDate($row[8]),
                ];
            }, $rows),
            ['id'],
            ['name_en', 'name_ar', 'country_id', 'status', 'created_by', 'deleted_at', 'updated_at']
        );

        $this->resetAutoIncrement('cities');
    }

    private function seedRegions()
    {
        $rows = $this->readInsertRows(database_path('seeders/data/regions.sql'), 'regions');

        DB::table('regions')->upsert(
            array_map(function ($row) {
                return [
                    'id' => (int) $row[0],
                    'name_en' => $row[1],
                    'name_ar' => $row[2],
                    'city_id' => $this->nullableInt($row[3]),
                    'status' => (int) $row[4],
                    'deleted_at' => $this->nullableDate($row[5]),
                    'created_at' => $this->nullableDate($row[6]),
                    'updated_at' => $this->nullableDate($row[7]),
                ];
            }, $rows),
            ['id'],
            ['name_en', 'name_ar', 'city_id', 'status', 'deleted_at', 'updated_at']
        );

        $this->resetAutoIncrement('regions');
    }

    private function readInsertRows($path, $table)
    {
        if (! file_exists($path)) {
            throw new RuntimeException("Seed data file not found: {$path}");
        }

        $sql = file_get_contents($path);
        $pattern = "/INSERT INTO `{$table}` \\([^\\)]*\\) VALUES\\s*(.*?);/s";

        if (! preg_match($pattern, $sql, $matches)) {
            return [];
        }

        preg_match_all('/\\((.*?)\\)(?:,|$)/s', $matches[1], $tuples);

        return array_map(function ($tuple) {
            return array_map(function ($value) {
                $value = trim($value);

                return strtoupper($value) === 'NULL' ? null : $value;
            }, str_getcsv($tuple, ',', "'", '\\'));
        }, $tuples[1]);
    }

    private function nullableInt($value)
    {
        return $value === null ? null : (int) $value;
    }

    private function nullableDate($value)
    {
        return $value === null ? null : $value;
    }

    private function resetAutoIncrement($table)
    {
        $nextId = ((int) DB::table($table)->max('id')) + 1;
        DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = {$nextId}");
    }
}
