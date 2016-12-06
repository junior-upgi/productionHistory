@inject('form', 'App\Presenters\FormPresenter')
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
                    <input type="hidden" id="mk_no" name="mk_no" value="">
                    <div class="form-group">
                        <label for="dutyDate" class="col-md-3 control-label">值班日期</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control date form_datetime" readonly id="dutyDate" name="dutyDate" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="class" class="col-md-3 control-label">班別</label>
                        <div class="col-md-3">
                            <select class="form-control" name="class" id="class">
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
                        <label for="machno" class="col-md-3 control-label">線別</label>
                        <div class="col-md-2">
                            {{ $machno = '' }}
                            <select class="form-control" name="machno" id="machno">
                                <option value="1-1" {{ $machno == '1-1' ? 'selected': '' }}>1-1</option>
                                <option value="01" {{ $machno == '01' ? 'selected': '' }}>01</option>
                                <option value="02" {{ $machno == '02' ? 'selected': '' }}>02</option>
                                <option value="03" {{ $machno == '03' ? 'selected': '' }}>03</option>
                                <option value="05" {{ $machno == '05' ? 'selected': '' }}>05</option>
                                <option value="06" {{ $machno == '06' ? 'selected': '' }}>06</option>
                                <option value="07" {{ $machno == '07' ? 'selected': '' }}>07</option>
                                <option value="08" {{ $machno == '08' ? 'selected': '' }}>08</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="snm" class="col-md-3 control-label">產品名稱</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="snm" id="snm" value="" maxlength="10" required>
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
                            <input type="number" class="form-control" name="piece" id="piece" value="" min="0" required>
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
                            <input type="text" class="form-control" name="anneal" id="anneal" value="" maxlength="10" required>
                        </div>
                    </div>
                    @php
                        $date = Carbon\Carbon::now();
                        $date = date('Y-m-d', strtotime($date));
                    @endphp
                    <div class="form-group">
                        <label for="startShutdown" class="col-md-3 control-label">停機時間(起)</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control time form_datetime" readonly id="startShutdown" name="startShutdown">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="endShutdown" class="col-md-3 control-label">停機時間(迄)</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control time form_datetime" readonly id="endShutdown"  name="endShutdown">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="changeModel" class="col-md-3 control-label">換模記錄</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="changeModel" id="changeModel" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="changeSpeed" class="col-md-3 control-label">升降機速</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="changeSpeed" id="changeSpeed" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="improve" class="col-md-3 control-label">缺點改進</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="improve" id="improve" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="suggest" class="col-md-3 control-label">建議事項</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="suggest" id="suggest" rows="5" style="resize: none;"></textarea>
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