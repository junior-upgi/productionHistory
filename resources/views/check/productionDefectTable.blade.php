<div class="table-responsive">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <td rowspan="2"></td>
                <td rowspan="2"></td>
                <td rowspan="2" width="80">生產日期</td>
                <td rowspan="2">班別</td>
                <td v-for="item in items"
                    v-if="itemsCount[item.itemID] != 0"
                    v-bind:colspan="itemsCount[item.itemID]">@{{ item.itemName }}</td>
                <td rowspan="2">實際生產數量</td>
                <td rowspan="2">機速</td>
                <td rowspan="2">分鐘</td>
                <td rowspan="2">檢瓶率</td>
                <td rowspan="2">實際生產數量最小值</td>
                <td rowspan="2">實際生產數量最大值</td>
                <td rowspan="2">歪力</td>
                <td rowspan="2">熱震</td>
            </tr>
            <tr>
                <td v-for="defect in defects">@{{ defect.defectName }}</td>
            </tr>
        </thead>
        <tbody>
            <tr v-for="p in productionData" v-if="p.spotCheck == 0">
                <td width="56"><button class="btn btn-sm btn-default" v-on:click="editProductionDefectShow(p.id)">編輯</button></td>
                <td width="56"><button class="btn btn-sm btn-danger" v-on:click="del(p.id)">刪除</button></td>
                <td>@{{ getDate(p.prodDate) }}</td>
                <td>@{{ getClassName(p.classType) }}</td>
                <td v-for="d in defectList" v-if="d.productionDataID == p.id">@{{ d.value }}</td>
                <td>@{{ p.actualQuantity }}</td>
                <td>@{{ p.speed }}</td>
                <td>@{{ p.minute }}</td>
                <td>@{{ p.checkRate }}</td>
                <td>@{{ p.actualMinWeight }}</td>
                <td>@{{ p.actualMaxWeight }}</td>
                <td>@{{ p.stressLevel }}</td>
                <td>@{{ p.thermalShock }}</td>
            </tr>
            <tr v-if="productionDataCount > 0">
                <td colspan="4" class="text-right">缺點平均值</td>
                <td v-for="a in avgDefect">@{{ a }}</td>
                <td>@{{ computedInfo['actualQuantity'] }}</td>
                <td></td>
                <td>@{{ computedInfo['minute'] }}</td>
                <td>@{{ computedInfo['checkRate'] }}</td>
                <td>@{{ computedInfo['actualMinWeight'] }}</td>
                <td>@{{ computedInfo['actualMaxWeight'] }}</td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>