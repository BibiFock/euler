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

    protected $way = array();

    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
Starting in the top left corner of a 2×2 grid, and only being able to move to the right and down,
 there are exactly 6 routes to the bottom right corner.


How many such routes are there through a 20×20 grid?
EOF;
        $this->definition[] = new InputOption(
            'size',
            's',
            InputOption::VALUE_OPTIONAL,
            'Size grid',
            2
        );

        $this->listPath = array();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $size = intval($input->getOption('size'));

        $return = $this->countAllPath($size);
        $this->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    protected function countAllPath($size, $x = 0, $y = 0)
    {
        $count = 0;
        if ($x == $size && $y == $size) {
            return 1;
        }

        $key = $x . 'x'. $y;

        if (!empty($this->ways[$key])) {
            return $this->ways[$key];
        }

        if ($x + 1 <= $size) {
            $count += $this->countAllPath($size, $x+1, $y);
        }

        if ($y + 1 <= $size) {
            $count += $this->countAllPath($size, $x, $y+1);
        }

        $this->ways[$key] = $count;

        return $count;
    }

    protected function countAllPath2($size, $x = 0, $y = 0)
    {
        $positions = array();
        $key = $x . "x" . $y;
        if (!empty($this->way[$key])) {
            return $this->way[$key];
        }

        if ($x + 1  <= $size) {
            $positions[] = array($x+1,$y);
        }
        if ($y + 1  <= $size) {
            $positions[] = array($x,$y+1);
        }

        if (empty($positions)) {
            return 1;
        }

        $return = 0;
        foreach ($positions as $coord) {
           $return += $this->countAllPath($size, $coord[0], $coord[1]);
        }

        $this->way[$key] = $return;

        return $return;
    }

}
