<x-app-layout>
  @if(session()->has('success'))
    <x-alert type="success" message="{{ session()->get('success') }}"></x-alert>
  @elseif(session()->has('failed'))
    <x-alert type="danger" message="{{ session()->get('failed') }}"></x-alert>
  @elseif(session()->has('error'))
    <x-alert type="danger" message="{{ session()->get('error') }}"></x-alert>
  @endif

  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-bold py-3 mb-0">Transaksi Barang</h4>
      </div>
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
        <i class="ti ti-plus me-1"></i> Tambah Data Transaksi
      </button>
    </div>

    <div class="card">
      <div class="card-datatable table-responsive p-3">
        <table id="table" class="table table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>Nama Barang</th>
            <th>Jenis Transaksi</th>
            <th>QTY</th>
            <th>Created By</th>
            <th>Tanggal Transaksi</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @foreach($transaksi_barang as $d)
            @if($d->barang->show == 1)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->barang->nama_barang }}</td>
                <td>Barang {{ $d->jenis }}</td>
                <td>{{ $d->qty }}</td>
                <td>{{ $d->user->name }}</td>
                <td>{{ date('Y-m-d', strtotime($d->tanggal_transaksi)) }}</td>
                <td>
                  <div class="d-flex">
                    <button class="btn btn-sm btn-warning me-2 edit" data-id="{{ $d->id }}">
                      <i class="ti ti-edit"></i>
                    </button>
                    <a href="{{ route('transaksi_barang.destroy', $d->id) }}" class="btn btn-sm btn-danger hapus">
                      <i class="ti ti-trash"></i>
                    </a>
                  </div>
                </td>
              </tr>
            @endif
          @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Modal Tambah Data Transaksi --}}
  <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('transaksi_barang.add') }}" method="POST">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="addModalLabel">Tambah Transaksi Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Jenis Transaksi</label>
              <select name="jenis" class="form-select" required>
                <option value="" disabled hidden selected>-- Pilih Jenis Transaksi --</option>
                <option value="Masuk">Barang Masuk</option>
                <option value="Keluar">Barang Keluar</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Barang</label>
              <select name="id_barang" id="id_barang" class="selectize" required>
                <option value="" disabled hidden selected>-- Pilih Barang --</option>
                @foreach($barang as $b)
                  @if($b->show == 1)
                    <option value="{{ $b->id }}">{{ $loop->iteration }} - {{ $b->nama_barang }}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Jumlah (QTY)</label>
              <input type="number" name="qty" class="form-control" placeholder="Masukkan jumlah" step="1" min="0" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Transaksi</label>
              <input type="date" name="tanggal_transaksi" class="form-control" required>
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
        <form action="{{ route('transaksi_barang.update') }}" method="POST" id="formEditBarang">
          @csrf
          <input type="hidden" id="id_transaksi" name="id">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit Transaksi Barang</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Jenis Transaksi</label>
              <select name="jenis" id="jenis" class="form-select" required>
                <option value="" disabled selected>-- Pilih Jenis Transaksi --</option>
                <option value="Masuk">Barang Masuk</option>
                <option value="Keluar">Barang Keluar</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Nama Barang</label>
              <select name="id_barang" id="id_barang" class="selectize" required>
                <option value="" disabled selected>-- Pilih Barang --</option>
                @foreach($barang as $b)
                  @if($b->show == 1)
                    <option value="{{ $b->id }}">{{ $b->id }} - {{ $b->nama_barang }}</option>
                  @endif
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Jumlah (QTY)</label>
              <input id="qty" type="number" name="qty" class="form-control" step="1" min="0" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Tanggal Transaksi</label>
              <input id="tanggal_transaksi" type="date" name="tanggal_transaksi" class="form-control" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  {{-- Scripts --}}
  <x-slot name="script">
    <script>
      $(document).ready(function () {
        $('#table').DataTable({
          responsive: true
        });

        $('.selectize').selectize({
          placeholder: "-- Pilih Barang --",
          allowEmptyOption: true,
          sortField: 'text',
          create: false,
          maxItems: 1,
          persist: true,
          onInitialize: function() {
            this.$control.addClass('form-control');
          }
        });

        // delete confirmation
        $('.hapus').on('click', function (e) {
          e.preventDefault();
          const href = $(this).attr('href');
          Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#7367F0',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!'
          }).then((result) => {
            if (result.isConfirmed) {
              document.location.href = href;
            }
          });
        });

        // edit handler
        $(document).on('click', '.edit', function () {
          const id = $(this).data('id');
          const modal = $('#editModal');
          const form = $('#formEditBarang');

          form.trigger('reset'); // Clear all form fields

          // Fetch data from API
          $.ajax({
            url: `/api/v1/transaksi/${id}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
              // Reduce opacity to indicate loading
              modal.find('.modal-body').css('opacity', '0.6');
            },
            success: function (response) {
              if (response.status === 'success') {
                const data = response.data[0];

                // Fill in form fields with API response
                form.find('select[name="jenis"]').val(data.jenis).trigger('change'); // Select option for transaction type
                form.find('input[name="qty"]').val(data.qty); // Set quantity
                form.find('input[name="tanggal_transaksi"]').val(data.tanggal_transaksi); // Set transaction date

                const selectizeBarang = form.find('select[name="id_barang"]')[0].selectize;
                selectizeBarang.setValue(data.id_barang);

                // Save transaction ID in hidden input
                if (form.find('input[name="id"]').length === 0) {
                  form.append(`<input type="hidden" name="id" value="${data.id}">`);
                } else {
                  form.find('input[name="id"]').val(data.id);
                }

                // Reset modal style and show
                modal.find('.modal-body').css('opacity', '1');
                modal.modal('show');
              } else {
                Swal.fire('Error', 'Data transaksi tidak ditemukan.', 'error');
              }
            },
            error: function (xhr) {
              console.error(xhr);
              Swal.fire('Error', 'Terjadi kesalahan saat mengambil data transaksi.', 'error');
              modal.find('.modal-body').css('opacity', '1');
            }
          });
        });
      });
    </script>
  </x-slot>
</x-app-layout>
