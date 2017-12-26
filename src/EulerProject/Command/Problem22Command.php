<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem22Command extends IndexCommand
{
    protected $urlFile = 'https://projecteuler.net/project/resources/p022_names.txt';

    protected $letters = array();
    protected $scores = array();


    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
Using names.txt (https://projecteuler.net/project/resources/p022_names.txt), a 46K text file containing over five-thousand first names, begin by sorting it into alphabetical order. Then working out the alphabetical value for each name, multiply this value by its alphabetical position in the list to obtain a name score.

For example, when the list is sorted into alphabetical order, COLIN, which is worth 3 + 15 + 12 + 9 + 14 = 53, is the 938th name in the list. So, COLIN would obtain a score of 938 × 53 = 49714.

What is the total of all the name scores in the file?
EOF;

        // define score rules
        $this->letters = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        // on inverse index + value comme ça le score se fait simplement
        for ($i = 1; $i <= count($this->letters); $i++) {
            $this->scores[] = $i;
        }

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $score = $this->getFileScore();

        $this->writeln("<info>" . $score  ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    public function getFileScore()
    {
        $names = $this->getSortedListNames();
        $score = 0;
        foreach ($names as $k => $name) {
            $score += ($k+1)*$this->getNameScore($name);
        }

        return $score;
    }

    public function getSortedListNames()
    {
        $content = file_get_contents($this->urlFile);
        $content = str_replace(
            array('"', ','),
            array('', PHP_EOL),
            $content
        );
        $names = explode("\n", $content);
        sort($names);

        return $names;
    }

    protected function getNameScore($str)
    {
        return array_sum(
            str_replace(
                $this->letters,
                $this->scores,
                str_split($str)
            )
        );
    }

}
