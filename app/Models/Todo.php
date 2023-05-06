<?php

namespace App\Models;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'completed', 'user_id'];

    /**
     * @return HasMany
     */
    final public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('id','desc');
    }

    /**
     * @return LengthAwarePaginator
     */
    final public function getAllTodos(): LengthAwarePaginator
    {
         return self::query()
             ->with('tasks')
             ->where('user_id',auth()->id())
             ->latest()
             ->paginate(10);
    }

    /**
     * @param array $input
     * @return void
     */
    final public function createTodo(array $input): void
    {
         self::query()->create($input);
    }



}
