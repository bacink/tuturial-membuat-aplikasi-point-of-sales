@extends('layouts.master')

@section('title')
Detail Transaksi
@endsection
@section('breadcrumb')
@parent
<li class="active">Detail Transaksi</li>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-6 col-lg-4 col-md-4">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Member</h3>
            </div>
            <div class="panel-body">

                <div class="list-group">
                    <a href="#" class="list-group-item">
                        <h4 class="list-group-item-heading">Nama</h4>
                        <p class="list-group-item-text">{{$member->nama}}</p>
                        <p class="list-group-item-text">{{$member->telepon}}</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-lg-12 col-xs-12 col-sm-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Detail</h3>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-stiped table-bordered table-pembelian">
                        <thead>
                            <th width="25%">Kode</th>
                            <th>Nama</th>
                            <th>Harga</th>
                            <th width="15%">QTY</th>
                            <th>Subtotal</th>
                            <th width="15%">
                                <center>
                                    <i class="fa fa-cog"></i>
                                </center>
                            </th>
                        </thead>
                        @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                        @endif
                        <tbody>
                            <form action="{{ route('new.transaksi.transaksi.store') }}" class="form-penjualan" method="post">
                                @csrf
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="hidden" name="id_member" id="id_member" value="{{$member->id_member}}">
                                            <input type="hidden" name="id_produk" id="id_produk">
                                            <input type="hidden" name="diskon" id="diskon" value="0">
                                            <input type="hidden" name="id_penjualan" id="id_penjualan" value="{{$penjualan->id_penjualan}}">
                                            <input type="text" class="form-control" name="kode_produk" id="kode_produk" readonly>
                                            <span class="input-group-btn">
                                                <button onclick="tampilProduk()" class="btn btn-info btn-flat" type="button"><i class="fa fa-search"></i></button>
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" name="nama_produk" id="nama_produk" class="form-control" readonly required>
                                    </td>
                                    <td>
                                        <input type="text" name="harga_jual" id="harga_jual" class="form-control price" required>
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                                    </td>
                                    <td>
                                        <input type="text" name="subtotal" id="subtotal" class="form-control price" readonly required>
                                    </td>
                                    <td>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-shopping-basket"></i> Keranjang</button>
                                    </td>
                                </tr>
                            </form>
                        </tbody>

                    </table>
                    <div class="table-responsive">
                        <table class="table table-stiped table-bordered">
                            <thead>
                                <th>No.</th>
                                <th width="25%">Kode</th>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th width="15%">QTY</th>
                                <th>Subtotal</th>
                                <th width="15%">
                                    <center>
                                        <i class="fa fa-cog"></i>
                                    </center>
                                </th>
                            </thead>
                            <tbody id="table_cart">

                            </tbody>
                        </table>
                    </div>
                    @if ($errors->any())
                    @foreach ($errors->all() as $error)

                    <div class="alert alert-danger" role="alert">
                        {{ $error }}
                    </div>
                    @endforeach

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xs-12 col-sm-12 col-md-offset-6">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">Bayar</h3>
            </div>
            <div class="panel-body">
                <form method="post" action="{{ route('new.penjualan.penjualan.store') }}">
                    <input type="hidden" name="id_penjualan" value="{{$penjualan->id_penjualan}}">
                    @csrf
                    <div class="form-group">
                        <label for="total_tagihan">Total Tagihan</label>
                        <input type="text" class="form-control price" id="total_tagihan" name="total_tagihan">
                    </div>
                    <div class="form-group">
                        <label for="total_bayar">Total Bayar</label>
                        <input type="text" class="form-control price" id="total_bayar" name="total_bayar">
                    </div>
                    <div class="form-group">
                        <label for="sisa_bayar">Sisa Tagihan</label>
                        <input type="text" class="form-control price" id="sisa_bayar" name="sisa_bayar">
                    </div>

                    <button type="submit" class="btn btn-primary pull-right">Bayar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@includeIf('penjualan_detail.produk')

@endsection
@push('scripts')
<script>
    $(function() {
        showTransaksi('{{$penjualan->id_penjualan}}')
    });

    function tampilProduk() {
        $('#modal-produk').modal('show');
    }

    function hideProduk() {
        $('#modal-produk').modal('hide');
    }

    function pilihProduk(id, kode) {
        $('#id_produk').val(id);
        $('#kode_produk').val(kode);
        hideProduk();
        searchProduk('{{$member->id_member}}', id);
    }

    function searchProduk(member_id, produk_id) {
        url = '/produk/' + produk_id + '/member/' + member_id;
        $.ajax({
            type: "get",
            url: url,
            data: {},
            dataType: "json",
            success: function(response) {

                if (response.produk) {
                    produk = response.produk
                    harga_jual = response.harga_jual
                } else {
                    produk = response
                    harga_jual = produk.harga_jual
                }

                $("#nama_produk").val(produk.nama_produk)
                $("#harga_jual").val(harga_jual).focus()


            }
        });
    }

    $(document).on('input', '#jumlah,#harga_jual', function() {
        harga = $("#harga_jual").unmask();
        qty = $("#jumlah").val()
        $("#subtotal").val(hitung(qty, harga))
    })

    $(document).on('input', '#total_tagihan,#total_bayar', function() {
        total_tagihan = $("#total_tagihan").unmask();
        total_bayar = $("#total_bayar").unmask()
        $("#sisa_bayar").val(bayar(total_tagihan, total_bayar))
    })

    function hitung(qty, harga) {
        subtotal = parseInt(qty) * parseInt(harga)
        return subtotal
    }

    function bayar(total_tagihan, total_bayar) {
        sisa_bayar = parseInt(total_tagihan) - parseInt(total_bayar)
        return sisa_bayar
    }

    function showTransaksi(id) {
        url = '/new/transaksi/' + id;
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(data) {
                total = 0;
                $.each(data.data, function(indexInArray, row) {

                    no = indexInArray + 1

                    url = '/new/transaksi/delete/' + row.id;
                    $("#table_cart").append('<tr>' +
                        '<td>' + no + '</td>' +
                        '<td>' + row.kode_produk + '</td>' +
                        '<td>' + row.nama_produk + '</td>' +
                        '<td class="price">' + row.harga_jual_rp + '</td>' +
                        '<td>' + row.jumlah + '</td>' +
                        '<td class="price">' + row.subtotal_rp + '</td>' +
                        '<td><a href="' + url + '" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a></td>' +
                        '</tr>')
                    total += parseInt(row.subtotal)
                });
                $("#total_tagihan").val(total)
                $("#sisa_bayar").val(total)
            }
        });
    }
</script>
@endpush