<?php
/**
 * 前端視圖元件
 *
 * @version 1.0.0
 * @author spark it@upgi.com.tw
 * @date 17/01/19
 * @since 1.0.0 spark: 於此版本開始編寫註解
 */
namespace App\Presenters;

/**
 * Class ServicePresenter
 *
 * @package App\Repositories
 */
class ServicePresenter
{    
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