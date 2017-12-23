<table class="bbn-100">
  <thead>
    <tr>
      <th class="k-header">
        <h4 class="bbn-c"><?=_("Champ")?></h4>
      </th>
      <th class="k-header"
          v-if="showBefore"
      >
        <h4 class="bbn-c"><?=_("Avant")?></h4>
      </th>
      <th class="k-header">
        <h4 class="bbn-c"><?=_("Après")?></h4>
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="bbn-b"
          v-text="source.column"
          style="height: 100%; vertical-align: middle; text-align: center"
      ></td>
      <td class="bbn-b"
          v-if="showBefore"
          v-text="beforeText"
          style="height: 100%; vertical-align: middle; text-align: center"
      ></td>
      <td class="bbn-b"
          v-text="afterText"
          style="height: 100%; vertical-align: middle; text-align: center"
      ></td>
    </tr>
  </tbody>
  <tfoot>
    <tr>
      <td class="k-header bbn-c bbn-p" :colspan="showBefore ? 3 : 2">
        <h4 v-text="message"></h4>
      </td>
    </tr>
  </tfoot>
</table>