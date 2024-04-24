@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-5">Edit Detail Komponen</h4>
                    <form action="{{ route('komponen-detail.update', $item->uuid) }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        @method('patch')
                        <div class='form-group mb-3'>
                            <label for='keterangan' class='mb-2'>Keterangan</label>
                            <input type='text' name='keterangan'
                                class='form-control @error('keterangan') is-invalid @enderror'
                                value='{{ $item->keterangan ?? old('keterangan') }}'>
                            @error('keterangan')
                                <div class='invalid-feedback'>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group text-right">
                            <a href="{{ route('komponen-detail.index', [
                                'komponen_uuid' => $item->komponen->uuid,
                            ]) }}"
                                class="btn btn-warning">Batal</a>
                            <button class="btn btn-primary">Update Detail Komponen</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
