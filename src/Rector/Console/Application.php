<?php
namespace Ssch\TYPO3Rector\Rector\Console;

use Symfony\Component\Console\Application as BaseApplication;

/**
 * Class Application
 * @package Ssch\TYPO3Rector\Rector\Console
 */
class Application extends BaseApplication
{
    /**
     * Generator: http://www.patorjk.com/software/taag/
     * Font: Slant
     *
     * @var string
     */
    private static $logo = "  ________  ______  ____ _____    ____            __
 /_  __/\ \/ / __ \/ __ \__  /   / __ \___  _____/ /_____  _____
  / /    \  / /_/ / / / //_ <   / /_/ / _ \/ ___/ __/ __ \/ ___/
 / /     / / ____/ /_/ /__/ /  / _, _/  __/ /__/ /_/ /_/ / /
/_/     /_/_/    \____/____/  /_/ |_|\___/\___/\__/\____/_/

       https://github.com/sabbelasichon/typo3-rector

        Hand coded with %s️ by the TYPO3 Rector team

";

    /**
     * @return string
     */
    public function getHelp()
    {
        $love = self::isColorSupported() ? "\e[31m♥\e[0m" : "♥";
        return sprintf(self::$logo, $love) . parent::getHelp();
    }

    /**
     * Check if color output is supported
     */
    public static function isColorSupported(): bool
    {
        if (DIRECTORY_SEPARATOR === '\\') {
            return getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON';
        }
        return \function_exists('posix_isatty') && @posix_isatty(STDOUT);
    }
}
