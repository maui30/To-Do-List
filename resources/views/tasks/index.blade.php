@extends('layouts.app')

@section('content')
    <div>
        <h2 class="text-center">To-do List</h2>
        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf
            @method('Post')
            <div class="text-center mt-3">
                <input type="text" name="task" class="bg-white rounded border-1 border-white p-2 mx-1 w-50"
                    placeholder="Enter Task">
                <div class="d-flex align-items-center">
                    <button class="btn btn-dark w-50 mt-2 mx-auto">Save</button>
                </div>
            </div>
        </form>

        <hr class="w-50 m-auto mt-4">
        <div class="d-flex flex-row justify-content-center">
            <div class="p-2">
                <form id="deleteAll" action="{{ route('tasks.deleteAll') }}" method="post">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger" onclick='return deleteAll()'>Delete
                        All</button>
                </form>
            </div>
            <div class="p-2">
                <button class="btn btn-success" id="mark-all-done">Mark All as Done</button>
            </div>
            <div class="p-2">
                <button class="btn btn-secondary" id="mark-all-undone">Mark All as Undone</button>
            </div>

        </div>

        @if ($tasks->count() > 0)
            <div style="max-height: 300px; overflow-y: scroll;" class="mt-2">
                @foreach ($tasks as $task)
                    <div class="w-75 m-auto shadow-sm p-3 mb-3 bg-white rounded">
                        <div class="d-flex justify-content-between">
                            <span class="task-text"
                                style="font-size:19px; {{ $task->done ? 'text-decoration: line-through;' : '' }}"
                                data-task-id="{{ $task->id }}">{{ $task->task }}
                            </span>
                            <div class="form-check">
                                <input type="checkbox"
                                    class="form-check-input border-solid border-1 task-checkbox rounded-circle"
                                    style="height: 1.5em; width:1.5em;  background-color: #ccd7c8;"
                                    data-task-id="{{ $task->id }}" {{ $task->done ? 'checked' : '' }}>
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex">
                            <div class="">
                                <button type="button" class="btn"
                                    data-path="{{ route('tasks.update', ['task' => $task]) }}" data-toggle="modal"
                                    data-target="#updateTask{{ $task->id }}">
                                    Update
                                </button>
                            </div>
                            <div>
                                <form id="deleteForm{{ $task->id }}"
                                    action="{{ route('tasks.delete', ['task' => $task]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" onclick="return deleteTask({{ $task->id }})"
                                        class="btn text-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>


                    <!-- Edit Modal -->
                    <div class="modal fade" id="updateTask{{ $task->id }}" tabindex="-1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>

                                <form action="{{ route('tasks.update', ['task' => $task]) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <input type="text" name="task" placeholder="Enter">
                                    <button type="submit">Update</button>
                                </form>

                            </div>
                        </div>
                    </div>
                    <!--End Update Modal-->
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        const taskCheckboxes = document.querySelectorAll('.task-checkbox');
        const markAllDone = document.getElementById('mark-all-done');
        const markAllUndone = document.getElementById('mark-all-undone');

        taskCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const taskId = this.getAttribute('data-task-id');
                const taskText = document.querySelector(`.task-text[data-task-id="${taskId}"]`);

                if (this.checked) {
                    taskText.style.textDecoration = 'line-through';
                    updateTaskStatus(taskId, 1);
                } else {
                    taskText.style.textDecoration = 'none';
                    updateTaskStatus(taskId, 0);
                }
            });
        });

        function updateTaskStatus(taskId, done) {
            axios.patch(`/${taskId}`, {
                    done: done
                })
                .then(response => {
                    // Handle success if needed
                    console.log(response.data.message);
                })
                .catch(error => {
                    // Handle error if needed
                    console.error("Error updating task status:", error);
                });
        }

        function deleteTask(taskId) {
            if (confirm("Are you sure you want to delete this task?")) {
                document.getElementById('delete-form-' + taskId).submit();
            }

            return false;
        }

        const deleteAll = () => {
            if (confirm("Are you sure you want to delete all the task?")) {
                document.getElementById('deleteAll').submit();
            }

            return false;
        }

        markAllDone.addEventListener('click', function() {
            taskCheckboxes.forEach(function(checkbox) {
                checkbox.checked = true; // Check the checkbox
                const taskId = checkbox.getAttribute('data-task-id');
                const taskText = document.querySelector(`.task-text[data-task-id="${taskId}"]`);
                taskText.style.textDecoration = 'line-through';
                updateTaskStatus(taskId, 1);
            });
        });

        markAllUndone.addEventListener('click', function() {
            taskCheckboxes.forEach(function(checkbox) {
                checkbox.checked = false; // Check the checkbox
                const taskId = checkbox.getAttribute('data-task-id');
                const taskText = document.querySelector(`.task-text[data-task-id="${taskId}"]`);
                taskText.style.textDecoration = '';
                updateTaskStatus(taskId, 0);
            });
        });
    </script>
@endsection
