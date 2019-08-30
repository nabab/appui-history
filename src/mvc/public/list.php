<?php
/** @var $ctrl \bbn\mvc\controller */

$d = $ctrl->get_plugin_model('list', $ctrl->data);
if ( is_null($d) ){
  $d = $ctrl->get_model();
}
$ctrl->set_title($d['title'] ?? _("History of use"));
$views = $ctrl->get_plugin_views('list', $d);
$ctrl->obj->data = $d;
echo $views['html'] ?: $ctrl->get_view();
$ctrl->add_script($views['js'] ?: $ctrl->get_view('', 'js'));
$ctrl->obj->css = $views['css'] ?: $ctrl->get_less();