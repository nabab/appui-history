<div class="bbn-padded bbn-w-100">
  <bbn-panelbar :source="[{
                  header: '<?= _('INSERT') ?>',
                  component: $options.components.list,
                  componentOptions: {
                    source: source.insert
                  }
                }, {
                  header: '<?= _('UPDATE') ?>',
                  component: $options.components.list,
                  componentOptions: {
                    source: source.update,
                    showColumn: true,
                    showValue: true
                  }
                }, {
                  header: '<?= _('DELETE') ?>',
                  component: $options.components.list,
                  componentOptions: {
                    source: source.delete
                  }
                }]"
                :scrollable="false"
                :opened="0"
  ></bbn-panelbar>
</div>