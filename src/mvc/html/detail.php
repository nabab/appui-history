<table id="table_historique_ligne">
  <thead>
  <tr>
    <th><h3 class="bbn-c"><?=_("Champ")?></h3></th>
    <?php foreach ( $moments as $m ){ ?>
      <th><h3 class="bbn-c"><?=$m['titre']?></h3></th>
    <?php } ?>
  </tr>
  </thead>
  <tbody>
  <?php foreach ( current($moments)['lines'] as $k => $l ){ ?>
  <tr>
    <td><h4><?=$l['titre']?></h4></td>
    <?php
    $i = 0;
    foreach ( $moments as $i => $m ){ ?>
      <td<?=(($i === 1) && ($l['valeur'] !== $m[0]['lines'][$k]['valeur']) ? ' class="bbn-red"' : '')?>>
        <?=$l['valeur']?>
      </td>
    <?php
    $i++;
    } ?>
  </tr>
  <?php } ?>
  </tbody>
</table>
