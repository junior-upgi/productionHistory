<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="modalTitle">@{{ formSet.title }}</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="type" name="type" v-model="dataSet.type">
                    <input type="hidden" name="id" v-model="dataSet.id">
                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">缺點項目名稱</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control" name="name" v-model="dataSet.name" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                <button type="button" class="btn btn-primary" data-loading-text="資料送出中..." 
                    autocomplete="off" id="btnSave" @click="save(dataSet)">
                    @{{ formSet.btn }}
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->