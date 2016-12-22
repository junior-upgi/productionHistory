@extends('layouts.report')
@section('title', '產品履歷表')
@section('content')
    <div style="margin:auto; width: 743px; min-height: 975px;">
        <h3 class="text-center">統義玻璃工業股份有限公司</h3>
        <h4 class="text-center">品質管制產品履歷表</h4>
        @if (isset($history))
            <table class="table table-bordered">
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr> 
                <tr>
                    <td>圖號</td>
                    <td colspan="2"></td>
                    <td>圖名</td>
                    <td colspan="3"></td>
                    <td>吹製方法</td>
                    <td></td>
                </tr>
                @foreach ($historyList as $item)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>      
                @endforeach     
            </table>
        @else
        @endif
    </div>
@endsection