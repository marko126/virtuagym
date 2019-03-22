<?php $plan = $data['plan']; ?>
<!-- ======== @Region: #highlighted ======== -->
<div id="highlighted">
    <div class="container">
        <div class="header">
            <h2 class="page-title">
                <span><?= $plan['name'] ?></span>
            </h2>
        </div>
    </div>
</div>

<!-- ======== @Region: #content ======== -->
<div id="content" class="demos">
    <div class="container">
        <!--Portfolio feature item-->
        <div class="row">
            <div class="col-md-12">
                <h2>
                    Workout days
                    <button type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#save-plan-modal">
                        <i class="fa fa-plus-circle"></i>
                        New Day
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <?php foreach ($plan->workoutDays as $workoutDay): ?>
            <div class="col-sm-6 workout-day" id="workout-day-<?= $workoutDay['id'] ?>">
                <h3>
                    <?= $workoutDay['name'] ?>
                    <button type="button" class="pull-right btn btn-success" data-toggle="modal" data-id="<?= $workoutDay['id'] ?>" data-action="save">
                        <i class="fa fa-plus-circle"></i>
                        Exercise
                    </button>
                    <button type="button" class="pull-right btn btn-danger" data-toggle="modal" data-id="<?= $workoutDay['id'] ?>" data-action="delete">
                        <i class="fa fa-minus-circle"></i>
                    </button>
                    <button type="button" class="pull-right btn btn-warning" data-toggle="modal" data-id="<?= $workoutDay['id'] ?>" data-action="edit">
                        <i class="fa fa-pencil-square"></i>
                    </button>
                </h3>
                <div class="demo-block">
                    <?php foreach ($workoutDay->exercises as $exercise): ?>
                    <div class="workout-day-exercise" id="workout-day-exercise-<?= $exercise->id ?>">
                        <?= $exercise->name; ?>
                        <span class="pull-right" data-id="<?= $exercise->id ?>" data-action="delete"><i class="fa fa-remove"></i></span>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <hr />
        <div class="row">
            <div class="col-md-12">
                <h2>
                    Assigned Users
                    <button type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#save-user-modal">
                        <i class="fa fa-plus-circle"></i>
                        Assign User
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">		
                <table class="table table-striped table-hover" id="user-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th class="actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="col-md-12">
                <div id="notif-errors"></div>
            </div>
        </div>
    </div>
</div>

<!-- Save Day Modal -->
<div id="save-plan-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add new day</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="submit" value="submit">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="id" value="">
                <input type="hidden" name="plan_id" value="<?= $plan['id'] ?>">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>  
                        <div class="col-md-5">
                            <input value="" type="text" name="name" class="form-control">
                        </div>
                        <div class="col-md-4 errors">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-action="save"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Save Exercise Modal -->
<div id="save-exercise-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add new exercise</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="submit" value="submit">
                <input type="hidden" name="action" value="create">
                <input type="hidden" name="workout_day_id" value="">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Exercise</label>  
                        <div class="col-md-5">
                            <select class="form-control" name="exercise_id">
				<option>--- Choose Exercise ---</option>
                            </select>
                        </div>
                        <div class="col-md-4 errors">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-action="save"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Assign User Modal -->
<div id="save-user-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Assign new user</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="submit" value="submit">
                <input type="hidden" name="plan_id" value="<?= $plan->id ?>">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Users</label>  
                        <div class="col-md-5">
                            <select class="form-control" name="user_id">
				<option>--- Choose User ---</option>
                                <?php foreach ($data['unassignedUsers'] as $user): ?>
                                <option value="<?= $user->id ?>"><?= $user->name ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 errors">
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-action="save"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Day Modal -->
<div class="modal fade" id="delete-plan-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <input type="hidden" name="id" value="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Workout day</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete workout day?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" data-action="delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Exercise Modal -->
<div class="modal fade" id="delete-exercise-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <input type="hidden" name="workout_day_id" value="">
    <input type="hidden" name="exercise_id" value="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Exercise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete esercise?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" data-action="delete">Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Unassign User Modal -->
<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <input type="hidden" name="user_id" value="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Unasign User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to unassign this user from the plan?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" data-action="delete">Remove</button>
            </div>
        </div>
    </div>
</div>

