<script src="{{ url('/js/duty/add.js?v=2') }}"></script>
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <form id="addForm" class="form-horizontal" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="type" name="type" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="prd_no" name="prd_no" value="">
                    <input type="hidden" id="schedate" name="schedate" value="">
                    <div class="form-group">
                        <label for="dutyDate" class="col-md-3 control-label">值班日期</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control date form_datetime" readonly id="dutyDate" name="dutyDate" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="shift" class="col-md-3 control-label">班別</label>
                        <div class="col-md-3">
                            <select class="form-control" name="shift" id="shift">
                                <option value="1">早班</option>
                                <option value="2">中班</option>
                                <option value="3">晚班</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="searchStaff" class="col-md-3 control-label">機台人員</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="hidden" id="staffID" name="staffID" value="">
                                <input type="text" class="form-control" id="searchStaff" required>
                                <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="glassProdLineID" class="col-md-3 control-label">線別</label>
                        <div class="col-md-2">
                            <select class="form-control" id="glassProdLineID" name="glassProdLineID" disabled="disabled">
                                <option value="L1-1">L1-1</option>
                                <option value="L1">L1</option>
                                <option value="L2">L2</option>
                                <option value="L3">L3</option>
                                <option value="L5">L5</option>
                                <option value="L6">L6</option>
                                <option value="L7">L7</option>
                                <option value="L8">L8</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="snm" class="col-md-3 control-label">產品名稱</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="snm" value="" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="quantity" class="col-md-3 control-label">生產支數(支/件)</label>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="quantity" id="quantity" value="" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="piece" class="col-md-3 control-label">生產件數</label>
                        <div class="col-md-3">
                            <input type="number" class="form-control" name="pack" id="pack" value="" min="0" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="efficiency" class="col-md-3 control-label">效率</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="efficiency" id="efficiency" value="" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="anneal" class="col-md-3 control-label">退火等級</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="annealGrade" id="annealGrade" value="" maxlength="10" required>
                        </div>
                    </div>
                    @php
                        $date = Carbon\Carbon::now();
                        $date = date('Y-m-d', strtotime($date));
                    @endphp
                    <div class="form-group">
                        <label for="startShutdown" class="col-md-3 control-label">停機時間(起)</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control time form_datetime" id="startShutdown" name="startShutdown">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endShutdown" class="col-md-3 control-label">停機時間(迄)</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control time form_datetime" id="endShutdown"  name="endShutdown">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="jobChange" class="col-md-3 control-label">換模記錄</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="jobChange" id="jobChange" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="speedChange" class="col-md-3 control-label">升降機速</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="speedChange" id="speedChange" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="improve" class="col-md-3 control-label">缺點改進</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="improve" id="improve" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="button" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" id="btnSave" onclick="save()"></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->