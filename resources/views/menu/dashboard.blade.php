@extends('layouts.sidebar')
@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('css/general.css') }}">
    <link rel="stylesheet" href="{{ asset('css/card.css') }}">
    <script src="https://kit.fontawesome.com/bdb0f9e3e2.js" crossorigin="anonymous"></script>
</head>

<style>
    .main-content {
    position: relative;
    z-index: 5;
}

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
    }

    .main-content {
        width: 100%;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .button {
        background-color: #4f52ba;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
        transition: background-color 0.3s;
    }

    .button:hover {
        background-color: #3e4a9a;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
        cursor: pointer;
    }

    .card-body {
        padding: 15px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #f7f7f7;
    }

    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1000; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgba(0, 0, 0, 0.7); /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 10% auto; /* 10% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        border-radius: 8px; /* Rounded corners */
        width: 90%; /* Could be more or less, depending on screen size */
        max-width: 600px; /* Maximum width for larger screens */
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2); /* Shadow for depth */
    }

    .modal-content img {
        width: 100%; /* Gambar akan mengambil lebar penuh dari modal */
        height: auto; /* Menjaga rasio aspek gambar */
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    label {
        font-weight: bold;
        margin-bottom: 5px;
        display: block;
    }

    /* Gaya untuk mengatur warna font dan font-family */
    .semantik-title {
        color: #4f52ba; 
        font-family: "Inter", sans-serif; 
        font-size: 20px;
    }

    /* Gaya untuk input form-control saat hover */
    .form-control:hover {
        border-color: #4f52ba; /* Mengubah warna border saat hover */
        box-shadow: 0 0 5px rgba(79, 82, 186, 0.5); /* Menambahkan efek shadow saat hover */
    }

    /* Gaya untuk memperbesar input file */
    .form-control.file-input {
        height: 45px; /* Mengatur tinggi input */
        font-size: 16px; /* Mengatur ukuran font */
    }

    .btn-upload {
        background-color: #4f52ba;
        color: white;
    }

    .btn-upload:hover {
        background-color: #3e4a9a;
        color: white;
    }

    .main-content {
        width: 100%;
        padding: 20px;
    }

    .button {
        background-color: #4f52ba;
        color: white;
        border: none;
        padding: 10px 15px;
        cursor: pointer;
        border-radius: 5px;
    }

    .button:hover {
        background-color: #3e4a9a;
    }

    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .card-body {
        padding: 15px;
    }

    .card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background-color: #f7f7f7;
    }

    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto; /* 15% from the top and centered */
        padding: 20px;
        border: 1px solid #888;
        width: 80%; /* Could be more or less, depending on screen size */
    }

    /* Menghilangkan outline pada input dan textarea saat fokus */
input, textarea {
    border: 2px solid #ccc; /* Tambahkan border biasa */
    outline: none; /* Hilangkan garis putus-putus */
    padding: 8px;
    border-radius: 5px;
    width: 100%;
    font-size: 16px;
}

/* Saat input dalam keadaan aktif (focus), tambahkan efek border lebih jelas */
input:focus, textarea:focus {
    border: 2px solid #4f52ba; /* Warna biru agar lebih elegan */
    box-shadow: 0 0 5px rgba(79, 82, 186, 0.5);
}

/* Untuk memperbaiki tampilan tombol */
button {
    cursor: pointer;
    padding: 10px 15px;
    background-color: #4f52ba;
    color: white;
    border: none;
    border-radius: 5px;
}

button:hover {
    background-color: #3d3f9f;
}
    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .hidden {
        display: none; /* Sembunyikan elemen */
    }

    .header {
        position: relative; /* Pastikan header memiliki posisi relatif */
        z-index: 1; /* Z-index lebih rendah dari modal */
    }

    /* Gaya untuk label saat hover */
    label:hover {
        color: #4f52ba; /* Ubah warna saat hover */
        cursor: pointer; /* Ubah kursor menjadi pointer */
    }
</style>

<div class="main">
    <div class="container">
        <div class="header">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h1 style="color: #4f52ba; font-size: 20px;">Dasbor</h1>
                <button class="add-button" onclick="openAddPendaftaranModal()">Daftar</button>
            </div>
        
            <div class="welcome-banner" style="background-color: #f4f5ff; padding: 20px; border-radius: 10px; display: flex; align-items: center; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
                <img src="{{ asset('img/avatars/1.png') }}" alt="Foto Profil" style="width: 80px; height: 80px; border-radius: 50%; margin-right: 15px; border: 2px solid #4f52ba;">
                <div>
                    <h2 style="color: #4f52ba; margin: 0; font-size: 24px;">Selamat datang, {{ Auth::user()->name }}! 👋</h2>
                    <p style="color: #6c6fba; margin: 5px 0 0; font-size: 16px;">Semoga harimu menyenangkan dan penuh produktivitas!</p>
                </div>
            </div>
        </div>

        @if(auth()->user()->role == '1' || auth()->user()->role == '2')
        <div class="card-grid">
            <div class="card-counter device-icon">
            <i class="fa-solid fa-city"></i>
            <div class="count-numbers">{{ $regionCount }}</div>
                <div class="count-name">Region</div>
            </div>
            <div class="card-counter pop-icon">
                <i class="fa-solid fa-building"></i>
                <div class="count-numbers">{{ $popCount }}</div>
                <div class="count-name">POP</div>
            </div>
            <div class="card-counter facility-icon">
                <i class="fa-solid fa-building-user"></i>
                <div class="count-numbers">{{ $pocCount }}</div>
                <div class="count-name">POC</div>
            </div>
            <div class="card-counter rack-icon">
                <i class="fas fa-server"></i>
                <div class="count-numbers">{{ $totalRacksPOP }}</div>
                <div class="count-name">Rack POP</div>
            </div>
            <div class="card-counter rack-icon">
                <i class="fas fa-server"></i>
                <div class="count-numbers">{{ $totalRacksPOC }}</div>
                <div class="count-name">Rack POC</div>
            </div>
            <div class="card-counter device-icon">
                <i class="fas fa-laptop"></i>
                <div class="count-numbers">{{ $perangkatCount }}</div>
                <div class="count-name">Perangkat</div>
            </div>
            <div class="card-counter facility-icon">
                <i class="fas fa-tools"></i>
                <div class="count-numbers">{{ $fasilitasCount }}</div>
                <div class="count-name">Fasilitas</div>
            </div>
            <div class="card-counter">
                <i class="fas fa-ruler-combined"></i>
                <div class="count-numbers">{{ $alatukurCount }}</div>
                <div class="count-name">Alat Ukur</div>
            </div>
            <div class="card-counter">
                <i class="fas fa-circle-nodes"></i>
                <div class="count-numbers">{{ $jaringanCount }}</div>
                <div class="count-name">Jaringan</div>
            </div>
        </div>
        @endif
    </div>
</div>

@include('menu.pendaftaran-vms')

@endsection

