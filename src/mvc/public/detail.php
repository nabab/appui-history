<?php
/* @var $ctrl \bbn\mvc\controller */
if ( !empty($ctrl->post['uid']) && !empty($ctrl->post['col']) && !empty($ctrl->post['tst']) ){
  $d = $ctrl->get_plugin_model('detail', $ctrl->post);
  if ( is_null($d) ){
    $d = $ctrl->get_model($ctrl->post);
  }
  $ctrl->set_title($d['title'] ?? _("History line"));
  $views = $ctrl->get_plugin_views('detail', $d);
  $ctrl->obj->data = $d;
  echo $views['html'] ?: $ctrl->get_view();
  $ctrl->add_script($views['js'] ?: $ctrl->get_view('', 'js'));
  $ctrl->obj->css = $views['css'] ?: $ctrl->get_less();
}