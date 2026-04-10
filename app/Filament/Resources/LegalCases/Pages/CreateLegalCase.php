<?php

namespace App\Filament\Resources\LegalCases\Pages;

use App\Filament\Resources\LegalCases\LegalCaseResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLegalCase extends CreateRecord
{
    protected static string $resource = LegalCaseResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $data;
    }

    // protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    // {
    //     // Remove attachments from data to avoid mass assignment error
    //     $attachments = $data['attachments'] ?? [];
    //     unset($data['attachments']);

    //     // Create the LegalCase
    //     $legalCase = static::getModel()::create($data);

    //     // Save attachments using morph relation
    //     foreach ($attachments as $filePath) {
    //         $legalCase->attachments()->create([
    //             'type' => 'court_document', // valid enum value
    //             'file' => $filePath,
    //         ]);
    //     }

    //     return $legalCase;
    // }
}
