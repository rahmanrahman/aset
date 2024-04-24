@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3">Detail Komponen : {{ $komponen->nama }}</h4>
                        @can('Komponen Detail Create')
                            <a href="{{ route('komponen-detail.create', [
                                'komponen_uuid' => $komponen->uuid,
                            ]) }}"
                                class="btn btn-sm btn-primary mb-3 btnAdd"><i class="fas fa-plus"></i>
                                Tambah Data</a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Komponen</th>
                                    <th>Keterangan</th>
                                    @canany(['Komponen Detail Edit', 'Komponen Detail Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->komponen->nama }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        <td>
                                            @can('Komponen Detail Edit')
                                                <a href="{{ route('komponen-detail.edit', $item->uuid) }}"
                                                    class="btn btn-sm py-2 btn-info">Edit</a>
                                            @endcan
                                            @can('Komponen Detail Delete')
                                                <form action="javascript:void(0)" method="post" class="d-inline"
                                                    id="formDelete">
                                                    @csrf
                                                    @method('delete')
                                                    <button class="btn btnDelete btn-sm py-2 btn-danger"
                                                        data-action="{{ route('komponen-detail.destroy', $item->uuid) }}">Hapus</button>
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
