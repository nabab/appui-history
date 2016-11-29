<?php
/* @var $ctrl \bbn\mvc\controller */
if ( isset($ctrl->post['history_id']) ){
  $ctrl->data['history_id'] = $ctrl->post['history_id'];
  if ( $ctrl->data = $ctrl->get_model() ){
    echo $ctrl->set_title("Ligne d'historique")->add_js()->get_view();
  }
}