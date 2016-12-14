@extends('layouts.report')
@section('content')
<script src="{{ url('/js/report/meeting.js?v=2') }}"></script>
<div class="row" style="padding-top: 10px; height: 100%; overflow: auto;">
    <div class="col-md-3">
        <div class="input-group">
            <input type="text" class="form-control" id="searchSnm" placeholder="請輸入產品編號">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button" onclick="search()">搜尋</button>
            </span>
        </div><!-- /input-group -->
        <p></p>
        <table class="table table-bordered" id="history_table" style="display:none">
            <thead>
                <tr>
                    <td></td>
                    <td>日期</td>
                    <td>線別</td>
                    <td>良率</td>
                </tr>
            </thead>
            <tbody id="history_tbody">
            </tbody>
        </table>
    </div>
    <div class="col-md-9">
        <div class="panel panel-default" style="height: 120px; overflow: auto; margin-bottom:10px; display:none;" id="remind">
            <div class="panel-body">
                <table class="table table-condensed">
                    <tbody id="remind_tbody">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default" style="margin-bottom:10px; display:none;" id="basicData">
            <div class="panel-body" id="basicDataInfo">
            </div>
        </div>
        <ul class="list-group" id="productInfo" style="display:none;">
            <!--
            <li class="list-group-item">
                <p>濃縮品管記錄1</p>
                <p>濃縮生產條件1</p>
            </li>

            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody id="basicData_tbody">
                    </tbody>
                </table>
            -->
        </ul>
    </div>
</div>
@endsection