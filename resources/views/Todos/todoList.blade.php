@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center ">
        <div class="col-md-8">
            <div class="card ">
                <div class="card-header bg-success text-white d-flex justify-content-between">
                    <h2>{{ __('Todo List') }}</h2>
                    <button type="button" class="btn btn-outline-warning" id="createNewTodo">
                        Add New
                    </button>
                </div>
                <div class="card-body todolist">
                    <div class="accordion" id="accordionExample ">
                        @forelse($todos as $key=>$todo)
                        @php
                        $completed = $todo->completed == 1;
                        @endphp
                        <div class="accordion-item ">
                            <div class=" d-flex justify-content-between main_btn ">
                                <div class="d-flex justify-content-between align-items-center">

                                    <input type="checkbox" {{ $completed ? 'checked' : '' }} class="todo_checkbox"
                                        data-id="{{ $todo->id }}">
                                    <h5 class="{{ $completed ? 'text-decoration-line-through' : '' }}" id="todos_title">
                                        {{ $todo->title }}</h5>
                                </div>
                                <div class="action_btn d-flex align-items-center   ">
                                    <i data-id="{{ $todo->id }}"
                                        class="fa fa-eye {{ $completed ? ' text_dark' : 'text-info ' }}  viewTodo"></i>
                                    <i data-id="{{ $todo->id }}"
                                        class="fa fa-edit {{ $completed ? ' text_dark' : 'text-warning ' }}  editTodo"></i>
                                    <i data-id="{{ $todo->id }}"
                                        class="fa fa-trash {{ $completed ? ' text_dark' : 'text-danger' }}  deleteTodo"></i>
                                </div>
                                <button id="collapseBtn{{ $todo->id }}" onclick="setStorageCollpase({{$todo->id }})" class="accordion-button collapsed"  type="button"
                                    data-bs-toggle="collapse" data-bs-target="#collapse{{ $todo->id }}"
                                    aria-expanded="false" aria-controls="collapse{{ $todo->id }}"></button>
                            </div>

                            <div id="collapse{{ $todo->id }}"  class="accordion-collapse collapse "
                                aria-labelledby="heading{{ $todo->id }}" data-bs-parent="#accordionExample">

                                <div class="accordion-body   bg-white">

                                    <div class="btn-group-sm text-center ">
                                        <button class="btn btn-sm btn-outline-warning" id="createNewTask"
                                            data-id="{{ $todo->id }}"> <i class="fa fa-plus"></i></button>
                                    </div>
                                    @foreach ($todo['tasks'] as $Key => $task)
                                    <div
                                        class="alert  {{ $task->status == '1' ? 'alert-success' : 'alert-warning ' }} d-flex justify-content-between align-items-center my-2 p-2  task">
                                        <div>
                                            <input type="checkbox" {{ $task->status == 1 ? 'checked' : '' }}
                                            class="task_checkbox" data-id="{{ $task->id }}">
                                            <strong id="task_name"
                                                class="{{ $task->status == 1 ? 'text-decoration-line-through' : '' }}">
                                                {{ $task->name }}</strong>
                                                @php
                                                    $endData = Carbon\Carbon::parse( $task->start_date ?? $task->created_at);
                                                    $startDate = Carbon\Carbon::parse( $task->end_date ?? today());
                                                    $diff = $startDate->diffInDays($endData);
                                                @endphp

                                                @if (!is_null($task->end_date) &&  $diff >= 0)
                                                    <span class="badge rounded-pill {{ $diff > 0  ?   'bg-success' : 'bg-warning'}} mx-2"> <i class="far fa-clock"></i> {{ $diff }} days </span>
                                                @endif

                                        </div>
                                        <div class="">
                                            <button data-id="{{ $task->id }}"
                                                class="btn btn-sm btn-outline-dark taskview"> <i
                                                    class="fa fa-eye"></i></button>
                                            <button data-id="{{ $task->id }}"
                                                class="btn btn-sm btn-outline-dark editTask"> <i
                                                    class="fa fa-edit"></i></button>
                                            <button data-id="{{ $task->id }}"
                                                class="btn btn-sm btn-outline-dark deleteTask"> <i
                                                    class="fa fa-trash"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @empty
                        @endforelse

                    </div>

                </div>
                <div class="card-footer">
                    {{$todos->links()}}
                </div>
            </div>
        </div>
    </div>
</div>


{{-- Todo modal --}}
<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="todoForm" name="todoForm" class="form-horizontal">
                    <div id="todoError"></div>
                    <input type="hidden" name="td_id" id="td_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter Name"
                                value=""required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="description" name="description" required="" placeholder="Enter Details"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn  btn-primary mt-3" id="saveBtn" value="create">Save
                            changes</button>
                        <button type="submit" class="btn btn-warning mt-3" id="updBtn" value="Update">Save
                            changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Todo modal end --}}


{{-- Task modal --}}
<div class="modal fade" id="taskModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="taskHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="taskForm" name="taskForm" class="form-horizontal">
                    <div id="taskError"></div>
                    <input type="hidden" name="task_id" id="task_id">
                    <input type="hidden" name="todo_id" id="todo_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name"
                                value=""  required="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="desc" name="description" placeholder="Enter Details"
                                class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Start</label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        placeholder="Set Start date">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">End</label>
                                <div class="col-sm-12">
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        placeholder="Set end date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary mt-3" id="saveTask" value="create">Change
                            Task</button>
                        <button type="submit" class="btn btn-warning mt-3" id="updTask" value="create">Change
                            Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- Task modal end --}}

{{-- View Task modal --}}
<div class="modal fade" id="viewTaskModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="detailTitle"></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>Title</th>
                        <td id="tname"></td>
                    </tr>
                    <tr>
                        <th>Details</th>
                        <td id="tdesc"></td>
                    </tr>
                    <tr id="tstart_date">
                        <th>Start</th>
                        <td></td>
                    </tr>
                    <tr id="tend_date">
                        <th>End</th>
                        <td></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td id="tstatus"></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- view Task modal end --}}
@endsection

@section('script')
<script type="text/javascript">

function setStorageCollpase(t){
    sessionStorage.setItem('selectedCollapse', t);
}

var selectedCollapse = sessionStorage.getItem('selectedCollapse');

function extendAccordion(e){
    if(selectedCollapse != null) {
        $('#collapse' +selectedCollapse ).addClass('show');
    }
}
extendAccordion(selectedCollapse)


//====== Task script  ======\\
    @include('scripts/task-script')

//====== Todo script  ======\\
    @include('scripts/todo-script')

</script>

@stop
