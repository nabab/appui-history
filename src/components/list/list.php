<bbn-table :source="root + 'data/list'"
           class="appui-history-list"
           ref="table"
           :pageable="true"
           :info="true"
           :sortable="true"
           :filterable="true"
           :multifilter="true"
           :order="[{field: 'tst', dir: 'DESC'}]"
           :showable="true"
           :filters="table ? {logic: 'AND', conditions: [{field: 'bbn_table', value: table}]} : []">
	<bbns-column label="<?= _('UID') ?>"
              field="uid"
              :width="260"
              :invisible="true"/>
	<bbns-column field="usr"
              label="<?= _('User') ?>"
              :render="renderUser"
              :source="users"/>
	<bbns-column field="bbn_table"
              label="<?= _('Table') ?>"
              :source="tablesList"
              :render="r => r.tab_name"
              :invisible="!!table"
              :showable="!table"/>
	<bbns-column field="col"
              label="<?= _('Column') ?>"
              :render="renderCols"
              :source="columnsList"/>
	<bbns-column field="dt"
              label="<?= _('Date & Time') ?>"
              type="datetime"
              :render="renderDate"
              :width="150"/>
	<bbns-column field="opr"
              label="<?= _('Operation') ?>"
              :render="renderOperation"
              :source="['INSERT', 'UPDATE', 'DELETE', 'RESTORE']"
              cls="bbn-c"
              :width="150"/>
  <bbns-column :buttons="renderButtons"
              :width="100"
              cls="bbn-c"/>
</bbn-table>