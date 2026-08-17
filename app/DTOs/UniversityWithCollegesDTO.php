<?php

namespace App\DTOs;

class UniversityWithCollegesDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly ?string $address,
        public readonly ?string $accreditation,
        public readonly ?string $logo_path,
        public readonly array $colleges, // مصفوفة من CollegeWithDeanDTO
    ) {}

    public static function fromModel($university): self
    {
        $colleges = $university->colleges->map(function ($college) {
            return [
                'id' => $college->id,
                'name' => $college->name,
                'dean' => $college->dean && $college->dean->person ? [
                    'id' => $college->dean->id,
                    'full_name' => $college->dean->person->full_name,
                ] : null,
            ];
        })->values()->toArray();

        return new self(
            id: $university->id,
            name: $university->name,
            address: $university->address,
            accreditation: $university->accreditation,
            logo_path: $university->logo_path,
            colleges: $colleges,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'address' => $this->address,
            'accreditation' => $this->accreditation,
            'logo_path' => $this->logo_path,
            'colleges' => $this->colleges,
        ];
    }
}
