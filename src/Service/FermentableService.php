<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Fermentable;
use App\Repository\FermentableRepository;

class FermentableService
{
    private FermentableRepository $fermentableRepository;

    public function __construct(FermentableRepository $fermentableRepository)
    {
        $this->fermentableRepository = $fermentableRepository;
    }

    public function getById(int $id): ?Fermentable
    {
        return $this->fermentableRepository->find($id);
    }
}
