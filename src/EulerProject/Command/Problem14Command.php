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
        parent::init();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $limit = intval($input->getOption('limit'));

        $return = $this->findLongestChain($limit, $output);

        $output->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->output->writeln("--------------------------------");
    }

    protected function findLongestChain($limit, $output)
    {
        $nbNode = 0;
        $number = 0;
        // for ($i = 2; $i < $limit; $i++) {
        for ($i = $limit; $i >= 2; $i--) {
            $nbTerms = $this->howManyTerms(array($i));
            if ($nbTerms > $nbNode) {
                $nbNode = $nbTerms;
                $number = $i;
            }
        }

        return $number;
    }

    protected function howManyTerms($previous)
    {
        $start = $previous[0];
        if (!empty($this->listChain[$start])) {
            $this->storeList($previous, $this->listChain[$start]);
            return count($previous) + $this->listChain[$start];
        }

        if ($start % 2 === 0) {
            $next = $start/2;
        } else {
            $next = 3*$start+1;
        }
        array_unshift($previous, $next);
        if ($next === 1) {
            $this->storeList($previous);
            return count($previous);
        }

        return $this->howManyTerms($previous);
    }

    protected function storeList($previous, $add = 1)
    {
        while (count($previous) > 1) {
            $last = array_pop($previous);
            if (!empty($this->listChain[$last])) {
                break;
            }
            $this->listChain[$last] = count($previous) + $add;
        }
    }


    /** other solution **/
    protected function findLongestChain2($start, $output)
    {
        $lastResult = $lastStart = 0;
        $limit = 1;
        for ($i = $start; $i >= $limit; $i--) {
            $result = $this->makeTheChain($i, $output);
            if ($lastResult < $result) {
                $lastResult = $result;
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

        return $lastStart ." => ". $lastResult;
    }

    protected function makeTheChain($nb, $output)
    {
        if (!empty($lastChain[$nb])) {
            return $lastChain[$nb];
        }
        $chains = array($nb);
        $comp = 0;
        while ($nb != 1) {
            if ($nb%2 == 0) {
                $nb = $nb/2;
            } else {
                $nb = 3*$nb +1;
            }
            if (!empty($this->listChain[$nb])) {
                $comp = $this->listChain[$nb];
                break;
            }
            $chains[] = $nb;
        }
        $return = $count = count($chains) + $comp;
        //$output->writeln("(" . implode(' -> ', $chains) . ") Result: " . $count);
        while ($count > 0 && empty($this->listChain[current($chains)])) {
            $this->listChain[current($chains)] = $count--;
            array_shift($chains);
            reset($chains);
        }

        return $return;
    }

}
