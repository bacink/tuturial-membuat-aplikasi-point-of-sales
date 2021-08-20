<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="post" class="form-horizontal" id="formHargaMember">
            @csrf
            @method('post')

            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="id_member" class="col-lg-2 col-lg-offset-1 control-label">Pelanggan</label>
                        <div class="col-lg-6">
                            <select name="id_member" id="id_member" class="form-control" required>
                                <option value="">Pilih Pelanggan</option>
                                @foreach ($member as $key => $item)
                                <option value="{{ $key }}">{{ $item }}</option>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="id_produk" class="col-lg-2 col-lg-offset-1 control-label">Produk</label>
                        <div class="col-lg-6">
                            <select name="id_produk" id="id_produk" class="form-control" required>
                                <option value="">Pilih Produk</option>
                                @foreach ($kategori as $item)
                                <optgroup label="{{ $item->nama_kategori }}">
                                    @foreach ($produk as $r)
                                        @if ($item->id_kategori == $r->id_kategori)
                                            <option value="{{$r->id_produk}}" data-harga-beli="{{$r->harga_beli}}" data-harga-jual="{{$r->harga_jual}}">{{$r->nama_produk}}</option>
                                        @endif 
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_beli" class="col-lg-2 col-lg-offset-1 control-label">Harga Beli</label>
                        <div class="col-lg-6">
                            <input type="text" name="harga_beli" id="harga_beli" class="form-control price" readonly required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_jual" class="col-lg-2 col-lg-offset-1 control-label">Harga Jual</label>
                        <div class="col-lg-6">
                            <input type="text" name="harga_jual" id="harga_jual" class="form-control price" readonly required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="harga_member" class="col-lg-2 col-lg-offset-1 control-label">Harga Member</label>
                        <div class="col-lg-6">
                            <input type="text" name="harga_member" id="harga_member" class="form-control price" required>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-flat btn-primary"><i class="fa fa-save"></i> Simpan</button>
                    <button type="button" class="btn btn-sm btn-flat btn-warning" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Batal</button>
                </div>
            </div>
        </form>
    </div>
</div>