@extends('layouts.report')
@section('title', '生產資訊報表')
@section('content')
<script src="{{ url('/js/report/meeting.js?v=4') }}"></script>
@if (isset($historyList)) 
    <div class="row" style="padding-top: 10px; height: 100%; overflow: auto;">
        <div class="col-md-3">
            <form class="" action="{{ url('/Report/ProductionMeeting') }}" role="form">
                <div class="input-group">
                    <input type="text" class="form-control" id="snm" name="snm" value="{{ $snm }}" placeholder="請輸入產品編號">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default" type="button">搜尋</button>
                    </span>
                </div><!-- /input-group -->
            </form>
            <p></p>
            <table class="table table-bordered" id="history_table">
                <thead>
                    <tr>
                        <td></td>
                        <td>日期</td>
                        <td>線別</td>
                        <td>良率</td>
                    </tr>
                </thead>
                <tbody id="history_tbody">
                    @foreach ($historyList as $item)
                        <tr>
                            <td><input type="checkbox" class="ch" value="{{ $item['id'] }}"></td>
                            <td>{{ date('Y-m-d', strtotime($item['schedate'])) }}</td>
                            <td>{{ $item['glassProdLineID'] }}</td>
                            <td>{{ $item['efficiency'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="col-md-9">
            <!--顯示提示訊息-->
            @if (count($task) > 0)
                <div style="height: 120px; overflow: auto; margin-bottom:10px; border: solid 1px #a0a0a0; border-radius: 4px;" id="task">
                    <table class="table table-bordered" style="margin-bottom: 0px;">
                        <tbody id="taskDetail">
                            @foreach ($task as $item)
                                <tr id="ta_{{ $item['id'] }}">
                                    <td style="width: 50px;">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="delTask('{{ $item['id'] }}')">刪除</button>
                                    </td>
                                    <td style="width: 100px;">
                                        {{ $item['SALM_NAME'] }}
                                    </td>
                                    <td>{{ $item['description'] }}</td>
                                    <td style="width: 120px;">
                                        {{ date('Y-m-d', strtotime($item['deadline'])) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
            <!--顯示產品基本資訊 暫不顯示-->
            <div class="panel panel-info" style="margin-bottom:10px; display:none;" id="basicData">
                <table class="table table-bordered">
                    <tr>
                        <td>圖號</td><td></td>
                    </tr>
                </table>
            </div>

            <!--顯示生產品管管制資訊-->
            <div class="panel panel-info" style="margin-bottom:10px;" id="qc">
                <div class="panel-heading">
                    <h3 class="panel-title">品管管制條件</h3>
                </div>
                <table class="table table-bordered">
                    <tr>
                        <td>線別</td><td>{{ $qc['glassProdLineID'] }}</td>
                        <td colspan="2">預定生產日期</td><td>{{ $qc['schedate'] }}</td>
                        <td colspan="2">預定生產數量</td><td>{{ $qc['orderQty'] }}</td>
                        <td>口部</td><td>{{ $qc['finishType'] }}</td>
                        <td colspan="4"></td>
                    </tr>
                    <tr>
                        <td></td><td>口外徑</td><td>口內徑</td><td>螺牙徑</td><td>唇徑</td><td>護圈徑</td><td>胴徑</td>
                        <td>天肩高</td><td>高度規</td><td>垂直度</td><td>天波</td><td>天傾斜</td><td>重量</td><td>滿容量</td>
                    </tr>
                    <tr>
                        <td>GO</td>
                        <td>{{ $qc['finishDiaGo'] }}</td><td>{{ $qc['finishInnerDiaGo'] }}</td><td>{{ $qc['threadDiaGo'] }}</td>
                        <td>{{ $qc['lipDiaGo'] }}</td><td>{{ $qc['saftyRingGo'] }}</td><td>{{ $qc['bodyDiaGo'] }}</td>
                        <td>{{ $qc['hSettingMax'] }}</td><td>{{ $qc['heightGo'] }}</td><td>{{ $qc['verticality'] }}</td>
                        <td>{{ $qc['wavyFinish'] }}</td><td>{{ $qc['slantedFinish'] }}</td><td>{{ $qc['weight'] }}</td>
                        <td>{{ $qc['brimCapueity'] }}</td>
                    </tr>
                    <tr>
                        <td>NOGO</td>
                        <td>{{ $qc['finishDiaNoGo'] }}</td><td>{{ $qc['finishInnerDiaNoGo'] }}</td><td>{{ $qc['threadDiaNoGo'] }}</td>
                        <td>{{ $qc['lipDiaNoGo'] }}</td><td>{{ $qc['saftyRingNoGo'] }}</td><td>{{ $qc['bodyDiaNoGo'] }}</td>
                        <td>{{ $qc['hSettingMin'] }}</td><td>{{ $qc['heightNoGo'] }}</td><td></td>
                        <td></td><td></td><td></td>
                        <td></td>
                    </tr>
                    <tr><td colspan="2">客戶要求注意事項</td><td colspan="12">{!! nl2br($qc['requirement']) !!}</td></tr>
                    <tr>
                        <td colspan="1">加工別</td><td colspan="8">{{ $qc['decoration'] }}</td>
                        <td>級別</td><td>{{ $qc['qualityLevel'] }}</td>
                        <td>國別</td><td>{{ $qc['country'] }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">附註</td><td colspan="10">{{ $qc['note'] }}</td>
                        <td>圖示</td>
                        <td>
                            <a href="{{ url('/Service/BlankPic/' . $qc['draw']) }}" target="_blank">
                                <span class="glyphicon glyphicon-picture"></span>
                            </a>
                        </td>
                    </tr>
                </table>
            </div>

            <!--顯示生產履歷資訊-->
            <div id="historyDetail">
                @foreach ($prodData as $item)
                    @if (true)
                        <div class="panel panel-warning" id="{{ 'hi_' . $item['historyID'] }}" style="display:none;">
                            <div class="panel-heading">
                                <h2 class="panel-title">
                                    <div class="col-md-3">生產日期： {{ date('Y-m-d', strtotime($item['hschedate'])) }}</div>
                                    <div class="col-md-2">線別： {{ $item['hline'] }}</div>
                                    良率： {{ $item['efficiency'] }}
                                </h2>
                            </div>
                            <table class="table table-bordered">
                                <!--生產條件資料-->
                                <tr>
                                    <td style="width: 100px;">套筒</td><td>心棒間隔物</td><td>承受螺絲</td><td>接收槽</td><td>直槽</td><td>彎槽</td>
                                    <td>出口杯規格</td><td>剪刀規格</td><td>磚棒規格</td><td>口模規</td>
                                    <td>冷卻段</td><td>調溫段</td><td>Gob溫度</td>
                                </tr>
                                <tr>
                                    <td>{{ $item['thimble'] }}</td>
                                    <td>{{ $item['spacer'] }}</td>
                                    <td>{{ $item['stopScrew'] }}</td>
                                    <td>{{ $item['scoop'] }}</td>
                                    <td>{{ $item['trough'] }}</td>
                                    <td>{{ $item['deflector'] }}</td>
                                    <td>{{ $item['orificeRing'] }}</td>
                                    <td>{{ $item['shear'] }}</td>
                                    <td>{{ $item['plunger'] }}</td>
                                    <td>{{ $item['nrGauge'] }}</td>
                                    <td>{{ $item['fhCoolingTemp'] }}</td>
                                    <td>{{ $item['fhAdjustTemp'] }}</td>
                                    <td>{{ $item['gobTemp'] }}</td>
                                </tr>
                                <!--生產履歷資料-->
                                <tr>
                                    <td>吹製方法</td><td>{{ $item['formingMethod'] }}</td><td>量規</td><td colspan="2">{{ $item['gauge'] }}</td>
                                    <td>重量</td><td>{{ $item['weight'] }}</td><td>機速</td><td>{{ $item['speed'] }}</td>
                                    <td colspan="2">檢瓶率/繳庫率</td><td  colspan="2">{{ $item['efficiency'] }}</td>
                                </tr>
                                <tr>
                                    <td>生產狀況記錄</td>
                                    <td colspan="12">{!! nl2br($item['note']) !!}</td>
                                </tr>
                                <tr>
                                    <td>生產缺點</td>
                                    <td colspan="12">{!! nl2br($item['defect']) !!}</td>
                                </tr>
                            </table>
                        </div>
                    @else
                        <div class="panel panel-warning" id="{{ 'hi_' . $item['historyID'] }}" style="display:none;">
                            <div class="panel-heading">
                                <h3 class="panel-title">生產日期： {{ $item['hschedate'] }}</h3>
                            </div>
                            <div class="panel-body">
                                <h4>無此次生產條件資料</h4>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
@else
    <div class="row" style="padding-top: 10px; height: 100%; overflow: auto;">
        <div class="col-md-3">
            <form class="" action="{{ url('/Report/ProductionMeeting') }}" role="form">
                <div class="input-group">
                    <input type="text" class="form-control" id="snm" name="snm" value="{{ $snm }}" placeholder="請輸入產品編號">
                    <span class="input-group-btn">
                        <button type="submit" class="btn btn-default" type="button">搜尋</button>
                    </span>
                </div><!-- /input-group -->
            </form>
            <p></p>
            @if (isset($snm))
                <div class="alert alert-warning" role="alert">
                    沒有 {{ $snm }} 的生產履歷記錄!!
                </div>
            @endif
        </div>
        <div class="col-md-9">
        </div>
    </div>
@endif
@endsection