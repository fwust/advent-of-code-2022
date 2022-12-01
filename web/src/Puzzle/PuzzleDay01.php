<?php

namespace Puzzle;

use Entity\PuzzleBase;

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
  public function processPart1() {
    $this->render($this->helper->printPart('one'));

    $elve_n = 1;
    foreach ($this->input as $input_val) {
      // New elve, setting calories to 0 to begin
      if (!array_key_exists($elve_n, $this->elves_calories)) {
        $this->elves_calories[$elve_n] = 0;
      }

      if ($input_val === '') {
        // New elve
        $elve_n++;
      } else {
        $this->elves_calories[$elve_n] += $input_val;
      }

    }

    $max_elve_calories = max($this->elves_calories);

    $this->render("How many total Calories is that Elf carrying? <br> <strong>$max_elve_calories</strong>");

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    $this->render($this->helper->printPart('two'));

    asort($this->elves_calories);
    $result = array_sum(array_slice(array_reverse($this->elves_calories), 0, 3));
    $this->render("Find the top three Elves carrying the most Calories. How many Calories are those Elves carrying in total? <br> <strong>$result</strong>");
  }

}