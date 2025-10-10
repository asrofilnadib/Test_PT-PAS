<x-app-layout>
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
          <!-- /# column -->
          <div class="col-lg-4 p-l-0 title-margin-left">
            <div class="page-header">
              <div class="page-title">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item active">barang</li>
                </ol>
              </div>
            </div>
          </div>
          <!-- /# column -->
        </div>
        <!-- /# row -->
        <section id="main-content">
          <div class="row">
            <div class="container">
              <h1 class="text-center"> Data Barang</h1>
              <div class="button mb-2" style="float:right;">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                  Tambah Data
                </button>
              </div>
              <table id="table" class="table table-striped table-bordered mt-5">
                <thead>
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
                      <td>{{ $d->jenis_barang  }}</td>
                      <td>{{ $d->satuan->name . " / " . " 1 " . $d->satuan->name }}</td>
                      <td>{{ $d->stock }}</td>
                      <td>{{ $d->user->name }}</td>
                      <td>{{ $d->expired_at }}</td>
                      <td style="text-align:left;">
                        <a href="{{ route('barang.destroy', $d->id) }}" class="btn btn-danger hapus">Hapus</a>
                        <button class="btn btn-warning edit" data-id="{{ $d->id }}"> Edit</button>
                      </td>
                    </tr>
                  @endif
                @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-12">
              <div class="footer">
                <p class="text-center">2023 ©STOCK OPNAME (OPNAME)</p>
              </div>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>

  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
       aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Tambah Data barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('barang.add') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <input type="text" name="nama_barang" class="form-control" placeholder="Masukan Nama Barang" required>
            </div>
            <div class="form-group">
              <input type="text" name="jenis_barang" class="form-control" placeholder="Jenis Barang" required>
            </div>
            <div class="form-group">
              <select name="id_satuan" id="id_satuan" class="form-control">
                <option value="" selected>-- Pilih Satuan Barang --</option>
                @foreach($satuan as $sa)
                  <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <input type="number" name="stock" class="form-control" placeholder="Masukan stock barang" required>
            </div>
            <div class="form-group">
              <input type="date" name="expired_at" class="form-control"
                     placeholder="Masukan tanggal kadaluarsa barang" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <div class="modal fade" id="editData" tabindex="-1" role="dialog" aria-labelledby="editDataLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editDataLabel">Update Data barang</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('barang.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" id="id_barang" name="id">
          <div class="modal-body">
            <div class="form-group">
              <input id="nama_barang" type="text" name="nama_barang" class="form-control"
                     placeholder="Masukan Nama Barang" required>
            </div>
            <div class="form-group">
              <input id="jenis_barang" type="text" name="jenis_barang" class="form-control"
                     placeholder="Masukan Jenis Barang" required>
            </div>
            <div class="form-group">
              <select name="id_satuan" id="id_satuan" class="form-control">
                <option value="" selected>-- Pilih Satuan Barang --</option>
                @foreach($satuan as $sa)
                  <option value="{{ $sa->id }}">{{ $sa->name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <input id="stock" type="number" name="stock" class="form-control" placeholder="Masukan stock barang"
                     required>
            </div>
            <div class="form-group">
              <input type="date" name="expired_at" id="expired_at" class="form-control"
                     placeholder="Masukan tanggal kadaluarsa barang" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <x-slot name="script">
    <script>
      @if(Session::has('success'))
      Swal.fire(
        'Success!',
        '{{ session('success') }}',
        'success'
      )
      @endif

      @if(Session::has('error'))
      Swal.fire(
        'Error!',
        '{{ session('error') }}',
        'Error'
      )
      @endif
      $(document).ready(function () {
        $('#table_1').DataTable();
        $('#table').DataTable();
        $('.hapus').on('click', function (e) {

          e.preventDefault();
          const href = $(this).attr('href');


          Swal.fire({
            title: 'Apakah anda yakin ?',
            text: "Data akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Hapus data !'
          }).then((result) => {
            if (result.isConfirmed) {
              document.location.href = href;
            }
          });

        });

        $('.edit').on('click',function(e){
          let id = $(this).data('id');
          $.ajax({
            type: "POST",
            url: "{{ route('barang.detail') }}",
            data: {id:id, "_token": $('#token').val()},
            success: function (data) {
              console.log(data);
              $('#id_barang').val(data.data.id);
              $('#nama_barang').val(data.data.nama_barang);
              $('#jenis_barang').val(data.data.jenis_barang);
              $('#expired_at').val(data.data.expired_at);
              $('#id_satuan').val(data.data.satuan.name);
              $('#stock').val(data.data.stock);
              $('#editData').modal('toggle');
            }
          });
        })
      });
    </script>
  </x-slot>
</x-app-layout>
