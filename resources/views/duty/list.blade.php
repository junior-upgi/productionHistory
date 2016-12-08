@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/duty/list.js?x=5') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>生產值班清單</h2>
        <div class="pull-left">
            <a class="btn btn-primary" href="{{ url('/Duty/ScheduleList') }}">新增</a>
        </div>
        <form class="form-inline pull-right" action="{{ url('/Duty/DutyList') }}" role="form">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="snm" class="form-control" placeholder="請輸入瓶號" value="{{ $snm }}">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <select class="form-control" name="glassProdLineID">
                        <option value="" {{ $glassProdLineID == '' ? 'selected': '' }}>全部產線</option>
                        <option value="L1-1" {{ $glassProdLineID == 'L1-1' ? 'selected': '' }}>L1-1</option>
                        <option value="L1" {{ $glassProdLineID == 'L1' ? 'selected': '' }}>L1</option>
                        <option value="L2" {{ $glassProdLineID == 'L2' ? 'selected': '' }}>L2</option>
                        <option value="L3" {{ $glassProdLineID == 'L3' ? 'selected': '' }}>L3</option>
                        <option value="L5" {{ $glassProdLineID == 'L5' ? 'selected': '' }}>L5</option>
                        <option value="L6" {{ $glassProdLineID == 'L6' ? 'selected': '' }}>L6</option>
                        <option value="L7" {{ $glassProdLineID == 'L7' ? 'selected': '' }}>L7</option>
                        <option value="L8" {{ $glassProdLineID == 'L8' ? 'selected': '' }}>L8</option>
                    </select>
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="dutyDate" class="form-control date form_datetime" placeholder="請輸入值班日期" value="{{ $dutyDate }}">
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
<p></p>
<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td style="width: 51px;"></td>
                    <td>瓶號</td>
                    <!--<td>製令單號</td>-->
                    <td>值班日期</td>
                    <td>班別</td>
                    <td>產線</td>
                    <td>機台人員</td>
                    <td>生產數量</td>
                    <td>效率</td>
                    <td>退火等級</td>
                    <td>停機時間</td>
                </tr>
            </thead>
            <tbody>
                @foreach($list as $item)
                    <tr>
                        <td>
                            <button type="button" class="btn btn-default btn-sm" data-toggle="tooltip" 
                                data-placement="top" title="編輯" onclick="edit('{{ $item['id'] }}')">
                                <span class="glyphicon glyphicon-edit"></span>
                            </button>
                            <!--
                            <a href="{{ url('/nav/parameter.detail') }}" class="btn btn-default btn-sm" data-toggle="tooltip" 
                                data-placement="top" title="詳細內容">
                                <span class="glyphicon glyphicon-list-alt"></span>
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" data-toggle="tooltip" 
                                data-placement="top" title="刪除" onclick="">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                            -->
                        </td>
                        <!--<td>{{ isset($item['mk_no'])? $item['mk_no']: '試模' }}</td>-->
                        <td>{{ $item['snm'] }}</td>
                        <td>{{ date('Y-m-d', strtotime($item['dutyDate'])) }}</td>
                        @php
                            switch ($item['shift']) {
                                case 1:
                                    $shift = '早班';
                                    break;
                                case 2:
                                    $shift = '中班';
                                    break;
                                case 3:
                                    $shift = '晚班';
                                    break;
                                default:
                                    $shift = '';
                            }
                        @endphp
                        <td>{{ $shift }}</td>
                        <td>{{ $item['glassProdLineID'] }}</td>
                        <td>{{ $item['staffName'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['efficiency'] }}</td>
                        <td>{{ $item['annealGrade'] }}</td>
                        <td>
                            @if(!isset($item['startShutdown']))
                                {{ date('hh:ii', strtotime($item['startShutdown'])) }}
                            @endif
                            @if(!isset($item['endShutdown']))
                                ~{{ date('hh:ii', strtotime($item['endShutdown'])) }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            {{ $list->setPath('/Duty/DutyList?snm=' . $snm . '&glassProdLineID=' . $glassProdLineID . '&dutyDate=' . $dutyDate) }}
        </p>
    </div>
</div>
@include('duty.add')
@endsection