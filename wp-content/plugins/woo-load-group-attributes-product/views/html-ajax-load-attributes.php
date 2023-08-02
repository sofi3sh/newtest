<?php
$html = '';

if (is_array($post_content)) {

    $html .= ' <form action="" method="post"> ';
    $html .= ' <table id="form_load_attributes"> ';
    $html .= ' <tbody> <tr> <input type="checkbox" id="checkAll" checked="checked"> <label for="checkAll"> <b>' . __('Check All', 'woolgap') . '</b> </label> <td> </td> </tr> <tr> <td> ';

    foreach ($post_content as $key => $value) {

        if (!isset ($value["name"])) continue;
        if (!isset ($value["slug"])) continue;

        $name 		= $value["name"];
        $slug 		= $value["slug"];
        $type 		= isset($value["type"]) 	? $value["type"] 		: 'select';
        $order_by 	= isset($value["order_by"]) ? $value["order_by"] 	: 'menu_order';
        $visible 	= isset($value["visible"]) 	? $value["visible"] 	: '1';
        
        $html .= '<span><input type="checkbox" id="lc_' . $slug . '" class="checkbox" checked="checked" name="load_attribute_names[]" value="' . $slug . '"> <lable for="lc_' . $slug . '">' . $name . '</label></span>';
    }

    $html .= ' </td> </tr> </tbody> ';
    $html .= ' </table> ';

} else {

    $html = __("Not Found", "woolgap");
}

$html .= '';
