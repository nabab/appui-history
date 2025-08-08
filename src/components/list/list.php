<bbn-table class="appui-history-list"
           :source="tableSource"
           :data="data"
           ref="table"
           :pageable="true"
           :info="true"
           :sortable="true"
           :filterable="true"
           :multifilter="true"
           :order="[{field: 'tst', dir: 'DESC'}]"
           :showable="true"
           :filters="tableFilters"
           :total="false"
           :storage-full-name="storage">
	<bbns-column label="<?= _('UID') ?>"
              field="uid"
              :width="260"
              :invisible="true"/>
  <bbns-column field="dt"
              label="<?= _('Date & Time') ?>"
              type="datetime"
              :render="renderDate"
              :width="140"/>
  <bbns-column field="opr"
              label="<?= _('Operation') ?>"
              :render="renderOperation"
              :source="operations"
              cls="bbn-c"
              :width="150"/>
  <bbns-column field="bbn_table"
              label="<?= _('Table') ?>"
              :source="tablesList"
              :render="r => r.tab_name"
              :invisible="!!table"
              :showable="!table"/>
	<bbns-column field="col"
              label="<?= _('Column') ?>"
              :render="renderColumn"
              :source="columnsList"/>
	<bbns-column field="usr"
              label="<?= _('Author') ?>"
              :render="renderUser"
              :source="users"
              :width="200"/>
  <bbns-column :buttons="renderButtons"
              :width="100"
              cls="bbn-c"/>
</bbn-table>
