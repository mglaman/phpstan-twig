<?php declare(strict_types=1);

namespace PHPStan\Twig;

use PHPStan\Analyser\Analyser;
use PHPStan\DependencyInjection\ContainerFactory;
use PHPStan\File\FileHelper;
use PHPUnit\Framework\TestCase;

class HelloWorldTest extends TestCase {

  public function testHelloWorld() {
    $results = $this->runAnalyser(__DIR__ . '/../fixtures/hello_world.twig', false);
  }

  /**
   * @param mixed[] $ignoreErrors
   * @param bool $reportUnmatchedIgnoredErrors
   * @param string $filePath
   * @param bool $onlyFiles
   * @return string[]|\PHPStan\Analyser\Error[]
   */
  private function runAnalyser(string $filePath): array
  {
    $tmpDir = sys_get_temp_dir() . '/phpstan-tests';
    if (!@mkdir($tmpDir, 0777, true) && !is_dir($tmpDir)) {
      self::fail(sprintf('Cannot create temp directory %s', $tmpDir));
    }

    $rootDir = __DIR__ . '/../..';
    $containerFactory = new ContainerFactory($rootDir);
    $container = $containerFactory->create($tmpDir, [
      [__DIR__ . '/../fixtures/config/phpunit-phpstan.neon'],
    ], []);
    $fileHelper = $container->getByType(FileHelper::class);
    assert($fileHelper instanceof FileHelper);

    $analyser = $container->getByType(Analyser::class);
    assert($analyser instanceof Analyser);
    return $analyser->analyse([$fileHelper->normalizePath($filePath)], false);
  }

}
