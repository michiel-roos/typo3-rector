<?php
namespace Ssch\TYPO3Rector\Rector\Command;

use Ssch\TYPO3Rector\Rector\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RectorCommand
 * @package Ssch\TYPO3Rector\Rector\Command
 */
class RectorCommand extends Command
{
    /**
     * Configure
     */
    protected function configure()
    {
        $this->setName('test')->setDescription('Croptesting')->setDefinition([
            new InputOption('target', 't', InputOption::VALUE_OPTIONAL, 'TYPO3 version to target', '10'),
            new InputOption('only', 'o', InputOption::VALUE_OPTIONAL, 'Only report: [breaking, deprecation, important, feature] changes', 'breaking,deprecation,important,feature'),
            new InputOption('indicators', 'i', InputOption::VALUE_OPTIONAL, 'Only report: [strong, weak] matches', 'strong,weak'),
            new InputOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format', 'plain'),
            new InputOption('reportFile', 'r', InputOption::VALUE_OPTIONAL, 'Report file', null),
            new InputOption('templatePath', null, InputOption::VALUE_OPTIONAL, 'Path to template folder'),
        ])->setHelp(<<<EOT
The <info>scan</info> command scans a path for deprecated code</info>.

Scan a folder:
<info>php typo3scan.phar scan ~/tmp/source</info>

Scan a folder for v8 changes:
<info>php typo3scan.phar scan --target 8 ~/tmp/source</info>

Scan a folder and output to report file:
<info>php typo3scan.phar scan --target 8 --reportFile ~/tmp/report.txt ~/tmp/source</info>

Scan a folder for v7 changes and output in markdown:
<info>php typo3scan.phar scan --target 7 --format markdown ~/tmp/source</info>

Scan a folder for v7 WEAK changes and output in markdown:
<info>php typo3scan.phar scan --indicator weak --target 7 --format markdown ~/tmp/source</info>

Scan a folder for v9 changes and output in markdown with custom template:
<info>php typo3scan.phar scan --format markdown --templatePath ~/path/to/templates --path ~/tmp/source</info>

Scan a folder for v7 changes, only show the breaking changes and output in markdown:
<info>php typo3scan.phar scan --target 7 --only breaking --format markdown ~/tmp/source</info>
EOT
        );
    }

    /**
     * Execute
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stdErr = $output;
        if ($output instanceof ConsoleOutputInterface) {
            $stdErr = $output->getErrorOutput();
        }

        // Path to root of this phar file
        $pharPath = \Phar::running();

        foreach (new \DirectoryIterator($pharPath . '/config/') as $fileInfo) {
            if ($fileInfo->isDot()) {
                continue;
            }
            $output->writeln($fileInfo->getFilename());
        }

        // Find rector executable
        // Assume execution from site root where composer.json is stored.
        $workingDir = getcwd();

        $composerJson = $workingDir . DIRECTORY_SEPARATOR . 'composer.json';
        if (!file_exists($composerJson)) {
            $stdErr->writeln('composer.json not found, please run typo3rector from your project root');
        }
        if (!is_readable($composerJson)) {
            $stdErr->writeln('composer.json not readable');
        }

        $composer = json_decode(file_get_contents($composerJson), true);

        $vendorDir = DIRECTORY_SEPARATOR . 'vendor';
        if (is_array($composer) && array_key_exists('config', $composer)) {
            $composerConfig = $composer['config'];
            if (array_key_exists('vendor-dir', $composerConfig)) {
                $vendorDir = $composerConfig['vendor-dir'];
                $vendorDir = DIRECTORY_SEPARATOR . trim($vendorDir, DIRECTORY_SEPARATOR);
            }
        }
        $binDir = $vendorDir . DIRECTORY_SEPARATOR . 'bin';

        $command = $workingDir . $binDir . DIRECTORY_SEPARATOR . 'rector list';

        if (Application::isColorSupported()) {
            $command .= ' --ansi';
        }

        echo passthru($command);

//        $path = realpath($input->getArgument('path'));
//        if (!is_dir($path)) {
//            $stdErr->writeln(sprintf('Path does not exist: "%s"', $input->getArgument('path')));
//            exit;
//        }
        return 0;
    }
}
