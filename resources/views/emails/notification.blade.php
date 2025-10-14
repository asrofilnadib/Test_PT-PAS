<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>{{ $data['subject'] ?? 'Notifikasi Warehouse' }}</title>
</head>
<body>
<h2>{{ $data['subject'] }}</h2>
<p>{!! nl2br(e($body)) !!}</p>

<h4>Periode: </h4> {{ $periode ?? '-' }}

@if(!empty($report_data))
  <h4>📦 Data Laporan:</h4>
  <table>
    <thead>
    <tr>
      <th>No</th>
      <th>Nama Barang</th>
      <th>Jenis Barang</th>
      <th>Satuan</th>
      <th>Jenis Transaksi</th>
      <th>Jumlah</th>
      <th>Tanggal Transaksi</th>
    </tr>
    </thead>
    <tbody>
    @foreach($report_data as $index => $item)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $item['nama_barang'] ?? '-' }}</td>
        <td>{{ $item['jenis_barang'] ?? '-' }}</td>
        <td>{{ $item['name_satuan'] ?? '-' }}</td>
        <td>
          @if(($item['jenis'] ?? '') === 'Masuk')
            <span class="badge badge-success">Masuk</span>
          @else
            <span class="badge badge-danger">Keluar</span>
          @endif
        </td>
        <td>{{ number_format($item['jumlah'] ?? 0) }}</td>
        <td>{{ \Carbon\Carbon::parse($item['tanggal_transaksi'])->format('d/m/Y') ?? '-' }}</td>
      </tr>
    @endforeach
    </tbody>
  </table>
@else
  <p><em>Tidak ada data laporan yang ditemukan.</em></p>
@endif

<p style="font-size: 12px; color: #777; margin-top: 16px;">
  Dikirim otomatis oleh sistem Warehouse pada {{ now()->format('d/m/Y H:i') }}.
</p>
</body>
</html>
