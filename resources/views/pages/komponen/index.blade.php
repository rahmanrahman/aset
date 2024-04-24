@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3">Komponen</h4>
                        @can('Komponen Create')
                            <a href="{{ route('komponen.create') }}" class="btn btn-sm btn-primary mb-3 btnAdd"><i
                                    class="fas fa-plus"></i>
                                Tambah Data</a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    @canany(['Komponen Edit', 'Komponen Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nama }}</td>
                                        <td>{{ $item->jenis->nama }}</td>
                                        <td>
                                            @can('Komponen Detail Index')
                                                <a href="{{ route('komponen-detail.index', [
                                                    'komponen_uuid' => $item->uuid,
                                                ]) }}"
                                                    class="btn btn-sm py-2 btn-warning">Detail</a>
                                            @endcan
                                            @can('Komponen Edit')
                                                <a href="{{ route('komponen.edit', $item->uuid) }}"
                                                    class="btn btn-sm py-2 btn-info">Edit</a>
                                            @endcan
                                            @can('Komponen Delete')
                                                <form action="javascript:void(0)" method="post" class="d-inline"
                                                    id="formDelete">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                        data-action="{{ route('komponen.destroy', $item->uuid) }}">Hapus</button>
                                                </form>
                                            @endcan
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
