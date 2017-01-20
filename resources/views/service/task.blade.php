<script src="{{ url('services') }}"></script>
<div class="modal fade" id="taskModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalTitle">生產備註記錄</h4>
            </div>
            <form id="taskForm" class="form-horizontal" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="type" name="type" value="add">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" name="SAL_NO" value="{{ Auth::user()->erpID }}">
                    <div class="form-group">
                        <label for="name" class="col-md-3 control-label">記錄人員</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="name" value="{{ Auth::user()->staff()->first()->name }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deadline" class="col-md-3 control-label">日期</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control date form_datetime" readonly id="deadline" name="deadline" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="taskProduct" class="col-md-3 control-label">產品名稱</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="hidden" id="PRD_NO" name="PRD_NO" value="">
                                <input type="text" class="form-control" id="taskProduct">
                                <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-md-3 control-label">記錄事項</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="description" id="description" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" id="btnSave">新增</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->