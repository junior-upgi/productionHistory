@extends('layouts.master_page')
@section('title', '檢查表清單')
@section('content')
<div id="check">
    <div class="row">
        <div class="col-md-12">
            <h2>檢查表清單</h2>
            <div class="pull-left">
                <a class="btn btn-primary" href="{{ url('/nav/check.schedule') }}">新增</a>
            </div>
            <div class="form-inline pull-right">
                <div class="row form-group">
                    <div class="col-md-3">
                        <input type="text" name="snm" class="form-control" placeholder="請輸入瓶號" value="">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <button class="btn btn-default">搜尋</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <p></p>
    <div class="row" v-show="checkList.length > 0">
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td></td>
                    <td class="col-md-1">生產開始日期</td>
                    <td>瓶號</td>
                    <td>線別</td>
                    <td class="col-md-3">客戶</td>
                    <td class="col-md-3">加工</td>
                    <td>選瓶時間</td>
                    <td>下線時間</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="check in checkList">
                    <td width="51">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip"
                            data-placement="top" title="編輯" v-on:click="edit(check)" @mouseenter="tooltip($event)">
                        <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                    <td width="51">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip"
                                data-placement="top" title="履歷表" v-on:click="print(check)" @mouseenter="tooltip($event)">
                        <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                    <td>@{{ check.schedate }}</td>
                    <td>@{{ check.snm }}</td>
                    <td>@{{ check.glassProdLineID }}</td>
                    <td>@{{ check.customer }}</td>
                    <td>@{{ check.decoration }}</td>
                    <td>@{{ check.selectTime }}</td>
                    <td>@{{ check.offlineTime }}</td>
                    <td width="51">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                data-placement="top" title="刪除" v-on:click="del(check.id)" @mouseenter="tooltip($event)">
                        <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('/js/check/list.js?v=6') }}"></script>
@stop