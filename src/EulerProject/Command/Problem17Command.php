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

        $return = $this->num_to_letter(10);


        //$return = $this->findNumberLetters($start, $limit);
        $this->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    public function num_to_letter($num, $uppercase = FALSE)
    {
        $num -= 1;

        $letter =   chr(($num % 26) + 97);
        $letter .=  (floor($num/26) > 0) ? str_repeat($letter, floor($num/26)) : '';
        return      ($uppercase ? strtoupper($letter) : $letter);
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

    /*
function convert_number_to_words($number) {
    
    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        1000000             => 'million',
        1000000000          => 'billion',
        1000000000000       => 'trillion',
        1000000000000000    => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );
    
    if (!is_numeric($number)) {
        return false;
    }
    
    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }
    
    $string = $fraction = null;
    
    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }
    
    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }
    
    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }
    
    return $string;
}
     * */

}
