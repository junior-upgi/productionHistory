@extends('layouts.report')
@section('title', '品管管製表')
@section('content')
    <div style="margin:auto; width: 743px; min-height: 975px;">
        <h3 class="text-center">統義玻璃工業股份有限公司</h3>
        <h4 class="text-center">品管管制表</h4>
        @if (isset($qc))
            <table style="width: 743px;">
                <tr>
                    <td style="width: 155px;">圖號： {{ $qc['snm'] }}</td>
                    <td style="width: 216px;">預定生產數量： {{ $qc['orderQty'] }}</td>
                    <td style="width: 248px;">預定生產日期： {{ $qc['schedate'] }}</td>
                    <td style="width: 124px;">線別： {{ $qc['glassProdLineID'] }}</td>
                </tr>
            </table>
            <table class="table table-bordered" style="font-size: 12px; margin-bottom: 10px;">
                <tr>
                    <td rowspan="11" style="width: 15px; vertical-align: middle;">規<br>格<br>尺<br>寸<br>m<br>/<br>m</td>
                    <td class="text-center">名稱</td>
                    <td class="text-center">規格</td>
                    <td class="text-center">名稱</td>
                    <td class="text-center">規格</td>
                    <td class="text-center">名稱</td>
                    <td class="text-center">規格</td>
                    <td class="col-md-6 text-center">圖示</td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">口外徑</div>
                        <div style="margin-bottom: 10px;">{{ $qc['finishType'] }}</div>
                        {{ in_array('finishDia', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['finishDiaGo'] }}</td>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">胴徑</div>
                        {{ in_array('bodyDia', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['bodyDiaGo'] }}</td>
                    <td>
                        <div style="margin-bottom: 10px;">垂直度</div>
                        {{ in_array('verticality', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['verticality'] }}</td>
                    <td rowspan="12" style="font-size: 14px;">
                        @if (trim($qc['draw']) != '')
                            <img src="{{ 'data:' . $qc['type'] . ';base64,' . $qc['code'] }}" alt="圖示" style="max-width: 354px; max-height: 500px;">
                        @endif
                        <h5 class="text-center">(瓶圖請參閱附件)</h5>
                        <span class="col-md-6">級別： {{ $qc['qualityLevel'] }}</span>
                        <span class="col-md-6">國別： {{ $qc['country'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td>{{ $qc['finishDiaNoGo'] }}</td>
                    <td>{{ $qc['bodyDiaNoGo'] }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">口內徑</div>
                        {{ in_array('finishInnerDia', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['finishInnerDiaGo'] }}</td>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">天肩高</div>
                        {{ in_array('hSetting', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['hSettingMax'] }}</td>
                    <td>
                        <div style="margin-bottom: 10px;">天波</div>
                        {{ in_array('wavyFinish', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['wavyFinish'] }}</td>
                </tr>
                <tr>
                    <td>{{ $qc['finishInnerDiaNoGo'] }}</td>
                    <td>{{ $qc['hSettingMin'] }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">螺牙徑</div>
                        {{ in_array('threadDia', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['threadDiaGo'] }}</td>
                    <td>
                        <div style="margin-bottom: 10px;">高度規</div>
                        {{ in_array('height', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['heightGo'] }}</td>
                    <td>
                        <div style="margin-bottom: 10px;">天傾斜</div>
                        {{ in_array('slantedFinish', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['slantedFinish'] }}</td>
                </tr>
                <tr>
                    <td>{{ $qc['threadDiaNoGo'] }}</td>
                    <td></td>
                    <td>{{ $qc['heightNoGo'] }}</td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">唇徑</div>
                        {{ in_array('lipDia', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['lipDiaGo'] }}</td>
                    <td></td>
                    <td></td>
                    <td>
                        <div style="margin-bottom: 10px;">重量</div>
                        {{ array('weight', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['weight'] }}</td>
                </tr>
                <tr>
                    <td>{{ $qc['lipDiaNoGo'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <div style="margin-bottom: 10px;">護圈徑</div>
                        {{ in_array('saftyRing', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['saftyRingGo'] }}</td>
                    <td></td>
                    <td></td>
                    <td>
                        <div style="margin-bottom: 10px;">滿(容)量</div>
                        {{ in_array('brimCapueity', explode(',', $qc['fullInspection'])) ? '全程管制' : '' }}
                    </td>
                    <td>{{ $qc['brimCapueity'] }}</td>
                </tr>
                <tr>
                    <td>{{ $qc['saftyRingNoGo'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center" style="font-size: 18px;">客戶要求與注意事項</td>
                </tr>
                <tr>
                    <td colspan="7" style="font-size: 14px;">
                        {!! nl2br($qc['requirement']) !!}
                    </td>
                </tr>
                <tr>
                    <td colspan="8" style="font-size: 14px;">
                        加工別： {{ $qc['decoration'] }}
                        <br>
                        附註： {{ $qc['note'] }}
                    </td>
                </tr>
                <tr>
                    <td colspan="7" class="text-center" style="font-size: 18px;">
                        上次生產缺點
                    </td>
                    <td>
                        <span class="col-md-5" style="font-size: 18px;">麥頭/裝箱方式</span>
                        <span class="col-md-7 text-right" style="font-size: 18px;">客戶： {{ $qc['customer'] }}</span>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="font-size: 14px;">
                        @if (isset($history))
                            {!! nl2br($history->defect) !!}
                        @endif
                    </td>
                    <td rowspan="2" class="text-center" style="vertical-align: middle;">
                        <h4>嘜頭與裝箱方式請參閱<u>包裝規格表</u></h4>
                    </td>
                </tr>
                <tr>
                    <td colspan="7" style="font-size: 14px;">
                        上次檢瓶率/繳庫良率：
                        @if (isset($history))
                            {{ $history->efficiency }} %
                        @endif
                    </td>
                </tr>
            </table>
        @else

        @endif
    </div>
    <div style="margin: auto; width: 743px;">
        <table style="width: 743px; height: 60px;">
            <tr>
                <td style="width: 214.75px; vertical-align: top;">研發主管：</td>
                <td style="width: 214.75px; vertical-align: top;">業務部副理：</td>
                <td style="width: 214.75px; vertical-align: top;">研發課長：</td>
                <td style="width: 101px; vertical-align: top;">製表：</td>
            </tr>
        </table>
        <h5 class="text-right">R8-02-01-A</h5>
    </div>
@endsection