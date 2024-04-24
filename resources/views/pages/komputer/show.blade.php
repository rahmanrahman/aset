@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title text-center mb-5 text-center">Detail Komputer</h4>
                    <form action="{{ route('komputer.update', $item->uuid) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='kode' class='mb-2'>Kode</label>
                            <input type='text' name='kode' id='kode'
                                class='form-control @error('kode') is-invalid @enderror'
                                value='{{ $item->kode ?? old('kode') }}' readonly>
                            @error('kode')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nama' class='mb-2'>Nama</label>
                            <input type='text' name='nama' class='form-control @error('nama') is-invalid @enderror'
                                value='{{ $item->nama ?? old('nama') }}' readonly>
                            @error('nama')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='keterangan' class='mb-2'>Keterangan</label>
                            <textarea name='keterangan' id='keterangan' cols='30' rows='3'
                                class='form-control @error('keterangan') is-invalid @enderror' readonly>{{ $item->keterangan ?? old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='spesifikasi' class='mb-2'>Spesifikasi</label>
                            <textarea name='spesifikasi' id='spesifikasi' cols='30' rows='3'
                                class='form-control @error('spesifikasi') is-invalid @enderror' readonly>{{ $item->spesifikasi ?? old('spesifikasi') }}</textarea>
                            @error('spesifikasi')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='department' class='mb-2'>Department</label>
                            <input type='text' name='department' id='department'
                                class='form-control @error('department') is-invalid @enderror'
                                value='{{ $item->department->nama ?? '-' }}' readonly>
                            @error('department')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='sistem_operasi' class='mb-2'>Sistem Operasi</label>
                            <input type='text' name='sistem_operasi' id='sistem_operasi'
                                class='form-control @error('sistem_operasi') is-invalid @enderror'
                                value='{{ $item->getOs() }}' readonly>
                            @error('sistem_operasi')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='ip' class='mb-2'>IP Address</label>
                            <input type='text' name='ip' id='ip'
                                class='form-control @error('ip') is-invalid @enderror'
                                value='{{ $item->ip_address->ip ?? '-' }}' readonly>
                            @error('ip')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nama_user' class='mb-2'>Nama User</label>
                            <input type='text' name='nama_user' id='nama_user'
                                class='form-control @error('nama_user') is-invalid @enderror'
                                value='{{ $item->nama_user ?? old('nama_user') }}' readonly>
                            @error('nama_user')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tanggal_pembelian' class='mb-2'>Tanggal Pembelian</label>
                            <input type='text' name='tanggal_pembelian' id='tanggal_pembelian'
                                class='form-control @error('tanggal_pembelian') is-invalid @enderror'
                                value='{{ $item->tanggal_pembelian ? $item->tanggal_pembelian->translatedFormat('Y-m-d') : old('tanggal_pembelian') }}'
                                readonly>
                            @error('tanggal_pembelian')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md">
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-5 text-center">Riwayat Kerusakan</h4>
                            <div class="table-responsive">
                                <table class="table dtTable table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                            <th>Tanggal Selesai Perbaikan</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->kerusakan as $kerusakan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $kerusakan->created_at->translatedFormat('d F Y H:i:s') }}</td>
                                                <td>{{ $kerusakan->deskripsi }}</td>
                                                <td>
                                                    @if ($kerusakan->status == 2)
                                                        {{ $kerusakan->tanggal_perbaikan->translatedFormat('d F Y H:i:s') }}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{!! $kerusakan->status() !!}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title mb-5 text-center">Riwayat Penggantian</h4>
                            <div class="table-responsive">
                                <table class="table dtTable table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Tanggal</th>
                                            <th>Deskripsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($item->pergantian as $pergantian)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $pergantian->created_at->translatedFormat('d F Y H:i:s') }}</td>
                                                <td>{{ $pergantian->deskripsi }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
<x-Sweetalert />
@push('scripts')
    <script>
        $(function() {
            $('.dtTable').DataTable();
        })
    </script>
@endpush
