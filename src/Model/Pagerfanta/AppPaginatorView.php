<?php declare(strict_types = 1);

namespace App\Model\Pagerfanta;

use Pagerfanta\View\DefaultView;

class AppPaginatorView extends DefaultView
{
    protected function createDefaultTemplate()
    {
        return new AppPaginatorTemplate();
    }

    protected function getDefaultProximity(): int
    {
        return 1;
    }

    public function getName(): string
    {
        return 'app_paginator';
    }
}
