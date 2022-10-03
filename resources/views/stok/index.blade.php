@extends('layouts.master')

@section('title')
Manajemen Stok
@endsection

@section('breadcrumb')
@parent
<li class="active">Manajemen Stok</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <div class="btn-group">
                    <button type="button" class="btn btn-default" data-toggle="modal" data-target="#create">

                        Tambah Material

                    </button>
                    <div class="modal fade" id="create" style="display: none;" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">

                                    <h4 class="modal-title">Tambah Material</h4>

                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nama_barang">Nama Material</label>
                                        <input type="text" class="form-control" id="nama_barang" name="nama_barang">
                                    </div>
                                    <div class="form-group">
                                        <label for="deskripsi">Deskripsi</label>
                                        <textarea name="deskripsi" class="form-control" id="deskripsi"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="sisa">Sisa</label>
                                        <input type="number" class="form-control" id="sisa" name="sisa">
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary" onclick="buatStok()">Save
                                        changes</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Nama Material</th>
                        <th>Deskripsi</th>
                        <th>Sisa</th>
                        <th>Action</th>
                    </thead>
                    <tbody>
                        @foreach($stoks as $nomor => $stok)
                        <tr>
                            <td>{{$nomor + 1}}</td>
                            <td>{{$stok->created_at->format("Y-m-d")}}</td>
                            <td>{{$stok->nama_barang}}</td>
                            <td>{{$stok->deskripsi}}</td>
                            <td>{{$stok->sisa}}</td>
                            <td class="d-flex">
                                <button class="btn btn-warning" data-toggle="modal"
                                    data-target="#edit_{{$stok->id}}">Edit</button>
                                <div class="modal fade" id="edit_{{$stok->id}}" style="display: none;"
                                    aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Edit Material</h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group" style="display: block; width: 100%;">
                                                    <label for="nama_barang">Nama Barang</label>
                                                    <input type="text" style="display: block; width: 100%;"
                                                        class="form-control" id="nama_barang" name="nama_barang"
                                                        value="{{$stok->nama_barang}}">
                                                </div>
                                                <div class="form-group" style="display: block; width: 100%;">
                                                    <label for="deskripsi">Deskripsi</label>
                                                    <textarea name="deskripsi" style="display: block; width: 100%;"
                                                        class="form-control"
                                                        id="deskripsi">{{$stok->deskripsi}}</textarea>
                                                </div>
                                                <div class="form-group" style="display: block; width: 100%;">
                                                    <label for="sisa">Sisa</label>
                                                    <input type="number" style="display: block; width: 100%;"
                                                        class="form-control" id="sisa" name="sisa"
                                                        value="{{$stok->sisa}}">
                                                </div>
                                            </div>
                                            <div class="modal-footer justify-content-between">
                                                <button type="button" class="btn btn-default"
                                                    data-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-primary"
                                                    onclick="editStok({{$stok->id}})">Save changes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-danger" onclick="hapusStok({{$stok->id}})">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let row = col = undefined;
    let table = $('.table').DataTable();
    let tanggal = new Date().toISOString().slice(0, 10);

    function hapusStok(id) {
        $.ajax({
            url: "/stok/" + id,
            type: "DELETE",
            data: {
                _token: "{{csrf_token()}}"
            },
            success: function (data) {
                alert(data.message);
                table.row($(`#edit_${id}`).parents('tr')).remove().draw();
            }
        });
    }

    function editStok(id) {
        $.ajax({
            url: "/stok/" + id,
            type: "PUT",
            data: {
                _token: "{{csrf_token()}}",
                nama_barang: $(`#edit_${id} #nama_barang`).val(),
                deskripsi: $(`#edit_${id} #deskripsi`).val(),
                sisa: $(`#edit_${id} #sisa`).val(),
            },
            success: function (data) {
                $('.modal').modal('hide');
                $(`#edit_${id} input`).val("");
                $(`#edit_${id} textarea`).val("");
                $(`#edit_${id}`).parents("tr").find("td").eq(2).text(data.nama_barang);
                $(`#edit_${id}`).parents("tr").find("td").eq(3).text(data.deskripsi);
                $(`#edit_${id}`).parents("tr").find("td").eq(4).text(data.sisa);
            }
        });
    }

    function buatStok() {
        var nama_barang = $('#create #nama_barang').val();
        var deskripsi = $('#create #deskripsi').val();
        var sisa = $('#create #sisa').val();
        $.ajax({
            url: "/stok",
            type: "POST",
            data: {
                _token: "{{csrf_token()}}",
                nama_barang: nama_barang,
                deskripsi: deskripsi,
                sisa: sisa
            },
            success: function (response) {
                $('.modal').modal('hide');
                $("#create input").val("");
                $("#create textarea").val("");
                row = document.createElement("tr");
                $(row).append(`<td>${table.rows().count() + 1}</td>
                                <td>${tanggal}</td>
                                <td>${response.nama_barang}</td>
                                <td>${response.deskripsi}</td>
                                <td>${response.sisa}</td>
                                <td class="d-flex">
                                    <button class="btn btn-warning" data-toggle="modal"
                                        data-target="#edit_${response.id}">Edit</button>
                                    <div class="modal fade" id="edit_${response.id}" style="display: none;"
                                        aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title">Edit Material</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group" style="display: block; width: 100%;">
                                                        <label for="nama_barang">Nama Barang</label>
                                                        <input type="text" class="form-control" id="nama_barang"
                                                            name="nama_barang" style="display: block; width: 100%;" value="${response.nama_barang}">
                                                    </div>
                                                    <div class="form-group" style="display: block; width: 100%;">
                                                        <label for="deskripsi">Deskripsi</label>
                                                        <textarea name="deskripsi" style="display: block; width: 100%;" class="form-control"
                                                            id="deskripsi">${response.deskripsi}</textarea>
                                                    </div>
                                                    <div class="form-group" style="display: block; width: 100%;">
                                                        <label for="sisa">Sisa</label>
                                                        <input type="number" style="display: block; width: 100%;" class="form-control" id="sisa" name="sisa"
                                                            value="${response.sisa}">
                                                    </div>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="button" class="btn btn-primary"
                                                        onclick="editStok(${response.id})">Save changes</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-danger" onclick="hapusStok(${response.id})">Hapus</button>`)
                table.row.add(row).draw();
            }
        });
    }
</script>
@endpush