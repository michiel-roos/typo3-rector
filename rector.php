<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::SETS, [
        SetList::PRIVATIZATION, SetList::SOLID, SetList::DEAD_CODE, SetList::CODING_STYLE, SetList::CODE_QUALITY]
    );

    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);
};
