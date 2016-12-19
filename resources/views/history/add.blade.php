<script src="{{ url('/js/history/add.js?v=7') }}"></script>
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <form id="addForm" class="form-horizontal">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="type" name="type" value="">
                    <input type="hidden" id="id" name="id" value="">
                    <input type="hidden" id="sampling" name="sampling" value="">
                    <!--<input type="hidden" id="prd_no" name="prd_no" value="">-->
                    <!--<input type="hidden" id="schedate" name="schedate" value="">-->
                    <div class="form-group">
                        <label for="productionDate" class="col-md-3 control-label">填表日期</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control date form_datetime" readonly id="productionDate" name="productionDate" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="schedate" class="col-md-3 control-label">生產日期</label>
                        <div class="col-md-3">
                            <input type="text" class="form-control date form_datetime" id="schedate" name="schedate" value="" required>
                        </div>
                    </div>
                    <div class="form-group allscheqty">
                        <label for="allscheqty" class="col-md-3 control-label">生產數量</label>
                        <div class="col-md-5">
                            <input type="number" class="form-control" id="allscheqty" name="allscheqty" value="" maxlength="10">
                        </div>
                    </div>
                    <div class="form-group prd">
                        <label for="searchProd" class="col-md-3 control-label">產品名稱</label>
                        <div class="col-md-9">
                            <div class="input-group">
                                <input type="hidden" id="prd_no" name="prd_no" value="">
                                <input type="text" class="form-control" id="searchProd">
                                <ul class="dropdown-menu dropdown-menu-right" role="menu" style="height: 350px;">
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="glassProdLineID" class="col-md-3 control-label">線別</label>
                        <div class="col-md-2">
                            <select class="form-control" id="glassProdLineID" name="glassProdLineID">
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
                    <div class="form-group">
                        <label for="gauge" class="col-md-3 control-label">量規</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="gauge" name="gauge" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="formingMethod" class="col-md-3 control-label">吹製方法</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="formingMethod" name="formingMethod" value="">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="other" class="col-md-3 control-label">其它</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="other" name="other" value="">
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