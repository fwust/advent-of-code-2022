<?php

namespace Puzzle;

use Entity\PuzzleBase;
use function array_key_exists;
use function array_reverse;
use function array_slice;
use function array_sum;
use function asort;
use function max;

class PuzzleDay01 extends PuzzleBase {

  private array $elves_calories = [];

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 1;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));

    $elf_n = 1;
    foreach ($this->input as $input_val) {
      // New elf, setting calories to 0 to begin
      if (!array_key_exists($elf_n, $this->elves_calories)) {
        $this->elves_calories[$elf_n] = 0;
      }

      if ($input_val === '') {
        // New elf
        $elf_n++;
      } else {
        $this->elves_calories[$elf_n] += $input_val;
      }

    }

    $max_elf_calories = max($this->elves_calories);

    $this->render("The total calories is <br> <strong>$max_elf_calories</strong>");

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));

    asort($this->elves_calories);
    $result = array_sum(array_slice(array_reverse($this->elves_calories), 0, 3));
    $this->render("The top three total calories is <br> <strong>$result</strong>");
  }

}