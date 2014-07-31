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
        $return = "";
        for ($i =$start; $i <= $limit; $i++) {
            $return .= $this->convertNumber($i);
        }

        if ($this->debug) {
            $this->writeln($return);
        }

        $this->writeln("<info>" . strlen($return) . "</info>");
    }

    protected function convertNumber($number)
    {
        $digits = str_split($number);

        $return = "";
        for ($i = 0; $i < count($digits); $i++) {
            $pos = count($digits)-$i;
            $return .= $this->getNumberNames($pos, $digits[$i]);
        }

        return $return;
    }

    protected function getNumberNames($pos, $digit)
    {
        $numbers = array(
            array(
                'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight',
                'nine', 'ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen',
                'sixteen', 'seventeen', 'eighteen', 'nineteen'
            ),
            array(
                'twenty', 'thirty', 'fourty', 'fifty', 'sixty', 'seventy',
                'eighty', 'ninety'
            ),
            array( 'hundred', 'hundreds' ),
            array( 'thousand', 'thousands')
        );

        return $numbers[$pos][$digit-1];
    }


}
