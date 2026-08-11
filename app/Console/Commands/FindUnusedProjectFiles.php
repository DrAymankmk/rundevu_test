<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FindUnusedProjectFiles extends Command
{
    protected $signature = 'project:find-unused-files
                            {--output= : Optional filename inside issues/}
                            {--only= : Limit scan to controllers,models,views,routes}';

    protected $description = 'Find likely unused controllers, models, views, and route files and write a review report';

    public function handle(): int
    {
        $only = $this->option('only');
        $sections = $only ? array_map('trim', explode(',', strtolower($only))) : ['controllers', 'models', 'views', 'routes'];
        $snapshot = $this->projectSnapshot();
        $results = [];

        if (in_array('controllers', $sections, true)) {
            $results['controllers'] = $this->unusedControllers($snapshot);
        }

        if (in_array('models', $sections, true)) {
            $results['models'] = $this->unusedModels($snapshot);
        }

        if (in_array('views', $sections, true)) {
            $results['views'] = $this->unusedViews($snapshot);
        }

        if (in_array('routes', $sections, true)) {
            $results['routes'] = $this->unusedRouteFiles($snapshot);
        }

        $path = $this->writeReport($results);
        $this->info('Unused files report written to: ' . $path);

        foreach ($results as $section => $items) {
            $this->line(sprintf('%s: %d candidate(s)', ucfirst($section), count($items)));
        }

        return self::SUCCESS;
    }

    private function projectSnapshot(): array
    {
        $files = collect([
            ...$this->files('app', '*.php'),
            ...$this->files('routes', '*.php'),
            ...$this->files('resources/views', '*.blade.php'),
            ...$this->files('resources/lang', '*.php'),
            ...$this->files('config', '*.php'),
            ...$this->files('database', '*.php'),
        ])->unique()->values();

        $contents = [];

        foreach ($files as $file) {
            $contents[$file] = File::get($file);
        }

        return [
            'files' => $files->all(),
            'contents' => $contents,
            'all_text' => implode("\n", $contents),
        ];
    }

    private function files(string $directory, string $pattern): array
    {
        $path = base_path($directory);

        if (!File::isDirectory($path)) {
            return [];
        }

        return collect(File::allFiles($path))
            ->filter(fn ($file) => $file->getFilename() !== '.gitkeep')
            ->filter(fn ($file) => fnmatch($pattern, $file->getFilename()))
            ->map(fn ($file) => $file->getRealPath())
            ->values()
            ->all();
    }

    private function unusedControllers(array $snapshot): array
    {
        return collect($this->files('app/Http/Controllers', '*.php'))
            ->reject(fn ($file) => basename($file) === 'Controller.php')
            ->map(function ($file) use ($snapshot) {
                $class = $this->className($file);

                if (!$class) {
                    return null;
                }

                $short = class_basename($class);
                $references = $this->countReferences($snapshot['contents'], $file, [
                    $class,
                    '\\' . $class,
                    $short . '::class',
                    $short . '@',
                    "'" . $short . '@',
                    '"' . $short . '@',
                    str_replace('\\', '\\\\', $class),
                ]);

                return $references === 0
                    ? $this->candidate($file, 'No static route/class reference found for ' . $short)
                    : null;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function unusedModels(array $snapshot): array
    {
        return collect($this->files('app/Models', '*.php'))
            ->map(function ($file) use ($snapshot) {
                $class = $this->className($file);

                if (!$class) {
                    return null;
                }

                $short = class_basename($class);
                $references = $this->countReferences($snapshot['contents'], $file, [
                    $class,
                    '\\' . $class,
                    $short . '::class',
                    'new ' . $short,
                    'extends ' . $short,
                    'use ' . $class,
                    str_replace('\\', '\\\\', $class),
                ]);

                return $references === 0
                    ? $this->candidate($file, 'No static PHP reference found for model ' . $short)
                    : null;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function unusedViews(array $snapshot): array
    {
        return collect($this->files('resources/views', '*.blade.php'))
            ->map(function ($file) use ($snapshot) {
                $view = $this->viewName($file);
                $slashView = str_replace('.', '/', $view);

                $references = $this->countReferences($snapshot['contents'], $file, [
                    "'" . $view . "'",
                    '"' . $view . '"',
                    '(' . $view . ')',
                    "'" . $slashView . "'",
                    '"' . $slashView . '"',
                    '@include(\'' . $view . '\'',
                    '@include("' . $view . '"',
                    '@extends(\'' . $view . '\'',
                    '@extends("' . $view . '"',
                    '@component(\'' . $view . '\'',
                    '@component("' . $view . '"',
                ]);

                return $references === 0
                    ? $this->candidate($file, 'No static view/include/extends reference found for ' . $view)
                    : null;
            })
            ->filter()
            ->values()
            ->all();
    }

    private function unusedRouteFiles(array $snapshot): array
    {
        $provider = base_path('app/Providers/RouteServiceProvider.php');
        $providerText = File::exists($provider) ? File::get($provider) : '';

        return collect($this->files('routes', '*.php'))
            ->reject(fn ($file) => in_array(basename($file), ['web.php', 'api.php', 'console.php', 'channels.php'], true))
            ->map(function ($file) use ($providerText) {
                return str_contains($providerText, "routes/" . basename($file))
                    ? null
                    : $this->candidate($file, 'Route file is not loaded by RouteServiceProvider');
            })
            ->filter()
            ->values()
            ->all();
    }

    private function className(string $file): ?string
    {
        $content = File::get($file);

        if (!preg_match('/namespace\s+([^;]+);/m', $content, $namespace)) {
            return null;
        }

        if (!preg_match('/class\s+([A-Za-z_][A-Za-z0-9_]*)/m', $content, $class)) {
            return null;
        }

        return trim($namespace[1]) . '\\' . trim($class[1]);
    }

    private function viewName(string $file): string
    {
        $base = str_replace('\\', '/', base_path('resources/views'));
        $path = str_replace('\\', '/', $file);
        $relative = ltrim(str_replace($base, '', $path), '/');
        $relative = preg_replace('/\.blade\.php$/', '', $relative);

        return str_replace('/', '.', $relative);
    }

    private function countReferences(array $contents, string $ownFile, array $needles): int
    {
        $count = 0;

        foreach ($contents as $file => $content) {
            if ($file === $ownFile) {
                continue;
            }

            foreach (array_unique($needles) as $needle) {
                if ($needle !== '' && str_contains($content, $needle)) {
                    $count++;
                    break;
                }
            }
        }

        return $count;
    }

    private function candidate(string $file, string $reason): array
    {
        return [
            'path' => str_replace(base_path() . DIRECTORY_SEPARATOR, '', $file),
            'reason' => $reason,
        ];
    }

    private function writeReport(array $results): string
    {
        $directory = base_path('issues');
        File::ensureDirectoryExists($directory);

        $filename = $this->option('output') ?: 'unused-files-' . now()->format('Y-m-d_H-i-s') . '.md';
        $path = $directory . DIRECTORY_SEPARATOR . $filename;
        $lines = [
            '# Likely Unused Files',
            '',
            'Generated at: ' . now()->toDateTimeString(),
            '',
            'Review these candidates before deleting. Static scanning can miss dynamic Laravel references.',
            '',
        ];

        foreach ($results as $section => $items) {
            $lines[] = '## ' . ucfirst($section) . ' (' . count($items) . ')';
            $lines[] = '';

            if (!$items) {
                $lines[] = 'No candidates found.';
                $lines[] = '';
                continue;
            }

            foreach ($items as $item) {
                $lines[] = '- `' . $item['path'] . '`';
                $lines[] = '  Reason: ' . $item['reason'];
            }

            $lines[] = '';
        }

        File::put($path, implode("\n", $lines));

        return $path;
    }
}
