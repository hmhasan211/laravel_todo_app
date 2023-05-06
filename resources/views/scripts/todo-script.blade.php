
$(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


//view todo details
    $('body').on('click','.viewTodo',function(){
        var id = $(this).data('id');
        $.ajax({
            type:'GET',
            url:"{{route('todos.show','')}}"+"/"+id,
            success:function(res){
                $('#detailTitle').text('Todo Details')
                $('#viewTaskModel').modal('show');
                $('#tname').text(res.title);
                $('#tdesc').text(res.description);
                $('#tstart_date').hide()
                $('#tend_date').hide()
                if(res.status === 1){
                    $('#tstatus').text('Completed');
                }else{
                    $('#tstatus').text('Pending');
                }
            }
        })
    })
//todo change status
    $(document).on('change', '.todo_checkbox', function() {
        var id = $(this).data('id');

        $.ajax({
            type: "GET",
            dataType: "json",
            url: '{{route("todos.status","")}}'+ '/' + id ,
            success: function (res){
            toastr.success('Data Deleted successfully','Success');
            window.setTimeout(function(){
            location.reload();
            } ,500);

        },
        });
    });

    $('#createNewTodo').click(function() {
        $('#modelHeading').html("Create New Todo");
        $('#updBtn').hide();
        $('#saveBtn').show()
        $('#saveBtn').text("Create-Todo");
        $('#td_id').val('');
        $('#todoForm').trigger("reset");
        $('#ajaxModel').modal('show');
        $('#todoError').html('');
    });
//edit and update todo
    $('body').on('click', '.editTodo', function() {
        var td_id = $(this).data('id');
        $.get("{{ route('todos.index') }}" + '/' + td_id + '/edit', function(data) {
            $('#saveBtn').hide();
            $('#updBtn').show().text('Update')
            $('#modelHeading').html("Edit Todo");
            $('#ajaxModel').modal('show');
            $('#td_id').val(data.id);
            $('#title').val(data.title);
            $('#description').val(data.description);
            $('#todoError').html('');
        })

        $('#updBtn').click(function(e) {
            // var td_id = $(this).data('id');
            e.preventDefault();
            $(this).html('Sending..');
            $.ajax({
                data: $('#todoForm').serialize(),
                url: "{{ route('todos.update', '') }}" + '/' + td_id,
                type: "PUT",
                dataType: 'json',
                success: function(data) {

                    $('#todoForm').trigger("reset");
                    $('#ajaxModel').modal('hide');
                    toastr.success('Data updated successfully','Success');
                    window.setTimeout(function(){
                    location.reload();
                    } ,500);
                },
                error: function (data) {
                    $('#todoError').html("<div class='alert alert-danger py-0'>"+data['responseJSON']['message'] + "</div>");
                    $('#updBtn').html('Update');
                }
            });
        });

    });


    $('#saveBtn').click(function(e) {
        e.preventDefault();
        $(this).html('Sending..');

        $.ajax({
            data: $('#todoForm').serialize(),
            url: "{{ route('todos.store') }}",
            type: "POST",
            dataType: 'json',
            success: function(data) {

                $('#todoForm').trigger("reset");
                $('#ajaxModel').modal('hide');
                toastr.success('Data Saved successfully','Success');
                window.setTimeout(function(){
                location.reload();
                } ,500);
            },
            error: function (data) {
                $('#todoError').html("<div class='alert alert-danger py-0'>"+data['responseJSON']['message'] + "</div>");
                $('#saveBtn').html('Save Changes');
            }
        });
    });


//delete todo
    $('body').on('click', '.deleteTodo', function() {
        var td_id = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
            type: "DELETE",
            url: "{{ route('todos.store') }}" + '/' + td_id,
            success: function(data) {
            toastr.success('Data Deleted successfully','Success');
                window.setTimeout(function(){ location.reload(); } ,500);
            },
            error: function(data) {
                console.log('Error:', data);
            }
        });
    });
});
