<?php
namespace PhpUnit;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Process\Process;

class ResolverTest extends TestCase
{
    /**
     * @dataProvider commandProvider
     */
    public function testImportTypes(string $expected_file, string $input)
    {
        $process = new Process(
            'resolver ' . $input,
            __DIR__ . '/fixtures',
            ['PATH' => realpath(__DIR__ . '/../bin')]
        );
        $process->run();

        self::assertTrue($process->isSuccessful(), $process->getErrorOutput());
        self::assertEquals(
            $this->fixLineEndings(file_get_contents(__DIR__ . '/' . $expected_file)),
            $this->fixLineEndings($process->getOutput())
        );
    }

    public function commandProvider()
    {
        return [
            ['expected.less-import-syntax.txt', 'less/import-syntax/main.less'],
            ['expected.js-require-syntax.txt', 'js/require-syntax/main.js'],
            ['expected.ts-import-syntax.txt', 'ts/import-syntax/main.ts'],
        ];
    }

    private function fixLineEndings(string $input, string $desired = "\n"): string
    {
        $input = implode($desired, array_map('trim', explode("\n", $input)));

        return str_replace("\\", '/', $input);
    }
}