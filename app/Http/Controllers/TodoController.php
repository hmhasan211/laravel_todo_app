<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Http\Requests\StoreTodoRequest;
use App\Http\Requests\UpdateTodoRequest;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $todos = (new Todo())->getAllTodos();
        return view('todos.todoList',compact('todos'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTodoRequest $request)
    {
        $requestData = $request->all();
        $requestData['user_id'] = auth()->id();
        (new Todo())->createTodo($requestData);
        return response()->json(['msg'=>'Data has been Created!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Todo $todo)
    {
        return response()->json($todo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Todo $todo)
    {
        if($todo->user_id == auth()->id()){
            return response()->json($todo);
        }
        abort(401);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTodoRequest $request, Todo $todo)
    {
        $updateTodo = $request->all();
        $updateTodo['user_id'] = auth()->id();
        $todo->update($updateTodo);
        return response()->json($updateTodo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Todo $todo)
    {
        if($todo->user_id == auth()->id()){
            $todo->delete();
            $msg['msg'] = 'Data has been deleted!';
        }else{
           $msg['msg'] = 'you dont have access to delete';
        }
        return response()->json($msg);
    }

    public function todoStatusChange(Todo $todo,$id)
    {
       $findData = $todo->find($id);
       if ($findData->completed == 1){
           $findData->completed = 0;
       }else{
           $findData->completed = 1;
       }
       $findData->save();
        return response()->json(['msg'=>'Status has been changed!']);
    }
}
