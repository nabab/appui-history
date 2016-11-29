<?php
if ( isset($ctrl->post['skip']) ){
  $ctrl->data = $ctrl->post;
  $ctrl->obj = $ctrl->get_model();
}
else{
  $ctrl->obj->title = _("Historique d'utilisation");
  echo $ctrl->get_view();
  $ctrl->add_js();
}