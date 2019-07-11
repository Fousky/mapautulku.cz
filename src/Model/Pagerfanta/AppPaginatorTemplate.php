<?php declare(strict_types = 1);

namespace App\Model\Pagerfanta;

use Pagerfanta\View\Template\Template;

class AppPaginatorTemplate extends Template
{
    public function container(): string
    {
        return '<div class="pagination-container margin-top-30"><nav class="pagination"><ul>%pages%</ul></div></nav>';
    }

    public function page($page): string
    {
        return sprintf(
            '<li><a href="%s">%s</a></li>',
            $this->generateRoute($page),
            $page
        );
    }

    public function pageWithText($page, $text): string
    {
        return sprintf(
            '<li><a href="%s">%s</a></li>',
            $this->generateRoute($page),
            $page
        );
    }

    public function previousDisabled(): string
    {
        return '<li><a class="disabled"><i class="sl sl-icon-arrow-left"></i></a></li>';
    }

    public function previousEnabled($page): string
    {
        return sprintf(
            '<li><a href="%s"><i class="sl sl-icon-arrow-left"></i></a></li>',
            $this->generateRoute($page)
        );
    }

    public function nextDisabled(): string
    {
        return '<li><a class="disabled"><i class="sl sl-icon-arrow-right"></i></a></li>';
    }

    public function nextEnabled($page): string
    {
        return sprintf(
            '<li><a href="%s" title="Strana %s"><i class="sl sl-icon-arrow-right"></i></a></li>',
            $this->generateRoute($page),
            $page
        );
    }

    public function first(): string
    {
        return $this->page(1);
    }

    public function last($page): string
    {
        return $this->page($page);
    }

    public function current($page): string
    {
        return sprintf(
            '<li><a class="current-page">%s</a></li>',
            $page
        );
    }

    public function separator(): string
    {
        return '<li>...</li>';
    }
}
