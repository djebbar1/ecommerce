<?php

namespace App\Twig;

use App\Repository\CategoriesRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
class AppExtension extends AbstractExtension
{
    private $categoriesRepository;
    public function __construct(CategoriesRepository $categoriesRepository)
    {
        $this->categoriesRepository = $categoriesRepository;
    }
    public function getFunctions()
    {
        return [
            new TwigFunction('categoriesNavbar', [$this,'categories']),
        ];
    }
    public function categories(): array
    {
        return $this->categoriesRepository->findAll();
    }
}