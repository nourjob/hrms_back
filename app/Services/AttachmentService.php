<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttachmentService
{
    public function upload(UploadedFile $file, $attachable, int $uploadedBy): Attachment
    {
        $path = $file->store('attachments', 'public');

        return $attachable->attachments()->create([
            'file_path' => $path,
            'file_type' => $file->getClientMimeType(),
            'uploaded_by' => $uploadedBy,
        ]);
    }

    public function delete(Attachment $attachment): void
    {
        Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();
    }
}
