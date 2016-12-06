@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/duty/list.js?x=1') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>生產值班清單</h2>
        <div class="pull-left">
            <a class="btn btn-primary" href="{{ url('/Duty/ScheduleList') }}">新增</a>
        </div>
        <form class="form-inline pull-right" action="{{ url('/Duty/DutyList') }}" role="form">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="prd_no" class="form-control" placeholder="請輸入瓶號">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
                    {{ $machno = '' }}
                    <select class="form-control" name="machno">
                        <option value="" {{ $machno == '' ? 'selected': '' }}>全部產線</option>
                        <option value="1-1" {{ $machno == '1-1' ? 'selected': '' }}>1-1</option>
                        <option value="01" {{ $machno == '01' ? 'selected': '' }}>01</option>
                        <option value="02" {{ $machno == '02' ? 'selected': '' }}>02</option>
                        <option value="03" {{ $machno == '03' ? 'selected': '' }}>03</option>
                        <option value="05" {{ $machno == '05' ? 'selected': '' }}>05</option>
                        <option value="06" {{ $machno == '06' ? 'selected': '' }}>06</option>
                        <option value="07" {{ $machno == '07' ? 'selected': '' }}>07</option>
                        <option value="08" {{ $machno == '08' ? 'selected': '' }}>08</option>
                    </select>
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
                    <td>製令單號</td>
                    <td>值班日期</td>
                    <td>班別</td>
                    <td>產線</td>
                    <td>機台人員</td>
                    <td>瓶號</td>
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
                        <td>{{ isset($item['mk_no'])? $item['mk_no']: '試模' }}</td>
                        <td>{{ date('Y-m-d', strtotime($item['dutyDate'])) }}</td>
                        @php
                            switch ($item['class']) {
                                case 1:
                                    $class = '早班';
                                    break;
                                case 2:
                                    $class = '中班';
                                    break;
                                case 3:
                                    $class = '晚班';
                                    break;
                                default:
                                    $class = '';
                            }
                        @endphp
                        <td>{{ $class }}</td>
                        <td>{{ substr($item['machno'], 0, 3) }}</td>
                        <td>{{ $item['staffName'] }}</td>
                        <td>{{ $item['NAME'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['efficiency'] }}</td>
                        <td>{{ $item['anneal'] }}</td>
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
    </div>
</div>
@include('duty.add')
@endsection