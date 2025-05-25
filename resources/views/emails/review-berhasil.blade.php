<!DOCTYPE html>
<html>

<head>
    <title>Review Buku Berhasil</title>
</head>

<body>
    <h1>Review Baru Telah Ditambahkan!</h1>
    <p>Sebuah review baru telah berhasil disimpan ke dalam sistem.</p>
    <p>Berikut adalah detailnya:</p>
    <ul>
        {{-- Gunakan $dataReview yang berasal dari Mailable --}}
        <li><strong>NIM:</strong> {{ $dataReview['nim'] }}</li>
        <li><strong>Kode Buku:</strong> {{ $dataReview['induk_buku'] }}</li>
        <li><strong>Judul:</strong> {{ $dataReview['judul'] }}</li>
        <li><strong>Pengarang:</strong> {{ $dataReview['pengarang'] }}</li>
        <li><strong>Review:</strong> {{ $dataReview['review'] }}</li>
        <li><strong>Link Sosmed:</strong> <a href="{{ $dataReview['link_upload'] }}">{{ $dataReview['link_upload'] }}</a>
        </li>
        <li><strong>Tanggal Review:</strong> {{ $dataReview['tgl_review'] }}</li>
    </ul>
    <p>Terima kasih.</p>
</body>

</html>
