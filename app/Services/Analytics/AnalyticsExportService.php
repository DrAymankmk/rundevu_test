<?php

namespace App\Services\Analytics;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsExportService
{
    public function export(string $format, string $filename, array $headers, iterable $rows, ?string $title = null)
    {
        $format = strtolower($format);
        $title = $title ?: $filename;

        if ($format === 'csv') {
            return $this->csv($filename, $headers, $rows);
        }

        if ($format === 'xlsx' || $format === 'excel') {
            return $this->excel($filename, $headers, $rows);
        }

        if ($format === 'pdf' || $format === 'print') {
            $locale = app()->getLocale();

            return view('analytics.export_print', [
                'title' => $title,
                'headers' => $headers,
                'rows' => $rows,
                'autoPrint' => $format === 'print' || $format === 'pdf',
                'locale' => $locale,
                'dir' => $locale === 'ar' ? 'rtl' : 'ltr',
            ]);
        }

        abort(422, 'Unsupported export format');
    }

    protected function csv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $this->normalizeRow($row, count($headers)));
            }
            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.csv"');

        return $response;
    }

    protected function excel(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        $response = new StreamedResponse(function () use ($headers, $rows, $filename) {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<?mso-application progid="Excel.Sheet"?>';
            echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" ';
            echo 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">';
            echo '<Worksheet ss:Name="Report"><Table>';

            echo '<Row>';
            foreach ($headers as $header) {
                echo '<Cell><Data ss:Type="String">' . $this->xml($header) . '</Data></Cell>';
            }
            echo '</Row>';

            foreach ($rows as $row) {
                $values = $this->normalizeRow($row, count($headers));
                echo '<Row>';
                foreach ($values as $value) {
                    $type = $this->isExcelNumber($value) ? 'Number' : 'String';
                    echo '<Cell><Data ss:Type="' . $type . '">' . $this->xml($value) . '</Data></Cell>';
                }
                echo '</Row>';
            }

            echo '</Table></Worksheet></Workbook>';
        });

        $response->headers->set('Content-Type', 'application/vnd.ms-excel; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');

        return $response;
    }

    protected function normalizeRow($row, int $count): array
    {
        if ($row instanceof \Illuminate\Support\Collection) {
            $row = $row->values()->all();
        } elseif (is_object($row)) {
            $row = (array) $row;
        }

        $values = array_values($row);
        if (count($values) > $count) {
            $values = array_slice($values, 0, $count);
        }

        while (count($values) < $count) {
            $values[] = '';
        }

        return $values;
    }

    protected function isExcelNumber($value): bool
    {
        if ($value === null || $value === '' || !is_numeric($value)) {
            return false;
        }

        $string = (string) $value;

        if (preg_match('/^0\d/', $string) || strlen($string) > 11) {
            return false;
        }

        return true;
    }

    protected function xml($value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }
}
