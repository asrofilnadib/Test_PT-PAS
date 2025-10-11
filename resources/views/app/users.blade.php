<x-app-layout>
  <x-slot name="header"></x-slot>
  @if(session()->has('success'))
    <x-alert type="success" message="{{ session()->get('success') }}"></x-alert>
  @elseif(session()->has('failed'))
    <x-alert type="danger" message="{{ session()->get('failed') }}"></x-alert>
  @elseif(session()->has('error'))
    <x-alert type="danger" message="{{ session()->get('error') }}"></x-alert>
  @endif

  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">

    <!-- Greeting Card -->
    <div class="row g-4 mb-4">
      <div class="col-sm-6 col-xl-6">
        <div class="card shadow-sm border-0">
          <div class="card-body d-flex align-items-center justify-content-between">
            <div>
              <h4 class="mb-0">Hello, {{ Auth::user()->name }}</h4>
              <small class="text-muted">Welcome back 👋</small>
            </div>
            <div class="avatar">
              <span class="avatar-initial rounded bg-label-primary">
                <i class="ti ti-user ti-sm"></i>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- User Table Section -->
    <section id="user-content">
      <div class="container-fluid px-0">
        <div class="card shadow-sm border-0">
          <div class="card-body pb-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <h3 class="mb-0">Data User</h3>
              <button type="button" class="btn btn-primary" data-bs-toggle="offcanvas"
                      data-bs-target="#offcanvasAddUser">
                <i class="ti ti-plus"></i> Tambah User
              </button>
            </div>
          </div>

          <div class="card-body pt-0">
            <table id="userTable" class="table table-striped table-bordered w-100">
              <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach($users as $index => $user)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $user->name }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                  <td>
                    @if($user->status === 'active')
                      <span class="badge bg-success">Active</span>
                    @else
                      <span class="badge bg-secondary">Inactive</span>
                    @endif
                  </td>
                  <td>{{ $user->created_at->format('d M Y') }}</td>
                  <td>
                    <button class="btn btn-sm btn-warning edit" data-bs-toggle="modal" data-bs-target="#editModal"
                            data-id="{{ $user->id }}">
                      <i class="ti ti-edit"></i>
                    </button>
                    <a href="{{ route('user.destroy', $user->id) }}" class="btn btn-sm btn-danger hapus">
                      <i class="ti ti-trash"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- Offcanvas Add User -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddUser" aria-labelledby="offcanvasAddUserLabel">
      <div class="offcanvas-header">
        <h5 id="offcanvasAddUserLabel" class="offcanvas-title">Tambah User</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body mx-0 flex-grow-0 pt-0 h-100">
        <form action="{{ route('user.add') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama user" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat</label>
            <textarea name="alamat" rows="2" class="form-control" placeholder="Masukan alamat"
                      required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">No.Telp</label>
            <input type="number" name="no_telp" class="form-control" placeholder="Masukkan Nomor Telepon"
                   required>
          </div>
          <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" required>
              <option value="" selected disabled hidden>-- Pilih Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
          </div>
          <button type="submit" class="btn btn-primary w-100">Simpan</button>
        </form>
      </div>
    </div>
  </div>

  {{-- Modal Edit Data --}}
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <form action="{{ route('user.update') }}" method="POST" id="formEditUser">
          @csrf
          <input type="hidden" id="id_user" name="id">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Edit User</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Nama</label>
              <input type="text" name="name" class="form-control" placeholder="Masukkan nama user" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Alamat</label>
              <textarea name="alamat" id="alamat" rows="2" class="form-control" placeholder="Masukan alamat"
                        required></textarea>
            </div>
            <div class="mb-3">
              <label class="form-label">No.Telp</label>
              <input type="number" name="no_telp" class="form-control" step="1" placeholder="Masukkan Nomor Telepon" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Role</label>
              <select name="role" class="form-select" required>
                <option value="" selected disabled hidden>-- Pilih Role --</option>
                @foreach($roles as $role)
                  <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Masukkan password">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

  <x-slot name="script">
    <script>
      $(document).ready(function () {
        $('#userTable').DataTable({
          responsive: true,
          language: {
            search: "_INPUT_",
            searchPlaceholder: "Cari user...",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Tidak ditemukan data yang sesuai",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data tersedia"
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

        $(document).on('click', '.edit', function () {
          const id = $(this).data('id');
          const modal = $('#editModal');
          const form = $('#formEditUser')

          form.trigger('reset');

          $.ajax({
            url: `api/v1/user/${id}`,
            type: 'GET',
            dataType: 'json',
            beforeSend: function () {
              modal.find('.modal-body').css('opacity', '0.6');
            },
            success: function (response) {
              if (response.status === 'success') {
                const data = response.data;
                console.log(data)
                // Isi field form dengan data API
                form.find('input[name="name"]').val(data.name);
                form.find('input[name="email"]').val(data.email);
                form.find('textarea[name="alamat"]').val(data.alamat);
                form.find('input[name="no_telp"]').val(data.no_telp);
                form.find('select[name="role"]').val(data.roles[0].id || '').trigger('change');
                form.find('input[name="password"]').val('');

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
            error: function (xhr, status, error) {

            },
          })
        })
      });
    </script>
  </x-slot>
</x-app-layout>
