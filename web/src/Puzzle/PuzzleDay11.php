<?php
declare(strict_types=1);

namespace Puzzle;

use Entity\PuzzleBase;
use function array_column;
use function array_fill;
use function array_pop;
use function array_product;
use function array_shift;
use function array_slice;
use function array_sum;
use function arsort;
use function explode;
use function floor;
use function str_contains;
use function str_replace;
use function str_starts_with;
use function trim;

class PuzzleDay11 extends PuzzleBase {

  private array $monkeys = [];

  /**
   * @inheritDoc
   */
  public function __construct(bool $load_input = TRUE, string $input_delimiter = "\n") {
    $this->day = 11;
    parent::__construct($load_input, $input_delimiter);
  }

  /**
   * @inheritDoc
   */
  final public function processPart1(): void {
    $this->render($this->helper->printPart('one'));

    $this->parseMonkeys();
    $result = $this->processRounds(20, fn($result): float => floor($result / 3));

    $this->render("The level of monkey business after 20 rounds is : <br> <strong>$result</strong>");
    $this->processPart2();
  }

  /**
   * @inheritDoc
   */
  final public function processPart2(): void {
    $this->render($this->helper->printPart('two'));

    $worry = fn($result): float => $result % array_product(array_column($this->monkeys, 'test'));
    $result = $this->processRounds(10000, $worry);

    $this->render("The level of monkey business after 10000 rounds is : <br> <strong>$result</strong>");
  }

  /**
   * @param int $rounds Number of rounds to run.
   * @param callable $worry
   * @return int
   */
  private function processRounds(int $rounds, callable $worry): int {
    $monkeys = $this->monkeys;
    $inspections = array_fill(0, count($monkeys), 0);

    for ($i = 0; $i < $rounds; $i++) {
      foreach ($monkeys as $monkey => $monkey_data) {
        $monkey_data = $monkeys[$monkey];
        if (empty($monkey_data['items'])) {
          continue;
        }
        foreach ($monkey_data['items'] as $item ) {
          $inspections[$monkey]++;
          array_shift($monkeys[$monkey]['items']);
          $result = $this->proceedOperation($monkeys[$monkey]['operation'], $item);
          $result = $worry($result);

          if (0 === $result % (int)$monkeys[$monkey]['test']) {
            $monkeys[(int) $monkeys[$monkey]['if_true']]['items'][] = (string) $result;
          } else {
            $monkeys[(int) $monkeys[$monkey]['if_false']]['items'][] = (string) $result;
          }
        }
      }
    }
    arsort($inspections);
    return array_product(array_slice($inspections, 0, 2));
  }

  /**
   * Parses the input file and initialises monkeys global variable.
   * @return void
   */
  private function parseMonkeys(): void {
    foreach ($this->input as $line) {
      if (str_starts_with($line, 'Monkey')) {
        $this->monkeys[] = [];
      }
      else if ($line !== '') {
        $monkey = array_pop($this->monkeys);
        if (str_contains($line, 'Starting items:')) {
          $monkey['items'] = explode(', ', explode(': ', $line)[1]);
        } elseif (str_contains($line, 'Operation:')) {
          $monkey['operation'] = explode('Operation: new = ', $line)[1];
        } elseif (str_contains($line, 'Test:')) {
          $monkey['test'] = explode('Test: divisible by ', $line)[1];
        } elseif (str_contains($line, 'If true:')) {
          $monkey['if_true'] = explode('If true: throw to monkey ', $line)[1];
        } elseif (str_contains($line, 'If false:')) {
          $monkey['if_false'] = explode('If false: throw to monkey ', $line)[1];
        }
        $this->monkeys[] = $monkey;
      }
    }
  }

  /**
   * Handles the math operation.
   * @param string $operation
   * @param string $item
   *
   * @return float|int
   */
  private function proceedOperation(string $operation, string $item): float|int {
    $math = explode(' ', trim(str_replace('old', $item, $operation)));
    $op = $math[1];
    unset($math[1]);

    return '*' === $op ? array_product($math) : array_sum($math);
  }
}