<?php

namespace App\Mr20\Application\Partners;

use App\Mr20\Infrastructure\Persistence\Eloquent\Repositories\ProgramEloquentRepository;

class EnrollPartnerInProgramHandler
{
    public function __construct(
        protected ProgramEloquentRepository $programRepository
    ) {
    }

    public function handle(int $programId): array
    {
        $program = $this->programRepository->findById($programId);

        if (!$program) {
            throw new \RuntimeException('Program not found');
        }

        // Note: In a real application, we would create a pivot table entry here
        // For now, we just return confirmation
        return [
            'program_id' => $program->id,
            'status' => 'enrolled',
        ];
    }
}
