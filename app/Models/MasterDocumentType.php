<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterDocumentType extends Model
{
    protected $table = 'master_document_types';

    protected $fillable = [
        'code',
        'name',
        'google_drive_folder',
        'description',
        'is_required',
        'allowed_mime_types',
        'max_size_mb',
        'ocr_enabled',
        'ocr_keywords',
        'ocr_min_confidence',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'allowed_mime_types' => 'array',
        'max_size_mb' => 'integer',
        'ocr_enabled' => 'boolean',
        'ocr_keywords' => 'array',
        'ocr_min_confidence' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(TrbDocument::class, 'document_type_id');
    }
}
