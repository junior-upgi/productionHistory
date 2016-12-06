<?php
namespace App\Presenters;

class FormPresenter
{
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
}