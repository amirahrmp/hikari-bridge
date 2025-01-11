<!-- Menghubungkan dengan view template layout2 -->
@extends('layout2')

@section('konten')

<h1>Data Purchase</h1>

<table border="1">
    <thead>
        <tr>
            <th>Kode Bahan Baku</th>
            <th>Nama Bahan Baku</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purchase as $p)
            <tr>
                <td>{{ $p->kode_bahanbaku }}</td>
                <td>{{ $p->nama_bahanbaku }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

@endsection