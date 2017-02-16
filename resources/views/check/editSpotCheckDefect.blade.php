<div class="modal fade" id="editSpotCheckDefectModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title">編輯缺點記錄</h4>
            </div>
            <form class="form-horizontal" id="editSpotCheckDefectForm" role="form">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" :value="editData.id">
                        <input type="hidden" name="checkID" :value="checkID">
                        <input type="hidden" name="classRemark" :value="editData.classRemark">
                        <input type="hidden" name="spotCheck" value="1">
                        <input type="hidden" name="classType" value="4">
                        <div class="col-md-12">
                            <div class="form-group form-group-sm">
                                <label for="prodDate" class="control-label col-md-2">生產日期</label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control input-sm date"
                                           name="prodDate" :value="getDate(editData.prodDate)">
                                </div>
                            </div>
                            <!--
                            <div class="form-group form-group-sm">
                                <label for="classType" class="control-label col-md-2">班別</label>
                                <div class="col-md-3">
                                    <select class="form-control" name="classType" v-model="editData.classType">
                                        <option v-for="c in classTypeOption" :value="c.value">@{{ c.text }}</option>
                                    </select>
                                </div>
                            </div>
                            -->
                        </div>
                        <div class="panel-group col-md-12" role="tablist" aria-multiselectable="true">
                            <div class="panel panel-default" v-for="item in spotCheckItems"
                                 v-if="spotCheckItemsCount[item.itemID] != 0">
                                <div class="panel-heading" role="tab">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" v-bind:href="'#s_edit_' + item.itemID"
                                           aria-expanded="true" v-bind:aria-controls="item.itemID">
                                            <span>@{{ item.itemName }}</span>
                                        </a>
                                    </h4>
                                </div>
                                <div v-bind:id="'s_edit_' + item.itemID" class="panel-collapse collapse" role="tabpanel">
                                    <div class="panel-body">
                                        <table class="table table-condensed table-bordered" style="margin: 0px;">
                                            <tr v-for="defect in spotCheckDefects" v-if="item.itemID == defect.itemID">
                                                <td>@{{ defect.defectName }}</td>
                                                <td><input type="number" class="form-control input-sm"
                                                           v-bind:name="defect.itemID + defect.defectID"
                                                           :value="editDefect[defect.itemID + defect.defectID]"></td>
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