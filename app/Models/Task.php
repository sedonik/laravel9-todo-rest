<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * class Task
 */
class Task extends Model
{
    use HasFactory;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'status', 'priority', 'title', 'description', 'created_at', 'completed_at'];

    /**
     * @param $taskId
     * @param $userId
     * @return Model|\Illuminate\Database\Query\Builder|object|null
     */
    public static function getTask($taskId, $userId)
    {
        $task = DB::table('tasks')
            ->where('id', $taskId)
            ->where('user_id', $userId)
            ->first();

        return $task;
    }

    /**
     * @param $filter
     * @param $userId
     * @return \Illuminate\Support\Collection
     */
    public static function findAll($filter, $userId)
    {
        $tasks = DB::table('tasks')
            ->where($filter['filter'])
            ->whereRaw("(MATCH (title) AGAINST (? IN BOOLEAN MODE))" , [$filter['filter']['title']])
            ->where("user_id" , $userId)
            ->orderBy("priority", $filter['sort']['priority'])
            ->orderBy("completed_at", $filter['sort']['completed_at'])
            ->orderBy("created_at", $filter['sort']['created_at'])
            ->get();


        return $tasks;
    }

    /**
     * @param $taskId
     * @param $userId
     * @return void
     */
    public static function deleteTask($taskId, $userId)
    {
        DB::table('tasks')
            ->where('user_id', $userId)
            ->delete($taskId);
    }
}
