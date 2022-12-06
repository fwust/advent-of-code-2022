<?php

namespace Puzzle;
use Entity\PuzzleBase;
use function array_slice;
use function array_unique;
use function count;
use function str_split;

class PuzzleDay06 extends PuzzleBase {

  /** @var array The input string split into an array */
  private array $input_string = [];

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 6;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));
    $this->input_string = str_split($this->input[0]);

    $result = $this->getFirstMarkerPos(4);
    $this->render("First marker after character <br> <strong>$result</strong>");
    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));
    $result = $this->getFirstMarkerPos(14);
    $this->render("First marker after character <br> <strong>$result</strong>");
  }

  /**
   * @param array $array
   * @return bool
   */
  private function hasDuplicates(array $array): bool {
    return count($array) !== count(array_unique($array));
  }

  /**
   * @param int $length
   * @return false|int|string
   */
  private function getFirstMarkerPos(int $length): int {
    $result = false;
    foreach ($this->input_string as $k => $letter) {
      $chunk = array_slice($this->input_string, $k, $length);
      if (!$this->hasDuplicates($chunk)) {
        $result = $k + $length;
        break;
      }
    }
    return $result;
  }
}