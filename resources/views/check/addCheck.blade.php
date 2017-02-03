@extends('layouts.masterpage')
@section('title', '新增檢查表')
@section('content')
<div id="addCheck">
    <div class="row">
        <div class="col-md-12">
            <h3>新增檢查表</h3>
            <form class="form" action="" id="addCheckForm">
                <div class="row">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" v-bind:value="schedule.id">
                    <input type="hidden" name="schedate" v-bind:value="schedule.schedate">
                    <input type="hidden" name="prd_no" v-bind:value="schedule.prd_no">
                    <div class="col-md-1">
                        <div class="form-group form-group-sm">
                            <label for="schedate" class="control-label">開始生產日期</label>
                            <input type="text" class="form-control" readonly v-bind:value="schedule.schedate">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group form-group-sm">
                            <label for="snm" class="control-label">圖號</label>
                            <input type="text" class="form-control" readonly v-bind:value="schedule.snm">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-sm">
                            <label for="customer" class="control-label">客戶</label>
                            <input type="text" class="form-control" v-bind:value="customer" name="customer">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group form-group-sm">
                            <label for="glassProdLineID" class="control-label">線別</label>
                            <input type="text" class="form-control" name="glassProdLineID" readonly v-bind:value="schedule.glassProdLineID">
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <div class="form-group form-group-sm">
                            <label for="decoration" class="control-label">加工別</label>
                            <div>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration[]" value="白瓶">白瓶
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration[]" value="氟化">氟化
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration[]" value="印刷">印刷
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration[]" value="噴漆">噴漆
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration[]" value="噴砂">噴砂
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration[]" value="燙金">燙金
                                </label>
                                <label class="checkbox-inline">
                                    <input type="checkbox" name="decoration]" value="水轉印">水轉印
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="templateID" class="control-label">套板名稱</label>
                            <select name="templateID" class="form-control">
                                <option v-for="option in template" v-bind:value="option.id">@{{ option.name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="" class="control-label">其它</label>
                            <textarea class="form-control input-sm" name="other" rows="4" style="resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="form-group">
                            <label for="" class="control-label">備註</label>
                            <textarea class="form-control input-sm" name="remark" rows="4" style="resize: none;"></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="formingMethod" class="control-label">吹製方法</label>
                            <input type="text" class="form-control" name="formingMethod" value="">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group form-group-sm">
                            <label for="gauge" class="control-label">量規</label>
                            <input type="text" class="form-control" name="gauge" value="">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group form-group-sm">
                            <label for="weight" class="control-label">重量</label>
                            <input type="text" class="form-control" name="weight" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="speed" class="control-label">機速</label>
                            <input type="text" class="form-control" name="speed" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="checkRate" class="control-label">檢瓶率</label>
                            <input type="text" class="form-control" name="checkRate" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="payRate" class="control-label">繳率率</label>
                            <input type="text" class="form-control" name="payRate" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="thermalShock" class="control-label">熱震</label>
                            <input type="text" class="form-control" name="thermalShock" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="stressLevel" class="control-label">歪力</label>
                            <input type="text" class="form-control" name="stressLevel" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="selectTime" class="control-label">選瓶時間</label>
                            <input type="text" class="form-control time" name="selectTime" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="offlineTime" class="control-label">下線時間</label>
                            <input type="text" class="form-control time" name="offlineTime" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="actualWeightMin" class="control-label">實際生產重量最小值</label>
                            <input type="text" class="form-control" name="actualWeightMin" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="actualWeightMax" class="control-label">實際生產重量最大值</label>
                            <input type="text" class="form-control" name="actualWeightMax" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="predictQuantity" class="control-label">預計生產數量</label>
                            <input type="text" class="form-control" name="predictQuantity" v-bind:value="schedule.orderQty">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="actualQuantity" class="control-label">實際生產數量</label>
                            <input type="text" class="form-control" name="actualQuantity" value="">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group form-group-sm">
                            <label for="payQuantity" class="control-label">實際繳庫數量</label>
                            <input type="text" class="form-control" name="payQuantity" value="">
                        </div>
                    </div>
                </div>
                <div>
                    <button type="button" class="btn btn-default">取消</button>
                    <button type="submit" class="btn btn-primary" id="BtnSave">儲存</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ url('/js/check/addCheck.js?v=9') }}"></script>
@endsection