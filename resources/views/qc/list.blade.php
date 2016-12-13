@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/qc/list.js?v=1') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>品管管制表</h2>
        <div class="pull-left">
            <a class="btn btn-primary" href="{{ url('/QC/ScheduleList') }}">新增</a>
        </div>
        <form class="form-inline pull-right" action="{{ url('/QC/qcList') }}" role="form">
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
                    <input type="text" name="schedate" class="form-control date form_datetime" placeholder="請輸入預定生產日期" value="{{ $schedate }}">
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
                    <td>圖號</td>
                    <td>預定生產數量</td>
                    <td>預定生產日期</td>
                    <td>線別</td>
                    <td>客戶</td>
                    <td>級別</td>
                    <td>國別</td>
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
                        </td>
                        <td>{{ $item['snm'] }}</td>
                        <td>{{ $item['orderQty'] }}</td>
                        <td>{{ $item['schedate'] }}</td>
                        <td>{{ $item['glassProdLineID'] }}</td>                      
                        <td>{{ $item['customer'] }}</td>
                        <td>{{ $item['qualityLevel'] }}</td>
                        <td>{{ $item['country'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            {{ $list->setPath('/QC/qcList?snm=' . $snm . '&glassProdLineID=' . $glassProdLineID . '&schedate' . $schedate) }}
        </p>
    </div>
</div>
@include('qc.add')
@endsection