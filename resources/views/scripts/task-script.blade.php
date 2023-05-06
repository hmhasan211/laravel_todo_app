
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

//task status change
    $(document).on('click', '.task_checkbox', function() {
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
            url: "{{ route('tasks.status', '') }}" + "/" + id,
            success: function(res) {
            toastr.success('Status changed successfully','Success');
            window.setTimeout(function(){
            location.reload();
            } ,500);
            },
        });
    });

    $('#createNewTask ').click(function() {
        $('#taskHeading').html("Create New Task");
        $('#updTask').hide();
        $('#saveTask').show().text("Create-Task");
        $('#task_id').val('');
        $('#todo_id').val($(this).data("id"));
        $('#taskForm').trigger("reset");
        $('#taskModel').modal('show');
        $('#taskError').html('');
    });

//edit task
    $('body').on('click', '.editTask', function() {
        let task_id = $(this).data('id');
        $.get("{{ route('tasks.index') }}" + '/' + task_id + '/edit', function(data) {
            console.log(data);
            $('#taskHeading').text("Edit Task");
            $('#saveTask').hide();
            $('#updTask').show().text('Update')
            $('#taskModel').modal('show');
            $('#task_id').val(data.id);
            $('#todo_id').val(data.todo_id);
            $('#name').val(data.name);
            $('#desc').val(data.description);
            $('#start_date').val(data.start_date);
            $('#end_date').val(data.start_date);
            $('#taskError').html('');
        })

        $('#updTask').click(function(e){
            e.preventDefault();
            $(this).html('Updating..');
            $.ajax({
                type:"PUT",
                data: $('#taskForm').serialize(),
                url:"{{route('tasks.update','')}}"+'/'+task_id,
                dataType: 'json',
                success:function(res){
                    console.log(res);
                    $('#taskForm').trigger("reset");
                    $('#taskModel').modal('hide');
                    toastr.success('Data updated successfully','Success');
                    window.setTimeout(function(){
                    location.reload();
                    } ,500);
                },
                error: function (data) {
                    $('#taskError').html("<div class='alert alert-danger py-0'>"+data['responseJSON']['message'] + "</div>");
                    $('#updTask').html('Update Task');
                }
            })
        })
    });


//save task
    $('#saveTask').click(function(e) {
        e.preventDefault();
        $(this).html('Sending..');

        $.ajax({
            data: $('#taskForm').serialize(),
            url: "{{ route('tasks.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {
                $('#taskForm').trigger("reset");
                $('#taskModel').modal('hide');
                toastr.success('Data Saved successfully','Success');
                window.setTimeout(function(){
                location.reload();
                } ,500);
            },
            error: function (data) {
                $('#taskError').html("<div class='alert alert-danger py-0'>"+data['responseJSON']['message'] + "</div>");
                $('#saveTask').html('Create Todo');
            }
        });
    });

//view task
    $('body').on('click','.taskview',function(){
        var id = $(this).data('id');
        var completed = 1;
        $.ajax({
            type:'GET',
            url:"{{route('tasks.show','')}}"+'/'+id,
            success:function(res){
                $('#detailTitle').text('Task Details')
                $('#viewTaskModel').modal('show');
                $('#tname').text(res.name);
                $('#tdesc').text(res.description);
                $('#tstart_date').show()
                $('#tend_date').show()
                $('#tstart_date td').text(res.start_date);
                $('#tend_date td').text(res.end_date);
                if(res.status == 1){
                    $('#tstatus').text('Completed');
                }else{
                    $('#tstatus').text('Pending');
                }
            }
        })
    })
//delete task
    $('body').on('click', '.deleteTask', function() {

        var taskId = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
            type: "DELETE",
            url: "{{ route('tasks.destroy', '') }}" + '/' + taskId,
            success: function(data) {
            toastr.success('Data Deleted successfully','Success');
            window.setTimeout(function(){
            location.reload();
            } ,500);
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    });
});
