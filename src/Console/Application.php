<?php

namespace Jmerilainen\SatisBuilder\Console;

use Jmerilainen\SatisBuilder\Console\Commands\BuildCommand;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct()
    {
        parent::__construct('satis.json builder', '1.0');

        $this->add(new BuildCommand());
    }
}
