<?php
namespace App\Services\Contracts;

interface NewsProviderInterface
{
    public function fetchArticles(): array;
}
