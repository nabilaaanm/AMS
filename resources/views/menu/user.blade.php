@extends('layouts.sidebar') 

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/tabelaset.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Header style */
.header {
    position: relative;
    z-index: 1;
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding: 20px 10px;
    width: 100%;
}

.header h1 {
    color: #4f52ba;
    font-size: 24px;
    margin: 0;
    padding: 10px 0;
}

.add-button {
    background-color: #4f52ba;
    color: white;
    border: none;
    padding: 8px 15px;
    cursor: pointer;
    border-radius: 5px;
    transition: background-color 0.3s;
    font-size: 13px;
    width: 150px;
    font-family: Arial, sans-serif;
    font-weight: bold;
}

.add-button:hover {
    background-color: #3e4a9a;
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

.main-content {
    position: relative;
    z-index: 5;
    width: 95%;
    padding: 15px;
    background-color: #fff;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    margin: 15px auto;
}
</style>

<div class="main">
    <div class="main-content">
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
                <div class="card mb-4 p-4">
                    <h4>Edit User</h4>
                    <form action="{{ route('user.update', $editUser->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama</label>
                                    <input type="text" name="name" class="form-control" value="{{ $editUser->name }}" required>
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
                            <label>Region</label>
                            <select name="region" class="form-control" required>
                                <option value="">Pilih Region</option>
                                @foreach($regions as $region)
                                    <option value="{{ $region->nama_region }}" {{ isset($editUser) && $editUser->region == $region->nama_region ? 'selected' : '' }}>
                                        {{ $region->nama_region }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">Pilih Role</option>
                                <option value="1" {{ $editUser->role == '1' ? 'selected' : '' }}>Super Admin</option>
                                <option value="2" {{ $editUser->role == '2' ? 'selected' : '' }}>Admin</option>
                                <option value="3" {{ $editUser->role == '3' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('user.index') }}" class="btn btn-secondary">Batal</a>
                    </form>
                </div>
            @else
                <div class="header">
                    <h1>Users</h1>
                    <div class="btn-group">
                        <button type="button" class="add-button" data-toggle="modal" data-target="#createUserModal">
                            Tambah User
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
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="4" class="no-data">Tidak ada data pengguna.</td>
                            </tr>
                        @else
                            @foreach($users as $u)
                                <tr>
                                    <td>{{ $u->id }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td>
                                        @switch($u->role)
                                            @case(1)
                                                Super Admin
                                                @break
                                            @case(2)
                                                Admin
                                                @break
                                            @case(3)
                                                User
                                                @break
                                            @default
                                                Unknown Role
                                        @endswitch
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button type="button" class="edit-btn" 
                                                    onclick="editUser({{ $u->id }}, '{{ $u->name }}', '{{ $u->email }}', '{{ $u->mobile_number }}', '{{ $u->region }}', '{{ $u->role }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button type="button" class="delete-btn" 
                                                    onclick="deleteUser('{{ $u->id }}', '{{ $u->email }}')">
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
            <form id="createUserForm" action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP</label>
                        <input type="text" name="mobile_number" id="mobile_number" class="form-control" 
                               value="{{ old('mobile_number') }}" 
                               oninput="validateMobileNumber(this)"
                               maxlength="15" 
                               required>
                        <small class="text-danger" id="mobile_number_error" style="display: none;">
                            Nomor telepon tidak boleh lebih dari 15 digit
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Region</label>
                        <select name="region" class="form-control" required>
                            <option value="">Pilih Region</option>
                            @foreach($regions as $region)
                                <option value="{{ $region->nama_region }}" {{ old('region') == $region->nama_region ? 'selected' : '' }}>
                                    {{ $region->nama_region }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" class="form-control" required>
                            <option value="">Pilih Role</option>
                            <option value="1" {{ old('role') == '1' ? 'selected' : '' }}>Super Admin</option>
                            <option value="2" {{ old('role') == '2' ? 'selected' : '' }}>Admin</option>
                            <option value="3" {{ old('role') == '3' ? 'selected' : '' }}>User</option>
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
                        <label>Nama</label>
                        <input type="text" name="name" id="edit_name" class="form-control" required>
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
                        <label>Region</label>
                        <select name="region" id="edit_region" class="form-control" required>
                            <option value="">Pilih Region</option>
                            @if(isset($regions))
                                @foreach($regions as $region)
                                    <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select name="role" id="edit_role" class="form-control" required>
                            <option value="1">Super Admin</option>
                            <option value="2">Admin</option>
                            <option value="3">User</option>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function validateMobileNumber(input) {
    // Hapus karakter non-digit
    input.value = input.value.replace(/\D/g, '');
    
    const mobileNumberError = document.getElementById('mobile_number_error');
    const submitButton = document.querySelector('#createUserForm button[type="submit"]');
    
    if (input.value.length > 15) {
        input.value = input.value.slice(0, 15); // Potong ke 15 digit
        mobileNumberError.style.display = 'block';
        submitButton.disabled = true;
    } else {
        mobileNumberError.style.display = 'none';
        submitButton.disabled = false;
    }
}

$(document).ready(function() {
    // Handle form submit untuk create user
    $('#createUserForm').on('submit', function(e) {
        e.preventDefault();
        
        // Validasi nomor telepon sebelum submit
        const mobileNumber = document.getElementById('mobile_number').value;
        if (mobileNumber.length > 15) {
            Swal.fire({
                title: 'Error!',
                text: 'Nomor telepon tidak boleh lebih dari 15 digit',
                icon: 'error',
                confirmButtonColor: '#4f52ba'
            });
            return false;
        }
        
        $('#createUserModal').modal('hide');
        
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin menambahkan user ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f52ba',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'User berhasil ditambahkan.',
                            icon: 'success',
                            confirmButtonColor: '#4f52ba',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        Swal.fire('Error!', errorMessage, 'error');
                        $('#createUserModal').modal('show');
                    }
                });
            } else {
                $('#createUserModal').modal('show');
            }
        });
    });

    // Auto hide alert after 3 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 3000);

    // Jika ada form edit user di halaman (bukan modal), populate region
    @if(isset($editUser))
        $('#edit_region').val('{{ $editUser->region }}');
    @endif
});

