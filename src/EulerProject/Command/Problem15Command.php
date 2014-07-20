<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem15Command extends IndexCommand
{
    protected $listPath = null;

    protected function init()
    {
        $this->help = <<<EOF
Starting in the top left corner of a 2×2 grid, and only being able to move to the right and down,
 there are exactly 6 routes to the bottom right corner.


How many such routes are there through a 20×20 grid?
EOF;
        $this->definition = array(
            new InputOption(
                'size',
                's',
                InputOption::VALUE_OPTIONAL,
                'Size grid',
                2
            )
        );

        $this->listPath = array();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $size = intval($input->getOption('size'));

        $return = $this->getAllThePath($size);
        //$return = $this->countAllPath($size);
        $output->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->output->writeln("--------------------------------");
    }

    protected function countAllPath($limit)
    {
        $count = 0;
        $y = $x = $limit;
        do {

        } while ($x < $limit);
        for ($x = $limit; $x > 0; $x++) {
             for ($y = 0; $y < count($limit); $y++) {
                 $count++;
             }
        }
        return $count;
    }

    protected function getAllThePath($limit)
    {
        $searchPath = array(
            array(array('x'=> 0, 'y' => 0))
        );
        $limit = $limit/2+1;
        $lastCount = 0;
        $howMuchWhile = 0;
        do {
            $howMuchWhile++;
            $return = array();
            $found = false;

            foreach ($searchPath as $path) {
                end($path);
                $coord = current($path);
                if ($limit/2 == $coord['x'] || $coord['y'] == ($limit)) {
                    $return[] = $path;
                    continue;
                }
                if ($coord['y']+1 <= $limit) {
                    $return[] = array_merge(
                        $path,
                        array(array( 'x' => $coord['x'], 'y' => $coord['y']+1))
                    );
                    $found = true;
                }
                if ($coord['x']+1 <= $limit/2) {
                    $return[] = array_merge(
                        $path,
                        array(array('x' => $coord['x']+1, 'y' => $coord['y']))
                    );
                    $found = true;
                }
            }
            $searchPath = $return;
            $this->output->writeln(implode("\n", array_map(function($row) {
                return implode('=>', array_map(function($row) {
                    return implode('x', $row);
                }, $row));
            }, $return)));

            if ($lastCount != count($searchPath)) {
                $lastCount = count($searchPath);
                $this->output->writeln("Result: " . count($searchPath) . " Finish / " . $lastCount . " path to find");
                if (!$this->askConfirmation()) {
                    break;
                }
            }
        } while($found);
        $this->output->writeln("Nb While: ".$howMuchWhile);

        return count($searchPath);
    }

}
