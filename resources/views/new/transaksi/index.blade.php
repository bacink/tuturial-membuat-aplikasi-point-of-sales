@extends('layouts.master')

@section('title')
Transaksi Hari Ini
@endsection

@section('breadcrumb')
@parent
<li class="active">Data Transaksi Hari Ini</li>
@endsection

@section('content')
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            @include('penjualan.kasir')
        </div>
    </div>
</div>
<!-- /.row (main row) -->

@endsection