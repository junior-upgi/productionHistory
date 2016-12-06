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
                    {!! $form->formGroupInput('圖號', '', '') !!}
                    {!! $form->formGroupInput('圖名', '', '') !!}
                    {!! $form->formGroupInput('量規', '', '') !!}
                    {!! $form->formGroupInput('吹製方法', '', '') !!}
                    {!! $form->formGroupInput('其它', '', '') !!}
                    {!! $form->formGroupInput('生產日期', '', '') !!}
                    {!! $form->formGroupInput('線別', '', '') !!}
                    {!! $form->formGroupInput('重量', '', '') !!}
                    {!! $form->formGroupInput('實際生產重量', '', '') !!}
                    {!! $form->formGroupInput('歪力', '', '') !!}
                    {!! $form->formGroupInput('熱震', '', '') !!}
                    {!! $form->formGroupInput('機速(支/分)', '', '') !!}
                    {!! $form->formGroupInput('檢瓶率/繳庫率', '', '') !!}
                    {!! $form->formGroupInput('客戶/麥頭', '', '') !!}
                    {!! $form->formGroupInput('客戶/麥頭', '', '') !!}
                    {!! $form->formGroupInput('加工別', '', '') !!}
                    {!! $form->formGroupInput('預定生產數量', '', '') !!}
                    {!! $form->formGroupInput('生產數量', '', '') !!}
                    {!! $form->formGroupInput('繳庫數量', '', '') !!}
                    {!! $form->formGroupInput('缺點', '', '') !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">關閉</button>
                    <button type="submit" class="btn btn-primary" data-loading-text="資料送出中..." autocomplete="off" id="btnSave"></button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->