<?php declare(strict_types=1);

namespace PHPStan\Parser;

class DirectTwigParser extends DirectParser {

  public function parseString(string $sourceCode): array {
    return parent::parseString($sourceCode);
  }

}
