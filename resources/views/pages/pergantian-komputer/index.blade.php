@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title text-center mb-3">Pergantian</h4>
                        @can('Pergantian Komputer Create')
                            <a href="{{ route('pergantian-komputer.create') }}" class="btn btn-sm btn-primary mb-3 btnAdd"><i
                                    class="fas fa-plus"></i>
                                Tambah Data</a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tanggal</th>
                                    <th>Kode</th>
                                    <th>Sistem Operasi</th>
                                    <th>User</th>
                                    <th>Deskripsi</th>
                                    @canany(['Pergantian Komputer Edit', 'Pergantian Komputer Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->created_at->translatedFormat('d F Y H:i:s') }}</td>
                                        <td>{{ $item->komputer->kode }}</td>
                                        <td>{{ $item->komputer->getOs() }}</td>
                                        <td>{{ $item->komputer->nama_user }}</td>
                                        <td>{{ $item->deskripsi }}</td>
                                        @canany(['Pergantian Komputer Edit', 'Pergantian Komputer Delete'])
                                            <td>
                                                @can('Pergantian Komputer Edit')
                                                    <a href="{{ route('pergantian-komputer.edit', $item->uuid) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Pergantian Komputer Delete')
                                                    <form action="javascript:void(0)" method="post" class="d-inline"
                                                        id="formDelete">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                            data-action="{{ route('pergantian-komputer.destroy', $item->uuid) }}">Hapus</button>
                                                    </form>
                                                @endcan
                                            </td>
                                        @endcanany
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
