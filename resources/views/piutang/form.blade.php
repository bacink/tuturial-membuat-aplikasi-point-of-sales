<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form">
    <div class="modal-dialog modal-lg" role="document">
        <form action="" method="POST" class="form-horizontal" id="formPiutang">
            @csrf
            @method('POST')
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="piutang" class="col-lg-2 col-lg-offset-1 control-label">Piutang</label>
                        <div class="col-lg-6">
                            <input type="text" name="piutang" id="piutang" class="form-control" required readonly>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
             
                    <div class="form-group row">
                        <label for="bayar" class="col-lg-2 col-lg-offset-1 control-label">Bayar</label>
                        <div class="col-lg-6">
                            <input type="text" name="bayar" id="bayar" class="form-control price" required autofocus>
                            <span class="help-block with-errors"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="sisa" class="col-lg-2 col-lg-offset-1 control-label">Sisa</label>
                        <div class="col-lg-6">
                            <input type="text" name="sisa" id="sisa" class="form-control price" required>
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

