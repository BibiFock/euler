<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class IndexCommand extends Command
{
    protected $name = null;
    protected $description = "Sample description for our command named test";
    protected $definition = array();
    protected $help = <<<EOT
The <info>test</info> command does things and stuff
EOT;

    protected $debug = false;
    protected $output = null;

    protected $start = null;

    public function __construct()
    {
        $this->name = strtolower(preg_replace(
            '/^.+\\\\([^\\\\]+)command$/i',
            '$1',
            get_class($this)
        ));

        $this->start = microtime(true);
        $this->init();

        return parent::__construct();
    }

    protected function getDuration()
    {
        return microtime(true) - $this->start;
    }

    protected function init()
    {
        $this->definition[] = new InputOption(
            'debug',
            null,
            InputOption::VALUE_NONE,
            'Si défini, mode debug'
        );
    }

    protected function configure()
    {
        $this->setName($this->name)
             ->setDescription($this->description)
             ->setDefinition($this->definition)
             ->setHelp($this->help);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->debug =  ($input->getOption('debug'));
        if ($this->debug) {
            $this->output->writeln("<info>-----------------------</info>");
            $this->output->writeln("<info>DEBUG MODE</info>");
            $this->output->writeln("<info>-----------------------</info>");
        }
    }

    protected function askConfirmation()
    {
        if (!$this->debug) {
            return true;
        }
        $dialog = $this->getHelperSet()->get('dialog');
        return $dialog->askConfirmation(
            $this->output,
            '<question>Continue with this action?</question>',
            false
        );
    }

    protected function writeln($msg, $debug = false)
    {
        if ($debug && !$this->debug) {
            return false;
        }
        $this->output->writeln($msg . " (".$this->getMemoryUsage().")");
    }

    protected function getMemoryUsage()
    {
        $size = memory_get_usage();
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }

    protected static function isPrime($nb)
    {
        if ($nb < 2) {
            return false;
        }

        if ($nb == 2) {
            return true;
        }

        if ($nb%2 == 0) {
            return false;
        }

        for ($i = 3; $i <= ceil(sqrt($nb)); $i +=2 ) {
            if ($nb%$i == 0) {
                return false;
            }
        }

        return true;
    }

    protected static function getNbDivisor($nb)
    {
        $result = array(1);
        $limit = floor($nb/ 2);
        for ($i = 2; $i < $limit; $i++) {
            if ($nb%$i == 0) {
                $result[] = $i;
                $result[] = $nb/$i;
                $limit = $nb/$i;
            }
        }
        $result[] = $nb;
        //showlog($nb . "/" . $count);
        sort($result);

        return $result;
    }

}
