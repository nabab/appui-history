// Javascript Document
/* jshint esversion: 6 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        users: bbn.fn.order(appui.app.users, 'text', 'ASC')
      }
    },
    computed: {
      columns(){
        return bbn.fn.order(this.source.columns, 'text', 'ASC');
      },
      tables(){
        return bbn.fn.order(this.source.tables, 'text', 'ASC');
      }
    },
    methods: {
      renderDate(r){
        return moment(r.dt).format('DD/MM/YYYY HH:mm:ss');
      },
      renderUser(r){
        return appui.app.getUserName(r.usr);
      },
      renderOperation(r){
        return '<span class="' + r.opr.toLowerCase() + '">' + r.opr + '</span>';
      },
      renderCols(row) {
        let cols = row.col_name.split(',')
        res = '';
        if (cols.length) {
          cols.forEach((c, i) => {
            res += c + (cols[i + 1] !== undefined ? '<br>' : '');
          });
        }
        return res;
      },
      renderButtons(r){
        return [{
          action: this.seen,
          icon: 'nf nf-fa-eye',
          text: bbn._('Seen'),
          notext: true,
          disabled: (r.opr === 'DELETE') || (r.opr === 'RESTORE')
        }, {
          action: this.undo,
          icon: 'nf nf-fa-undo',
          text: bbn._('Cancel'),
          notext: true
        }];
      },
      seen(r){
        bbn.vue.closest(this, 'bbns-container').popup().load({
          url: this.source.root + 'detail',
          data: {
            uid: r.uid,
            col: r.col,
            tst: r.tst,
            usr: r.usr
          },
          
          width: 700
        });
      },
      undo(r){
        let msg;
        switch ( r.opr ){
          case "INSERT":
            msg = bbn._("delete this record");
            break;
          case "UPDATE":
            msg = bbn._("go back to the previous value");
            break;
          case "DELETE":
            msg = bbn._("restore this record");
            break;
          case "RESTORE":
            msg = bbn._("delete this record again");
            break;
        }
        this.confirm(bbn._("Do you really want to") + ' ' + msg + "?", () => {
          this.post(this.source.root + "actions/cancel", {
            uid: r.uid,
            col: r.col,
            tst: r.tst,
            usr: r.usr
          }, (d) => {
            if ( d.success ){
              this.$refs.table.updateData();
            }
            else {
              this.alert(bbn._("This didn't work...") + ' <br><br>' + bbn._("Thanks to create a bug with the UID you couldn't cancel"));
            }
          });
        });
      }
    }
  };
})();