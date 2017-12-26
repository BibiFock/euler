<?php

namespace EulerProject\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

class Problem67Command extends Problem18Command
{

    protected $triangleUrl = "https://projecteuler.net/project/resources/p067_triangle.txt";

    protected function init()
    {
        parent::init();

        $this->help = <<<EOF
By starting at the top of the triangle below and moving to adjacent numbers on the row below, the maximum total from top to bottom is 23.

3
7 4
2 4 6
8 5 9 3

That is, 3 + 7 + 4 + 9 = 23.

Find the maximum total from top to bottom in triangle.txt (https://projecteuler.net/project/resources/p067_triangle.txt), a 15K text file containing a triangle with one-hundred rows.

NOTE: This is a much more difficult version of Problem 18. It is not possible to try every route to solve this problem, as there are 299 altogether! If you could check one trillion (1012) routes every second it would take over twenty billion years to check them all. There is an efficient algorithm to solve it. ;o)
EOF;

        $this->listPath = array();
    }


    public function getTriangle()
    {
        $triangle = file_get_contents($this->triangleUrl);
        return trim($triangle);
    }
}
