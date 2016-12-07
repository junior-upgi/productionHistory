@extends('layouts.masterpage')
@section('content')
<script src="{{ url('/js/history/list.js') }}"></script>
<div class="row">
    <div class="col-md-12">
        <h2>品質管制產品履歷表</h2>
        <div class="pull-left">
            <a class="btn btn-primary" href="{{ url('/History/ScheduleList') }}">新增</a>
        </div>
        <form class="form-inline pull-right" action="{{ url('/History/HistoryList') }}" role="form">
            <div class="row form-group">
                <div class="col-md-3">
                    <input type="text" name="pname" class="form-control" placeholder="請輸入瓶號" value="{{ $pname }}">
                </div>
            </div>
            <div class="row form-group">
                <div class="col-md-3">
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
                    <td style="width: 127px;"></td>
                    <td>圖號</td>
                    <td>日期</td>
                    <td>線別</td>
                    <td>重量</td>
                    <td>實際生產重量</td>
                    <td>歪力</td>
                    <td>熱震</td>
                    <td>機速</td>
                    <td>檢瓶率/繳庫率</td>
                    <td>客戶/麥頭</td>
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
                        <td>圖號</td>
                        <td>日期</td>
                        <td>線別</td>
                        <td>重量</td>
                        <td>實際生產重量</td>
                        <td>歪力</td>
                        <td>熱震</td>
                        <td>機速</td>
                        <td>檢瓶率/繳庫率</td>
                        <td>客戶/麥頭</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p>
            {{ $list->setPath('/History/HistoryList?pname=' . $pname . '&machno=' . $machno) }}
        </p>
    </div>
</div>
@include('history.add')
@endsection