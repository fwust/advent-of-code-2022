<?php
declare(strict_types=1);

namespace Puzzle;
//require PROJECT_ROOT.'/kint.phar';
use Entity\PuzzleBase;

class PuzzleDay09 extends PuzzleBase {

  private int $hx = 0;
  private int $hy = 0;
  private int $tx = 0;
  private int $ty = 0;
  private array $move_matrix = [
    'R' => [1, 0],
    'L' => [-1, 0],
    'U' => [0, 1],
    'D' => [0, -1]
  ];
  private array $tail_visited = [];
  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 9;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));

    $this->processMotions();
    $this->tail_visited = array_unique($this->tail_visited, SORT_REGULAR);
    $result = count($this->tail_visited);

    $this->render("The number of positions that the tail of the rope visit at least once is <br> <strong>$result</strong>");
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));
  }

  private function doMove(int $x, int $y): void {
    $this->hx += $x;
    $this->hy += $y;
    if (!$this->isAdjacent($this->hx, $this->hy, $this->tx, $this->ty)) {
      $this->tx += ($this->hx === $this->tx) ? 0 : ($this->hx - $this->tx) / abs($this->hx - $this->tx);
      $this->ty += ($this->hy === $this->ty) ? 0 : ($this->hy - $this->ty) / abs($this->hy - $this->ty);
    }
  }

  private function isAdjacent(int $x1, int $y1, int $x2, int $y2): bool {
    return abs($x1 - $x2) <= 1 && abs($y1 - $y2) <= 1;
  }

  private function processMotions(): void {
    $this->tail_visited[] = [$this->tx, $this->ty];
    foreach ($this->input as $input) {
      [$op, $moves] = explode(' ', $input);
      [$x, $y] = $this->move_matrix[$op];
      for ($i = 0; $i < $moves; $i++) {
        $this->doMove($x, $y);
        $this->tail_visited[] = [$this->tx, $this->ty];
      }
    }
  }
}