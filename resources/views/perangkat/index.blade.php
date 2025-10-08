@php
    // helper for old values in edit mode
    function clean($s) { return e($s); }
@endphp
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Aset Perangkat Jaringan — Dinas Kominfo</title>
  <link rel="icon" href="{{ asset('icon_profile.ico') }}" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
  <style>
  body { background:url("{{ asset('images/background.jpg') }}") center center fixed; background-size:cover; background-repeat:no-repeat; position:relative; }
  body::before { content: ''; position: fixed; inset: 0; background: rgba(0,0,0,0.35); pointer-events: none; }
    .card { color: #fff; backdrop-filter: blur(20px); padding: 30px 40px; background-color: transparent; border:2px solid rgba(255, 255, 255, .2); border-radius:1rem; box-shadow:0 0 10px rgba(0, 0, 0, .2); }
  .table thead th { background:#f0f2f6; }
  .badge-status { text-transform:capitalize; }

  /* Action buttons: keep them inline inside table cells and prevent overflow */
  .action-buttons { display: inline-flex; gap: .5rem; align-items: center; }
  .action-buttons form { display: inline-block; margin: 0; }
  .table td .btn { white-space: nowrap; }
  .table td .btn-outline-primary { padding: .25rem .5rem; }
  .table td .btn-outline-danger { padding: .25rem .5rem; }
    footer { color: white; padding: 20px; }
  </style>
</head>
<body>
<div class="container py-4">
  <div class="d-flex align-items-center justify-content-between mb-3">
    <h2 class="mb-0">Sistem Pengelolaan Aset Perangkat Jaringan</h2>
    <a class="btn btn-outline-secondary" href="{{ route('perangkat.index') }}">Muat Ulang</a>
  </div>

  @if(session('flash'))
    <div class="alert alert-success" role="alert">{{ session('flash') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger" role="alert">
      <ul class="mb-0">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
      </ul>
    </div>
  @endif

  <div class="row g-4">
    <!-- Form Tambah / Edit -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">{{ isset($editRow) ? 'Edit Perangkat' : 'Tambah Perangkat' }}</h5>
          @if(isset($editRow))
            <form method="post" action="{{ route('perangkat.update', $editRow->id) }}">
            @method('PUT')
          @else
            <form method="post" action="{{ route('perangkat.store') }}">
          @endif
            @csrf
            <div class="mb-3">
              <label class="form-label">Nama Perangkat <span class="text-danger">*</span></label>
              <input type="text" name="nama" class="form-control" maxlength="100" required value="{{ old('nama', $editRow->nama ?? '') }}">
            </div>
            <div class="mb-3">
              <label class="form-label">Tipe <span class="text-danger">*</span></label>
              <select name="tipe" class="form-select" required>
                @php
                  $tipeOptions = ['Router','Switch','Access Point','Firewall','Modem','Lainnya'];
                  $currentTipe = old('tipe', $editRow->tipe ?? '');
                @endphp
                @foreach($tipeOptions as $opt)
                  <option value="{{ $opt }}" {{ $opt === $currentTipe ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Lokasi <span class="text-danger">*</span></label>
              <input type="text" name="lokasi" class="form-control" maxlength="150" required value="{{ old('lokasi', $editRow->lokasi ?? '') }}" placeholder="Contoh: Lantai 10 - Ruang Server">
            </div>
            <div class="mb-3">
              <label class="form-label">Status <span class="text-danger">*</span></label>
              <div class="d-flex gap-3">
                @php $curStatus = old('status', $editRow->status ?? 'aktif'); @endphp
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="status" id="st1" value="aktif" {{ $curStatus==='aktif'?'checked':'' }}>
                  <label class="form-check-label" for="st1">Aktif</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="status" id="st2" value="tidak_aktif" {{ $curStatus==='tidak_aktif'?'checked':'' }}>
                  <label class="form-check-label" for="st2">Tidak Aktif</label>
                </div>
              </div>
            </div>
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">{{ isset($editRow) ? 'Simpan Perubahan' : 'Tambah' }}</button>
              @if(isset($editRow))
                <a href="{{ route('perangkat.index') }}" class="btn btn-outline-secondary">Batal</a>
              @endif
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Tabel Daftar -->
    <div class="col-lg-8">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Daftar Perangkat</h5>
            <form class="d-flex" role="search" method="get" action="{{ route('perangkat.index') }}">
              <input type="text" class="form-control me-2" placeholder="Cari nama/tipe/lokasi" name="q" value="{{ old('q', $keyword ?? '') }}">
              <button class="btn btn-outline-primary" type="submit">Cari</button>
            </form>
          </div>

          <div style="border:2px solid black rgba(255, 255, 255, .2); border-radius: .5rem; overflow-x:auto; text-align: center;">
            <table style="min-width:700px;" class="table table-hover mb-0">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nama</th>
                  <th>Tipe</th>
                  <th>Lokasi</th>
                  <th>Status</th>
                  <th>Dibuat</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
              @if($rows->isEmpty())
                <tr><td colspan="7" class="text-center text-muted">Belum ada data</td></tr>
              @else
                @foreach($rows as $r)
                <tr>
                  <td>{{ $r->id }}</td>
                  <td>{{ e($r->nama) }}</td>
                  <td>{{ e($r->tipe) }}</td>
                  <td>{{ e($r->lokasi) }}</td>
                  <td>
                    @if($r->status === 'aktif')
                      <span class="badge bg-success badge-status">Aktif</span>
                    @else
                      <span class="badge bg-secondary badge-status">Tidak Aktif</span>
                    @endif
                  </td>
                  <td><small class="text-muted">{{ $r->created_at }}</small></td>
                  <td class="d-flex gap-2 justify-content-center">
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('perangkat.edit', $r->id) }}">Edit</a>
                    <form method="post" action="{{ route('perangkat.destroy', $r->id) }}" onsubmit="return confirm('Hapus perangkat ini?');">
                      @csrf
                      @method('DELETE')
                      <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                    </form>
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
  </div>
  @include('footer')
</body>
</html>
