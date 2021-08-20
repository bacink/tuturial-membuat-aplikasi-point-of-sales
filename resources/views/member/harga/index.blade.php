@extends('layouts.master')

@section('title')
    Daftar Harga Spesial
@endsection

@section('breadcrumb')
    @parent
    <li class="active">Harga Spesial</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="addForm('{{ route('harga-member.store') }}')" class="btn btn-success btn-xs btn-flat"><i class="fa fa-plus-circle"></i> Tambah Pelanggan Spesial</button>
            </div>
            <div class="box-body table-responsive">
                <form action="" method="post" class="form-harga-member">
                    @csrf
                    <table class="table table-stiped table-bordered">
                        <thead>
                            <!-- <th width="5%">
                                <input type="checkbox" name="select_all" id="select_all">
                            </th> -->
                            <th width="5%">No</th>
                            <th>Dibuat</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Produk</th>
                            <th>Harga Beli</th>
                            <th>Harga Jual</th>
                            <th>Harga Member</th>
                            <th width="15%"><i class="fa fa-cog"></i></th>
                        </thead>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('member.harga.form')
@endsection

@push('scripts')
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            processing: true,
            autoWidth: false,
            ajax: {
                url: '{{ route('harga-member.data') }}',
            },
            columns: [
                // {data: 'select_all', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                {data: 'kode_member'},
                {data: 'nama'},
                {data: 'produk'},
                {data: 'harga_beli'},
                {data: 'harga_jual'},
                {data: 'harga_member'},
                {data: 'aksi', searchable: false, sortable: false},
            ]
        });

        $('#modal-form').validator().on('submit', function (e) {
            if (! e.preventDefault()) {
                $.post($('#modal-form form').attr('action'), $('#modal-form form').serialize())
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('Tidak dapat menyimpan data');
                        return;
                    });
            }
        });

        $('[name=select_all]').on('click', function () {
            $(':checkbox').prop('checked', this.checked);
        });

        $("#id_produk").on('change',function(){

            harga_beli = $(this).find(':selected').data('harga-beli');
            harga_jual = $(this).find(':selected').data('harga-jual');

            $("#harga_beli").val(harga_beli)
            $("#harga_jual").val(harga_jual)
        });

        $('#formHargaMember').on('submit',function(event) {

        event.preventDefault(); //this will prevent the default submit

        // your code here (But not asynchronous code such as Ajax because it does not wait for a response and move to the next line.)
            harga_member = $("#harga_member").unmask();
            harga_beli = $("#harga_beli").unmask();
            console.log(harga_member)
            
            if(parseInt(harga_member) <= parseInt(harga_beli)){
                alert('Harga Member harus lebih besar dari harga beli')
            }

        })
    });

    function addForm(url) {
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Tambah harga-Member');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('post');
        $('#modal-form [name=nama]').focus();
    }

    function editForm(url) {
  
        $('#modal-form').modal('show');
        $('#modal-form .modal-title').text('Edit harga-Member');

        $('#modal-form form')[0].reset();
        $('#modal-form form').attr('action', url);
        $('#modal-form [name=_method]').val('put');
        $('#modal-form [name=nama]').focus();

        $.get(url)
            .done((response) => {
                $('#modal-form [name=id_member]').val(response.id_member);
                $('#modal-form [name=id_produk]').val(response.id_produk);
                $('#modal-form [name=harga_beli]').val(response.produk.harga_beli);
                $('#modal-form [name=harga_jual]').val(response.produk.harga_jual);
                $('#modal-form [name=harga_member]').val(response.harga_member);
            })
            .fail((errors) => {
                alert('Tidak dapat menampilkan data');
                return;
            });
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

</script>
@endpush