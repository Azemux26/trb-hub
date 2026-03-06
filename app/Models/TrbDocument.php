<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrbDocument extends Model
{
    protected $table = 'trb_documents';

    protected $fillable = [
        'trb_registration_id',
        'document_type_id',
        'original_filename',
        'mime_type',
        'size_bytes',
        'checksum_sha256',
        'drive_file_id',
        'drive_view_url',
        'drive_download_url',
        'system_validation_status',
        'system_validation_message',
        'ocr_status',
        'ocr_confidence',
        'ocr_text_excerpt',
        'admin_verification_status',
        'admin_verification_note',
        'verified_by',
        'verified_at',
        'uploaded_at',
    ];

     protected $casts = [
        'size_bytes' => 'integer',
        'ocr_confidence' => 'decimal:2',
        'verified_at' => 'datetime',
        'uploaded_at' => 'datetime',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(TrbRegistration::class, 'trb_registration_id');
    }

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(MasterDocumentType::class, 'document_type_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
