<?php

namespace App\Console\Commands;

use App\Services\ProjectIssueScanner;
use Illuminate\Console\Command;

class ScanProjectIssues extends Command
{
    protected $signature = 'project:scan-issues
                            {--output= : Optional filename inside issues/ (default: issues-YYYY-MM-DD_HH-mm-ss.md)}';

    protected $description = 'Scan codebase (PHP, routes, models, views, database) and write a timestamped ISSUES report to issues/';

    public function handle(ProjectIssueScanner $scanner): int
    {
        $this->info('Scanning project for syntax, logical, and structural issues...');

        $result = $scanner->scanAndWriteReport($this->option('output'));

        $summary = $result['summary'];
        $this->newLine();
        $this->table(
            ['Severity', 'Count'],
            [
                ['Critical', $summary['critical']],
                ['High', $summary['high']],
                ['Medium', $summary['medium']],
                ['Low', $summary['low']],
                ['Total', $summary['total']],
            ]
        );

        $this->newLine();
        $this->info('Report written to: ' . $result['path']);

        return $summary['critical'] > 0 ? Command::FAILURE : Command::SUCCESS;
    }
}