<?php
ob_start();
?>
<script src="https://cdn.datatables.net/1.10.11/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.1.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.1.2/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.0.2/js/dataTables.responsive.min.js"></script>
<script type="text/javascript">

    var app = {};
    
    app.table = $('#user-list').DataTable({
        "processing": true,
        //"serverSide": true,
        "ajax": {
            "url": "/public/plan/getassignedusers",
            "type": "POST",
            "data": {plan_id: <?= $plan->id ?>}
        },
        "columns": [
            {"data": "id"},
            {"data": "first_name"},
            {"data": "last_name"},
            {"data": "email"},
            {
                "render": function (data, type, full, meta) {
                    return "<td class='text-center'><button class='btn btn-default' data-id='" + full.id + "' data-action='delete'><i class='fa fa-trash'></i></button></td>";
                }
            }
        ]
    });

    app.bindEvents = function () {
    
        $('#save-plan-modal button[data-action="save"]').click(function (e) {
            e.preventDefault();
            var url = '/public/workoutday/create';
            if ($('#save-plan-modal input[name="action"]').val() == 'edit') {
                url = "/public/workoutday/edit/" + $('#save-plan-modal input[name="id"]').val();
            }
            var successFunction = function (data) {
                var savePopup = $('#save-plan-modal');
                if (data['status'] == 'error') {
                    for (var key in data['errors']) {
                        var ul = '<ul style="color: red">';
                        for (var key2 in data['errors'][key]) {
                            ul += '<li class="error">' + data['errors'][key][key2] + '</li>';
                        }
                        ul += '</ul>';
                        savePopup.find('input[name="' + key + '"]').parent().next('.errors').html(ul);
                    }
                } else {
                    savePopup.modal("hide");
                    savePopup.find('input[name="name"]').val('');
                    savePopup.find('input[name="id"]').val('');
                    savePopup.find('input[name="action"]').val('create');
                    location.reload();
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: url,
                dataType: "json",
                data: {
                    name: $('#save-plan-modal input[name="name"]').val(),
                    plan_id: $('#save-plan-modal input[name="plan_id"]').val()
                }
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });

        $('.workout-day').on('click', 'button[data-action="edit"]', function (e) {
            e.preventDefault();
            var successFunction = function (data) {
                var savePopup = $('#save-plan-modal');
                savePopup.find('input[name="id"]').val(data['data']['id']);
                savePopup.find('input[name="name"]').val(data['data']['name']);
                savePopup.find('input[name="action"]').val('edit');
                savePopup.modal("show");
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: "/public/workoutday/edit/" + $(this).attr('data-id'),
                dataType: "json",
                data: {}
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
            $('#save-plan-modal input[name="name"]').val('');
        });

        $('.workout-day').on('click', 'button[data-action="delete"]', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deletePopup = $('#delete-plan-modal');
            deletePopup.find('[name="id"]').val(id);
            deletePopup.modal('show');
        });

        $('#delete-plan-modal button[data-action="delete"]').click(function (e) {
            var successFunction = function (data) {
                if (data['status'] == 'ok') {
                    alert(data['message']);
                    $('#delete-plan-modal').modal("hide");
                    location.reload();
                } else if (data['status'] == 'error') {
                    alert(data['message']);
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: "/public/workoutday/delete/" + $('#delete-plan-modal').find('input[name="id"]').val(),
                dataType: "json",
                data: {id: $('#delete-plan-modal').find('input[name="id"]').val()}
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });
        
        $('.workout-day').on('click', 'button[data-action="save"]', function (e) {
            e.preventDefault();
            var elem = $(this);
            var successFunction = function (data) {
                var savePopup = $('#save-exercise-modal');
                savePopup.find('[name="workout_day_id"]').val(elem.attr('data-id'));
                var exercise_html = '<option>--- Choose Exercise ---</option>';
                for (var key in data['data']) {
                    exercise_html += '<option value="' + key + '">' + data['data'][key] + '</option>';
                }
                savePopup.find('[name="exercise_id"]').html(exercise_html);
                savePopup.find('[name="action"]').val('create');
                savePopup.modal("show");
            };
            $.ajax({
                type: "GET",
                cache: false,
                url: "/public/workoutday/getexercises/" + elem.attr('data-id'),
                dataType: "json",
                data: {}
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });

        $('#save-exercise-modal button[data-action="save"]').click(function (e) {
            e.preventDefault();
            var url = '/public/workoutday/addexercise';
            var successFunction = function (data) {
                var savePopup = $('#save-exercise-modal');
                if (data['status'] == 'error') {
                    for (var key in data['errors']) {
                        var ul = '<ul style="color: red">';
                        for (var key2 in data['errors'][key]) {
                            ul += '<li class="error">' + data['errors'][key][key2] + '</li>';
                        }
                        ul += '</ul>';
                        savePopup.find('[name="' + key + '"]').parent().next('.errors').html(ul);
                    }
                } else {
                    $('#workout-day-' + $('#save-exercise-modal [name="workout_day_id"]').val()).find('.demo-block').append('<div class="workout-day-exercise" id="workout-day-exercise-' + data['data']['id'] + '">' + data['data']['name'] + '<span class="pull-right" data-id="' + data['data']['id'] + '" data-action="delete"><i class="fa fa-remove"></i></span></div>');
                    savePopup.modal("hide");
                    savePopup.find('[name="workout_day_id"]').val('');
                    savePopup.find('[name="exercise_id"]').val('');
                    savePopup.find('[name="action"]').val('create');
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: url,
                dataType: "json",
                data: {
                    workout_day_id: $('#save-exercise-modal [name="workout_day_id"]').val(),
                    exercise_id: $('#save-exercise-modal [name="exercise_id"]').val()
                }
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });
        
        $('.demo-block').on('click', 'span[data-action="delete"]', function (e) {
            e.preventDefault();
            var exercise_id = $(this).attr('data-id');
            var parent = $(this).closest('.workout-day');
            var workout_day_id = parent.attr('id');
            workout_day_id = workout_day_id.replace('workout-day-', '');
            var deletePopup = $('#delete-exercise-modal');
            deletePopup.find('[name="exercise_id"]').val(exercise_id);
            deletePopup.find('[name="workout_day_id"]').val(workout_day_id);
            deletePopup.modal('show');
        });

        $('#delete-exercise-modal button[data-action="delete"]').click(function (e) {
            var workout_day_id = $('#delete-exercise-modal').find('[name="workout_day_id"]').val();
            var exercise_id = $('#delete-exercise-modal').find('[name="exercise_id"]').val();
            var successFunction = function (data) {
                if (data['status'] == 'ok') {
                    $('#delete-exercise-modal').modal("hide");
                    alert(data['message']);
                    $('#workout-day-exercise-' + exercise_id).remove();
                } else if (data['status'] == 'error') {
                    alert(data['message']);
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: "/public/workoutday/removeexercise/",
                dataType: "json",
                data: {exercise_id: exercise_id, workout_day_id: workout_day_id}
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });
        
        $('#save-user-modal button[data-action="save"]').click(function (e) {
            e.preventDefault();
            var url = '/public/plan/adduser';
            var savePopup = $('#save-user-modal');
            var successFunction = function (data) {
                if (data['status'] == 'error') {
                    for (var key in data['errors']) {
                        var ul = '<ul style="color: red">';
                        for (var key2 in data['errors'][key]) {
                            ul += '<li class="error">' + data['errors'][key][key2] + '</li>';
                        }
                        ul += '</ul>';
                        savePopup.find('[name="' + key + '"]').parent().next('.errors').html(ul);
                    }
                } else {
                    savePopup.modal("hide");
                    savePopup.find('[name="plan_id"]').val('');
                    savePopup.find('[name="user_id"]').val('');
                    app.table.ajax.reload();
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: url,
                dataType: "json",
                data: {
                    plan_id: savePopup.find('[name="plan_id"]').val(),
                    user_id: savePopup.find('[name="user_id"]').val()
                }
            }).done(successFunction).fail(function (xhr, result, status) {
                savePopup.modal("hide");
                app.table.ajax.reload();
                //$('#notif-errors').html(xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });
        
        $('#user-list').on('click', 'button[data-action="delete"]', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deletePopup = $('#delete-user-modal');
            deletePopup.find('[name="user_id"]').val(id);
            deletePopup.modal('show');
        });

        $('#delete-user-modal button[data-action="delete"]').click(function (e) {
            var successFunction = function (data) {
                if (data['status'] == 'ok') {
                    alert(data['message']);
                    $('#delete-user-modal').modal("hide");
                    app.table.ajax.reload();
                } else if (data['status'] == 'error') {
                    alert(data['message']);
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: "/public/plan/removeuser",
                dataType: "json",
                data: {
                    user_id: $('#delete-user-modal').find('input[name="user_id"]').val(),
                    plan_id: <?= $plan->id ?>
                }
            }).done(successFunction).fail(function (xhr, result, status) {
                $('#delete-user-modal').modal("hide");
                app.table.ajax.reload();
                //$('#notif-errors').html(xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });
    };

    app.init = function () {
        app.bindEvents();
    };

    $(app.init);

</script>
<?php
$this->customJs[] = ob_get_clean();
?>