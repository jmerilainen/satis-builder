<?php

namespace Jmerilainen\SatisBuilder\Console\Commands;

use Frc\Satis\Builder\JsonBuilder;
use Jmerilainen\SatisBuilder\SatisBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BuildCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('build')
            ->setDescription('Build satis.json file from folder strcuture and zip\'s.')
            ->addOption('from', null, InputOption::VALUE_REQUIRED, '', 'packages')
            ->addOption('external', null, InputOption::VALUE_REQUIRED, '', 'external.json')
            ->addOption('output', null, InputOption::VALUE_REQUIRED, '', 'satis.json')
            ->addOption('name', null, InputOption::VALUE_REQUIRED)
            ->addOption('homepage', null, InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $required = [
            'name',
            'homepage',
        ];

        $errors = false;

        foreach ($required as $option) {
            if (! $input->getOption($option)) {
                $output->writeln("<error>--$option option is required</error>");
                $errors = true;
            }
        }

        if ($errors) {
            return Command::FAILURE;
        }

        $from = $input->getOption('from');
        $to = $input->getOption('output');
        $output->writeln("Building from \"<comment>{$from}</comment>\"");

        (new SatisBuilder())
            ->external($input->getOption('external'))
            ->from($input->getOption('from'))
            ->name($input->getOption('name'))
            ->homepage($input->getOption('homepage'))
            ->save($input->getOption('output'));

        $output->writeln("Saved to \"<comment>{$to}</comment>\"");

        return Command::SUCCESS;
    }
}
