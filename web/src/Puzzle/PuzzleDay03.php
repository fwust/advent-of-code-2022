<?php

namespace Puzzle;

use Entity\PuzzleBase;
use function array_chunk;
use function array_intersect;
use function array_map;
use function array_unique;
use function floor;
use function str_split;
use function strlen;
use function substr;

class PuzzleDay03 extends PuzzleBase {

    /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 3;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));

    $common_letters = [];

    foreach ($this->input as $string) {
      $strings = $this->splitStringInHalf($string);
      $common_letters[] = $this->getCommonLetters(...$strings);
    }

    $result = $this->getSumOfPriorities($common_letters);
    $this->render("The sum is <br> <strong>$result</strong>");

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));

    $common_letters = [];
    $chunked_strings = $this->getChunkedStrings();

    foreach ($chunked_strings as $strings) {
      $common_letters[] = $this->getCommonLetters(...$strings);
    }

    $result = $this->getSumOfPriorities($common_letters);
    $this->render("The sum is <br> <strong>$result</strong>");
  }

  /**
   * @param array ...$strings
   * @return array
   */
  private function getCommonLetters(array ...$strings): array {
    return array_values(array_unique(array_intersect(...$strings)));
  }

  /**
   * Returns the sum of priorities for each letter.
   *
   * @param array $items
   * @return int
   */
  private function getSumOfPriorities(array $items): int {
    $sum = 0;
    foreach ($items as $item) {
      foreach ($item as $letter) {
        $sum += $this->getLetterPriority($letter);
      }
    }
    return $sum;
  }

  /**
   * Returns the letter priority :
   * - Lowercase item types a through z have priorities 1 through 26.
   * - Uppercase item types A through Z have priorities 27 through 52.
   *
   * @param string $letter
   * @return int
   */
  private function getLetterPriority(string $letter): int {
    $priority = ord(strtoupper($letter)) - ord('A') + 1;
    if (ctype_upper($letter)) {
      $priority += 26;
    }
    return $priority;
  }

  /**
   * Splits string in half and convert them into an array.
   *
   * @param string $string
   * @return array
   */
  private function splitStringInHalf(string $string): array {
    return [
      str_split(substr($string, 0, floor(strlen($string) / 2))),
      str_split(substr($string, floor(strlen($string) / 2))),
    ];
  }

  /**
   * Chunk the input in an array of 3 strings and convert them into an array.
   *
   * @return array
   */
  private function getChunkedStrings(): array {
    return array_map(static fn($value): array => array_map(static fn($v): array => str_split($v), $value), array_chunk($this->input, 3));
  }

}