<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSignature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'signature_uuid',
        'path',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the signature.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the latest signature for a specific user.
     *
     * @param int $userId
     * @return UserSignature|null
     */
    public static function getLatestForUser(int $userId): ?UserSignature
    {
        return self::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Scope a query to only include signatures of a specific user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to order by latest.
     */
    public function scopeLatestFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get all request-user records that used this signature.
     */
    public function requestUsers()
    {
        return $this->hasMany(RequestUser::class, 'user_signature_id');
    }

    // ================================================================
    // ============= دوال مساعدة جديدة للتوقيعات ====================
    // ================================================================

    /**
     * الحصول على المسار الكامل للصورة (إن وجد)
     * 
     * @return string|null
     */
    public function getFullPath(): ?string
    {
        // إذا كان هناك مسار مخزّن، استخدمه
        if ($this->path) {
            return $this->path;
        }

        // وإلا استخدم الـ UUID لتكوين المسار (الطريقة الموصى بها)
        if ($this->signature_uuid) {
            return 'private/signatures/' . $this->signature_uuid . '.png';
        }

        return null;
    }

    /**
     * التحقق من وجود ملف التوقيع في التخزين
     * 
     * @return bool
     */
    public function fileExists(): bool
    {
        $path = $this->getFullPath();
        if (!$path) {
            return false;
        }

        return \Illuminate\Support\Facades\Storage::disk('local')->exists($path);
    }

    /**
     * الحصول على محتوى التوقيع بصيغة Base64 (جاهز للـ PDF)
     * 
     * @return string|null
     */
    public function getBase64Content(): ?string
    {
        $path = $this->getFullPath();
        if (!$path || !$this->fileExists()) {
            return null;
        }

        $binary = \Illuminate\Support\Facades\Storage::disk('local')->get($path);
        return 'data:image/png;base64,' . base64_encode($binary);
    }

    /**
     * الحصول على اسم الملف فقط (بدون المسار)
     * 
     * @return string|null
     */
    public function getFilename(): ?string
    {
        if ($this->path) {
            return basename($this->path);
        }

        if ($this->signature_uuid) {
            return $this->signature_uuid . '.png';
        }

        return null;
    }
}
