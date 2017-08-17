<?php
namespace PhpUnit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class BundlerTest extends TestCase
{
    /**
     * @dataProvider commandProvider
     */
    public function testImportTypes(string $expected_file, string $input)
    {
        $process = new Process(
            'resolver -n bundle ' . $input,
            __DIR__ . '/fixtures',
            ['PATH' => realpath(__DIR__ . '/../bin')]
        );
        $process->run();

        self::assertTrue($process->isSuccessful(), $process->getErrorOutput());
        self::assertSame(
            $this->fixLineEndings(file_get_contents(__DIR__ . '/expected/' . $expected_file)),
            $this->fixLineEndings($process->getOutput())
        );
    }

    public function commandProvider()
    {
        return [
            ['bundler.jquery.js', 'jquery'],
            ['bundler.module_index.js', 'module_index'],
            ['bundler.module_package.js', 'module_package'],
            ['bundler.module_package_dir.js', 'module_package_dir'],
        ];
    }

    private function fixLineEndings(string $input, string $desired = "\n"): string
    {
        $input = implode($desired, array_map(function ($s) { return rtrim($s, "\r"); }, explode("\n", $input)));

        return str_replace("\\", '/', $input);
    }
}