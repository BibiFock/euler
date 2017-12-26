<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem21Command extends IndexCommand
{
    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
Let d(n) be defined as the sum of proper divisors of n (numbers less than n which divide evenly into n).
If d(a) = b and d(b) = a, where a ≠ b, then a and b are an amicable pair and each of a and b are called amicable numbers.

For example, the proper divisors of 220 are 1, 2, 4, 5, 10, 11, 20, 22, 44, 55 and 110; therefore d(220) = 284. The proper divisors of 284 are 1, 2, 4, 71 and 142; so d(284) = 220.

Evaluate the sum of all the amicable numbers under 10000.
EOF;

        $this->definition[] = new InputOption(
            'limit',
            'l',
            InputOption::VALUE_OPTIONAL,
            'limit value',
            10
        );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $limit = $input->getOption("limit");

        $sumAmicables = $this->getAmicablesSum($limit);

        $this->writeln("<info>" . $sumAmicables  ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    protected function getAmicablesSum($limit)
    {
        $amicables = array();
        for ($i = 1; $i <= $limit; $i++) {
            if (in_array($i, $amicables)) {
                continue;
            }

            $divSum = $this->getDivisorsSum($i);
            if ($divSum == $i) {
                continue;
            }
            $ddSum = $this->getDivisorsSum($divSum);

            if ($ddSum != $i) {
                continue;
            }

            $this->writeln($i . ', ' . $divSum . ' are amicable');
            $amicables[] = $i;
            $amicables[] = $divSum;
        }

        return array_sum($amicables);
    }

    protected function getDivisorsSum($source)
    {
        $limit = $source;
        $divisors = array(1);
        for ( $i = 2; $i < $limit; $i++) {
            if ($source%$i != 0) {
                continue;
            }
            $divisors[] = $i;
            $divisors[] = $source / $i;
            $limit = $source / $i;
        }

        return array_sum($divisors);
    }

}
