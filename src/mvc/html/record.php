<div class="appui-history-detail bbn-w-100">
  <div class="bbn-m bbn-b bbn-vpadding bbn-hlpadding bbn-c"
       bbn-text="operations[source.opr] + ' ' + _('in') + ' ' + source.table + ' ' + _('by user') + ' ' + appui.getUserName(source.usr) + ' ' + _('on the') + ' ' + bbn.fn.fdate(source.tst)"/>
  <appui-database-data-record bbn-if="source.data"
                              :source="source.data"/>
</div>
