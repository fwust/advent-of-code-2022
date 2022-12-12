<?php
declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function abs;
use function explode;
use function implode;
use function in_array;
use function range;

class PuzzleDay10 extends PuzzleBase {

  private array $X = [];

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 10;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));
    $x = 1;
    $n_cycle = 0;
    $signal_strength_sum = 0;
    $cycles = range(20, 220, 40);
    foreach ($this->input as $input) {
      $current_cycles = ($input === 'noop' ? 1 : 2);
      for ($i = 0; $i < $current_cycles; $i++) {
        $n_cycle++;
        if (in_array($n_cycle, $cycles, true)) {
          $signal_strength_sum += $x * $n_cycle;
        }
        $this->X[$n_cycle] = $x; // Save the X history for part 2...
        if ($i === 1) {
          $x += (int) explode(' ', $input)[1];
        }
      }
    }
    $this->render("The sum of the signal strengths is <br> <strong>$signal_strength_sum</strong>");

    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));
    $crt = [];
    for ($row = 0; $row < 6; $row++) {
      for ($col = 0; $col < 40; $col++) {
        $pixel_position = $row * 40 + $col + 1;
        /**
         * The sprite is 3 pixels wide and current X position represents the middle of the sprite.
         * So if the current column cursor ($col) is not more than 1 pixel further away for the current X value (left or right), we draw a "#".
         */
        if (abs($col - $this->X[$pixel_position]) <= 1) {
          $crt[$row][$col] = '#';
        } else {
          $crt[$row][$col] = ' ';
        }
      }
    }
    $output = '';
    foreach ($crt as $row) {
      $output .= implode('', $row) . '<br>';
    }
    $this->render("The sum of the signal strengths is : ");
    print("<div><pre>". $output ."</pre></div>");
  }
}