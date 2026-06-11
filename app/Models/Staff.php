<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    /** @use HasFactory<\Database\Factories\StaffFactory> */
    use HasFactory;

    /**
     * "staff" sudah merupakan bentuk jamak — set eksplisit agar tidak menjadi "staffs".
     */
    protected $table = 'staff';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'position',
        'unit',
        'nip',
        'email',
        'photo',
        'order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'order' => 'integer',
        ];
    }
}
