@inject('form', 'App\Presenters\FormPresenter')
<div class="modal fade" id="addModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title" id="modalTitle"></h4>
            </div>
            <form id="addForm" class="form-horizontal" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="_token" value="{{{ csrf_token() }}}">
                    <input type="hidden" id="type" name="type" value="">
                    <input type="hidden" id="id" name="id" value="">
                    {!! $form->formGroupInput('初模抱具', '', '') !!}
                    {!! $form->formGroupInput('成模抱具', '', '') !!}
                    {!! $form->formGroupInput('日模臂', '', '') !!}
                    {!! $form->formGroupInput('漏斗臂', '', '') !!}
                    {!! $form->formGroupInput('套筒', '', '') !!}
                    {!! $form->formGroupInput('心棒機構配件', '', '') !!}
                    {!! $form->formGroupInput('心棒間隔物', '', '') !!}
                    {!! $form->formGroupInput('承受螺絲', '', '') !!}
                    {!! $form->formGroupInput('接收槽', '', '') !!}
                    {!! $form->formGroupInput('直槽', '', '') !!}
                    {!! $form->formGroupInput('彎槽', '', '') !!}
                    {!! $form->formGroupInput('心棒上壓力', '', '') !!}
                    {!! $form->formGroupInput('定頭吹風壓力', '', '') !!}
                    {!! $form->formGroupInput('心棒冷卻壓力', '', '') !!}
                    {!! $form->formGroupInput('初成形吹風壓力', '', '') !!}
                    {!! $form->formGroupInput('成形吹風壓力', '', '') !!}
                    {!! $form->formGroupInput('主空氣壓|高壓', '', '') !!}
                    {!! $form->formGroupInput('徐冷窯|網速', '', '') !!}
                    {!! $form->formGroupInput('型式', '', '') !!}
                    {!! $form->formGroupInput('盆磚', '', '') !!}
                    {!! $form->formGroupInput('磚管速度', '', '') !!}
                    {!! $form->formGroupInput('出口杯規格', '', '') !!}
                    {!! $form->formGroupInput('前刀凸輪', '', '') !!}
                    {!! $form->formGroupInput('前刀規格', '', '') !!}
                    {!! $form->formGroupInput('磚棒凸輪', '', '') !!}
                    {!! $form->formGroupInput('磚棒規格', '', '') !!}
                    {!! $form->formGroupInput('機速', '', '') !!}
                    {!! $form->formGroupInput('製瓶間隔', '', '') !!}
                    {!! $form->formGroupInput('口模規', '', '') !!}
                    {!! $form->formGroupInput('初模模具溫度', '', '') !!}
                    {!! $form->formGroupInput('成模模具溫度', '', '') !!}
                    {!! $form->formGroupInput('輸送帶點火', '', '') !!}
                    {!! $form->formGroupInput('過橋板點火', '', '') !!}
                    {!! $form->formGroupInput('冷卻風壓力', '', '') !!}
                    {!! $form->formGroupInput('低壓', '', '') !!}
                    {!! $form->formGroupInput('退火爐溫度', '', '') !!}
                    {!! $form->formGroupInput('前爐溫度-冷卻段', '', '') !!}
                    {!! $form->formGroupInput('前爐溫度-調溫段', '', '') !!}
                    {!! $form->formGroupInput('前爐溫度-Gob溫度', '', '') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" id="btnSave"></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->