@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tambah Komponen</h4>
                    <form action="{{ route('komponen.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
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
                        <div class='form-group'>
                            <label for='jenis_id'>Jenis</label>
                            <select name='jenis_id' id='jenis_id'
                                class='form-control @error('jenis_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Jenis</option>
                                @foreach ($data_jenis as $jenis)
                                    <option @if ($jenis->id == old('jenis_id')) selected @endif value="{{ $jenis->id }}">
                                        {{ $jenis->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('komponen.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Komponen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
