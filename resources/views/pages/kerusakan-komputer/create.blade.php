@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Tambah Kerusakan Komputer</h4>
                    <form action="{{ route('kerusakan-komputer.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class='form-group'>
                            <label for='komputer_id'>Komputer</label>
                            <select name='komputer_id' id='komputer_id'
                                class='form-control @error('komputer_id') is-invalid @enderror'>
                                <option value='' selected disabled>Pilih Komputer</option>
                                @foreach ($data_komputer as $komputer)
                                    <option @if ($komputer->id == old('komputer_id')) selected @endif value="{{ $komputer->id }}">
                                        {{ $komputer->kode }}
                                    </option>
                                @endforeach
                            </select>
                            @error('komputer_id')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class='form-group mb-3'>
                            <label for='deskripsi' class='mb-2'>Deskripsi</label>
                            <textarea name='deskripsi' id='deskripsi' cols='30' rows='3'
                                class='form-control @error('deskripsi') is-invalid @enderror'>{{ old('deskripsi') }}</textarea>
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
                                <option value="0" selected>Belum Diperbaiki</option>
                                <option value="1">Sedang Diperbaiki</option>
                            </select>
                            @error('status')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('kerusakan-komputer.index') }}" class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Tambah Kerusakan Komputer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
