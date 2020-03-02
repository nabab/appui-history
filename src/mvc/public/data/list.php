<?php
if ( isset($ctrl->post['limit'], $ctrl->post['start']) ){
  if ( $model = $ctrl->get_plugin_model('data/list', $ctrl->post) ){
    $ctrl->obj = \bbn\x::to_object($model);
  }
  else {
    $ctrl->action();
  }
}