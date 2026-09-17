@extends('layouts.master')

@section('title', 'Admin | Users Management')

@section('content')
<div class="container-fluid">
    <div class="card">
       <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Users Management</h4>
            
            @can('add-users')
                <a class="btn btn-info" id="add_user" data-bs-toggle="modal" data-bs-target="#userModal">
                    <i class="ri-add-line align-middle me-1"></i> Create User
                </a>
            @endcan
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table id="user-table" class="table table-hover align-middle table-nowrap mb-3 table-striped w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Username</th> 
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Roles</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ==================== Create/Edit User Modal ==================== -->
<div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
    <form id="userForm">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="user-modal-title">Create User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="user_id">
                    
                    <div class="form-group mb-3">
                        <label>Name</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                        <span id="nameError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter Username">
                        <span id="usernameError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Email">
                        <span id="emailError" class="text-danger error-messages"></span>
                    </div>

                    <div class="form-group mb-3">
                        <label>Phone Number</label>
                        <input type="text" name="phone_num" id="phone_num" class="form-control" placeholder="Enter Phone number"> 
                        <span id="phoneNumError" class="text-danger error-messages"></span>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label>Password <small class="text-muted" id="pass-hint">(Required for new users)</small></label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter password">
                        <span id="passwordError" class="text-danger error-messages"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveUserBtn">Save User</button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- ==================== Assign Roles Modal ==================== -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="assign-role-title">Assign Roles</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="assign_user_id">
                
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    Assigning roles to: <strong id="display-user-name"></strong>
                </div>

                <!-- Select All Checkbox -->
                <div class="form-check mb-3 border-bottom pb-2">
                    <input class="form-check-input" type="checkbox" id="selectAllRoles">
                    <label class="form-check-label fw-bold" for="selectAllRoles">Select All Roles</label>
                </div>

                <div id="roles-list">
                    <p class="text-muted">Loading roles...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-success" id="saveUserRolesBtn">Save Roles</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script-bottom')
