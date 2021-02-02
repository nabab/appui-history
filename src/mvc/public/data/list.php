<?php
if ( isset($ctrl->post['limit'], $ctrl->post['start']) ){
  if ( $model = $ctrl->getPluginModel('data/list', $ctrl->post) ){
    $ctrl->obj = \bbn\X::toObject($model);
  }
  else {
    $ctrl->action();
  }
}