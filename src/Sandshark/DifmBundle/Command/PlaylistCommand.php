<?php
/**
 * Created by PhpStorm.
 * User: janvernieuwe
 * Date: 23/05/15
 * Time: 19:36
 */

namespace Sandshark\DifmBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class PlaylistCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('playlist:update')
            ->setDescription('Greet someone')
            ->addArgument(
                'site',
                InputArgument::REQUIRED,
                'What site do you want to update?'
            )
            ->addOption(
                'channel',
                null,
                InputOption::VALUE_OPTIONAL,
                'Only opdate this specified channel'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('site');
        if ($name) {
            $text = 'Hello ' . $name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('channel')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}
