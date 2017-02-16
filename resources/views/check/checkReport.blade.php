@extends('layouts.report')
@section('title', '生產管制產品履歷表')
@section('content')
    <div id="checkReport">
        <h3 class="text-center">統義玻璃工業股份有限公司</h3>
        <h4 class="text-center">品質管制產品履歷表</h4>
        <table class="table table-bordered table-condensed">
            <tr class="text-center">
                <td class="col-lg-1">圖號</td>
                <td class="col-lg-1 text-left">@{{ check.snm }}</td>
                <td class="col-lg-1">圖名</td>
                <td class="col-lg-6 text-left" colspan="4">@{{ check.spc }}</td>
                <td class="col-lg-1">吹製方法</td>
                <td class="col-lg-2 text-left" colspan="2">@{{ check.formingMethod }}</td>
            </tr>
            <tr class="text-center">
                <td>量規</td>
                <td class="text-left" colspan="6">@{{ check.guage }}</td>
                <td>其它</td>
                <td class="text-left" colspan="2">@{{ check.other }}</td>
            </tr>
            <tr class="text-center">
                <td>生產日期</td>
                <td>線別</td>
                <td>選瓶時間</td>
                <td>下線時間</td>
                <td>歪力</td>
                <td class="col-lg-2" colspan="2">熱震</td>
                <td colspan="3">客戶/嘜頭</td>
            </tr>
            <tr class="text-center">
                <td>@{{ check.schedate }}</td>
                <td>@{{ check.glassProdLineID }}</td>
                <td>@{{ check.selectTime }}</td>
                <td>@{{ check.offlineTime }}</td>
                <td>@{{ computedInfo.stressLevel }}</td>
                <td colspan="2">@{{ computedInfo.thermalShock}}</td>
                <td colspan="3">@{{ check.customer }}</td>
            </tr>
            <tr class="text-center">
                <td class="col-lg-1">重量(gr)</td>
                <td class="col-lg-1">實際生產重量最小值</td>
                <td class="col-lg-1">實際生產重量最大值</td>
                <td class="col-lg-1">預定生產數量</td>
                <td class="col-lg-1">實際生產數量</td>
                <td class="col-lg-1">實際繳庫數量</td>
                <td class="col-lg-1" colspan="2">機速(支/分)</td>
                <td class="col-lg-1">檢瓶率(%)</td>
                <td class="col-lg-1">繳庫率(%)</td>
            </tr>
            <tr class="text-center">
                <td class="col-lg-1">@{{ check.weight }}</td>
                <td class="col-lg-1">@{{ computedInfo.actualMinWeight }}</td>
                <td class="col-lg-1">@{{ computedInfo.actualMaxWeight }}</td>
                <td class="col-lg-1">@{{ check.predictQuantity }}</td>
                <td class="col-lg-1">@{{ computedInfo.actualQuantity }}</td>
                <td class="col-lg-1">@{{ check.payQuantity }}</td>
                <td class="col-lg-1" colspan="2">@{{ computedInfo.speed }}</td>
                <td class="col-lg-1">@{{ computedInfo.checkRate }}</td>
                <td class="col-lg-1">@{{ check.payRate }}</td>
            </tr>
        </table>
        <table class="table table-bordered table-condensed">
            <tr>
                <td colspan="6"><h5>生產缺點</h5></td>
            </tr>
            <tr class="text-center">
                <td class="col-lg-1">序號</td>
                <td class="col-lg-4">缺點項目</td>
                <td class="col-lg-1">缺點平均值</td>
                <td class="col-lg-1">序號</td>
                <td class="col-lg-4">缺點項目</td>
                <td class="col-lg-1">缺點平均值</td>
            </tr>
            <tr class="text-center" v-for="i in parseInt(productionDefect.length / 2)">
                <td>@{{ (i - 1) * 2 + 1 }}</td>
                <td>@{{ productionDefect[(i - 1) * 2].name }}</td>
                <td>@{{ parseFloat(productionDefect[(i - 1) * 2].value).toFixed(2) }}</td>
                <td>@{{ i * 2 }}</td>
                <td>@{{ productionDefect[(i * 2) - 1].name }}</td>
                <td>@{{ parseFloat(productionDefect[(i * 2) - 1].value).toFixed(2) }}</td>
            </tr>
        </table>
        <table class="table table-bordered table-condensed">
            <tr>
                <td colspan="6"><h5>抽驗缺點</h5></td>
            </tr>
            <tr class="text-center">
                <td>序號</td>
                <td>缺點項目</td>
                <td>缺點平均值</td>
                <td>序號</td>
                <td>缺點項目</td>
                <td>缺點平均值</td>
            </tr>
            <tr class="text-center" v-for="i in parseInt(spotCheckDefect.length / 2)">
                <td class="col-lg-1">@{{ (i - 1) * 2 + 1 }}</td>
                <td class="col-lg-4">@{{ spotCheckDefect[(i - 1) * 2].name }}</td>
                <td class="col-lg-1">@{{ parseFloat(spotCheckDefect[(i - 1) * 2].value).toFixed(2) }}</td>
                <td class="col-lg-1">@{{ i * 2 }}</td>
                <td class="col-lg-4">@{{ spotCheckDefect[(i * 2) - 1].name }}</td>
                <td class="col-lg-1">@{{ parseFloat(spotCheckDefect[(i * 2) - 1].value).toFixed(2) }}</td>
            </tr>
        </table>
        <table class="table table-bordered table-condensed">
            <tr>
                <td>
                    <span v-html="nl2br(check.remark)"></span>
                </td>
            </tr>
        </table>
    </div>
    <script src="{{ url('/js/check/checkReport.js?v=2') }}"></script>
@stop