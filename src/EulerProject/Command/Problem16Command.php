<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem16Command extends IndexCommand
{
    protected $listPath = null;

    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
215 = 32768 and the sum of its digits is 3 + 2 + 7 + 6 + 8 = 26.

What is the sum of the digits of the number 21000?
EOF;

        $this->definition[] = new InputOption(
            'pow',
            'p',
            InputOption::VALUE_OPTIONAL,
            'Pow Pow Pow',
            15
        );

        $this->listPath = array();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $pow = $input->getOption("pow");

        $result = gmp_pow(2, $pow);
        $result = gmp_strval($result)."\n";
        $return = 0;
        for ($i = 0; $i < strlen($result); $i++) {
            $return += $result[$i];
        }
        $this->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }


}
