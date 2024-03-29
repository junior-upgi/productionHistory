@extends('layouts.master_page')
@section('title', '編輯檢查表')
@section('content')
    <div id="editCheck">
        <div class="row">
            <div class="col-md-12">
                <h3>編輯檢查表</h3>
                <div>
                    <a class="btn btn-sm btn-default" href="{{ url('/nav/check.list') }}">
                        返回檢查表清單
                    </a>
                    <button class="btn btn-sm btn-primary" id="showBtn" data-toggle="collapse" data-target="#editCheckForm" aria-expanded="false" aria-controls="editCheckForm">
                        顯示檢查表資料
                    </button>
                    <h5 class="row">
                        <span class="col-md-2 info">開始生產日期：@{{ checkData.schedate }}</span>
                        <span class="col-md-1 info">瓶號：@{{ checkData.snm }}</span>
                        <span class="col-md-1 info">線別：@{{ checkData.glassProdLineID }}</span>
                    </h5>
                </div>
                <form class="form collapse" action="" id="editCheckForm">
                    <div class="row">
                        <div class="col-md-12">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" v-bind:value="checkData.id">
                            <input type="hidden" name="schedate" v-bind:value="checkData.schedate">
                            <input type="hidden" name="prd_no" v-bind:value="checkData.prd_no">
                            <div class="row">
                                <div class="col-md-1">
                                    <div class="form-group form-group-sm">
                                        <label for="schedate" class="control-label">開始生產日期</label>
                                        <input type="text" class="form-control" readonly v-bind:value="checkData.schedate">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group form-group-sm">
                                        <label for="snm" class="control-label">圖號</label>
                                        <input type="text" class="form-control" readonly v-bind:value="checkData.snm">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group form-group-sm">
                                        <label for="customer" class="control-label">客戶</label>
                                        <input type="text" class="form-control" v-bind:value="checkData.customer">
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group form-group-sm">
                                        <label for="glassProdLineID" class="control-label">線別</label>
                                        <input type="text" class="form-control" name="glassProdLineID" readonly
                                            v-bind:value="checkData.glassProdLineID">
                                    </div>
                                </div>
                                <div class="col-md-5 col-xs-12">
                                    <div class="form-group form-group-sm">
                                        <label for="decoration" class="control-label">加工別</label>
                                        <div>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration[]" value="白瓶" v-model="decoration">白瓶
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration[]" value="氟化" v-model="decoration">氟化
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration[]" value="印刷" v-model="decoration">印刷
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration[]" value="噴漆" v-model="decoration">噴漆
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration[]" value="噴砂" v-model="decoration">噴砂
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration[]" value="燙金" v-model="decoration">燙金
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="decoration]" value="水轉印" v-model="decoration">水轉印
                                            </label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group form-group-sm">
                                        <label for="templateID" class="control-label">生產缺點套板</label>
                                        <select name="templateID" class="form-control" v-model="templateID">
                                            <option value="">請選擇套板</option>
                                            <option v-for="option in template" v-bind:value="option.id">
                                                @{{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group form-group-sm">
                                        <label for="spotCheckTemplateID" class="control-label">抽驗缺點套板</label>
                                        <select name="spotCheckTemplateID" class="form-control" v-model="spotCheckTemplateID">
                                            <option value="">請選擇套板</option>
                                            <option v-for="option in template" v-bind:value="option.id">
                                                @{{ option.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="control-label">其它</label>
                                        <textarea class="form-control input-sm" name="other" rows="5" style="resize: none;"
                                            v-bind:value="checkData.other" ></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="" class="control-label">備註</label>
                                        <textarea class="form-control input-sm" name="remark" rows="5" style="resize: none;"
                                            v-bind:value="checkData.remark"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group form-group-sm">
                                <label for="formingMethod" class="control-label">吹製方法</label>
                                <input type="text" class="form-control" name="formingMethod"
                                    v-bind:value="checkData.formingMethod">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group form-group-sm">
                                <label for="gauge" class="control-label">量規</label>
                                <input type="text" class="form-control" name="gauge"
                                    v-bind:value="checkData.gauge">
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="form-group form-group-sm">
                                <label for="weight" class="control-label">重量</label>
                                <input type="text" class="form-control" name="weight"
                                    v-bind:value="checkData.weight">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-group-sm">
                                <label for="payRate" class="control-label">繳庫率</label>
                                <input type="text" class="form-control" name="payRate"
                                    v-bind:value="checkData.payRate">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-group-sm">
                                <label for="selectTime" class="control-label">選瓶時間</label>
                                <input type="text" class="form-control time" name="selectTime"
                                    v-bind:value="checkData.selectTime">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-group-sm">
                                <label for="offlineTime" class="control-label">下線時間</label>
                                <input type="text" class="form-control time" name="offlineTime"
                                    v-bind:value="checkData.offlineTime">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-group-sm">
                                <label for="predictQuantity" class="control-label">預計生產數量</label>
                                <input type="text" class="form-control" name="predictQuantity"
                                   v-bind:value="checkData.predictQuantity">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-group-sm">
                                <label for="payQuantity" class="control-label">實際繳庫數量</label>
                                <input type="text" class="form-control" name="payQuantity"
                                    v-bind:value="checkData.payQuantity">
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary" id="BtnSave">儲存</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="{{ url('/js/check/editCheck.js?v=11') }}"></script>
    @include('check.checkDefect')
@endsection