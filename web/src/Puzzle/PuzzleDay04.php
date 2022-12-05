<?php

namespace Puzzle;

use Entity\PuzzleBase;
use function array_diff;
use function array_intersect;
use function array_map;
use function count;

class PuzzleDay04 extends PuzzleBase {

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 4;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));
    $this->input = $this->convertInputData();

    $sum = 0;
    foreach ($this->input as $pairs) {
      if ($this->isIncluded($pairs)) {
        $sum++;
      }
    }
    $this->render("The sum of pairs included is <br> <strong>$sum</strong>");

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));

    $sum = 0;
    foreach ($this->input as $pairs) {
      if ($this->isOverlapping($pairs)) {
        $sum++;
      }
    }
    $this->render("The number of overlapping assignment pairs is <br> <strong>$sum</strong>");
  }

  /**
   * Checks if one of two pair is included in another.
   *
   * @param array $pairs
   * @return bool
   */
  private function isIncluded(array $pairs): bool {
    return count(array_diff($pairs[0], $pairs[1])) === 0 || count(array_diff($pairs[1], $pairs[0])) === 0;
  }

  /**
   * Checks if pairs are overlapping.
   *
   * @param array $pairs
   * @return bool
   */
  private function isOverlapping(array $pairs): bool {
    return (bool)array_intersect($pairs[0], $pairs[1]);
  }

  /**
   * @return array
   */
  private function convertInputData(): array {
    $pairs = [];
    foreach ($this->input as $i => $pair) {
      $pairs[$i] = array_map(static fn($value): array => explode('-', $value), explode(',', $pair));
      foreach ($pairs[$i] as $k => $p) {
        $pairs[$i][$k] = range($p[0], $p[1]);
      }
    }
    return $pairs;
  }
}