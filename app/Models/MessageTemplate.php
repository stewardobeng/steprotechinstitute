<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'subject',
        'message',
        'description',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get templates by category
     */
    public static function getByCategory(string $category)
    {
        return static::where('category', $category)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all active templates grouped by category
     */
    public static function getGroupedByCategory()
    {
        return static::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get()
            ->groupBy('category');
    }

    /**
     * Replace variables in message
     */
    public function replaceVariables(array $data): string
    {
        $message = $this->message;
        foreach ($data as $key => $value) {
            $message = str_replace('{{' . $key . '}}', $value, $message);
        }
        return $message;
    }

    /**
     * Replace variables in subject
     */
    public function replaceVariablesInSubject(array $data): string
    {
        $subject = $this->subject;
        foreach ($data as $key => $value) {
            $subject = str_replace('{{' . $key . '}}', $value, $subject);
        }
        return $subject;
    }
}
