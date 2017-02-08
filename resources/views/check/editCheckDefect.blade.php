<div class="modal fade" id="editProductionDefectModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">新增缺點記錄</h4>
            </div>
            <form class="form-horizontal" id="addCheckDefectForm" role="form">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" value="">
                        <input type="hidden" name="checkID" :value="checkID">
                        <input type="hidden" name="classRemark" :value="editData.classRemark">
                        <input type="hidden" name="spotCheck" value="0">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="prodDate" class="control-label col-md-2">生產日期</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control input-sm date"
                                           name="prodDate" :value="getDate(editData.prodDate)">
                                </div>
                            </div>
                            <div class="form-group form-group-sm">
                                <label for="classType" class="control-label col-md-2">班別</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="classType" v-model="editData.classType">
                                        <option v-for="c in classTypeOption" :value="c.value">@{{ c.text }}</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group col-md-12" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" href="#edit_productionParams"
                                           aria-expanded="true" aria-controls="productionInfo">
                                            <span>生產資訊</span>
                                        </a>
                                    </h4>
                                </div>
                                <div class="panel-collapse collapse in" role="tabpanel" id="edit_productionParams">
                                    <div class="panel-body">
                                        <table class="table table-condensed table-bordered" style="margin: 0px;">
                                            <tr>
                                                <td width="160">實際生產數量</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="actualQuantity" :value="editData.actualQuantity"></td>
                                            </tr>
                                            <tr>
                                                <td>機速</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="speed" :value="editData.speed"></td>
                                            </tr>
                                            <tr>
                                                <td>分鐘</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="minute" :value="editData.minute"></td>
                                            </tr>
                                            <tr>
                                                <td>檢瓶率</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="checkRate" :value="editData.checkRate"></td>
                                            </tr>
                                            <tr>
                                                <td>實際生產重量最小值</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="actualMinWeight" :value="editData.actualMinWeight"></td>
                                            </tr>
                                            <tr>
                                                <td>實際生產重量最大值</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="actualMaxWeight" :value="editData.actualMaxWeight"></td>
                                            </tr>
                                            <tr>
                                                <td>歪力</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="stressLevel" :value="editData.stressLevel"></td>
                                            </tr>
                                            <tr>
                                                <td>熱震</td>
                                                <td><input type="text" class="form-control input-sm"
                                                           name="thermalShock" :value="editData.thermalShock"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default" v-for="item in items">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" v-bind:href="'#p_edit_' + item.itemID"
                                           aria-expanded="true" v-bind:aria-controls="item.itemID">
                                            <span>@{{ item.itemName }}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div v-bind:id="'p_edit_' + item.itemID" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <table class="table table-condensed table-bordered" style="margin: 0px;">
                                            <tr v-for="defect in defects" v-if="item.itemID == defect.itemID">
                                                <td>@{{ defect.defectName }}</td>
                                                <td><input type="number" class="form-control input-sm"
                                                           v-bind:name="defect.itemID + defect.defectID"
                                                           :value="editDefect[defect.defectID]"></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..."
                            autocomplete="off" id="btnSave">儲存</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->