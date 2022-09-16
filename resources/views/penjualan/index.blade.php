@extends('layouts.master')

@section('title')
    Daftar Penjualan
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css"/>
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Daftar Penjualan</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-penjualan">
                    <thead>
                        <th width="5%">No</th>
                        <th>Tanggal</th>
                        <th>Kode Member</th>
                        <th>Total Item</th>
                        <th>Total Harga</th>
                        <th>Total Bayar</th>
                        <th>Kekurangan</th>
                        <th>Kasir</th>
                        <th width="15%"><i class="fa fa-cog"></i></th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-edit" tabindex="-1" role="dialog" aria-labelledby="modal-edit">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Edit Penjualan</h4>
            </div>
            <div class="modal-body">
                <form action="{{ route('penjualan.update') }}" method="post" id="form-edit">
                    @csrf
                    <input type="hidden" id="id_penjualan" name="id_penjualan">
                    <div class="form-group">
                        <label for="total_item">Total Item</label>
                        <input type="number" name="total_item" id="total_item" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="total_harga">Total Harga</label>
                        <input type="number" name="total_harga" id="total_harga" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="kekurangan">Kekurangan</label>
                        <input type="number" name="kekurangan" id="kekurangan" class="form-control">
                    </div>
                <button type="submit" onclick="hideEdit()" class="btn btn-warning">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan.detail')
@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script>
    let table, table1;

    $(function () {
        table = $('.table-penjualan').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('penjualan.data') }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'tanggal'},
                {data: 'kode_member'},
                {data: 'total_item'},
                {data: 'total_harga'},
                {data: 'bayar'},
                {data: 'kekurangan'},
                {data: 'kasir'},
                {data: 'aksi', searchable: false, sortable: false},
            ],
            dom: 'Brt',
            buttons: [
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns : [0,1,2,3,4,5,6,7],
                        format: {
                            body: function (data, row, column, node) {
                                return column ? "\0" + data : data;
                            }
                        },
                    }
                }
            ]
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_produk'},
                {data: 'nama_produk'},
                {data: 'harga_jual'},
                {data: 'jumlah'},
                {data: 'subtotal'},
            ]
        })
    });

    function hideEdit() {
        $('#modal-edit').modal('hide');
    }

    function showDetail(url) {
        $('#modal-detail').modal('show');

        table1.ajax.url(url);
        table1.ajax.reload();
    }

    function deleteData(url) {
        if (confirm('Yakin ingin menghapus data terpilih?')) {
            $.post(url, {
                    '_token': $('[name=csrf-token]').attr('content'),
                    '_method': 'delete'
                })
                .done((response) => {
                    table.ajax.reload();
                })
                .fail((errors) => {
                    alert('Tidak dapat menghapus data');
                    return;
                });
        }
    }

    function editPenjualan(id) {
        let penjualan = {!!$penjualan!!};
        penjualan = penjualan.filter(function(penjualan) {
            return penjualan.id_penjualan == id;
        })[0]
        $("#modal-edit #id_penjualan").val(penjualan.id_penjualan);
        $("#modal-edit #total_item").val(penjualan.total_item);
        $("#modal-edit #total_harga").val(penjualan.total_harga);
        $("#modal-edit #kekurangan").val(penjualan.kekurangan);
        $("#modal-edit").modal('show');
    }
</script>
@endpush