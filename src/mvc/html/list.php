<bbn-table :source="source.root + 'list'"
           class="appui-history-table-main"
           ref="table"
           :pageable="true"
           :info="true"
           :sortable="true"
           :filterable="true"
           :editable="true"
           :order="[{field: 'tst', dir: 'DESC'}]"
>
	<bbn-column field="uid"
              :hidden="true"
  ></bbn-column>
	<bbn-column field="usr"
              title="<?=_('User')?>"
              :render="renderUser"
              :source="users"
  ></bbn-column>
	<bbn-column field="tab_id"
              title="<?=_('Table')?>"
              :source="tables"
              :render="r => r.tab_name"
  ></bbn-column>
	<bbn-column field="col_id"
              title="<?=_('Column')?>"
              :render="r => r.col_name"
              :source="columns"
  ></bbn-column>
	<bbn-column field="dt"
              title="<?=_('Date & Time')?>"
              type="date"
              :render="renderDate"
              :width="150"
  ></bbn-column>
	<bbn-column field="opr"
              title="<?=_('Operation')?>"
              :render="renderOperation"
              :source="['INSERT', 'UPDATE', 'DELETE', 'RESTORE']"
              :width="150"
  ></bbn-column>
  <bbn-column title="<?=_("Élément")?>"
              field="adh"
              :render="renderAdh"
              :width="300"
  ></bbn-column>
  <bbn-column title="<?=_('Actions')?>"
              :buttons="[{
                title: '<?=_("Seen")?>',
                icon: 'fa fa-eye',
                notext: true,
                command: seen
              }, {
                title: '<?=_("Cancel")?>',
                icon: 'fa fa-undo',
                notext: true,
                command: undo
              }]"
              :width="100"
  ></bbn-column>
</bbn-table>