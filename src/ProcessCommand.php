<?php

namespace TextWheel\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use TextWheel\TextWheel;
use TextWheel\Utils\Debugger;

/**
 * Process a Rules File on a text.
 *
 * @author James <james@rezo.net>
 */
class ProcessCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('process')
            ->setDescription('Process rules on a text')
            ->addArgument(
                'rules',
                InputArgument::REQUIRED,
                'The rules file'
            )
            ->addArgument(
                'text',
                InputArgument::REQUIRED,
                'The text to transform'
            )
            ->addOption(
                'profile',
                null,
                InputOption::VALUE_NONE,
                'If set, this will compute some profiling datas'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rules = $input->getArgument('rules');
        $text = $input->getArgument('text');
        if (is_file($text)) {
            $text = file_get_contents($text);
        }
        
        if ($input->getOption('profile')) {
            $textwheel = new Debugger($rules);
            $text = $textwheel->process($text);
        } else {
            $textwheel = new TextWheel($rules);
            $text = $textwheel->text($text);
        }


        $output->writeln($text);

        if ($input->getOption('profile')) {
            $output->writeln($this->printDebug($textwheel->getDebugProcess()));
        }
    }

    private function printDebug(array $debug)
    {
        $results = '';

        foreach ($debug['results'] as $profile) {
            foreach ($profile as $key => $value) {
                $results .= '<info>' . $key . '</info>:' . $value . PHP_EOL;
            }
        }
        $results .= '<info>Total</info>:' . $debug['total'] . ' ms'.PHP_EOL;

        return $results;
    }
}
