<?php

declare(strict_types=1);

namespace Zorvia\WebProxy\Console\Commands;

use Illuminate\Console\Command;
use ReflectionClass;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RuntimeException;
use Zorvia\WebProxy\Attributes\WebhookTarget;
use Zorvia\WebProxy\Attributes\WebhookRouter;
use Zorvia\WebProxy\Contracts\WebhookJob;
use Zorvia\WebProxy\Contracts\WebhookEvent;
use Zorvia\WebProxy\Enums\WebhookProxyTargetType;

final class DiscoverWebProxyCommand extends Command
{
    protected $signature = 'web-proxy:discover
        {--output= : Config file to generate}';

    protected $description = 'Discover webhook routers and attributed targets';

    public function handle(): int
    {
        $path = base_path();

        $configuredTargets = config('web-proxy.targets', []);
        $configuredTargets = is_array($configuredTargets) ? $configuredTargets : [];
        $targets = [
            'event' => is_array($configuredTargets['event'] ?? null) ? $configuredTargets['event'] : [],
            'job' => is_array($configuredTargets['job'] ?? null) ? $configuredTargets['job'] : [],
        ];
        $routers = is_array(config('web-proxy.routers', [])) ? config('web-proxy.routers', []) : [];


        if (! is_dir($path)) {
            throw new RuntimeException("Webhook target discovery root [{$path}] does not exist.");
        }

        foreach ($this->phpFiles($path) as $file) {
            $class = $this->classFromFile($file);

            if ($class === null || ! class_exists($class)) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            foreach ($reflection->getAttributes(WebhookTarget::class) as $attribute) {
                $target = $attribute->newInstance();

                $type = $target->type ?? match (true) {
                    is_a($class, WebhookJob::class, true) => WebhookProxyTargetType::JOB,
                    is_a($class, WebhookEvent::class, true) => WebhookProxyTargetType::EVENT,
                    default => null,
                };
                $implementsTarget = match ($type) {
                    WebhookProxyTargetType::JOB => is_a($class, WebhookJob::class, true),
                    WebhookProxyTargetType::EVENT => is_a($class, WebhookEvent::class, true),
                    WebhookProxyTargetType::REQUEST => false,
                    null => false,
                };

                if (! $implementsTarget) {
                    throw new RuntimeException("Webhook target [{$class}] must implement a WebhookJob or WebhookEvent contract.");
                }

                $typeKey = $type->value;

                if (isset($targets[$typeKey][$target->key]) && $targets[$typeKey][$target->key] !== $class) {
                    throw new RuntimeException("Webhook target key [{$target->key}] is declared by multiple classes.");
                }

                $targets[$typeKey][$target->key] = $class;
            }

            foreach ($reflection->getAttributes(WebhookRouter::class) as $attribute) {
                $router = $attribute->newInstance();

                if (! is_a($class, \Zorvia\WebProxy\Contracts\Router::class, true)) {
                    throw new RuntimeException("Webhook router [{$class}] must implement the Router contract.");
                }

                if (isset($routers[$router->client]) && $routers[$router->client] !== $class) {
                    throw new RuntimeException("Webhook router client [{$router->client}] is declared by multiple classes.");
                }

                $routers[$router->client] = $class;
            }
        }

        foreach ($targets as &$typeTargets) {
            ksort($typeTargets);
        }
        unset($typeTargets);
        ksort($targets);
        ksort($routers);
        $output = $this->option('output');
        $output = is_string($output) && $output !== ''
            ? $output
            : base_path('bootstrap/cache/web-proxy.php');

        $directory = dirname($output);
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            throw new RuntimeException("Unable to create webhook discovery cache directory [{$directory}].");
        }

        $contents = "<?php\n\ndeclare(strict_types=1);\n\nreturn [\n";
        $contents .= "    'routers' => [\n";
        foreach ($routers as $client => $router) {
            $contents .= "        ".var_export($client, true).' => '.$router."::class,\n";
        }
        $contents .= "    ],\n";

        $contents .= "    'targets' => [\n";
        foreach ($targets as $type => $typeTargets) {
            ksort($typeTargets);
            $contents .= "        ".var_export($type, true)." => [\n";
            foreach ($typeTargets as $key => $class) {
                $contents .= "            ".var_export($key, true).' => '. $class."::class,\n";
            }
            $contents .= "        ],\n";
        }
        $contents .= "    ],\n";
        $contents .= "];\n";

        file_put_contents($output, $contents);
        $targetCount = array_sum(array_map('count', $targets));

        $this->info(sprintf(
            'Discovered %d webhook router(s) and %d webhook target(s) into %s.',
            count($routers),
            $targetCount,
            $output,
        ));

        return self::SUCCESS;
    }

    /** @return iterable<string> */
    private function phpFiles(string $path): iterable
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
        );

        foreach ($files as $file) {
            $relativePath = mb_ltrim(mb_substr($file->getPathname(), mb_strlen(base_path())), DIRECTORY_SEPARATOR);

            if ($this->isExcludedPath($relativePath)) {
                continue;
            }

            if ($file->isFile() && $file->getExtension() === 'php') {
                yield $file->getPathname();
            }
        }
    }

    private function classFromFile(string $file): ?string
    {
        $source = file_get_contents($file);

        if (! is_string($source)) {
            return null;
        }

        preg_match('/namespace\s+([^;]+);/i', $source, $namespaceMatch);
        preg_match('/(?:final\s+|abstract\s+)?class\s+(\w+)/i', $source, $classMatch);

        if (! isset($classMatch[1])) {
            return null;
        }

        $namespace = isset($namespaceMatch[1]) ? mb_trim($namespaceMatch[1]) : '';

        return $namespace === '' ? $classMatch[1] : $namespace.'\\'.$classMatch[1];
    }

    private function isExcludedPath(string $relativePath): bool
    {
        foreach ([
            'vendor/',
            'node_modules/',
            'storage/',
            'bootstrap/cache/',
            'config/',
            'database/',
            'routes/',
            'tests/',
        ] as $excludedPrefix) {
            if (str_starts_with($relativePath, $excludedPrefix)) {
                return true;
            }
        }

        return basename($relativePath) === '_ide_helper.php';
    }
}
