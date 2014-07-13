<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem12Command extends IndexCommand
{

    protected function init()
    {
        $this->definition = array(
            new InputOption(
                'limit',
                'l',
                InputOption::VALUE_OPTIONAL,
                'Number of divisors to find',
                5
            )
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = intval($input->getOption('limit'));

        $this->findTriangleDivisor($limit, $output);
        $output->writeln( "(".($this->getDuration())."s) ----");
    }

    public function findTriangleDivisor($limit, $output)
    {
        $sum = $it = 1;
        $biggestResult = 0;
        do {
            $sum += ++$it;
            $result = $this->getNbDivisor($sum);
            if (count($result) > $biggestResult) {
                $biggestResult = count($result);
            }
        } while (count($result) < $limit);

        $output->writeln("-------------------");
        $output->writeln(
            $sum . " (". count($result)
            . ") -> " . implode(', ',$result)
        );

        return $sum;
    }

}
