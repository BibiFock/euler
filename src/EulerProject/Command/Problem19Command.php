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

        $this->definition[] = new InputOption(
            'start',
            's',
            InputOption::VALUE_OPTIONAL,
            'start date',
            '1901-01-01'
        );


        $this->definition[] = new InputOption(
            'end',
            'l',
            InputOption::VALUE_OPTIONAL,
            'end date',
            '2000-12-31'
        );
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

        $start = date_create($input->getOption('start'));
        $end = date_create($input->getOption('end'));

        $return = $this->getNbSundays($start, $end);
        $this->writeln("<info>" . $return ."</info> ("
            . ($this->getDuration()) . "s) ----");
        $this->writeln("--------------------------------");
    }

    protected function getNbSundays($start, $end)
    {
        $this->writeln($start->format('Y-m-d') . ' -> ' . $end->format('Y-m-d'));
        $currentTime = $start;
        $while = $nbSundays = 0;
        while ($currentTime->getTimestamp() < $end->getTimestamp() ) {
            if ($currentTime->format('d D') == '01 Sun') {
                $nbSundays++;
                $this->writeln(
                    $currentTime->format('Y-m-d D')
                    . ($currentTime->format('D') == 'Sun' ? ' <info>' . $nbSundays . '</info>' : '' )
                );
            }

            $currentTime->modify('+1 day');

            // use in debug only
            if (++$while%10 == 0 && !$this->askConfirmation()) {
                break;
            }
        }

        return $nbSundays;
    }

}
