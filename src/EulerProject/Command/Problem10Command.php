<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem10Command extends IndexCommand
{

    protected function init()
    {
        $this->help = "get Biggest Prime";
        $this->definition = array(
            new InputOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Number of divisors to find',
                10
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = intval($input->getOption('limit'));
        $output->writeln($this->findBiggestPrime($limit) . "(".($this->getDuration())."s) ----)");
        $this->start = microtime(true);
        $output->writeln($this->findBiggestPrimeWidthGmp($limit) . "(".($this->getDuration())."s) ----)");
;
    }

    public function findBiggestPrime($limit)
    {
        $return = array();
        for ($i = 3; $i <= $limit; $i+=2) {
            $return[$i] = true;
        }
        $lastCount = count($return);
        reset($return);
        //$sum = 2;
        do {
            $nbToTest = key($return);
            if (!$this->isPrime($nbToTest)) {
                unset($return[$nbToTest]);
            }

            for ($i = 3; $nbToTest*$i < $limit; $i++) {
                if (isset($return[$nbToTest*$i])) {
                    unset($return[$nbToTest*$i]);
                }
            }
            //echo "$nbToTest - ".count($return)."\n";
            if ($lastCount == count($return)) {
                break;
            }
            $lastCount = count($return);
        } while (next($return));
        $return[2] = true;

        return array_sum(array_keys($return));
    }

    public function findBiggestPrimeWidthGmp($limit)
    {
        $i = 0;
        $return =0;
        do {
            $i = gmp_strval(gmp_nextprime($i));
            //echo $i."\n";
            $return += $i;
        } while($i <= $limit);
        $return -= $i;
        return $return;
    }
}
