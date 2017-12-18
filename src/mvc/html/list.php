<bbn-table :source="source.root + 'list'"
           class="appui-history-table-main"
           :pageable="true"
           :filterable="true"
           :sortable="true"
           >
	<bbn-column field="uid"
              :hidden="true"
              >
  </bbn-column>
	<bbn-column field="id_user"
              :title="_('User')"
              :render="renderUser"
              >
  </bbn-column>
	<bbn-column field="table"
              :title="_('Table')"
              :source="source.tables"
              :render="r => r.table"
              >
  </bbn-column>
	<bbn-column field="col"
              :title="_('Column')"
              :render="r => r.col"
              :source="source.columns"
              >
  </bbn-column>
	<bbn-column field="chrono"
              :title="_('Date & Time')"
              type="date"
              >
  </bbn-column>
	<bbn-column field="operation"
              :title="_('Operation')"
              :render="renderOperation"
              >
  </bbn-column>
  <bbn-column :title="_('Actions')"
              :buttons="getButtons"
              >
  </bbn-column>
</bbn-table>