@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5 text-center">Edit Komputer</h4>
                    <form action="{{ route('komputer.update', $item->uuid) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='kode' class='mb-2'>Kode</label>
                            <input type='text' name='kode' id='kode'
                                class='form-control @error('kode') is-invalid @enderror'
                                value='{{ $item->kode ?? old('kode') }}'>
                            @error('kode')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nama' class='mb-2'>Nama</label>
                            <input type='text' name='nama' class='form-control @error('nama') is-invalid @enderror'
                                value='{{ $item->nama ?? old('nama') }}'>
                            @error('nama')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='keterangan' class='mb-2'>Keterangan</label>
                            <textarea name='keterangan' id='keterangan' cols='30' rows='3'
                                class='form-control @error('keterangan') is-invalid @enderror'>{{ $item->keterangan ?? old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='spesifikasi' class='mb-2'>Spesifikasi</label>
                            <textarea name='spesifikasi' id='spesifikasi' cols='30' rows='3'
                                class='form-control @error('spesifikasi') is-invalid @enderror'>{{ $item->spesifikasi ?? old('spesifikasi') }}</textarea>
                            @error('spesifikasi')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='department_id'>Department</label>
                            <select name='department_id' id='department_id'
                                class='form-control @error('department_id') is-invalid @enderror'>
                                <option value='' selected>Pilih Department</option>
                                @foreach ($data_department as $department)
                                    <option @if ($department->id == $item->department_id) selected @endif value='{{ $department->id }}'>
                                        {{ $department->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('department_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='sistem_operasi_id'>Sistem Operasi</label>
                            <select name='sistem_operasi_id' id='sistem_operasi_id'
                                class='form-control @error('sistem_operasi_id') is-invalid @enderror'>
                                <option value='' selected>Pilih Sistem Operasi</option>
                                @foreach ($data_sistem_operasi as $sistem_operasi)
                                    <option @if ($sistem_operasi->id == $item->sistem_operasi_id) selected @endif
                                        value='{{ $sistem_operasi->id }}'>
                                        {{ $sistem_operasi->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('sistem_operasi_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='sistem_operasi_detail_id'>Tipe</label>
                            <select name='sistem_operasi_detail_id' id='sistem_operasi_detail_id'
                                class='form-control @error('sistem_operasi_detail_id') is-invalid @enderror'>
                                <option value='' selected>Pilih Tipe</option>
                            </select>
                            @error('sistem_operasi_detail_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='ip_address_id'>IP Address</label>
                            <select name='ip_address_id' id='ip_address_id'
                                class='form-control @error('ip_address_id') is-invalid @enderror'>
                                <option value='' selected>Pilih IP Address</option>
                                @foreach ($data_ip_address as $ip)
                                    <option @if ($ip->id == $item->ip_address_id) selected @endif value='{{ $ip->id }}'>
                                        {{ $ip->ip }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ip_address_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nama_user' class='mb-2'>Nama User</label>
                            <input type='text' name='nama_user' id='nama_user'
                                class='form-control @error('nama_user') is-invalid @enderror'
                                value='{{ $item->nama_user ?? old('nama_user') }}'>
                            @error('nama_user')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='tanggal_pembelian' class='mb-2'>Tanggal Pembelian</label>
                            <input type='date' name='tanggal_pembelian' id='tanggal_pembelian'
                                class='form-control @error('tanggal_pembelian') is-invalid @enderror'
                                value='{{ $item->tanggal_pembelian ? $item->tanggal_pembelian->translatedFormat('Y-m-d') : old('tanggal_pembelian') }}'>
                            @error('tanggal_pembelian')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group text-right">
                            <a href="{{ route('komputer.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Komputer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {

            let sistem_operasi_id = '{{ $item->sistem_operasi_id }}';
            if (sistem_operasi_id) {
                let sistem_operasi_detail_id = '{{ $item->sistem_operasi_detail_id }}';
                let id = sistem_operasi_id;
                $.ajax({
                    url: '{{ route('sistem-operasi.detail') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        id
                    },
                    success: function(data) {
                        $('#sistem_operasi_detail_id').empty();
                        if (data.length > 0) {
                            $('#sistem_operasi_detail_id').append(
                                `<option selected>Pilih Tipe</option>`);
                            data.forEach(detail => {
                                if (detail.id == sistem_operasi_detail_id) {
                                    $('#sistem_operasi_detail_id').append(
                                        `<option selected value="${detail.id}">${detail.tipe}</option>`
                                    )
                                }
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            }
            $('#sistem_operasi_id').on('change', function() {
                let id = $(this).val();
                $.ajax({
                    url: '{{ route('sistem-operasi.detail') }}',
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        id
                    },
                    success: function(data) {
                        $('#sistem_operasi_detail_id').empty();
                        if (data.length > 0) {
                            $('#sistem_operasi_detail_id').append(
                                `<option selected>Pilih Tipe</option>`);
                            data.forEach(detail => {
                                $('#sistem_operasi_detail_id').append(
                                    `<option value="${detail.id}">${detail.tipe}</option>`
                                )
                            });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                })
            })
        })
    </script>
@endpush
