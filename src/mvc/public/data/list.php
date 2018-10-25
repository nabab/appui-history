<?php
if ( isset($ctrl->post['limit'], $ctrl->post['start']) ){
  if ( $model = $ctrl->get_plugin_model('data/list', $ctrl->post) ){
    $ctrl->obj = $model;
  }
  else {
    $ctrl->action();
  }
}