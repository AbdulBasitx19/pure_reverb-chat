@extends('layouts.master')

@section('title',  'Admin | Roles Management')
   


@section('content')
<div class="card">
<div class="card-header">
    @can('add-roles')
                <a class="btn btn-info mb-2 float-end" id="add_role">
                    Create Role
                </a>
                @endcan
        <h4 class="card-title">Roles Management</h4>
</div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                

                <table id="role-table" class="table table-hover align-middle table-nowrap mb-3 table-striped w-100">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ==================== Create / Edit Role Modal ==================== -->
<div class="modal fade ajax-modal" id="roleModal" tabindex="-1" aria-hidden="true">
    <form id="roleForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="role-modal-title">Create Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="role_id" id="role_id">

                    <div class="form-group mb-3">
                        <label for="name">Role Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name">
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveBtn">Save Role</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ==================== Permissions Modal ==================== -->
<div class="modal fade" id="permissionsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="perm-modal-title">Manage Permissions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="perm_role_id">

                <!-- Select All -->
                <div class="form-check mb-3 border-bottom pb-2">
                    <input class="form-check-input" type="checkbox" id="selectAll">
                    <label class="form-check-label fw-bold" for="selectAll">Select All</label>
                </div>

                <!-- Grouped checkboxes injected by JS -->
                <div id="permissions-list">
                    <p class="text-muted">Loading...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="savePermissionsBtn">Save Permissions</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-bottom')
<script>
$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Initialize DataTable
    var table = $('#role-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.roles.index') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
    });

    // Open Modal for Create
    $('#add_role').on('click', function() {
        $('#roleForm')[0].reset();
        $('#role_id').val('');
        $('#role-modal-title').text('Create Role');
        $('#saveBtn').text('Save Role');
        $('.error-messages').html('');
        $('#roleModal').modal('show');
    });

   // Save Role (Create + Update)
$('#saveBtn').on('click', function() {
    let btn = $(this);
    let originalText = btn.text(); // "Save Role" || "Update Role"
    
    btn.html('Saving...').prop('disabled', true);
    $('.error-messages').html('');

    $.ajax({
        url: '{{ route("admin.roles.store") }}',
        method: 'POST',
        data: $('#roleForm').serialize(),
        success: function(response) {
            table.draw();
            
            // Modal close 
            let modalEl = document.getElementById('roleModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
            
            $('#roleForm')[0].reset();
            $('#role_id').val('');
            Swal.fire("Success!", response.success, "success");
        },
        error: function(xhr) {
            if (xhr.responseJSON?.errors?.name) {
                $('#nameError').html(xhr.responseJSON.errors.name[0]);
            } else {
                Swal.fire("Error!", "Something went wrong!", "error");
            }
        },
        complete: function() {
            //  Button reset 
            btn.html(originalText).prop('disabled', false);
        }
    });
});

    // Edit Role
    $(document).on('click', '.editButton', function() {
        let id = $(this).data('id');

        $.get("{{ url('admin/roles') }}/" + id + "/edit", function(role) {
            $('#role_id').val(role.id);
            $('#name').val(role.name);
            $('#role-modal-title').text('Edit Role');
            $('#saveBtn').text('Update Role');
            $('.error-messages').html('');
            $('#roleModal').modal('show');
        });
    });

    // Delete Role
     $(document).on('click', '.delButton', function() {
        let id = $(this).data('id');

        Swal.fire({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this role!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) { // <-- Yeh check zaroori hai
                $.ajax({
                    url: "{{ url('admin/roles') }}/" + id,
                    method: 'DELETE',
                    success: function(response) {
                        table.draw(); // DataTable refresh karega
                        Swal.fire("Deleted!", response.success || "Role has been deleted.", "success");
                    },
                    error: function(xhr) {
                        Swal.fire("Error!", xhr.responseJSON?.message || "Failed to delete role", "error");
                    }
                });
            }
        });
    });

    // Open Permissions Modal
    $(document).on('click', '.permButton', function() {
        let id = $(this).data('id');
        $('#perm_role_id').val(id);
        $('#perm-modal-title').text('Manage Permissions');
        $('#permissions-list').html('<p class="text-muted">Loading...</p>');
        $('#selectAll').prop('checked', false);
        $('#permissionsModal').modal('show');

        $.get("{{ url('admin/roles') }}/" + id + "/permissions", function(data) {
            $('#perm-modal-title').text('Permissions — ' + data.role.name);

            let html = '';

            if (Object.keys(data.allPermissions).length === 0) {
                html = '<p class="text-muted">No permissions found. Create some first.</p>';
            } else {
                $.each(data.allPermissions, function(moduleName, permissions) {
                    html += `
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted fw-bold mb-2" 
                                style="font-size:0.75rem; letter-spacing:0.05em;">
                                ${moduleName}
                            </h6>
                            <div class="row">
                    `;
                    permissions.forEach(function(perm) {
                        let checked = data.rolePermissions.includes(perm.id) ? 'checked' : '';
                        let badge = perm.parent
                            ? `<span class="badge bg-secondary ms-1" style="font-size:0.7rem">${perm.parent}</span>`
                            : '';
                        html += `
                            <div class="col-md-4 mb-1">
                                <div class="form-check">
                                    <input class="form-check-input perm-checkbox" type="checkbox"
                                        value="${perm.id}"
                                        id="perm_${perm.id}" ${checked}>
                                    <label class="form-check-label" for="perm_${perm.id}">
                                        ${perm.name} ${badge}
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                    html += `</div></div><hr class="my-2">`;
                });
            }

            $('#permissions-list').html(html);
        });
    });

    // Select All toggle
    $('#selectAll').on('change', function() {
        $('.perm-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Auto-update Select All when individual boxes change
    $(document).on('change', '.perm-checkbox', function() {
        let total = $('.perm-checkbox').length;
        let checked = $('.perm-checkbox:checked').length;
        $('#selectAll').prop('checked', total === checked);
    });

   // Save Permissions
$('#savePermissionsBtn').on('click', function() {
    let btn = $(this);
    let originalText = btn.text();
    
    btn.html('Saving...').prop('disabled', true);

    let roleId = $('#perm_role_id').val();
    let selected = [];

    $('.perm-checkbox:checked').each(function() {
        selected.push($(this).val());
    });

    $.ajax({
        url: "{{ url('admin/roles') }}/" + roleId + "/sync-permissions",
        method: 'POST',
        data: {
            permissions: selected,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            let modalEl = document.getElementById('permissionsModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) {
                modal.hide();
            }
            table.draw();
            Swal.fire("Saved!", response.success, "success");
        },
        error: function() {
            Swal.fire("Error!", "Failed to save permissions", "error");
        },
        complete: function() {
            btn.html(originalText).prop('disabled', false);
        }
    });
});

    // Fix modal backdrop
    $('#permissionsModal').on('hidden.bs.modal', function() {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    $('.ajax-modal').on('hidden.bs.modal', function() {
        $('.error-messages').html('');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

});
</script>
@endsection