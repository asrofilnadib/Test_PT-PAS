<x-app-layout>
  @if(session()->has('success'))
    <x-alert type="success" message="{{ session()->get('success') }}"></x-alert>
  @elseif(session()->has('failed'))
    <x-alert type="danger" message="{{ session()->get('failed') }}"></x-alert>
  @elseif(session()->has('error'))
    <x-alert type="danger" message="{{ session()->get('error') }}"></x-alert>
  @endif

  <div class="content-wrap">
    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
    <div class="main">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-8 p-r-0 title-margin-right">
            <div class="page-header">
              <div class="page-title">
                <h1>Hello, <span>{{ Auth::user()->name }}</span></h1>
              </div>
            </div>
          </div>
        </div>

        <section id="main-content">
          <div class="container mt-4">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
              <h3 class="mb-0">Data Barang</h3>
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="ti ti-plus"></i> Tambah Data Barang
              </button>
            </div>

            <div class="card shadow-sm">
              <div class="card-body">
                <table id="table" class="table table-striped table-bordered w-100">
                  <thead class="table-light">
                  <tr>
                    <th>Nama Barang</th>
                    <th>Jenis Barang</th>
                    <th>Nilai Konversi</th>
                    <th>Stock</th>
                    <th>Created By</th>
                    <th>Expired At</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($barang as $d)
                    @if($d->show == 1)
                      <tr>
                        <td>{{ $d->nama_barang }}</td>
                        <td>{{ $d->jenis_barang }}</td>
                        <td>{{ $d->satuan->nama . " / 1 " . $d->satuan->nama }}</td>
                        <td>{{ $d->stock_aktual }}</td>
                        <td>{{ $d->user->name }}</td>
                        <td>{{ $d->expired_at }}</td>
                        <td>
                          <button type="button" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-sm btn-warning edit" data-id="{{ $d->id }}">
                            <i class="ti ti-edit"></i>
                          </button>
                          <a href="{{ route('barang.destroy', $d->id) }}" class="btn btn-sm btn-danger hapus">
                            <i class="ti ti-trash"></i>
                          </a>
                        </td>
                      </tr>
                    @endif
                  @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  {{-- Modal Add Data --}}
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('barang.add') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Tambah Data Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Nama Barang</label>
              <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan Nama Barang" required>
            </div>
            <div class="form-group mb-3">
              <label>Jenis Barang</label>
              <select name="jenis_barang" class="form-select form-control">
                <option value="" selected hidden disabled>-- Pilih Jenis Barang --</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label>Satuan Barang</label>
              <select name="id_satuan" class="form-control" required>
                <option value="" selected hidden disabled>-- Pilih Satuan Barang --</option>
                @foreach($satuan as $sa)
                  <option value="{{ $sa->id }}">{{ $sa->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-3">
              <label>Stock</label>
              <input type="number" name="stock" class="form-control" placeholder="Masukkan stock barang" required>
            </div>
            <div class="form-group mb-3">
              <label>Tanggal Kadaluarsa</label>
              <input type="date" name="expired_at" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Edit Data --}}
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('barang.update') }}" method="POST" id="formEditBarang">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Data Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="form-group mb-3">
              <label>Nama Barang</label>
              <input type="text" name="nama_barang" class="form-control" placeholder="Masukkan Nama Barang" required>
            </div>
            <div class="form-group mb-3">
              <label>Jenis Barang</label>
              <select name="jenis_barang" class="form-select form-control">
                <option value="" selected hidden disabled>-- Pilih Jenis Barang --</option>
                <option value="Makanan">Makanan</option>
                <option value="Minuman">Minuman</option>
              </select>
            </div>
            <div class="form-group mb-3">
              <label>Satuan Barang</label>
              <select name="id_satuan" class="form-control" required>
                <option value="" selected hidden disabled>-- Pilih Satuan Barang --</option>
                @foreach($satuan as $sa)
                  <option value="{{ $sa->id }}">{{ $sa->nama }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-3">
              <label>Stock</label>
              <input type="number" name="stock" class="form-control" placeholder="Masukkan stock barang" required disabled>
            </div>
            <div class="form-group mb-3">
              <label>Tanggal Kadaluarsa</label>
              <input type="date" name="expired_at" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- SCRIPT --}}
  <x-slot name="script">
    <script>
      $(document).ready(function () {
        const table = $('#table').DataTable({
          responsive: true
        });

        $('.hapus').on('click', function (e) {
          e.preventDefault();
          const href = $(this).attr('href');
          Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7367F0',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
          }).then((result) => {
            if (result.isConfirmed) document.location.href = href;
          });
        });

        $(document).on('click', '.edit', function () {
          const id = $(this).data('id');
          const modal = $('#editModal');
          const form = $('#formEditBarang');

          form.trigger('reset');

          // Request an item from API
          $.ajax({
            url: `/api/v1/barang/${id}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
              modal.find('.modal-body').css('opacity', '0.6');
            },
            success: function (response) {
              if (response.status === 'success') {
                const data = response.data[0];
                // console.log(data)

                // Isi field form dengan data API
                form.find('input[name="nama_barang"]').val(data.nama_barang);
                form.find('select[name="jenis_barang"]').val(data.jenis_barang);
                form.find('select[name="id_satuan"]').val(data.id_satuan);
                form.find('input[name="stock"]').val(data.stock_aktual);
                form.find('input[name="expired_at"]').val(data.expired_at);

                // Simpan ID barang ke form (hidden input biar bisa dikirim saat submit)
                if (form.find('input[name="id"]').length === 0) {
                  form.append(`<input type="hidden" name="id" value="${data.id}">`);
                } else {
                  form.find('input[name="id"]').val(data.id);
                }

                // Reset style modal
                modal.find('.modal-body').css('opacity', '1');
                modal.modal('show');
              } else {
                Swal.fire('Error', 'Item not found.', 'error');
              }
            },
            error: function (xhr) {
              console.error(xhr);
              Swal.fire('Error', 'Failed to fetch item data.', 'error');
              modal.find('.modal-body').css('opacity', '1');
            }
          });
        });
      });

    </script>
  </x-slot>
</x-app-layout>
