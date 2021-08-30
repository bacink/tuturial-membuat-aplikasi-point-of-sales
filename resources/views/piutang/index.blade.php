@extends('layouts.master')

@section('title')
Daftar Piutang
@endsection

@section('breadcrumb')
@parent
<li class="active">Data Piutang</li>
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
                        <th>Total Tagihan</th>
                        <th>Total Bayar</th>
                        <th>Piutang</th>
                        <th width="5%">
                            <center><i class="fa fa-cog"></i></center>
                        </th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

@includeIf('piutang.detail')
@includeIf('piutang.form')
@endsection

@push('scripts')
<script>
    let table, table1;

    $(function() {
        table = $('.table-penjualan').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('piutang.data') }}',
            },
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'tanggal'
                },
                {
                    data: 'total_tagihan'
                },
                {
                    data: 'total_bayar'
                },
                {
                    data: 'piutang'
                },
                {
                    data: 'aksi',
                    searchable: false,
                    sortable: false,
                    class: "text-center"
                },
            ]
        });

        table1 = $('.table-detail').DataTable({
            processing: true,
            bSort: false,
            dom: 'Brt',
            columns: [{
                    data: 'DT_RowIndex',
                    searchable: false,
                    sortable: false
                },
                {
                    data: 'tanggal_bayar'
                },
                {
                    data: 'piutang'
                },
                {
                    data: 'bayar'
                },
                {
                    data: 'sisa'
                },
            ]
        })
    });

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

    function showBayar(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Form Pembayaran');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=bayar]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=piutang]').val(response.sisa);
                $('#modal-form [name=bayar]').val(response.sisa);
                $('#modal-form [name=sisa]').val(0);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
    }

    $(document).on('input', '#modal-form [name=bayar]', function() {
        piutang = $('#modal-form [name=piutang]').unmask()
        bayar = $('#modal-form [name=bayar]').unmask()
        sisa = parseInt(piutang) - parseInt(bayar)
        $('#modal-form [name=sisa]').val(sisa)
    })
</script>
@endpush