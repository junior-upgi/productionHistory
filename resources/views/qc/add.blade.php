<script src="{{ url('/js/qc/add.js?v=1') }}"></script>
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
                        <label for="snm" class="col-md-3 control-label">圖號</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="snm" value="" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="orderQty" class="col-md-3 control-label">預計生產量</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="orderQty" value="" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="schedate" class="col-md-3 control-label">預計生產日期</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="schedate" name="schedate" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="glassProdLineID" class="col-md-3 control-label">線別</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="glassProdLineID" name="glassProdLineID" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="customer" class="col-md-3 control-label">客戶</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="customer" name="customer" value="">
                        </div>
                    </div>
                    <!--
                    <div class="form-group">
                        <label for="glassProdLineID" class="col-md-3 control-label">線別</label>
                        <div class="col-md-2">
                            <select class="form-control disable" id="glassProdLineID" name="glassProdLineID">
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
                    -->
                    <!--
                    <div class="form-group cus">
                        <label for="searchCustomer" class="col-md-3 control-label">客戶</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="hidden" id="cus_no" name="cus_no" value="">
                                <input type="text" class="form-control" id="searchCustomer">
                                <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                                </ul>
                            </div>
                        </div>
                    </div>
                    -->
                    <div class="form-group">
                        <label for="finishType" class="col-md-3 control-label">口部</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="finishType" name="finishType" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="finishDiaGo" class="col-md-3 control-label">口外徑GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="finishDiaGo" name="finishDiaGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="finishDiaNoGo" class="col-md-3 control-label">口外徑NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="finishDiaNoGo" name="finishDiaNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="finishInnerDiaGo" class="col-md-3 control-label">口內徑GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="finishInnerDiaGo" name="finishInnerDiaGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="finishInnerDiaNoGo" class="col-md-3 control-label">口內徑NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="finishInnerDiaNoGo" name="finishInnerDiaNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="threadDiaGo" class="col-md-3 control-label">螺牙徑GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="threadDiaGo" name="threadDiaGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="threadDiaNoGo" class="col-md-3 control-label">螺牙徑NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="threadDiaNoGo" name="threadDiaNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lipDiaGo" class="col-md-3 control-label">唇徑GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="lipDiaGo" name="lipDiaGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lipDiaNoGo" class="col-md-3 control-label">唇徑NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="lipDiaNoGo" name="lipDiaNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="saftyRingGo" class="col-md-3 control-label">護圈徑GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="saftyRingGo" name="saftyRingGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="saftyRingNoGo" class="col-md-3 control-label">護圈徑NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="saftyRingNoGo" name="saftyRingNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bodyDiaGo" class="col-md-3 control-label">胴徑GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="bodyDiaGo" name="bodyDiaGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="bodyDiaNoGo" class="col-md-3 control-label">胴徑NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="bodyDiaNoGo" name="bodyDiaNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hSettingMax" class="col-md-3 control-label">天肩高MAX</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="hSettingMax" name="hSettingMax" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="hSettingMin" class="col-md-3 control-label">天肩高MIN</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="hSettingMin" name="hSettingMin" value="">
                        </div>
                    </div>
















                    <div class="form-group">
                        <label for="weight" class="col-md-3 control-label">重量</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="weight" name="weight" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="actualWeight" class="col-md-3 control-label">實際生產重量</label>
                        <div class="col-md-4">
                            <input type="text" class="form-control" id="actualWeight" name="actualWeight" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stressLevel" class="col-md-3 control-label">歪力</label>
                        <div class="col-md-2">
                            <input type="text" class="form-control" id="stressLevel" name="stressLevel" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="thermalShock" class="col-md-3 control-label">熱震</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="thermalShock" name="thermalShock" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="speed" class="col-md-3 control-label">機速(支/分)</label>
                        <div class="col-md-2">
                            <input type="number" class="form-control" id="speed" name="speed" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="efficiency" class="col-md-3 control-label">檢瓶率/繳庫率</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="efficiency" name="efficiency" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="defect" class="col-md-3 control-label">缺點</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="defect" id="defect" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" id="btnSave"></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->