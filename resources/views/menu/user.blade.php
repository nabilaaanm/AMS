@extends('layouts.sidebar') 

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<style>
/* Override style dari tabelaset.css */
.table-responsive {
    margin: 20px 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

table {
    width: 100%;
    margin-bottom: 0;
}

table thead {
    display: table;
    width: 100%; 
    table-layout: fixed;
}

table tbody {
    display: block;
    max-height: calc(100vh - 250px); /* Sesuaikan dengan tinggi header dan margin */
    overflow-y: auto;
}

table tbody tr {
    display: table;
    width: 100%;
    table-layout: fixed;
}

/* Atur lebar kolom */
th:nth-child(1), td:nth-child(1) { /* ID */
    width: 8%;
}

th:nth-child(2), td:nth-child(2) { /* Email */
    width: 52%;
    text-align: left; /* Email rata kiri */
}

th:nth-child(3), td:nth-child(3) { /* Role */
    width: 20%;
}

th:nth-child(4), td:nth-child(4) { /* Aksi */
    width: 20%;
}

/* Style untuk tombol aksi */
.action-buttons {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.action-buttons button {
    background: none;
    border: none;
    padding: 5px;
    cursor: pointer;
}

.edit-btn {
    color: #4f52ba;
}

.delete-btn {
    color: #dc3545;
}

.edit-btn:hover {
    color: #3a3e9b;
}

.delete-btn:hover {
    color: #b71c1c;
}

/* Style untuk header tabel */
table thead th {
    background-color: rgba(209, 210, 241, 0.316);
    color: #4f52ba;
    font-weight: 600;
    padding: 15px;
    text-align: center;
}

/* Style untuk sel tabel */
table tbody td {
    padding: 12px 15px;
    vertical-align: middle;
}

/* Hover effect pada baris tabel */
table tbody tr:hover {
    background-color: rgba(209, 210, 241, 0.1);
}

/* Style untuk pesan tidak ada data */
.no-data {
    text-align: center;
    color: #6c757d;
    padding: 20px;
    font-style: italic;
}

/* Container style */
.container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

/* Header style */
.header {
    margin-bottom: 20px;
}

.header h1 {
    margin: 0;
    color: #4f52ba;
    font-size: 24px;
}

/* Tombol tambah user */
.btn-primary {
    background-color: #4f52ba;
    border: none;
    padding: 8px 16px;
}

.btn-primary:hover {
    background-color: #3a3e9b;
}

/* Style untuk modal delete */
#deleteUserModal .modal-header {
    background-color: #dc3545;
    color: white;
    border-bottom: none;
}

#deleteUserModal .modal-header .close {
    color: white;
    opacity: 1;
}

#deleteUserModal .modal-body {
    padding: 20px;
    font-size: 16px;
}

#deleteUserModal .modal-footer {
    border-top: 1px solid #dee2e6;
    padding: 15px;
}

#deleteUserModal .btn-danger {
    background-color: #dc3545;
    border: none;
}

#deleteUserModal .btn-danger:hover {
    background-color: #c82333;
}

#delete_user_email {
    color: #dc3545;
}
</style>

<div class="main">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form untuk Create/Edit User --}}
        @if(isset($editUser))
            <div class="card mb-4">
                <h4>Edit User</h4>
                <form action="{{ route('user.update', $editUser->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Depan</label>
                                <input type="text" name="first_name" class="form-control" value="{{ $editUser->first_name }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Belakang</label>
                                <input type="text" name="last_name" class="form-control" value="{{ $editUser->last_name }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $editUser->email }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="mobile_number" class="form-control" value="{{ $editUser->mobile_number }}" required>
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="company" class="form-control" value="{{ $editUser->company }}" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin" {{ $editUser->role == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ $editUser->role == 'user' ? 'selected' : '' }}>User</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Update User</button>
                    <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                </form>
            </div>
        @else
            <div class="header">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <h1>Users</h1>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                        <i class="fas fa-plus"></i> Tambah User
                    </button>
                </div>
            </div>
        @endif

        {{-- Tabel User --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($user->isEmpty())
                        <tr>
                            <td colspan="4" class="no-data">Tidak ada data pengguna.</td>
                        </tr>
                    @else
                        @foreach($user as $u)
                            <tr>
                                <td>{{ $u->id }}</td>
                                <td>{{ $u->email }}</td>
                                <td>{{ $u->role }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button type="button" class="edit-btn" 
                                                onclick="editUser({{ $u->id }}, '{{ $u->first_name }}', '{{ $u->last_name }}', 
                                                   '{{ $u->email }}', '{{ $u->mobile_number }}', 
                                                   '{{ $u->company }}', '{{ $u->role }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="delete-btn" 
                                                onclick="deleteUser({{ $u->id }}, '{{ $u->email }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Create User --}}
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createUserModalLabel">Tambah User Baru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Depan</label>
                        <input type="text" name="first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Belakang</label>
                        <input type="text" name="last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="mobile_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="company" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Edit User --}}
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel">Edit User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Depan</label>
                        <input type="text" name="first_name" id="edit_first_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Nama Belakang</label>
                        <input type="text" name="last_name" id="edit_last_name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Password (Kosongkan jika tidak ingin mengubah)</label>
                        <input type="password" name="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="mobile_number" id="edit_mobile_number" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="company" id="edit_company" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Delete User --}}
<div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteUserModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="deleteUserForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus user ini?</p>
                    <p class="mb-0">Email: <span id="delete_user_email" class="font-weight-bold"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function editUser(id, firstName, lastName, email, mobileNumber, company, role) {
    $('#edit_first_name').val(firstName);
    $('#edit_last_name').val(lastName);
    $('#edit_email').val(email);
    $('#edit_mobile_number').val(mobileNumber);
    $('#edit_company').val(company);
    $('#edit_role').val(role);
    
    $('#editUserForm').attr('action', `/user/${id}`);
    $('#editUserModal').modal('show');
}

function deleteUser(id, email) {
    $('#delete_user_email').text(email);
    $('#deleteUserForm').attr('action', `/user/${id}`);
    $('#deleteUserModal').modal('show');
}

// Tambahkan script untuk menangani pesan alert
$(document).ready(function() {
    // Auto hide alert after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);
});
</script>
@endsection
