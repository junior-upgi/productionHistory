@extends('layouts.masterpage')
@section('title', '檢查表清單')
@section('content')
<div id="check">
    <div class="row">
        <div class="col-md-12">
            <h2>檢查表清單</h2>
            <div class="pull-left">
                <a class="btn btn-primary" href="{{ url('/nav/check.schedule') }}">新增</a>
            </div>
            <form class="form-inline pull-right" role="form">
                <div class="row form-group">
                    <div class="col-md-3">
                        <input type="text" name="snm" class="form-control" placeholder="請輸入瓶號" value="">
                    </div>
                </div>
                <!--
                <div class="row form-group">
                    <div class="col-md-3">
                        <select class="form-control" name="glassProdLineID">
                            <option value="">全部產線</option>
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
                <div class="row form-group">
                    <div class="col-md-3">
                        <input type="text" name="schedate" class="form-control date form_datetime" placeholder="請輸入生產日期" value="">
                    </div>
                </div>
                -->
                <div class="row form-group">
                    <div class="col-md-2">
                        <button class="btn btn-default">搜尋</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <p></p>
    <div class="row" v-show="checkList.length > 0">
        <div class="col-md-12 table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td></td>
                    <td>生產開始日期</td>
                    <td>瓶號</td>
                    <td>線別</td>
                    <td></td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in checkList">
                    <td width="51">
                        <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip"
                            data-placement="top" title="編輯" @click="edit(i.id)" @mouseenter="tooltip($event)">
                        <span class="glyphicon glyphicon-edit"></span>
                        </button>
                    </td>
                    <td>@{{ item.schedate }}</td>
                    <td>@{{ item.snm }}</td>
                    <td>@{{ item.glassProdLineID }}</td>
                    <td width="51">
                        <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip"
                                data-placement="top" title="刪除" @click="del(i.id)" @mouseenter="tooltip($event)">
                        <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="{{ url('/js/check/list.js?v=1') }}"></script>
@stop