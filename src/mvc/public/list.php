<?php
if ( isset($ctrl->post['limit']) ){
  $ctrl->obj = $ctrl->get_object_model($ctrl->post);
}
else{
  $ctrl->combo(_("Historique d'utilisation"), true);
}