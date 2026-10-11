<?php

require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Support\Str;

if ($argc < 3) {
    fwrite(STDERR, "Usage: php tools/add_clinic_slugs_to_sql.php input.sql output.sql\n");
    exit(1);
}

[$script, $inputPath, $outputPath] = $argv;

$sql = file_get_contents($inputPath);
if ($sql === false) {
    fwrite(STDERR, "Unable to read input file: {$inputPath}\n");
    exit(1);
}

$stats = [
    'insert_statements' => 0,
    'clinic_rows' => 0,
    'slugs_added' => 0,
];

$sql = preg_replace(
    "/(`name` varchar\\(191\\) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,\\R)/",
    "$1  `slug` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,\n",
    $sql,
    1,
    $columnCount
);

if ($columnCount < 1 && strpos($sql, '`slug`') === false) {
    fwrite(STDERR, "Unable to add slug column to clinics table definition.\n");
    exit(1);
}

$sql = preg_replace(
    "/(ADD UNIQUE KEY `clinics_phone_unique` \\(`phone`\\),\\R)/",
    "$1  ADD UNIQUE KEY `clinics_slug_unique` (`slug`),\n",
    $sql,
    1,
    $indexCount
);

if ($indexCount < 1 && strpos($sql, 'clinics_slug_unique') === false) {
    fwrite(STDERR, "Unable to add clinics_slug_unique index.\n");
    exit(1);
}

$usedSlugs = [];

$sql = preg_replace_callback(
    "/INSERT INTO `clinics` \\((.*?)\\) VALUES\\R(.*?);/s",
    function (array $matches) use (&$usedSlugs, &$stats): string {
        $stats['insert_statements']++;

        $columnsSql = $matches[1];
        $valuesSql = $matches[2];
        $columns = parseColumns($columnsSql);

        if (in_array('slug', $columns, true)) {
            return $matches[0];
        }

        $nameIndex = array_search('name', $columns, true);
        $idIndex = array_search('id', $columns, true);

        if ($nameIndex === false || $idIndex === false) {
            throw new RuntimeException('Unable to find id/name columns in clinics INSERT.');
        }

        array_splice($columns, $nameIndex + 1, 0, ['slug']);

        $tuples = splitTuples($valuesSql);
        $newTuples = [];

        foreach ($tuples as $tuple) {
            $fields = splitTupleValues($tuple);
            $id = (int) trim($fields[$idIndex]);
            $name = sqlValueToString($fields[$nameIndex]);
            $slug = uniqueClinicSlug($name !== '' ? $name : "clinic-{$id}", $id, $usedSlugs);

            array_splice($fields, $nameIndex + 1, 0, [sqlQuote($slug)]);
            $newTuples[] = '(' . implode(', ', $fields) . ')';

            $stats['clinic_rows']++;
            $stats['slugs_added']++;
        }

        return 'INSERT INTO `clinics` (' . implode(', ', array_map(fn ($column) => "`{$column}`", $columns)) . ") VALUES\n"
            . implode(",\n", $newTuples) . ';';
    },
    $sql
);

if ($stats['clinic_rows'] < 1) {
    fwrite(STDERR, "No clinics rows were updated.\n");
    exit(1);
}

if (file_put_contents($outputPath, $sql) === false) {
    fwrite(STDERR, "Unable to write output file: {$outputPath}\n");
    exit(1);
}

echo json_encode($stats, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . PHP_EOL;

function parseColumns(string $columnsSql): array
{
    preg_match_all('/`([^`]+)`/', $columnsSql, $matches);

    return $matches[1];
}

function splitTuples(string $valuesSql): array
{
    $tuples = [];
    $length = strlen($valuesSql);
    $inString = false;
    $escape = false;
    $depth = 0;
    $start = null;

    for ($i = 0; $i < $length; $i++) {
        $char = $valuesSql[$i];

        if ($inString) {
            if ($escape) {
                $escape = false;
                continue;
            }

            if ($char === '\\') {
                $escape = true;
                continue;
            }

            if ($char === "'") {
                $inString = false;
            }

            continue;
        }

        if ($char === "'") {
            $inString = true;
            continue;
        }

        if ($char === '(') {
            if ($depth === 0) {
                $start = $i + 1;
            }
            $depth++;
            continue;
        }

        if ($char === ')') {
            $depth--;
            if ($depth === 0 && $start !== null) {
                $tuples[] = substr($valuesSql, $start, $i - $start);
                $start = null;
            }
        }
    }

    return $tuples;
}

function splitTupleValues(string $tuple): array
{
    $fields = [];
    $length = strlen($tuple);
    $inString = false;
    $escape = false;
    $start = 0;

    for ($i = 0; $i < $length; $i++) {
        $char = $tuple[$i];

        if ($inString) {
            if ($escape) {
                $escape = false;
                continue;
            }

            if ($char === '\\') {
                $escape = true;
                continue;
            }

            if ($char === "'") {
                $inString = false;
            }

            continue;
        }

        if ($char === "'") {
            $inString = true;
            continue;
        }

        if ($char === ',') {
            $fields[] = trim(substr($tuple, $start, $i - $start));
            $start = $i + 1;
        }
    }

    $fields[] = trim(substr($tuple, $start));

    return $fields;
}

function sqlValueToString(string $value): string
{
    $value = trim($value);

    if (strcasecmp($value, 'NULL') === 0) {
        return '';
    }

    if (strlen($value) >= 2 && $value[0] === "'" && substr($value, -1) === "'") {
        $value = substr($value, 1, -1);
        $value = str_replace(
            ["\\0", "\\'", '\\"', '\\b', '\\n', '\\r', '\\t', '\\Z', '\\\\'],
            ["\0", "'", '"', "\b", "\n", "\r", "\t", chr(26), "\\"],
            $value
        );
    }

    return $value;
}

function uniqueClinicSlug(string $value, int $id, array &$usedSlugs): string
{
    $slug = trim(Str::slug($value, '-'));

    if ($slug === '') {
        $slug = "clinic-{$id}";
    }

    $base = $slug;
    $i = 2;

    while (isset($usedSlugs[$slug])) {
        $slug = "{$base}-{$i}";
        $i++;
    }

    $usedSlugs[$slug] = true;

    return $slug;
}

function sqlQuote(string $value): string
{
    return "'" . str_replace(["\\", "'"], ["\\\\", "\\'"], $value) . "'";
}
