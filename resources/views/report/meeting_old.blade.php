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
        <!--顯示提示訊息-->
        <div style="height: 120px; overflow: auto; margin-bottom:10px; display:none; border: solid 1px #a0a0a0; border-radius: 4px;" id="task">
            <table class="table table-bordered" style="margin-bottom: 0px;">
                <tbody id="taskDetail"></tbody>
            </table>
        </div>
        
        <!--顯示產品基本資訊 暫不顯示-->
        <div class="panel panel-default" style="margin-bottom:10px; display:none;" id="basicData">
            <div class="panel-body" id="basicDataInfo">
            </div>
        </div>

        <!--顯示生產品管管制資訊-->
        <div class="panel panel-default" style="margin-bottom:10px; display:none;" id="qc">
            <div class="panel-body" id="qcDetail">
            </div>
        </div>

        <!--顯示生產履歷資訊-->
        <ul class="list-group" id="historyDetail" style="display:none;">
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