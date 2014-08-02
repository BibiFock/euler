<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem17Command extends IndexCommand
{
    protected $listPath = null;

    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
If the numbers 1 to 5 are written out in words: one, two, three, four, five, then there are 3 + 3 + 5 + 4 + 4 = 19 letters used in total.

If all the numbers from 1 to 1000 (one thousand) inclusive were written out in words, how many letters would be used?
EOF;

        $this->definition[] = new InputOption(
            'limit',
            'l',
            InputOption::VALUE_OPTIONAL,
            'Limit number',
            5
        );

        $this->definition[] = new InputOption(
            'start',
            's',
            InputOption::VALUE_OPTIONAL,
            'Start number',
            1
        );


        $this->listPath = array();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $limit = $input->getOption("limit");
        $start = $input->getOption("start");

        $return = $this->findNumberLetters($start, $limit);
        $this->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    protected function findNumberLetters($start, $limit)
    {
        $return = array();
        for ($i =$start; $i <= $limit; $i++) {
            $return[] = $this->convertNumber($i);
        }
        if ($this->debug) {
            foreach($return as $row) {
                $this->writeln($row);
            }
        }
        $return = str_replace(' ', '', implode('',$return));

        $this->writeln("<info>" . strlen($return) . "</info>");
    }

    protected function convertNumber($number)
    {
        $dictionary = array(
            1 => 'one',
            2 => 'two',
            3 => 'three',
            4 => 'four',
            5 => 'five',
            6 => 'six',
            7 => 'seven',
            8 => 'eight',
            9 => 'nine',
            10 => 'ten',
            11 => 'eleven',
            12 => 'twelve',
            13 => 'thirteen',
            14 => 'fourteen',
            15 => 'fifteen',
            16 => 'sixteen',
            17 => 'seventeen',
            18 => 'eighteen',
            19 => 'nineteen',
            20 => 'twenty',
            30 => 'thirty',
            40 => 'forty',
            50 => 'fifty',
            60 => 'sixty',
            70 => 'seventy',
            80 => 'eighty',
            90 => 'ninety',
            100 => 'hundred',
            1000 => 'thousand',
        );
        krsort($dictionary);
        $return = "";
        foreach ($dictionary as $key => $dic) {
            if ($number == 0) {
                break;
            }
            if ($key > $number) {
                continue;
            }
            if ($key >= 100) {
                $iteration = floor($number/$key);
                $return .= " " . $dictionary[$iteration];
                $return .= " " . $dic;
                $number -= $iteration*$key;
                if ($number > 0) {
                    $return .= " and";
                }
                continue;
            }

            $number -= $key;
            $return .= " " . $dic;
        }

        return $return;
    }

}
