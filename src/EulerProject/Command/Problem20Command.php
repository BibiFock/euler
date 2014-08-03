<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem20Command extends IndexCommand
{
    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
n! means n × (n − 1) × ... × 3 × 2 × 1

For example, 10! = 10 × 9 × ... × 3 × 2 × 1 = 3628800,
and the sum of the digits in the number 10! is 3 + 6 + 2 + 8 + 8 + 0 + 0 = 27.

Find the sum of the digits in the number 100!
EOF;

        $this->definition[] = new InputOption(
            'factorial',
            'f',
            InputOption::VALUE_OPTIONAL,
            'factorial value',
            10
        );

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $factorial = $input->getOption("factorial");

        //$return = $this->getFactorial($factorial);
        $return = gmp_fact($factorial);
        $return = gmp_strval($return);

        $this->writeln( $return);
        $this->writeln("<info>" . array_sum(str_split($return)) ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    protected function getFactorial($value)
    {
        $return = 1;
        for ($i = $value; $i > 0 ; $i--) {
            $return *= $i;
        }

        return $return;
    }

}
