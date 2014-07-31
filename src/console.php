<?php

ini_set('memory_limit', '512M');

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

$console = new Application('My Silex Application', 'n/a');
$console->getDefinition()->addOption(new InputOption('--env', '-e', InputOption::VALUE_REQUIRED, 'The Environment name.', 'dev'));
$console->setDispatcher($app['dispatcher']);
$console
    ->register('my-command')
    ->setDefinition(array(
        // new InputOption('some-option', null, InputOption::VALUE_NONE, 'Some help'),
    ))
    ->setDescription('My command description')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {
        // do something
    })
;

$console->addCommands(array(
    new EulerProject\Command\IndexCommand()
));
for ($i = 0; $i < 452; $i++) {
    $class = "EulerProject\Command\Problem" . $i . "Command";
    if (class_exists($class)) {
        $console->addCommands(array(
            new $class()
        ));
    }
}

return $console;
