<?php

namespace App\Http\Controllers;

use App\Task;
use App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\TaskRepository;

class TaskController extends Controller
{
    /**
     * タスクリポジトリーインスタンス
     *
     * @var TaskRepository
     */

    protected $tasks;


    /**
     * 新しいコントローラインスタンスの作成
     *
     * @param TaskRepository $tasks
     * @return void
     */

    public function __construct(TaskRepository $tasks)
    {
        $this->middleware("auth");

        $this->tasks = $tasks;
    }

    /**
     * ユーザーの全タスクをリスト表示
     *
     * @param Request $request
     * @return Response
     */

    public function index(Request $request)
    {
        return view("tasks.index", [
            "tasks" => $this->tasks->forUser($request->user()),
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            "name" => "required|max:255",
        ]);

        $request->user()->tasks()->create([
            "name" => $request->name,
        ]);

        return redirect("/tasks");
    }

    public function destroy(Request $request, Task $task)
    {
        $this->authorize("destroy", $task);

        $task->delete();

        return redirect("/tasks");
    }
}
