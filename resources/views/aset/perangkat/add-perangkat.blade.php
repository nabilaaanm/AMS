<div>
    <!-- Order your soul. Reduce your wants. - Augustine -->
</div>
@extends('layouts.app')

@section('title', 'Aset Perangkat')
@section('page_title', 'Aset Perangkat')

@section('content')
    <div class="main">
        <button class="btn btn-primary mb-3" onclick="openModal('modalTambahPerangkat')">+ Tambah Perangkat</button>
        <!-- Tombol untuk membuka modal -->
        <button type="button" class="btn btn-primary" onclick="openModal('importModal')">Impor Data Perangkat</button>

        <!-- Modal -->
        <div id="importModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h5>Impor Data Perangkat</h5>
            <form action="{{ route('import.perangkat') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Pilih File (XLSX, XLS, CSV)</label>
                <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Impor Data</button>
            </form>
        </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th class="sortable" data-column="status">
                            Status
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="region">
                            Region
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="site">
                            Site
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="no_rack">
                            No Rack
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="perangkat">
                            Perangkat
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="perangkat_ke">
                            Perangkat Ke
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="brand">
                            Brand
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="type">
                            Type
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="uawal">
                            U Awal
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th class="sortable" data-column="uakhir">
                            U Akhir
                            <span class="sort-icon">
                                <i class="fas fa-sort"></i>
                            </span>
                        </th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($listperangkat as $perangkat)
                        <tr>
                            <td>
                                <div class="status-box {{ $perangkat->no_rack ? 'bg-success' : 'bg-danger' }}"></div>
                            </td>
                            <td>{{ $perangkat->region->nama_region }}</td>
                            <td>{{ $perangkat->site->nama_site }}</td>
                            <td>{{ $perangkat->no_rack }}</td>
                            <td>{{ $perangkat->jenisperangkat->nama_perangkat }}</td>
                            <td>{{ $perangkat->perangkat_ke }}</td>
                            <td>{{ optional($perangkat->brandperangkat)->nama_brand }}</td>
                            <td>{{ $perangkat->type }}</td>
                            <td>{{ $perangkat->uawal }}</td>
                            <td>{{ $perangkat->uakhir }}</td>
                            <td>
                                <button class="btn btn-edit"
                                    onclick="openModal('modalEditPerangkat{{ $perangkat->id_perangkat }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('perangkat.destroy', $perangkat->id_perangkat) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-delete"
                                        onclick="return confirm('Yakin hapus perangkat ini?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Modal Edit --}}
                        <div id="modalEditPerangkat{{ $perangkat->id_perangkat }}" class="modal">
                            <div class="modal-content">
                                <span class="close"
                                    onclick="closeModal('modalEditPerangkat{{ $perangkat->id_perangkat }}')">&times;</span>
                                <h5>Edit Perangkat</h5>
                                <form action="{{ route('perangkat.update', $perangkat->id_perangkat) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label>Kode Region</label>
                                        <select name="kode_region" class="form-control regionSelectEdit"
                                            data-id="{{ $perangkat->id_perangkat }}" required>
                                            <option value="">Pilih Region</option>
                                            @foreach($regions as $region)
                                                <option value="{{ $region->kode_region }}" {{ $perangkat->kode_region == $region->kode_region ? 'selected' : '' }}>
                                                    {{ $region->nama_region }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Site</label>
                                        <select name="kode_site" class="form-control siteSelectEdit"
                                            data-id="{{ $perangkat->id_perangkat }}" required>
                                            <option value="">Pilih Site</option>
                                            @foreach($sites as $site)
                                                @if($site->kode_region == $perangkat->kode_region)
                                                    <option value="{{ $site->kode_site }}" {{ $perangkat->kode_site == $site->kode_site ? 'selected' : '' }}>
                                                        {{ $site->nama_site }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label>No Rack</label>
                                        <input type="text" name="no_rack" class="form-control"
                                            value="{{ $perangkat->no_rack ?? '' }}">
                                    </div>
                                    <div class="mb-3">
                                        <label>Kode Perangkat</label>
                                        <select name="kode_perangkat" class="form-control" required>
                                            <option value="">Pilih Kode Perangkat</option>
                                            @foreach($types as $jenisperangkat)
                                                <option value="{{ $jenisperangkat->kode_perangkat }}" 
                                                    {{ $perangkat->kode_perangkat == $jenisperangkat->kode_perangkat ? 'selected' : '' }}>{{ $jenisperangkat->kode_perangkat }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>                   
                                    <div class="mb-3">
                                        <label>Kode Brand</label>
                                        <select name="kode_brand" class="form-control">
                                            <option value="">Pilih Kode Brand</option>
                                            @foreach($brands as $brandperangkat)
                                                <option value="{{ $brandperangkat->kode_brand }}" 
                                                    {{ $perangkat->kode_brand == $brandperangkat->kode_brand ? 'selected' : '' }}>
                                                    {{ $brandperangkat->nama_brand }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label>Type</label>
                                        <input type="text" name="type" class="form-control" value="{{ $perangkat->type ?? '' }}"
                                            >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Awal</label>
                                        <input type="number" name="uawal" class="form-control"
                                            value="{{ $perangkat->uawal ?? '' }}" >
                                    </div>
                                    <div class="mb-3">
                                        <label>U Akhir</label>
                                        <input type="number" name="uakhir" class="form-control"
                                            value="{{ $perangkat->uakhir ?? '' }}" >
                                    </div>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Modal Tambah --}}
        <div id="modalTambahPerangkat" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal('modalTambahPerangkat')">&times;</span>
                <h5>Tambah Perangkat</h5>
                <form action="{{ route('perangkat.store') }}" method="POST" id="formTambahPerangkat">
                    @csrf
                    <div class="mb-3">
            <label>Kode Region</label>
            <select id="regionSelectTambah" name="kode_region" class="form-control" required>
                <option value="">Pilih Region</option>
                @foreach($regions as $region)
                    <option value="{{ $region->kode_region }}">{{ $region->nama_region }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Kode Site</label>
            <select id="siteSelectTambah" name="kode_site" class="form-control" required disabled>
                <option value="">Pilih Site</option>
            </select>
        </div>

            <div class="mb-3">
                <label>No Rack</label>
                <input type="text" name="no_rack" class="form-control" id="no_rack" value="">
            </div>

            <div class="mb-3">
                <label>Kode Perangkat</label>
                <select name="kode_perangkat" class="form-control" required>
                    <option value="">Pilih Kode Perangkat</option>
                    @foreach($types as $jenisperangkat)
                        <option value="{{ $jenisperangkat->kode_perangkat }}">
                            {{ $jenisperangkat->kode_perangkat }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Kode Brand</label>
                <select name="kode_brand" class="form-control" >
                    <option value="">Pilih Kode Brand</option>
                    @foreach($brands as $brandperangkat)
                        <option value="{{ $brandperangkat->kode_brand }}">
                            {{ $brandperangkat->nama_brand }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label>Type</label>
                <input type="text" name="type" class="form-control" value="">
            </div>

            <div class="mb-3">
                <label>U Awal</label>
                <input type="number" name="uawal" class="form-control" value="" id="uawal">
            </div>

            <div class="mb-3">
                <label>U Akhir</label>
                <input type="number" name="uakhir" class="form-control" value="" id="uakhir">
            </div>

            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    </div>
</div>


    </div>
    <script>
        // Menangani perubahan pada Region
        document.getElementById('regionSelectTambah').addEventListener('change', function() {
            const regionId = this.value;
            const siteSelect = document.getElementById('siteSelectTambah');

            // Reset dan nonaktifkan site select
            siteSelect.innerHTML = '<option value="">Pilih Site</option>';
            siteSelect.disabled = true;

            // Jika Region dipilih, aktifkan site select dan filter site berdasarkan region
            if (regionId) {
                siteSelect.disabled = false;
                const sites = @json($sites);
                const filteredSites = sites.filter(site => site.kode_region == regionId);

                filteredSites.forEach(site => {
                    const option = document.createElement('option');
                    option.value = site.kode_site;
                    option.textContent = site.nama_site;
                    siteSelect.appendChild(option);
                });
            }
        });

        // Menangani perubahan pada input no_rack
        document.getElementById('no_rack').addEventListener('input', function () {
            const noRack = this.value;
            const uawalField = document.getElementById('uawal');
            const uakhirField = document.getElementById('uakhir');

            // Jika no_rack diisi, uawal dan uakhir menjadi required
            if (noRack) {
                uawalField.setAttribute('required', 'required');
                uakhirField.setAttribute('required', 'required');
            } else {
                uawalField.removeAttribute('required');
                uakhirField.removeAttribute('required');
            }
        });

        // Validasi form sebelum submit
        document.getElementById('formTambahPerangkat').addEventListener('submit', function (event) {
            const uawal = parseFloat(document.getElementById('uawal').value);
            const uakhir = parseFloat(document.getElementById('uakhir').value);

            // Pastikan uawal < uakhir dan tidak bernilai negatif
            if (uawal >= uakhir) {
                alert('U Awal harus lebih kecil dari U Akhir.');
                event.preventDefault(); // Cegah form untuk dikirim
            }

            if (uawal < 0 || uakhir < 0) {
                alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
                event.preventDefault(); // Cegah form untuk dikirim
            }
        });

        // Menangani perubahan pada Region di form edit
        document.querySelectorAll('.regionSelectEdit').forEach(select => {
            select.addEventListener('change', function() {
                const regionId = this.value;
                const perangkatId = this.getAttribute('data-id');
                const siteSelect = document.querySelector(`.siteSelectEdit[data-id="${perangkatId}"]`);

                // Reset dan nonaktifkan site select
                siteSelect.innerHTML = '<option value="">Pilih Site</option>';
                siteSelect.disabled = true;

                // Jika Region dipilih, aktifkan site select dan filter site berdasarkan region
                if (regionId) {
                    siteSelect.disabled = false;
                    const sites = @json($sites);
                    const filteredSites = sites.filter(site => site.kode_region == regionId);

                    filteredSites.forEach(site => {
                        const option = document.createElement('option');
                        option.value = site.kode_site;
                        option.textContent = site.nama_site;
                        siteSelect.appendChild(option);
                    });
                }
            });
        });

        // Validasi form edit sebelum submit
        document.querySelectorAll('form[action*="perangkat/update"]').forEach(form => {
            form.addEventListener('submit', function(event) {
                const uawal = parseFloat(this.querySelector('input[name="uawal"]').value);
                const uakhir = parseFloat(this.querySelector('input[name="uakhir"]').value);
                const noRack = this.querySelector('input[name="no_rack"]').value;

                // Jika no_rack diisi, pastikan uawal dan uakhir juga diisi
                if (noRack && (!uawal || !uakhir)) {
                    alert('U Awal dan U Akhir wajib diisi jika No Rack diisi.');
                    event.preventDefault();
                    return;
                }

                // Pastikan uawal < uakhir dan tidak bernilai negatif
                if (uawal >= uakhir) {
                    alert('U Awal harus lebih kecil dari U Akhir.');
                    event.preventDefault();
                }

                if (uawal < 0 || uakhir < 0) {
                    alert('U Awal dan U Akhir tidak boleh bernilai negatif.');
                    event.preventDefault();
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const table = document.querySelector('table');
            let currentSort = {
                column: null,
                direction: 'asc'
            };

            // Add click event listeners to all sortable headers
            document.querySelectorAll('th.sortable').forEach(headerCell => {
                headerCell.addEventListener('click', () => {
                    const column = headerCell.dataset.column;
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr'));

                    // Reset all headers
                    document.querySelectorAll('th.sortable').forEach(th => {
                        th.classList.remove('sort-asc', 'sort-desc');
                        th.querySelector('.sort-icon i').className = 'fas fa-sort';
                    });

                    // Determine sort direction
                    if (currentSort.column === column) {
                        currentSort.direction = currentSort.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        currentSort.column = column;
                        currentSort.direction = 'asc';
                    }

                    // Update header appearance
                    headerCell.classList.add(`sort-${currentSort.direction}`);
                    headerCell.querySelector('.sort-icon i').className = `fas fa-sort-${currentSort.direction}`;

                    // Sort the rows
                    rows.sort((rowA, rowB) => {
                        let a = getCellValue(rowA, column);
                        let b = getCellValue(rowB, column);

                        if (column === 'status') {
                            // Sort by success/danger status
                            a = rowA.querySelector('.status-box').classList.contains('bg-success');
                            b = rowB.querySelector('.status-box').classList.contains('bg-success');
                        } else if (!isNaN(a) && !isNaN(b)) {
                            // Convert to numbers if possible
                            a = parseFloat(a);
                            b = parseFloat(b);
                        }

                        if (a < b) return currentSort.direction === 'asc' ? -1 : 1;
                        if (a > b) return currentSort.direction === 'asc' ? 1 : -1;
                        return 0;
                    });

                    // Reorder the rows in the table
                    rows.forEach(row => tbody.appendChild(row));
                });
            });

            // Helper function to get cell value
            function getCellValue(row, column) {
                const cell = row.querySelector(`td:nth-child(${getColumnIndex(column) + 1})`);
                return cell ? cell.textContent.trim() : '';
            }

            // Helper function to get column index based on data-column attribute
            function getColumnIndex(column) {
                const headers = Array.from(table.querySelectorAll('th'));
                return headers.findIndex(header => header.dataset.column === column);
            }
        });
    </script>
@endsection

<style>
    .sortable {
        cursor: pointer;
        position: relative;
    }

    .sort-icon {
        margin-left: 5px;
    }

    .sort-icon i {
        color: #ccc;
    }

    .sort-asc .sort-icon i {
        color: #28a745;
    }

    .sort-desc .sort-icon i {
        color: #dc3545;
    }

    .sortable.sort-asc .sort-icon i:before {
        content: "\f0de"; /* Font Awesome up arrow */
    }

    .sortable.sort-desc .sort-icon i:before {
        content: "\f0dd"; /* Font Awesome down arrow */
    }
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
