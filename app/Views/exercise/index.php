<!-- ======== @Region: #highlighted ======== -->
<div id="highlighted">
    <div class="container">
        <div class="header">
            <h2 class="page-title">
                <span>CRUD - Exercises</span>
            </h2>
        </div>
    </div>
</div>

<!-- ======== @Region: #content ======== -->
<div id="content">
    <div class="container portfolio">
        <!--Portfolio feature item-->
        <div class="row">
            <div class="col-md-12">
                <h2>
                    CRUD Exercises
                    <button type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#save-record-modal">
                        <i class="fa fa-plus-circle"></i>
                        New Exercise
                    </button>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-striped table-hover" id="exercises-list">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Created At</th>
                            <th class="actions text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Save Modal -->
<div id="save-record-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content form-horizontal">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add new exercise</h4>
            </div>
            <div class="modal-body">

                <input type="hidden" name="task" value="save">
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-3 control-label">Name</label>  
                        <div class="col-md-5">
                            <input value="" type="text" name="name" class="form-control">
                        </div>
                        <div class="col-md-4 errors">
                        </div>
                    </div>
                    <input type="hidden" name="submit" value="submit">
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="id" value="">
                </fieldset>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" data-action="save"><i class="fa fa-save"></i> Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delete-record-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <input type="hidden" name="id" value="">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Exercise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete exercise?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" data-action="delete">Delete</button>
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

    app.table = $('#exercises-list').DataTable({
        "processing": true,
        //"serverSide": true,
        "ajax": {
            "url": "exercise/getall",
            "type": "POST"
        },
        "columns": [
            {"data": "id"},
            {"data": "name"},
            {"data": "created_at"},
            //{"data": null, "defaultContent": "<td class='text-center'><div class='btn-group'><button class='btn btn-default' data-action='edit'><i class='fa fa-pencil'></i></button><button class='btn btn-default' data-action='delete'><i class='fa fa-trash'></i></button></div></td>"}
            {
                "render": function (data, type, full, meta) {
                    return "<td class='text-center'><div class='btn-group'><button class='btn btn-default' data-id='" + full.id + "' data-action='edit'><i class='fa fa-pencil'></i></button><button class='btn btn-default' data-id='" + full.id + "' data-action='delete'><i class='fa fa-trash'></i></button></div></td>";
                }
            }
        ]
    });

    app.bindEvents = function () {
        $('#save-record-modal button[data-action="save"]').click(function (e) {
            e.preventDefault();
            var url = 'exercise/create';
            if ($('#save-record-modal input[name="action"]').val() == 'edit') {
                url = "exercise/edit/" + $('#save-record-modal input[name="id"]').val();
            }
            var successFunction = function (data) {
                var savePopup = $('#save-record-modal');
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
                    app.table.ajax.reload();
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: url,
                dataType: "json",
                data: {name: $('#save-record-modal input[name="name"]').val()}
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
        });

        $('#exercises-list').on('click', 'button[data-action="edit"]', function (e) {
            e.preventDefault();
            var successFunction = function (data) {
                var savePopup = $('#save-record-modal');
                savePopup.find('input[name="id"]').val(data['data']['id']);
                savePopup.find('input[name="name"]').val(data['data']['name']);
                savePopup.find('input[name="action"]').val('edit');
                savePopup.modal("show");
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: "exercise/edit/" + $(this).attr('data-id'),
                dataType: "json",
                data: {}
            }).done(successFunction).fail(function (xhr, result, status) {
                alert('GetPermissions ' + xhr.statusText + ' ' + xhr.responseText + ' ' + xhr.status);
            });
            $('#save-record-modal input[name="name"]').val($());
        });

        $('#exercises-list').on('click', 'button[data-action="delete"]', function (e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            var deletePopup = $('#delete-record-modal');
            deletePopup.find('[name="id"]').val(id);
            deletePopup.modal('show');
        });

        $('#delete-record-modal button[data-action="delete"]').click(function (e) {
            var successFunction = function (data) {
                if (data['status'] == 'ok') {
                    alert(data['message']);
                    $('#delete-record-modal').modal("hide");
                    app.table.ajax.reload();
                } else if (data['status'] == 'error') {
                    alert(data['message']);
                }
            };
            $.ajax({
                type: "POST",
                cache: false,
                url: "exercise/delete/" + $('#delete-record-modal').find('input[name="id"]').val(),
                dataType: "json",
                data: {id: $('#delete-record-modal').find('input[name="id"]').val()}
            }).done(successFunction).fail(function (xhr, result, status) {
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