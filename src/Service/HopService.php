<?php
declare(strict_types = 1);

namespace App\Service;

use App\Entity\Hop;
use App\Repository\HopRepository;

class HopService
{
    private HopRepository $hopRepository;

    public function __construct(HopRepository $hopRepository)
    {
        $this->hopRepository = $hopRepository;
    }

    public function getById(int $id): ?Hop
    {
        return $this->hopRepository->find($id);
    }
}
