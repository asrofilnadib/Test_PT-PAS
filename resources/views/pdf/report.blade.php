<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Laporan Barang - {{ $periode }}</title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
    th { background-color: #f5f5f5; }
  </style>
</head>
<body>
<h2 style="text-align:center;">Laporan Transaksi Barang</h2>
<p>Periode: {{ $periode }}</p>
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
  @foreach($data as $index => $item)
    <tr>
      <td>{{ $index + 1 }}</td>
      <td>{{ $item->nama_barang }}</td>
      <td>{{ $item->jenis_barang }}</td>
      <td>{{ $item->name_satuan }}</td>
      <td>{{ $item->jenis }}</td>
      <td>{{ number_format($item->jumlah) }}</td>
      <td>{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d/m/Y') }}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</body>
</html>
