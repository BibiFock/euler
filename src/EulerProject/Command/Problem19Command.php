<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem19Command extends IndexCommand
{
    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
You are given the following information, but you may prefer to do some research for yourself.

1 Jan 1900 was a Monday.
Thirty days has September,
April, June and November.
All the rest have thirty-one,
Saving February alone,
Which has twenty-eight, rain or shine.
And on leap years, twenty-nine.
A leap year occurs on any year evenly divisible by 4, but not on a century unless it is divisible by 400.
How many Sundays fell on the first of the month during the twentieth century (1 Jan 1901 to 31 Dec 2000)?
EOF;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $triangleLines = explode("\n", $this->getTriangle());
        for ($i = 0, $lines = array(); $i < count($triangleLines)/* && $i < 2*/; $i++){
            $lines[] = explode(" ", $triangleLines[$i]);
        }

        $return = $this->getBestPath($lines);
        if ($this->debug) {
            print_r($return);
        }
        $this->writeln("<info>" . $return['sum'] ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    public function getBestPath($lines, $level = 0, $pos =0)
    {
        $return = null;
        $sort = array();
        if(!isset($lines[$level+1])){
            //si on est bien en bas du tableau on renvoie la valeur trouvée
            $return = array('path' => $pos, 'values' => $lines[$level][$pos], 'sum' => $lines[$level][$pos]);
        }else{// sinon on teste les deux valeurs du dessous et on renvoie la somme la plus grande
            for($i = 0; $i < 2; $i++){
                $tmp = $this->getBestPath($lines, $level+1, $pos+$i);
                $tmp['path'] = $pos."-".$tmp['path'];
                $tmp['sum'] = $lines[$level][$pos]+$tmp['sum'];
                $tmp['values'] = $lines[$level][$pos]."+".$tmp['values'];
                if($return == null || $tmp['sum'] > $return['sum']) $return = $tmp;
            }
        }

        return $return;
    }

    public function getTriangle()
    {
        return <<<EOF
75
95 64
17 47 82
18 35 87 10
20 04 82 47 65
19 01 23 75 03 34
88 02 77 73 07 63 67
99 65 04 28 06 16 70 92
41 41 26 56 83 40 80 70 33
41 48 72 33 47 32 37 16 94 29
53 71 44 65 25 43 91 52 97 51 14
70 11 33 28 77 73 17 78 39 68 17 57
91 71 52 38 17 14 91 43 58 50 27 29 48
63 66 04 68 89 53 67 30 73 16 69 87 40 31
04 62 98 27 23 09 70 98 73 93 38 53 60 04 23
EOF;
    }
}
