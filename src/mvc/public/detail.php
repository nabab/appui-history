<?php
/* @var $ctrl \bbn\Mvc\Controller */
if ( !empty($ctrl->post['uid']) && !empty($ctrl->post['col']) && !empty($ctrl->post['tst']) ){
  $d = null;//$ctrl->getPluginModel('detail', $ctrl->post);
  if ( is_null($d) ){
    $d = $ctrl->getModel($ctrl->post);
  }
  bbn\X::ddump($d);
  $ctrl->setTitle($d['title'] ?? _("History line"));
  $views = ['html' => '', 'js' => '', 'css' => ''];//$ctrl->getPluginViews('detail', $d);
  $ctrl->obj->data = $d;
  echo $views['html'] ?: $ctrl->getView();
  $ctrl->addScript($views['js'] ?: $ctrl->getView('', 'js'));
  $ctrl->obj->css = $views['css'] ?: $ctrl->getLess();
}