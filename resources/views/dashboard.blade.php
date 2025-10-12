<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Warehouse Dashboard') }}
    </h2>
  </x-slot>

  <div class="row">
    <!-- Filter Date -->
    <div class="col-xl-4 mb-4 col-lg-5 col-12">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title mb-3">Filter Berdasarkan Tanggal</h5>

          <form id="filterForm">
            <div class="mb-3">
              <label for="fromDate" class="form-label">Dari Tanggal</label>
              <input type="date" id="fromDate" name="from_date" class="form-control">
            </div>

            <div class="mb-3">
              <label for="toDate" class="form-label">Sampai Tanggal</label>
              <input type="date" id="toDate" name="to_date" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary w-100">
              <i class="ti ti-filter me-1"></i> Tampilkan Data
            </button>
          </form>
        </div>
      </div>
    </div>
    <!-- /Filter Date -->

    <!-- Statistics -->
    <div class="col-xl-8 mb-4 col-lg-7 col-12">
      <div class="card h-100">
        <div class="card-header pb-1 pt-3">
          <div class="d-flex justify-content-between mb-3">
            <h5 class="card-title mb-0">Statistics</h5>
            <small class="text-muted" id="updatedTime">Diperbarui otomatis</small>
          </div>
        </div>
        <div class="card-body">
          <div class="row mb-4">
            <div class="col-md-3 col-6">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                  <div class="badge rounded-pill bg-label-primary me-3 p-2">
                    <i class="ti ti-box ti-sm"></i>
                  </div>
                  <div>
                    <h5 class="mb-0" id="totalBarang">0</h5>
                    <small>Total Barang</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-6">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                  <div class="badge rounded-pill bg-label-success me-3 p-2">
                    <i class="ti ti-arrow-down ti-sm"></i>
                  </div>
                  <div>
                    <h5 class="mb-0" id="barangMasuk">0</h5>
                    <small>Barang Masuk</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-6">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                  <div class="badge rounded-pill bg-label-danger me-3 p-2">
                    <i class="ti ti-arrow-up ti-sm"></i>
                  </div>
                  <div>
                    <h5 class="mb-0" id="barangKeluar">0</h5>
                    <small>Barang Keluar</small>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-3 col-6">
              <div class="card h-100">
                <div class="card-body d-flex align-items-center">
                  <div class="badge rounded-pill bg-label-warning me-3 p-2">
                    <i class="ti ti-alert-triangle ti-sm"></i>
                  </div>
                  <div>
                    <h5 class="mb-0" id="stokMenipis">0</h5>
                    <small>Stok Menipis</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
    <!--/ Statistics -->

    <!-- Barang Table -->
    <div class="col-8 col-xl-8 mb-4">
      <div class="card">
        <div class="card-header">
          <h5 class="mb-0">Aktivitas Terbaru</h5>
        </div>
        <div class="card-datatable table-responsive">
          <table id="tableAktivitasBarang" class="table border-top">
            <thead>
            <tr>
              <th>Tanggal</th>
              <th>Nama Barang</th>
              <th>Tipe</th>
              <th>Jumlah</th>
              <th>User</th>
            </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>
    <!--/ Barang Table -->

    <!-- Chart In Out Barang -->
    <div class="col-xl-4 col-md-6 mb-4" style="width: 405px; height: 350px;">
      <div class="card h-100">
        <div id="pieInOutBarang" class="p-3"></div>
      </div>
    </div>
  </div>
  <!--/ Chart -->

  <!-- Script -->
  <x-slot name="script">
    <script>
      $(document).ready(function() {
        // --- Initialize Chart ---
        let chart = Highcharts.chart('pieInOutBarang', {
          chart: {
            type: 'pie',
            backgroundColor: '#ffffff',
            borderRadius: 12,
            style: {
              fontFamily: '"Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            spacing: [20, 20, 20, 20]
          },

          title: {
            text: 'Komposisi Barang Masuk & Keluar',
            align: 'left',
            style: {
              fontWeight: '600',
              fontSize: '18px',
              color: '#2c3e50'
            },
            margin: 25
          },
          tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.85)',
            borderWidth: 0,
            borderRadius: 8,
            shadow: {
              color: 'rgba(0, 0, 0, 0.1)',
              offsetX: 0,
              offsetY: 2,
              width: 4
            },
            style: {
              color: '#ffffff',
              fontSize: '13px',
              fontWeight: '400'
            },
            useHTML: true,
            formatter: function() {
              return `
        <div style="padding: 8px 12px;">
          <div style="font-weight: 600; margin-bottom: 4px; font-size: 14px;">
            ${this.point.name}
          </div>
          <div style="color: #e0e0e0; font-size: 13px;">
            <b>${this.point.y.toLocaleString('id-ID')}</b> unit
          </div>
          <div style="color: #a0a0a0; font-size: 12px; margin-top: 2px;">
            ${this.percentage.toFixed(1)}% dari total
          </div>
        </div>
      `;
            }
          },
          plotOptions: {
            pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              borderWidth: 3,
              borderColor: '#ffffff',
              colors: ['#10b981', '#ef4444'], // Modern green & red
              dataLabels: {
                enabled: true,
                distance: 15,
                format: '<b>{point.y}</b>',
                style: {
                  fontSize: '15px',
                  fontWeight: '600',
                  color: '#2c3e50',
                  textOutline: '2px #ffffff'
                }
              },
              showInLegend: true,
              innerSize: '0%', // Solid pie
              shadow: false,
              states: {
                hover: {
                  brightness: 0.1,
                  halo: {
                    size: 8,
                    opacity: 0.25
                  }
                }
              },
              point: {
                events: {
                  mouseOver: function() {
                    this.graphic.attr({
                      translateX: 8,
                      translateY: 8
                    });
                  },
                  mouseOut: function() {
                    this.graphic.attr({
                      translateX: 0,
                      translateY: 0
                    });
                  }
                }
              }
            }
          },

          legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            floating: false,
            backgroundColor: '#f8f9fa',
            borderRadius: 8,
            padding: 12,
            itemMarginTop: 8,
            itemMarginBottom: 8,
            itemStyle: {
              fontWeight: '500',
              color: '#2c3e50',
              fontSize: '14px',
              cursor: 'pointer'
            },
            itemHoverStyle: {
              color: '#000000'
            },
            symbolRadius: 4,
            symbolHeight: 12,
            symbolWidth: 12,
            symbolPadding: 8,
            useHTML: true,
            labelFormatter: function() {
              return `
        <span style="display: inline-block; width: 120px;">
          ${this.name}
        </span>
      `;
            }
          },

          series: [{
            name: 'Jumlah',
            colorByPoint: true,
            size: '100%',
            data: [
              {
                name: 'Barang Masuk',
                y: 0,
                color: {
                  linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                  stops: [
                    [0, '#10b981'],
                    [1, '#10b981']
                  ]
                }
              },
              {
                name: 'Barang Keluar',
                y: 0,
                color: {
                  linearGradient: { x1: 0, y1: 0, x2: 1, y2: 1 },
                  stops: [
                    [0, '#ef4444'],
                    [1, '#dc2626']
                  ]
                }
              }
            ]
          }],

          credits: { enabled: false },

          responsive: {
            rules: [{
              condition: { maxWidth: 768 },
              chartOptions: {
                legend: {
                  layout: 'horizontal',
                  align: 'center',
                  verticalAlign: 'bottom',
                  floating: false,
                  y: 0
                },
                title: {
                  style: { fontSize: '16px' }
                },
                plotOptions: {
                  pie: {
                    dataLabels: {
                      distance: 10,
                      style: { fontSize: '13px' }
                    }
                  }
                }
              }
            }]
          }
        });

        let table = $('#tableAktivitasBarang').DataTable({
          columns: [
            { data: 'tanggal' },
            { data: 'nama_barang' },
            { data: 'jenis' },
            { data: 'qty' },
            { data: 'user' }
          ]
        });

        loadDashboardData();

        $('#filterForm').on('submit', function(e) {
          e.preventDefault();
          loadDashboardData($('#fromDate').val(), $('#toDate').val());
        });

        function loadDashboardData(from = null, to = null) {
          $.ajax({
            url: `/dashboard/filter`,
            type: "GET",
            data: { from_date: from, to_date: to },
            beforeSend: () => Swal.showLoading(),
            success: function(res) {
              Swal.close();

              $('#totalBarang').text(res.total_barang);
              $('#barangMasuk').text(res.barang_masuk);
              $('#barangKeluar').text(res.barang_keluar);
              $('#stokMenipis').text(res.barang_menipis);
              $('#updatedTime').text('Diperbarui: ' + new Date().toLocaleString());

              chart.series[0].setData([
                { name: 'Barang Masuk', y: res.barang_masuk },
                { name: 'Barang Keluar', y: res.barang_keluar }
              ]);

              table.clear().rows.add(res.aktivitas).draw();
            },
            error: function() {
              Swal.fire({ icon: 'error', text: 'Gagal memuat data dashboard.' });
            }
          });
        }
      });
    </script>
  </x-slot>
</x-app-layout>
