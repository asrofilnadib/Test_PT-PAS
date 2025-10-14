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
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="card-title mb-0">Statistics</h5>
            <small class="text-muted" id="updatedTime">Diperbarui otomatis</small>
          </div>
        </div>
        <div class="card-body d-flex flex-column justify-content-between">
          <!-- Stats Cards Row 1 -->
          <div class="row g-3 mb-3">
            <div class="col-md-6 col-6">
              <div class="card border shadow-none">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-primary me-3 p-2">
                      <i class="ti ti-box ti-sm"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted small">Total Barang</p>
                      <h4 class="mb-0 fw-bold" id="totalBarang">0</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-6">
              <div class="card border shadow-none">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-success me-3 p-2">
                      <i class="ti ti-arrow-down ti-sm"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted small">Barang Masuk</p>
                      <h4 class="mb-0 fw-bold text-success" id="barangMasuk">0</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Stats Cards Row 2 -->
          <div class="row g-3 mb-3">
            <div class="col-md-6 col-6">
              <div class="card border shadow-none">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-warning me-3 p-2">
                      <i class="ti ti-alert-triangle ti-sm"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted small">Stok Menipis</p>
                      <h4 class="mb-0 fw-bold text-warning" id="stokMenipis">0</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-md-6 col-6">
              <div class="card border shadow-none">
                <div class="card-body p-3">
                  <div class="d-flex align-items-center">
                    <div class="badge rounded-pill bg-label-danger me-3 p-2">
                      <i class="ti ti-arrow-up ti-sm"></i>
                    </div>
                    <div class="flex-grow-1">
                      <p class="mb-0 text-muted small">Barang Keluar</p>
                      <h4 class="mb-0 fw-bold text-danger" id="barangKeluar">0</h4>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Additional Info Section (Optional) -->
          <div class="row">
            <div class="col-12">
              <div class="alert alert-primary mb-0" role="alert">
                <div class="d-flex align-items-center">
                  <i class="ti ti-info-circle me-2"></i>
                  <small class="mb-0">
                    Data statistik diperbarui secara real-time berdasarkan filter tanggal yang dipilih.
                  </small>
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
      $(document).ready(function () {
        // initialize chart
        let chart = Highcharts.chart('pieInOutBarang', {
          chart: {
            type: 'pie',
            backgroundColor: '#ffffff',
            borderRadius: 12,
            style: {
              fontFamily: '"Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif'
            },
            spacing: [15, 15, 15, 15],
            height: null // Auto height
          },
          title: {
            text: 'Komposisi Ketersediaan Stock Barang',
            align: 'left',
            style: {
              fontWeight: '600',
              fontSize: '18px',
              color: '#2c3e50'
            },
            margin: 20,
            x: 5
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
              color: '#000000',
              fontSize: '13px',
              fontWeight: '400'
            },
            useHTML: true,
            formatter: function () {
              return `
        <div style="padding: 8px 12px;">
          <div style="font-weight: 600; margin-bottom: 4px; font-size: 14px;">
            ${this.point.name}
          </div>
          <div style="color: #848484; font-size: 13px;">
            <b>${this.point.y.toLocaleString('id-ID')}</b> Barang
          </div>
          <div style="color: #4e4e4e; font-size: 12px; margin-top: 2px;">
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
              borderWidth: 2,
              borderColor: '#ffffff',
              colors: ['#10b981', '#ef4444', '#3b82f6'],
              dataLabels: {
                enabled: true,
                distance: 20,
                format: '<b>{point.percentage:.1f}%</b>',
                style: {
                  fontSize: '14px',
                  fontWeight: '600',
                  color: '#2c3e50',
                  textOutline: '2px #ffffff'
                }
              },
              showInLegend: true,
              innerSize: '0%',
              shadow: false,
              size: '75%',
              center: ['40%', '50%'],
              states: {
                hover: {
                  brightness: 0.1,
                  halo: {
                    size: 6,
                    opacity: 0.25
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
            backgroundColor: 'transparent',
            borderWidth: 0,
            padding: 0,
            margin: 10,
            itemMarginTop: 6,
            itemMarginBottom: 6,
            itemDistance: 15,
            itemStyle: {
              fontWeight: '500',
              color: '#2c3e50',
              fontSize: '13px',
              cursor: 'pointer',
              lineHeight: '18px'
            },
            itemHoverStyle: {
              color: '#000000'
            },
            symbolRadius: 3,
            symbolHeight: 10,
            symbolWidth: 10,
            symbolPadding: 8,
            useHTML: true,
            labelFormatter: function () {
              return `
        <div style="display: flex; align-items: center; gap: 4px;">
          <span style="white-space: nowrap;">${this.name}</span>
          <span style="color: #64748b; font-size: 12px;">(${this.y})</span>
        </div>
      `;
            }
          },
          series: [{
            name: 'Jumlah',
            colorByPoint: true,
            data: [
              {
                name: 'Stock Aman',
                y: 0,
                color: '#10b981'
              },
              {
                name: 'Stock Menipis',
                y: 0,
                color: '#ef8e44'
              },
              {
                name: 'Stock Habis',
                y: 0,
                color: '#f63b3b'
              }
            ]
          }],
          credits: {enabled: false},
          responsive: {
            rules: [
              {
                condition: {maxWidth: 768},
                chartOptions: {
                  chart: {
                    spacing: [10, 10, 10, 10]
                  },
                  title: {
                    style: {fontSize: '16px'},
                    margin: 15,
                    x: 0
                  },
                  plotOptions: {
                    pie: {
                      size: '85%',
                      center: ['50%', '45%'],
                      dataLabels: {
                        distance: 15,
                        style: {fontSize: '12px'}
                      }
                    }
                  },
                  legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom',
                    floating: false,
                    y: 5,
                    margin: 5,
                    itemMarginTop: 4,
                    itemMarginBottom: 4,
                    itemDistance: 10,
                    itemStyle: {
                      fontSize: '12px'
                    },
                    symbolHeight: 8,
                    symbolWidth: 8,
                    symbolPadding: 6
                  }
                }
              },
              {
                condition: {maxWidth: 480},
                chartOptions: {
                  chart: {
                    spacing: [8, 8, 8, 8]
                  },
                  title: {
                    style: {fontSize: '14px'},
                    margin: 12
                  },
                  plotOptions: {
                    pie: {
                      size: '80%',
                      center: ['50%', '42%'],
                      dataLabels: {
                        distance: 10,
                        style: {fontSize: '11px'},
                        format: '<b>{point.percentage:.0f}%</b>'
                      }
                    }
                  },
                  legend: {
                    itemMarginTop: 3,
                    itemMarginBottom: 3,
                    itemDistance: 8,
                    itemStyle: {
                      fontSize: '11px'
                    },
                    symbolHeight: 7,
                    symbolWidth: 7,
                    symbolPadding: 5,
                    labelFormatter: function () {
                      return `
                <div style="display: flex; align-items: center; gap: 3px;">
                  <span style="white-space: nowrap; font-size: 11px;">${this.name}</span>
                </div>
              `;
                    }
                  }
                }
              }
            ]
          }
        });

        // initialize datatable
        let table = $('#tableAktivitasBarang').DataTable({
          columns: [
            {data: 'tanggal'},
            {data: 'nama_barang'},
            {data: 'jenis'},
            {data: 'qty'},
            {data: 'user'}
          ],
          columnDefs: [
            {width: '20%', target: 0},
            {width: '30%', target: 1},
            {width: '12%', target: 2},
            {width: '15%', target: 3},
          ]
        });

        loadDashboardData();

        $('#filterForm').on('submit', function (e) {
          e.preventDefault();
          loadDashboardData($('#fromDate').val(), $('#toDate').val());
        });

        function loadDashboardData(from = null, to = null) {
          // fetch api for filter dashboard
          $.ajax({
            url: `/dashboard/filter`,
            type: "GET",
            data: {from_date: from, to_date: to},
            beforeSend: () => Swal.showLoading(),
            success: function (res) {
              Swal.close();

              $('#totalBarang').text(res.total_barang);
              $('#barangMasuk').text(res.barang_masuk);
              $('#barangKeluar').text(res.barang_keluar);
              $('#stokMenipis').text(res.barang_menipis);
              $('#updatedTime').text('Diperbarui: ' + new Date().toLocaleString());

              chart.series[0].setData([
                {name: 'Stock Aman', y: res.stock_aman},
                {name: 'Stock Menipis', y: res.stock_menipis},
                {name: 'Stock Habis', y: res.stock_habis},
              ]);

              table.clear().rows.add(res.aktivitas).draw();
            },
            error: function () {
              Swal.fire({icon: 'error', text: 'Gagal memuat data dashboard.'});
            }
          });
        }
      });
    </script>
  </x-slot>
</x-app-layout>
