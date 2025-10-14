{{--@dd(request()->query())--}}
<x-app-layout>
  <x-slot name="header">
  </x-slot>

  <!-- Filter Reporting -->
  <div class="row g-4 mb-4">
    <div class="col-sm-12">
      <div class="card shadow-sm border-0">
        <div class="card-body pb-0">
          <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h3 class="mb-0">Filter Reporting Barang</h3>
          </div>
        </div>

        <div class="card-body pt-2">
          <form id="filterReportingForm" method="GET" action="{{ route('report') }}"
                class="d-flex flex-wrap align-items-end justify-content-between gap-3">
            <!-- Select Barang -->
            <div class="flex-grow-1">
              <label for="selectBarang" class="form-label mb-1">Barang</label>
              <select id="selectBarang" name="id_barang" class="selectize">
                <option value="">-- Semua Barang --</option>
                @foreach($barang as $b)
                  <option value="{{ $b->id }}" {{ request('id_barang') == $b->id ? 'selected' : '' }}>
                    {{ $b->id . ' - ' . $b->nama_barang }}
                  </option>
                @endforeach
              </select>
            </div>

            <!-- Filter By -->
            <div>
              <label for="filterBy" class="form-label mb-1">Filter Berdasarkan</label>
              <select id="filterBy" name="filter_by" class="form-control">
                <option value="all" {{ request('filter_by') === 'all' ? 'selected' : '' }}>Semua Transaksi</option>
                <option value="barang_masuk" {{ request('filter_by') === 'barang_masuk' ? 'selected' : '' }}>Report
                  Barang Masuk
                </option>
                <option value="barang_keluar" {{ request('filter_by') === 'barang_keluar' ? 'selected' : '' }}>Report
                  Barang Keluar
                </option>
              </select>
            </div>

            <!-- Date From -->
            <div>
              <label for="fromDate" class="form-label mb-1">Dari Tanggal</label>
              <input type="date" id="fromDate" name="from_date" class="form-control" value="{{ request('from_date') }}">
            </div>

            <!-- Date To -->
            <div>
              <label for="toDate" class="form-label mb-1">Sampai Tanggal</label>
              <input type="date" id="toDate" name="to_date" class="form-control" value="{{ request('to_date') }}">
            </div>

            <!-- Button Submit -->
            <div class="d-flex gap-2">
              <button type="submit" class="btn btn-primary">
                <i class="ti ti-filter me-1"></i> Tampilkan
              </button>
              <a href="{{ route('report') }}" class="btn btn-outline-secondary">
                <i class="ti ti-refresh me-1"></i> Reset
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Report Results -->
    @if(request()->has('id_barang') || request()->has('filter_by') || request()->has('from_date'))
      <div class="col-sm-12">
        <div class="card shadow-sm border-0">
          <div class="card-body">
            <!-- Header Info -->
            <div class="d-flex justify-content-between align-items-center mb-3">
              <div>
                <h5 class="mb-1">Hasil Laporan</h5>
                @if(isset($periode))
                  <small class="text-muted">Periode: {{ $periode }}</small>
                @endif
              </div>
              @if(isset($data) && $data->isNotEmpty())
                <div class="d-flex gap-2">
                  <a href="{{ url('report/print') . '?' . http_build_query(request()->query()) }}" class="btn btn-danger btn-sm">
                    <i class="ti ti-file-text me-1"></i> Print PDF
                  </a>
                  <button type="button" class="btn btn-success btn-sm" onclick="exportToExcel()">
                    <i class="ti ti-file-spreadsheet me-1"></i> Export Excel
                  </button>
                  <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                          data-bs-target="#emailModal">
                    <i class="ti ti-mail me-1"></i> Kirim Email
                  </button>
                </div>
              @endif
            </div>

            <!-- Summary Cards -->
            @if(isset($summary))
              <div class="row g-3 mb-4">
                @if(isset($summary['masuk']))
                  <div class="col-md-4">
                    <div class="card bg-success bg-opacity-10 border-success" style="--bs-bg-opacity: .1;">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                            <h6 class="text-success mb-1">Total Barang Masuk</h6>
                            <h3 class="mb-0">{{ number_format($summary['masuk']) }}</h3>
                          </div>
                          <i class="ti ti-arrow-down-circle text-success" style="font-size: 2.5rem;"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif

                @if(isset($summary['keluar']))
                  <div class="col-md-4">
                    <div class="card bg-danger bg-opacity-10 border-danger" style="--bs-bg-opacity: .1;">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                            <h6 class="text-danger mb-1">Total Barang Keluar</h6>
                            <h3 class="mb-0">{{ number_format($summary['keluar']) }}</h3>
                          </div>
                          <i class="ti ti-arrow-up-circle text-danger" style="font-size: 2.5rem;"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif

                @if(isset($summary['selisih']))
                  <div class="col-md-4">
                    <div class="card bg-info bg-opacity-10 border-info" style="--bs-bg-opacity: .1;">
                      <div class="card-body">
                        <div class="d-flex align-items-center">
                          <div class="flex-grow-1">
                            <h6 class="text-info mb-1">Selisih</h6>
                            <h3 class="mb-0">{{ number_format($summary['selisih']) }}</h3>
                          </div>
                          <i class="ti ti-chart-line text-info" style="font-size: 2.5rem;"></i>
                        </div>
                      </div>
                    </div>
                  </div>
                @endif
              </div>
            @endif

            <!-- Data Table -->
            <div class="table-responsive">
              @if(isset($data) && $data->isNotEmpty())
                <table class="table table-hover table-striped" id="reportTable">
                  <thead class="table-light">
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Jenis Barang</th>
                    <th>Satuan</th>
                    <th>Jenis Transaksi</th>
                    <th class="text-end">Jumlah</th>
                    <th>Tanggal Transaksi</th>
                  </tr>
                  </thead>
                  <tbody>
                  @foreach($data as $index => $item)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $item->nama_barang }}</td>
                      <td>{{ $item->jenis_barang }}</td>
                      <td>{{ $item->name_satuan }}</td>
                      <td>
                        @if($item->jenis === 'Masuk')
                          <span class="badge bg-success">Masuk</span>
                        @else
                          <span class="badge bg-danger">Keluar</span>
                        @endif
                      </td>
                      <td class="text-end">{{ number_format($item->jumlah) }}</td>
                      <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}</td>
                    </tr>
                  @endforeach
                  </tbody>
                </table>
              @else
                <div class="text-center py-5">
                  <i class="ti ti-file-search" style="font-size: 4rem; color: #ccc;"></i>
                  <p class="text-muted mt-3">Tidak ada data yang ditemukan. Silakan ubah filter pencarian.</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- Email Modal -->
  <div class="modal fade" id="emailModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form method="POST" action="{{ route('report.mail') }}">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title">Kirim Laporan via Email</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="emailSubject" class="form-label">Subject</label>
              <input type="text" class="form-control" id="emailSubject" name="subject"
                     value="Laporan Transaksi Barang - {{ date('d/m/Y') }}" required>
            </div>
            <div class="mb-3">
              <label for="emailBody" class="form-label">Pesan</label>
              <textarea class="form-control" id="emailBody" name="body" rows="4" required>Terlampir laporan transaksi barang periode {{ $periode ?? '' }}</textarea>
            </div>
            <input type="hidden" name="report_data" value="{{ e(json_encode($data ?? [])) }}">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Kirim Email</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <x-slot name="script">
    <script>
      $(document).ready(function () {
        // Initialize Selectize
        $('#selectBarang').selectize({
          placeholder: 'Pilih barang...',
          allowEmptyOption: true,
          create: false,
          sortField: 'text',
          plugins: ['clear_button', 'restore_on_backspace'],
        });

        // Initialize DataTable if exists
        if ($('#reportTable').length) {
          $('#reportTable').DataTable({
            language: {
              url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
            },
            order: [[0, 'asc']],
            pageLength: 25
          });
        }

        // Form validation
        $('#filterReportingForm').on('submit', function (e) {
          const fromDate = $('#fromDate').val();
          const toDate = $('#toDate').val();

          if (fromDate && toDate && fromDate > toDate) {
            e.preventDefault();
            alert('Tanggal awal tidak boleh lebih besar dari tanggal akhir!');
            return false;
          }
        });
      });

      // Export to Excel function
      function exportToExcel() {
        const table = document.getElementById('reportTable');
        if (!table) return;

        let html = table.outerHTML;
        const url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        const link = document.createElement('a');
        link.href = url;
        link.download = 'laporan_barang_' + new Date().getTime() + '.xls';
        link.click();
      }
    </script>
  </x-slot>
</x-app-layout>