<script>
$(document).ready(function() {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    // 1. Initialize DataTable
    var table = $('#user-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.users.index') }}",
        lengthMenu: [[5, 10, 25, 50, -1], [5, 10, 25, 50, "All"]],
        pageLength: 5,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'username', name: 'username', defaultContent: '' }, 
            { data: 'email', name: 'email' },
            { data: 'phone_num', name: 'phone_num', defaultContent: '' }, 
            { data: 'roles', orderable: false, searchable: false, defaultContent: '' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
    });

    // 2. Modal Open Event (For Create User)
    $('#userModal').on('show.bs.modal', function (e) {
        if ($(e.relatedTarget).attr('id') === 'add_user') {
            $('#userForm')[0].reset();
            $('#user_id').val('');
            $('#user-modal-title').text('Create User');
            $('#saveUserBtn').text('Save User');
            $('#pass-hint').text('(Required for new users)');
            $('.error-messages').html('');
        }
    });

    // 3. Save User (Create + Update)
    $('#saveUserBtn').on('click', function() {
        let btn = $(this);
        let originalText = btn.text(); 
        
        btn.html('Saving...').prop('disabled', true);
        $('.error-messages').html('');

        $.ajax({
            url: '{{ route("admin.users.store") }}',
            method: 'POST',
            data: $('#userForm').serialize(), //  Sends all form data including hidden user_id
            success: function(response) {
                let modalEl = document.getElementById('userModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                
                table.draw(); // Refreshes DataTable without page reload
                $('#userForm')[0].reset();
                $('#user_id').val('');
                Swal.fire("Success!", response.success, "success");
            },
            error: function(xhr) {
                let errors = xhr.responseJSON?.errors || {};
                $('#nameError').html(errors.name?.[0] || '');
                $('#usernameError').html(errors.username?.[0] || '');
                $('#emailError').html(errors.email?.[0] || '');
                $('#phoneNumError').html(errors.phone_num?.[0] || ''); 
                $('#passwordError').html(errors.password?.[0] || '');
               
                if (Object.keys(errors).length > 0) {
                    Swal.fire("Error!", "Please fix the highlighted errors", "error");
                } else {
                    Swal.fire("Error!", xhr.responseJSON?.message || "Something went wrong", "error");
                }
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    // 4. Edit User
    $(document).on('click', '.editButton', function() {
        let id = $(this).data('id');
        
        $.ajax({
            url: "{{ url('admin/users') }}/" + id + "/edit",
            method: 'GET',
            success: function(data) {
                $('#user_id').val(data.id);
                $('#name').val(data.name);
                $('#username').val(data.username || '');
                $('#email').val(data.email);
                $('#phone_num').val(data.phone_num || ''); 
                $('#password').val(''); // Clear password for security
                
                $('#user-modal-title').text('Edit User');
                $('#saveUserBtn').text('Update User');
                $('#pass-hint').text('(Leave empty to keep current password)');
                $('.error-messages').html('');
                
                var modalElement = document.getElementById('userModal');
                var modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modalInstance.show();
            },
            error: function() {
                Swal.fire("Error!", "Failed to load user data", "error");
            }
        });
    });

    // 5. ASSIGN ROLES BUTTON CLICK
    $(document).on('click', '.assignRoleBtn', function() {
        let userId = $(this).data('id');
        let userName = $(this).closest('tr').find('td:eq(1)').text(); // Get name from table row
        
        $('#assign_user_id').val(userId);
        $('#display-user-name').text(userName);
        $('#assign-role-title').text('Assign Roles - ' + userName);
        $('#roles-list').html('<p class="text-muted">Loading roles...</p>');
        $('#selectAllRoles').prop('checked', false);
        
        // Fetch from getUserRoles route
        $.ajax({
            url: "{{ url('admin/users') }}/" + userId + "/roles",
            method: 'GET',
            success: function(data) {
                let html = '<div class="row">';
                
                if (data.allRoles.length === 0) {
                    html = '<p class="text-danger">No roles found. Please create roles first.</p>';
                } else {
                    data.allRoles.forEach(function(role) {
                        let isChecked = data.userRoles.includes(role.id) ? 'checked' : '';
                        html += `
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input role-checkbox" type="checkbox" 
                                           value="${role.id}" id="role_${role.id}" ${isChecked}>
                                    <label class="form-check-label" for="role_${role.id}">
                                        <strong>${role.name}</strong>
                                    </label>
                                </div>
                            </div>
                        `;
                    });
                }
                html += '</div>';
                $('#roles-list').html(html);
                
                var modalElement = document.getElementById('assignRoleModal');
                var modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
                modalInstance.show();
            },
            error: function() {
                $('#roles-list').html('<p class="text-danger">Failed to load roles</p>');
            }
        });
    });

    // 6. SELECT ALL TOGGLE
    $('#selectAllRoles').on('change', function() {
        $('.role-checkbox').prop('checked', $(this).is(':checked'));
    });

    // 7. SAVE ROLES (The RBAC Magic)
    $('#saveUserRolesBtn').on('click', function() {
        let btn = $(this);
        let originalText = btn.text();
        btn.html('Saving...').prop('disabled', true);

        let userId = $('#assign_user_id').val();
        let selectedRoles = [];
        
        //  Build array of checked role IDs
        $('.role-checkbox:checked').each(function() {
            selectedRoles.push($(this).val());
        });

        $.ajax({
            url: "{{ url('admin/users') }}/" + userId + "/sync-roles",
            method: 'POST',
            data: {
                roles: selectedRoles, // ✅Sends array: e.g., [1, 3]
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                let modalEl = document.getElementById('assignRoleModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
                
                table.draw(); // ✅ Refreshes table to show new roles
                Swal.fire("Success!", response.success, "success");
            },
            error: function(xhr) {
                Swal.fire("Error!", xhr.responseJSON?.message || "Failed to assign roles", "error");
            },
            complete: function() {
                btn.html(originalText).prop('disabled', false);
            }
        });
    });

    // 8. Delete User
    $(document).on('click', '.delButton', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: "Are you sure?",
            text: "User will be permanently deleted!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ url('admin/users') }}/" + id,
                    method: 'DELETE',
                    success: function(response) {
                        table.draw();
                        Swal.fire("Deleted!", response.success, "success");
                    },
                    error: function() {
                        Swal.fire("Error!", "Failed to delete user", "error");
                    }
                });
            }
        });
    });

    // 9. Fix modal backdrop (Bootstrap bug fix)
    $('#assignRoleModal, #userModal').on('hidden.bs.modal', function () {
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });
});
</script>
@endsection