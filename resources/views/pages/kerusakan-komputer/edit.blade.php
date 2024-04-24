@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Kerusakan</h4>
                    <form action="{{ route('kerusakan-komputer.update', $item->uuid) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='kode' class='mb-2'>Kode</label>
                            <input type='text' name='kode' id='kode'
                                class='form-control @error('kode') is-invalid @enderror'
                                value='{{ $item->komputer->kode ?? old('kode') }}' readonly>
                            @error('kode')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='nama' class='mb-2'>Nama</label>
                            <input type='text' name='nama' class='form-control @error('nama') is-invalid @enderror'
                                value='{{ $item->komputer->nama ?? old('nama') }}' readonly>
                            @error('nama')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='keterangan' class='mb-2'>Keterangan</label>
                            <textarea name='keterangan' id='keterangan' cols='30' rows='3'
                                class='form-control @error('keterangan') is-invalid @enderror' readonly>{{ $item->komputer->keterangan ?? old('keterangan') }}</textarea>
                            @error('keterangan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='deskripsi' class='mb-2'>Deskripsi</label>
                            <textarea name='deskripsi' id='deskripsi' cols='30' rows='3'
                                class='form-control @error('deskripsi') is-invalid @enderror'>{{ $item->deskripsi ?? old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group'>
                            <label for='status'>Status</label>
                            <select name='status' id='status'
                                class='form-control @error('status') is-invalid @enderror'>
                                <option value='' disabled>Pilih Status</option>
                                <option @if ($item->status == 0) selected @endif value="0">Belum Diperbaiki
                                </option>
                                <option @if ($item->status == 1) selected @endif value="1" selected>Sedang
                                    Diperbaiki</option>
                                <option @if ($item->status == 2) selected @endif value="2">Sudah Diperbaiki
                                </option>
                            </select>
                            @error('status')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3 d-selesai'>
                            <label for='tanggal_perbaikan' class='mb-2'>Tanggal Selesai Perbaikan</label>
                            <input type='datetime-local' name='tanggal_perbaikan' id='tanggal_perbaikan'
                                class='form-control @error('tanggal_perbaikan') is-invalid @enderror'
                                @if ($item->tanggal_perbaikan) value='{{ $item->tanggal_perbaikan->translatedFormat('Y-m-d H:i:s') }}' @else value='{{ old('tanggal_perbaikan') }}' @endif>
                            @error('tanggal_perbaikan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <label class="form-check-label text-muted">
                                    <input type="checkbox" class="form-check-input" id="pergantian_cek"
                                        name="pergantian_cek" @if ($item->pergantian) checked @endif>
                                    Ada Pergantian
                                </label>
                            </div>
                        </div>
                        <div class='form-group mb-3 @if (!$item->pergantian) d-none @endif d-pergantian'>
                            <label for='deskripsi_pergantian' class='mb-2'>Deskripsi Pergantian</label>
                            <textarea name='deskripsi_pergantian' id='deskripsi_pergantian' cols='30' rows='3'
                                class='form-control @error('deskripsi_pergantian') is-invalid @enderror'>{{ $item->pergantian->deskripsi ?? '' }}</textarea>
                            @error('deskripsi_pergantian')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('kerusakan-komputer.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Kerusakan</button>
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
            let status = '{{ $item->status }}';
            let old_status = "{{ old('status') }}";
            let tanggal_perbaikan = '{{ $item->tanggal_perbaikan }}';
            if (status != 2) {
                $('.d-selesai').addClass('d-none');
            }
            if (old_status == 2) {
                $('d-selesai').val(tanggal_perbaikan);
                $('.d-selesai').removeClass('d-none');
            }
            $('#status').on('change', function() {
                let status = $(this).val();
                if (status == 2) {
                    $('d-selesai').val(tanggal_perbaikan);
                    $('.d-selesai').removeClass('d-none');
                } else {
                    $('.d-selesai').addClass('d-none');
                }
            })

            $('#pergantian_cek').on('change', function() {
                let is_penggantian = $(this).prop('checked');
                if (is_penggantian) {
                    $('.d-pergantian').removeClass('d-none');
                } else {
                    $('.d-pergantian').addClass('d-none');
                }
            })
        })
    </script>
@endpush
