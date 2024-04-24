@extends('layouts.app')
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h4 class="card-title mb-3">User</h4>
                        @can('User Create')
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary mb-3 btnAdd"><i class="fas fa-plus"></i>
                                Tambah Data</a>
                        @endcan
                    </div>
                    <div class="table-responsive">
                        <table class="table dtTable table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    @canany(['User Edit', 'User Delete'])
                                        <th>Aksi</th>
                                    @endcanany
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="javascript:void(0)" method="post" id="myForm">
                    <div class="modal-body">
                        @csrf
                        <input type="number" id="id" name="id" hidden>
                        <div class="form-group">
                            <label for="name">Nama</label>
                            <input type="text" class="form-control" name="name" id="name">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" id="email">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control"></select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" id="password">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation"
                                id="password_confirmation">
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
<x-Datatable />
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2/sweetalert2.all.min.js') }}">
    <link rel="stylesheet" href="{{ asset('assets/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush
@push('scripts')
    <script src="{{ asset('assets/sweetalert2/sweetalert2.min.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        $(function() {
            let otable = $('.dtTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.data') }}',
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'roles',
                        name: 'roles'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });


            let getRoles = function(callback) {
                $.ajax({
                    url: '{{ route('roles.get') }}',
                    dataType: 'JSON',
                    type: 'POST',
                    success: function(data) {
                        callback(data);
                    },
                    error: function(err) {
                        console.error(err);
                        callback([]);
                    }
                });
            };

            let getUserDetail = function(user_id, callback) {
                var url = '{{ route('users.show', ':id') }}';
                url = url.replace(':id', user_id);
                $.ajax({
                    url: url,
                    dataType: 'JSON',
                    type: 'GET',
                    success: function(data) {
                        callback(data); // Panggil callback dengan data yang diterima dari server
                    },
                    error: function(error) {
                        console.log(error);
                        callback(null); // Panggil callback dengan null jika terjadi kesalahan
                    }
                });
            }

            $('.btnAdd').on('click', function() {
                getRoles(function(roles) {
                    $('#role').empty();
                    $('#role').append(`<option selected disabled>Pilih Role</option>`);
                    roles.forEach(role => {
                        $('#role').append(
                            `<option value="${role.name}">${role.name}</option>`);
                    });
                    $('#myModal .modal-title').text('Tambah Data');
                    $('#myModal').modal('show');
                });
            });

            $('#myModal #myForm').on('submit', function(e) {
                e.preventDefault();
                let form = $('#myModal #myForm');
                $.ajax({
                    url: '{{ route('users.store') }}',
                    type: 'POST',
                    dataType: 'JSON',
                    data: form.serialize(),
                    success: function(response) {
                        sweetalert('success', response.message);
                        otable.ajax.reload();
                        $('#myModal').modal('hide');
                    },
                    error: function(response) {
                        let errors = response.responseJSON?.errors
                        $(form).find('.text-danger.text-small').remove()
                        if (errors) {
                            for (const [key, value] of Object.entries(errors)) {
                                $(`[name='${key}']`).parent().append(
                                    `<sp class="text-danger text-small">${value}</sp>`)
                                $(`[name='${key}']`).addClass('is-invalid')
                            }
                        }
                    }
                })
            })

            $('body').on('click', '.btnEdit', function() {
                let id = $(this).data('id');
                getUserDetail(id, function(user) {

                    if (user) {
                        $('#myForm #id').val(id);
                        $('#myForm #name').val(user.name);
                        $('#myForm #email').val(user.email);
                        $('#role').empty();
                        $('#role').append(`<option selected disabled>Pilih Role</option>`);
                        getRoles(function(roles) {
                            roles.forEach(role => {
                                let isSelected = role.id == user.roles[0].id;
                                $('#role').append(
                                    `<option ${isSelected ? 'selected' : ''} value="${role.name}">${role.name}</option>`
                                );
                            });
                        });

                        $('#myModal .modal-title').text('Edit Data');
                        $('#myModal').modal('show');
                    } else {
                        console.error('Gagal mendapatkan detail pengguna.');
                    }
                });
            });

            $('body').on('click', '.btnDelete', function(e) {
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                Swal.fire({
                    title: 'Apakah Yakin?',
                    text: `${name} akan dihapus dan tidak bisa dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yakin'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var url =
                            '{{ route('users.destroy', ':id') }}';
                        url = url.replace(':id', id);
                        $.ajax({
                            url: url,
                            type: 'DELETE',
                            dataType: 'JSON',
                            success: function(response) {
                                sweetalert('success', response.message);
                                otable.ajax.reload();
                                $('#myModal').modal('hide');

                            },
                            error: function(response) {
                                sweetalert('success', response.responseJSON.errors
                                    .name);
                            }
                        })
                    }
                })
            })

            $('#myModal').on('hidden.bs.modal', function() {
                let form = $('#myModal #myForm');
                $(form).find('.text-danger.text-small').remove();
                $(form).find('.form-control').removeClass('is-invalid');
                form.trigger('reset');
            })
        })
    </script>
@endpush
