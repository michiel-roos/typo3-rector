<?php

declare(strict_types=1);

namespace TYPO3\CMS\IndexedSearch\Controller;

if (class_exists(SearchFormController::class)) {
    return;
}

class SearchFormController
{
    public function pi_list_browseresults($showResultCount = true, $addString = '', $addPart = '', $freeIndexUid = -1)
    {
        return '';
    }

    protected function renderPagination(): void
    {
    }
}
