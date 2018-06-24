<bbn-table :source="source.root + 'list'"
           class="appui-history-table-main"
           ref="table"
           :pageable="true"
           :info="true"
           :sortable="true"
           :filterable="true"
           :multifilter="true"
           :editable="true"
           :order="[{field: 'tst', dir: 'DESC'}]"
>
	<bbns-column field="uid"
              :hidden="true"
  ></bbns-column>
	<bbns-column field="usr"
              title="<?=_('User')?>"
              :render="renderUser"
              :source="users"
  ></bbns-column>
	<bbns-column field="tab_id"
              title="<?=_('Table')?>"
              :source="tables"
              :render="r => r.tab_name"
  ></bbns-column>
	<bbns-column field="col_id"
              title="<?=_('Column')?>"
              :render="r => r.col_name"
              :source="columns"
  ></bbns-column>
	<bbns-column field="dt"
              title="<?=_('Date & Time')?>"
              type="date"
              :render="renderDate"
              :width="150"
  ></bbns-column>
	<bbns-column field="opr"
              title="<?=_('Operation')?>"
              :render="renderOperation"
              :source="['INSERT', 'UPDATE', 'DELETE', 'RESTORE']"
              :width="150"
  ></bbns-column>
  <bbns-column title="<?=_("Élément")?>"
              field="adh"
              :render="renderAdh"
              :width="300"
              :filterable="false"
  ></bbns-column>
  <bbns-column :buttons="renderButtons"
              :width="100"
              cls="bbn-c"
  ></bbns-column>
</bbn-table>