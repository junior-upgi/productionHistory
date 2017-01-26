@extends('layouts.masterpage')
@section('title', '檢查表-生產排程清單')
@section('content')
<div id="schedule">
    <div class="row">
        <div class="col-md-12">
            <h2>檢查表-生產排程清單</h2>
            <div class="pull-left col-xs-6 row">
                <a class="btn btn-default" href="{{ url('/nav/check.list') }}" style="margin-bottom: 10px;">返回檢查表清單</a>
            </div>
            <form class="form-inline pull-right" role="form" style="margin-bottom: 10px;">
                <div class="row form-group">
                    <div class="col-md-3">
                        <input type="text" name="snm" class="form-control" placeholder="請輸入瓶號" value="">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <select class="form-control" name="glassProdLineID">
                            <option value="">全部產線</option>
                        </select>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-3">
                        <input type="text" name="schedate" readonly class="form-control date form_datetime"
                               placeholder="請輸入生產日期" value="">
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-md-2">
                        <button class="btn btn-default">搜尋</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td style="width: 127px;"></td>
                    <td style="width: 45px;">類型</td>
                    <td>生產日期</td>
                    <td>產品名稱</td>
                    <td>線別</td>
                    <td>預計生產量</td>
                </tr>
                </thead>
                <tbody>
                <tr v-for="item in pageList">
                    <td>
                        <button class="btn btn-primary">填寫檢查表</button>
                    </td>
                    <td>
                        <span v-if="item.sampling == 0"> 量產</span>
                        <span v-else>試模</span>
                    </td>
                    <td>@{{ item.schedate }}</td>
                    <td>@{{ item.snm }}</td>
                    <td>@{{ item.glassProdLineID }}</td>
                    <td class="text-right">@{{ item.orderQty }}</td>
                </tr>
                </tbody>
            </table>
            <ul class="pagination" style="margin: 0px;">
                <li v-bind:class="{'disabled': (currentPage === 1)}"
                    @click.prevent="setPage(currentPage - 1)"><a href="#">Prev</a></li>
                <li v-for="(n, index) in pageIndex"
                    v-bind:class="{'active': (currentPage === (n))}"
                    @click.prevent="setPage(n)"><a href="#">@{{ n }}</a></li>
                <li v-bind:class="{'disabled': (currentPage === totalPage)}"
                    @click.prevent="setPage(currentPage + 1)"><a href="#">Next</a></li>
            </ul>
        </div>
    </div>
</div>
<script src="{{ url('/js/check/schedule.js?v=6') }}"></script>
@stop