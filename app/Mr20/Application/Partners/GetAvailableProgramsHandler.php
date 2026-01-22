<?php

namespace App\Mr20\Application\Partners;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProgramEloquentRepository;

class GetAvailableProgramsHandler
{
    public function __construct(
        protected ProgramEloquentRepository $programRepository
    ) {
    }

    public function handle(): array
    {
        // Get all active programs
        // Note: In a real application, this might filter by partner eligibility
        $programs = $this->programRepository->findAllActive();

        return [
            'programs' => $programs->toArray(),
        ];
    }
}
