<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    
    protected $fillable = ['name'];

    /**
     * Define the relationship with achievements.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function achievements()
    {
        return $this->hasMany(Achievement::class);
    }

    /**
     * get available badges.
     *
     * @return array
    */
    public static function availableBadges()
    {
        return static::pluck('name')->toArray();
    }

    /**
     * Define the relationship with users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}
