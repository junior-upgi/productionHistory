<?php
namespace App\Presenters;

//
class ServicePresenter
{
    /*
    public function formGroupInput($title, $id, $name, $require = false, $label=3, $control=9)
    {
        $require ? $req = 'required' : $req = '';
        $str = "<div class=\"form-group\">
                    <label for=\"$id\" class=\"col-md-$label control-label\">$title</label>
                    <div class=\"col-md-$control\">
                        <input type=\"text\" class=\"form-control\" id=\"$id\" name=\"$name\" value=\"\" $req>
                    </div>
                </div>";
        return $str;
    }
    */
    
    //
    public function picIcon($id)
    {
        $html = '';
        if (trim($id) != '') {
            $html = "
                <span class=\"glyphicon glyphicon-picture\" onclick=\"showImage('$id')\"></span>
            ";
        }
        return $html;
    }
    
    //
    public function picScript()
    {
        $script = "
            <script>
                function showImage(id) {
                    $.ajax({
                        url: url + '/Service/GetPic/' + id,
                        type: 'GET',
                        dataType: 'text',
                        error: function (xhr) {
                            swal(\"取得圖片資料失敗!\", xhr.statusText, \"error\");
                        },
                        success: function (result) {
                            $('#picModal').find('#img_show').html('<image src=\'' + result + '\' class=\"carousel-inner img-responsive img-rounded\" />');
                            $('#picModal').modal('show');
                        }
                    })
                }
            </script>
        ";
        return $script;
    }
}