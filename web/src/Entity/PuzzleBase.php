<?php

namespace Entity;

use Service\AoCHelper;

class PuzzleBase implements PuzzleInterface {

  /**
   * Puzzle day.
   *
   * @var int
   */
  protected int $day;

  /**
   * Helper service.
   *
   * @var \Service\AoCHelper
   */
  protected AoCHelper $helper;

  /**
   * Data set to be processed.
   *
   * @var array
   */
  protected array $input;

  /**
   * PuzzleBase constructor.
   *
   * @param bool $load_input
   * @param string $input_delimiter
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n"){
    // Set properties.
    $this->helper = new AoCHelper();

    // Start processing the puzzle.
    $this->preprocess($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  public function preprocess(bool $load_input = TRUE, string $input_delimiter = "\n") {
    // Load the input dataset if required
    if ($load_input) {
      $this->input = $this->helper->getInput($this->day, $input_delimiter);
    }

    if (!empty($this->day)) {
      // Print day title.
      $this->render($this->helper->printDay($this->day));
    }

    // Process the input.
    $this->processPart1();
  }

  /**
   * @inheritDoc
   */
  public function processPart1() {
    $this->render('<h1>Advent of Code 2022</h1>');

    // Process the second part of the puzzle.
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  public function processPart2() {
    // Nothing to process here.
  }

  /**
   * @inheritDoc
   */
  public function render(string $message) {
    print "<div>".$message."</div>";
  }

}