<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>{{ $data['subject'] ?? 'Laporan Warehouse' }}</title>
</head>
<body>
<h2>{{ $data['subject'] }}</h2>
<p>{!! nl2br(e($body)) !!}</p>

<p><strong>Periode:</strong> {{ $periode ?? '-' }}</p>

<p>📎 Laporan ini dilampirkan dalam bentuk file PDF dan Excel.</p>

<p style="font-size: 12px; color: #777; margin-top: 16px;">
  Dikirim otomatis oleh sistem Warehouse pada {{ now()->format('d/m/Y H:i') }}.
</p>
</body>
</html>
