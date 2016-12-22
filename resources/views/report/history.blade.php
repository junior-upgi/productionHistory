@extends('layouts.report')
@section('title', '產品履歷表')
@section('content')
    <div style="margin:auto; width: 743px; min-height: 975px;">
        <h3 class="text-center">統義玻璃工業股份有限公司</h3>
        <h4 class="text-center">品質管制產品履歷表</h4>
        @if (isset($history))
            <table class="table table-bordered"> 
                <tr>
                    <td>圖號</td>
                    <td colspan="2">{{ $history['snm'] }}</td>
                    <td>圖名</td>
                    <td colspan="3">{{ $history['spc'] }}</td>
                    <td>吹製方法</td>
                    <td>{{ $history['formingMethod'] }}</td>
                </tr>
                <tr>
                    <td>量規</td>
                    <td colspan="6">{{ $history['gauge'] }} <mark>最新資料顯示</mark></td>
                    <td>其它</td>
                    <td>{{ $history['other'] }}</td>
                </tr>
                @php
                    $setItem = ['schedate', 'glassProdLineID', 'weight', 'actualWeight', 'stressLevel', 
                        'thermalShock', 'speed', 'efficiency', 'customer', '', '', '', '', ''];
                    $setName = ['生產日期', '線別', '重量(gr)', '實際生產重量', '歪力', '熱震', '機速(支/分)', '檢瓶率/繳庫率', '客戶/麥頭', 
                        '客戶/麥頭', '加工別', '預定生產數量', '生產數量', '繳庫數量'];
                @endphp
                @for ($s = 0; $s < count($setItem); $s++)
                    <tr>
                        @if ($s == 8)
                            <td>{{ $setName[$s] }}</td>
                            @for ($i = (count($historyList) - 1); $i >= 0; $i--)
                                @if ($historyList[$i]['sampling'] == 0) 
                                    @foreach($customer as $c)
                                        @if ($c['id'] == $historyList[$i]['id'])
                                            <td>{{ $c['cus_snm'] }}</td>
                                        @endif
                                    @endforeach
                                @else
                                    <td>{{ $historyList[$i]['sname'] }}</td>
                                @endif
                            @endfor
                            @for ($i = (8 - count($historyList)); $i > 0; $i-- )
                                <td></td>
                            @endfor
                        @else
                            <td>{{ $setName[$s] }}</td>
                            @for ($i = (count($historyList) - 1); $i >= 0; $i--)
                                @if (isset($historyList[$i][$setItem[$s]]))
                                    <td>{{ $historyList[$i][$setItem[$s]] }}</td>
                                @else
                                    <td></td>
                                @endif
                            @endfor
                            @for ($i = (8 - count($historyList)); $i > 0; $i-- )
                                <td></td>
                            @endfor
                        @endif
                    </tr>      
                @endfor
                @foreach ($historyList as $item)
                    <tr>
                        <td>{{ $item['schedate'] }}</td>
                        <td colspan="8">{{ $item['defect'] }}</td>
                    </tr>
                @endforeach
            </table>
        @else
        @endif
    </div>
    <div style="margin: auto; width: 743px;">
        <h5 class="text-right">R8-02-07-B</h5>
    </div>
@endsection