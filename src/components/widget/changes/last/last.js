(() => {
  return {
    methods: {
      fdatetime: bbn.fn.fdatetime,
      user(user){
        return bbn.fn.getField(appui.users, 'text', 'value', user);
      }
    },
    components: {
      list: {
        template: `
        <div class="bbn-background">
          <div v-if="source.length" class="bbn-w-100">
            <div v-for="m in source"
                class="bbn-padding bbn-grid-fields"
            >
              <label>` + bbn._('Table') + `</label>
              <div v-text="m.table"></div>
              <template v-if="showColumn">
                <label>` + bbn._('Column') + `</label>
                <div v-text="m.column"></div>
              </template>
              <label>` + bbn._('User') + `</label>
              <div v-text="user(m.usr)"></div>
              <label>` + bbn._('Date') + `</label>
              <div v-text="fdatetime(m.dt)"></div>
              <template v-if="showValue">
                <label>` + bbn._('Value') + `</label>
                <div>
                  <bbn-button @click="openDetails(m)"
                              icon="nf nf-fa-eye"
                  ></bbn-button>
                </div>
              </template>
            </div>
          </div>
          <div v-else class="bbn-padding bbn-c bbn-w-100">` + bbn._('There is no data available') + `</div>
        </div>
        `,
        props: {
          source: {
            type: Array
          },
          showValue: {
            type: Boolean,
            default: false
          },
          showColumn: {
            type: Boolean,
            default: false
          }
        },
        data(){
          return {
            historyRoot: appui.plugins['appui-history'] + '/'
          }
        },
        methods: {
          fdatetime: bbn.fn.fdatetime,
          user(user){
            return bbn.fn.getField(appui.users, 'text', 'value', user);
          },
          openDetails(r){
            this.getPopup().load({
              url: this.historyRoot + 'detail',
              data: {
                uid: r.uid,
                col: r.col,
                tst: r.tst,
                usr: r.usr
              },
              width: 700
            });
          }
        }
      }
    }
  }
})();
