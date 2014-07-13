<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem14Command extends IndexCommand
{
    protected $listChain = null;

    protected function init()
    {
        $this->help = <<<EOF
The following iterative sequence is defined for the set of positive integers:

n → n/2 (n is even)
n → 3n + 1 (n is odd)

Using the rule above and starting with 13, we generate the following sequence:

13 → 40 → 20 → 10 → 5 → 16 → 8 → 4 → 2 → 1
It can be seen that this sequence (starting at 13 and finishing at 1) contains 10 terms. Although it has not been proved yet (Collatz Problem), it is thought that all starting numbers finish at 1.

Which starting number, under one million, produces the longest chain?

NOTE: Once the chain starts the terms are allowed to go above one million.
EOF;
        $this->definition = array(
            new InputOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'limit number',
                5
            )
        );

        $this->listChain = array();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = intval($input->getOption('limit'));

        $return = $this->findLongestChain($limit, $output);
        $output->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
    }

    protected function findLongestChain($start, $output)
    {
        $lastResult = $lastStart = 0;
        $limit = 1;
        for ($i = $start; $i >= $limit; $i--) {
            $result = $this->makeTheChain($i, $output);
            if ($lastResult < count($result)) {
                $lastResult = count($result);
                $lastStart = $i;
                $output->writeln($lastStart . " (<info>".$lastResult."</info>) ". $this->getDuration());
            }
            $findNewLimit = false;
            while (!empty($this->listChain[$limit])) {
                $limit++;
                $findNewLimit = true;
            }
            if ($findNewLimit) {
                $output->writeln('New limit: ' . $limit);
            }
        }

        return $lastStart;
    }

    protected function makeTheChain($nb, $output)
    {
        if (!empty($lastChain[$nb])) {
            return $lastChain[$nb];
        }
        $return = array($nb);
        while ($nb != 1) {
            if ($nb%2 == 0) {
                $nb = $nb/2;
            } else {
                $nb = 3*$nb +1;
            }
            if (!empty($this->listChain[$nb])) {
                $return = array_merge($return, $this->listChain[$nb]);
                break;
            }
            $return[] = $nb;
        }
        //$output->writeln("(" . implode(' -> ', $return) . ")");
        $tmp = $return;
        while ( !empty($tmp) && empty($this->listChain[$tmp[0]])) {
            $this->listChain[$tmp[0]] = $tmp;
            array_shift($tmp);
        }

        return $return;
    }

}
