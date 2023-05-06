<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Todo;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        (new Task())->createTask( $request->all());
        return response()->json(['msg'=>'Data has been Created']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
       $task->update($request->all());
       return response()->json("Data has been Updated");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        $task->delete();
        return response()->json(['msg' => 'Data has been deleted!']);
    }

    public function todoStatusChange(Task $task,$id)
    {
        $findData = $task->find($id);
        if ($findData->status == 1){
            $findData->status = 0;
        }else{
            $findData->status = 1;
        }
        $findData->save();
        return response()->json(['msg'=>'Status has been changed!']);
    }
}
