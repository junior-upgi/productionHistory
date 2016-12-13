<script src="{{ url('/js/qc/add.js?v=3') }}"></script>
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
                    <div class="form-group">
                        <label for="snm" class="col-md-3 control-label">圖號</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="snm" value="" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="orderQty" class="col-md-3 control-label">預計生產量</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control" readonly id="orderQty" name="orderQty" value="" maxlength="10" required>
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
                        <div class="col-md-9">
                            <input type="text" class="form-control" readonly id="customer" name="customer" value="">
                        </div>
                    </div>
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
                        <label for="heightGo" class="col-md-3 control-label">高度規GO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="heightGo" name="heightGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="heightNoGo" class="col-md-3 control-label">高度規NOGO</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="heightNoGo" name="heightNoGo" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="verticality" class="col-md-3 control-label">垂直度</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="verticality" name="verticality" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="wavyFinish" class="col-md-3 control-label">天波</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="wavyFinish" name="wavyFinish" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="slantedFinish" class="col-md-3 control-label">天傾斜</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="slantedFinish" name="slantedFinish" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="weight" class="col-md-3 control-label">重量</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="weight" name="weight" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="brimCapueity" class="col-md-3 control-label">滿(容)量</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="brimCapueity" name="brimCapueity" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="requirement" class="col-md-3 control-label">客戶要求與注意事項</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="requirement" id="requirement" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="draw" class="col-md-3 control-label">圖示</label>
                        <div class="col-md-9">
                            <input type="file" id="draw" name="draw" class="form-control file-loading" data-show-upload="false" data-show-preview="false" accept="image/*">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="qualityLevel" class="col-md-3 control-label">級別</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="qualityLevel" name="qualityLevel" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class="col-md-3 control-label">國別</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="country" name="country" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="decoration" class="col-md-3 control-label">加工別</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" readonly id="decoration" name="decoration" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="note" class="col-md-3 control-label">附註</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" readonly id="note" name="note" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="recentProdDefectList" class="col-md-3 control-label">上次生產缺點</label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="recentProdDefectList" id="recentProdDefectList" rows="5" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="packRate" class="col-md-3 control-label">上次檢瓶率/繳庫率</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="packRate" name="packRate" value="">
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