function editUser(id, name, email, mobileNumber, region, role) {
    $('#edit_name').val(name);
    $('#edit_email').val(email);
    $('#edit_mobile_number').val(mobileNumber);
    $('#edit_region').val(region);
    $('#edit_role').val(role);
    
    $('#editUserForm').attr('action', `/user/${id}`);
    $('#editUserModal').modal('show');
}

function deleteUser(id, email) {
    Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus user dengan email: ${email}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: `/user/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message || 'User berhasil dihapus.',
                            icon: 'success',
                            confirmButtonColor: '#4f52ba',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire('Error!', response.message || 'Gagal menghapus user.', 'error');
                    }
                },
                error: function(xhr) {
                    console.error('Error response:', xhr.responseJSON);
                    let errorMessage = 'Gagal menghapus user.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                }
            });
        }
    });
}

$('#editUserForm').on('submit', function(e) {
    e.preventDefault();
    
    const mobileNumber = $('#edit_mobile_number').val();
    if (mobileNumber.length > 15) {
        Swal.fire({
            title: 'Error!',
            text: 'Nomor telepon tidak boleh lebih dari 15 digit',
            icon: 'error',
            confirmButtonColor: '#4f52ba'
        });
        return false;
    }
    
    $('#editUserModal').modal('hide');
    
    Swal.fire({
        title: 'Konfirmasi',
        text: "Apakah Anda yakin ingin mengupdate user ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#4f52ba',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, update!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'User berhasil diupdate.',
                        icon: 'success',
                        confirmButtonColor: '#4f52ba',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }
                    Swal.fire('Error!', errorMessage, 'error');
                    $('#editUserModal').modal('show');
                }
            });
        } else {
            $('#editUserModal').modal('show');
        }
    });
});
</script>
@endsection
