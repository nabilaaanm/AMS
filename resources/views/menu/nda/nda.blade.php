@extends('layouts.sidebar')
@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .main-content {
        position: relative;
        z-index: 5;
        width: 98%;
        padding: 15px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin: 15px auto;
        transition: all 0.3s ease;
    }

    .container {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 30px;
        transition: all 0.3s ease;
    }

    .header {
        position: relative;
        z-index: 1;
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        margin-bottom: 20px;
        padding: 20px 10px;
        width: 100%;
    }

    .header h1 {
        color: #4f52ba;
        font-size: 24px;
        margin: 0;
        padding: 10px 0;
    }

    .header .btn-group {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 10px;
    }

    .header .btn {
        white-space: nowrap;
        margin: 5px 0;
        padding: 8px 15px;
    }

    .table-responsive {
        width: 100%;
        max-height: 80vh;
        overflow-x: auto;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        padding: 0;
        margin: 10px 0;
        overflow-y: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        background-color: transparent;
    }

    table thead {
        background-color: rgba(209, 210, 241, 0.316);
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        overflow: hidden;
        display: block;
    }

    table tbody {
        display: block;
        max-height: 70vh;
        overflow-y: auto;
    }

    table tbody tr {
        display: table;
        width: 100%;
        table-layout: fixed;
    }

    /* Style untuk header tabel */
    table thead th {
        color: #4f52ba;
        border-bottom: 2px solid #e3e6f0;
        font-weight: bold;
        padding: 15px;
        font-size: 14px;
        position: sticky;
        top: 0;
        z-index: 1;
        text-align: center;
    }

    /* Style untuk sel tabel */
    table tbody td {
        padding: 15px;
        border-bottom: 1px solid #e3e6f0;
        color: #5a5c69;
        font-size: 14px;
        vertical-align: middle;
        font-weight: normal;
        text-align: center;
    }

    /* Hover effect pada baris tabel */
    table tbody tr:hover {
        background-color: rgba(209, 210, 241, 0.316);
    }

    /* Style untuk tombol aksi */
    .action-buttons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }

    .view-btn {
        color: #4f52ba;
        background: none;
        border: none;
        cursor: pointer;
        font-size: 16px;
        padding: 5px;
        transition: color 0.3s ease;
    }

    .view-btn:hover {
        color: #3a3e9b;
    }

    /* Style untuk pesan tidak ada data */
    .no-data {
        text-align: center;
        color: rgba(79, 82, 186, 0.2);
        padding: 20px;
        font-style: italic;
    }

    .btn {
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .btn-primary {
        background-color: #4f52ba;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #3e4a9a;
    }

    /* Responsive styles */
    @media (max-width: 768px) {
        .main-content {
            width: 100%;
            margin: 10px 0;
        }
        
        .container {
            padding: 0 10px;
        }
        
        .header {
            padding: 15px 5px;
        }
        
        .btn {
            width: 100%;
            margin-top: 10px;
        }
    }

    /* Width settings for specific columns */
    th:nth-child(1), td:nth-child(1) { /* No */
        width: 5%;
    }

    th:nth-child(2), td:nth-child(2) { /* Nama */
        width: 20%;
    }

    th:nth-child(3), td:nth-child(3) { /* No. KTP */
        width: 15%;
    }

    th:nth-child(4), td:nth-child(4) { /* Alamat */
        width: 25%;
    }

    th:nth-child(5), td:nth-child(5) { /* Tanggal */
        width: 12%;
    }

    th:nth-child(6), td:nth-child(6) { /* Tanggal Berlaku */
        width: 12%;
    }

    th:nth-child(7), td:nth-child(7) { /* Aksi */
        width: 11%;
    }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js"></script>
</head>

<div class="main">
    <div class="container">
        <div class="header">
            <h1>NDA</h1>
            <div class="btn-group">
                <button class="btn btn-primary" onclick="openAddNdaModal('internal')">Tambah NDA Internal</button>
                <button class="btn btn-primary" onclick="openAddNdaModal('eksternal')">Tambah NDA Eksternal</button>
            </div>
        </div>

        {{-- Tabel NDA --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No. KTP</th>
                        <th>Alamat</th>
                        <th>Tanggal Sekarang</th>
                        <th>Berlaku Sampai Dengan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @if($ndas->isEmpty())
                        <tr>
                            <td colspan="7" class="no-data">Tidak ada data NDA.</td>
                        </tr>
                    @else
                        @foreach($ndas as $index => $nda)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $nda->name }}</td>
                                <td>{{ $nda->no_ktp }}</td>
                                <td>{{ $nda->alamat }}</td>
                                <td>{{ \Carbon\Carbon::parse($nda->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($nda->tanggal_berlaku)->format('d-m-Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('nda.download', $nda->id) }}" class="view-btn">Lihat</a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah NDA --}}
        <div class="modal fade" id="addNdaModal" tabindex="-1" role="dialog" aria-labelledby="addNdaModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNdaModalLabel">Tambah NDA Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addNdaForm" method="POST" action="{{ route('nda.store') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nda_name">Nama</label>
                                <input type="text" class="form-control" id="nda_name" name="nda_name" required>
                            </div>
                            <div class="form-group">
                                <label for="no_ktp">No. KTP</label>
                                <input type="text" class="form-control" id="no_ktp" name="no_ktp" required>
                            </div>
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" required>
                            </div>
                            <div class="form-group" id="perusahaanGroup" style="display: none;">
                                <label for="perusahaan">Perusahaan</label>
                                <input type="text" class="form-control" id="perusahaan" name="perusahaan">
                            </div>
                            <div class="form-group" id="regionGroup" style="display: none;">
                                <label for="region">Region</label>
                                <select name="region" id="region" class="form-control">
                                    <option value="">Pilih Region</option>
                                    @foreach($regions as $region)
                                        <option value="{{ $region->nama_region }}">{{ $region->nama_region }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group" id="bagianGroup" style="display: none;">
                                <label for="bagian">Bagian</label>
                                <input type="text" class="form-control" id="bagian" name="bagian">
                            </div>
                            <div class="form-group">
                                <label for="signature">Tanda Tangan</label>
                                <canvas id="signature-pad" style="border: 1px solid #000; width: 100%; height: 150px; cursor: crosshair;"></canvas>
                                <button type="button" id="clear-signature" class="btn btn-danger">Hapus Tanda Tangan</button>
                                <input type="hidden" name="signature" id="signature">
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
    </div>
</div>

<script>
function openAddNdaModal(type) {
    $('#addNdaModal').modal('show');
    
    if (type === 'eksternal') {
        $('#perusahaanGroup').show();
        $('#regionGroup').show();
        $('#bagianGroup').show();
    } else {
        $('#perusahaanGroup').hide();
        $('#regionGroup').hide();
        $('#bagianGroup').hide();
    }
}

$(document).ready(function() {
    const canvas = document.getElementById('signature-pad');
    const signaturePad = new SignaturePad(canvas);

    document.getElementById('clear-signature').addEventListener('click', function () {
        signaturePad.clear();
    });

    $('#addNdaForm').on('submit', function(e) {
        e.preventDefault();
        
        if (!signaturePad.isEmpty()) {
            const dataURL = signaturePad.toDataURL();
            document.getElementById('signature').value = dataURL;
            
            $('#addNdaModal').modal('hide');
            
            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin ingin menambahkan NDA ini?",
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
                                text: 'NDA berhasil ditambahkan.',
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
                            $('#addNdaModal').modal('show');
                        }
                    });
                } else {
                    $('#addNdaModal').modal('show');
                }
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: 'Silakan tambahkan tanda tangan.',
                icon: 'error',
                confirmButtonColor: '#4f52ba'
            });
        }
    });
});
</script>
@endsection
