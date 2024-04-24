@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3 text-center">Komputer</h4>
                        @can('Komputer Create')
                            <a href="{{ route('komputer.create') }}" class="btn btn-sm btn-primary mb-3 btnAdd"><i
                                    class="fas fa-plus"></i>
                                Tambah Data</a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Keterangan</th>
                                    <th>Sistem Operasi</th>
                                    <th>User</th>
                                    <th>Tanggal Pembelian</th>
                                    <th>Status</th>
                                    @canany(['Komputer Edit', 'Komputer Delete', 'Komputer Show'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kode }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>{{ $item->getOs() }}</td>
                                        <td>{{ $item->nama_user }}</td>
                                        <td>{{ $item->tanggal_pembelian() }}</td>
                                        <td>{!! $item->status() !!}</td>
                                        @canany(['Komputer Edit', 'Komputer Delete', 'Komputer Show'])
                                            <td>
                                                @can('Komputer Detail Index')
                                                    <a href="{{ route('komputer.show', $item->uuid) }}"
                                                        class="btn btn-sm py-2 btn-warning">Detail</a>
                                                @endcan
                                                @can('Komputer Edit')
                                                    <a href="{{ route('komputer.edit', $item->uuid) }}"
                                                        class="btn btn-sm py-2 btn-info">Edit</a>
                                                @endcan
                                                @can('Komputer Delete')
                                                    <form action="javascript:void(0)" method="post" class="d-inline"
                                                        id="formDelete">
                                                        @csrf
                                                        @method('delete')
                                                        <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                            data-action="{{ route('komputer.destroy', $item->uuid) }}">Hapus</button>
                                                    </form>
                                                @endcan
                                            @endcanany
                                        </td>
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
