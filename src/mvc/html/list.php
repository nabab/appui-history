<bbn-table :source="source.root + 'data/list'"
           class="appui-history-table-main"
           ref="table"
           :pageable="true"
           :info="true"
           :sortable="true"
           :filterable="true"
           :multifilter="true"
           :order="[{field: 'tst', Dir: 'DESC'}]"
           :showable="true"
>
	<bbns-column title="<?=_('UID')?>"
              field="uid"
              :width="260"
              :hidden="true"
  ></bbns-column>
	<bbns-column field="usr"
              title="<?=_('User')?>"
              :render="renderUser"
              :source="users"
  ></bbns-column>
	<bbns-column field="bbn_table"
              title="<?=_('Table')?>"
              :source="tables"
              :render="r => r.tab_name"
  ></bbns-column>
	<bbns-column field="col"
              title="<?=_('Column')?>"
              :render="renderCols"
              :source="columns"
  ></bbns-column>
	<bbns-column field="dt"
              title="<?=_('Date & Time')?>"
              type="datetime"
              :render="renderDate"
              :width="150"
  ></bbns-column>
	<bbns-column field="opr"
              title="<?=_('Operation')?>"
              :render="renderOperation"
              :source="['INSERT', 'UPDATE', 'DELETE', 'RESTORE']"
              cls="bbn-c"
              :width="150"
  ></bbns-column>
  <bbns-column :buttons="renderButtons"
              :width="100"
              cls="bbn-c"
  ></bbns-column>
</bbn-table>