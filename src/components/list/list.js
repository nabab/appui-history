(() => {
  return {
    props: {
      source: {
        type: String
      },
      data: {
        type: Object
      },
      tables: {
        type: Array
      },
      columns: {
        type: Array
      },
      table: {
        type: String
      },
      storage: {
        type: String
      }
    },
    data(){
      return {
        root: appui.plugins['appui-history'] + '/',
        users: bbn.fn.order(appui.users, 'text', 'ASC'),
        operations: [{
          text: bbn._("Insertion"),
          value: "INSERT",
          color: "green"
        }, {
          text: bbn._("Modification"),
          value: "UPDATE",
          color: "blue"
        }, {
          text: bbn._("Restoration"),
          value: "RESTORE",
          color: "orange"
        }, {
          text: bbn._("Deletion"),
          value: "DELETE",
          color: "red"
        }]
      }
    },
    computed: {
      tableSource(){
        return this.source || (this.root + 'data/list');
      },
      columnsList(){
        return bbn.fn.order(this.columns, 'text', 'ASC');
      },
      tablesList(){
        return bbn.fn.order(this.tables, 'text', 'ASC');
      },
      tableFilters(){
        const filters = {
          logic: 'AND',
          conditions: []
        };

        if (this.table) {
          filters.conditions.push({field: 'bbn_table', value: this.table});
        }

        return filters;
      }
    },
    methods: {
      renderDate(row){
        return bbn.dt(row.dt).format('DD/MM/YYYY HH:mm:ss');
      },
      renderUser(row){
        return appui.getUserName(row.usr);
      },
      renderOperation(row){
        let op;
        if (row.opr
          && (op = bbn.fn.getRow(this.operations, 'value', row.opr))
        ){
          return '<span style="color:' + op.color + '">' + op.text + '</span>';
        }

        return '';
      },
      renderColumn(row) {
        if (['INSERT', 'DELETE', 'RESTORE'].includes(row.opr)) {
          return '<em>' + bbn._('N/A') + '</em>';
        }

        if (!row.col_name){
          return '<em>' + bbn._('Inconnu') + '</em>';
        }

        let cols = row.col_name.split(',');
        let res = '';
        if ( cols.length ){
          cols.forEach((c, i) => {
            res += c + (cols[i+1] !== undefined ? '<br>' : '');
          });
        }
        return res;
      },
      renderButtons(row){
        return [{
          action: this.seen,
          icon: 'nf nf-md-database_eye_outline',
          title: bbn._('See the modification'),
          notext: true,
          disabled: (row.opr === 'DELETE') || (row.opr === 'RESTORE')
        }, {
          action: this.recording,
          icon: 'nf nf-md-archive_eye_outline',
          title: bbn._("See recording"),
          notext: true
        }, {
          action: this.undo,
          icon: 'nf nf-fa-undo',
          title: bbn._('Cancel'),
          notext: true
        }];
      },
      seen(row){
        this.getPopup().load({
          url: this.root + 'detail',
          data: {
            uid: row.uid,
            col: row.col,
            tst: row.tst,
            usr: row.usr
          },
          width: 700
        });
      },
      recording(row){
        this.getPopup().load({
          url: this.root + 'record',
          // Sending everything obliges to have already info accessible and therefore granting access for all
          data: {
            uid: row.uid,
            col: row.col,
            dt: row.dt
          },
          maximizable: true
        });
      },
      undo(row){
        const ev = new CustomEvent('undo');
        this.$emit('undo', ev, row);
        if (!ev.defaultPrevented) {
          let msg;
          switch (row.opr) {
            case "INSERT":
              msg = bbn._("Do you really want to delete this record?");
              break;
            case "UPDATE":
              msg = bbn._("Do you really want to go back to the previous value?");
              break;
            case "DELETE":
              msg = bbn._("Do you really want to restore this record?");
              break;
            case "RESTORE":
              msg = bbn._("Do you really want to delete this record again?");
              break;
          }
          this.confirm(msg, () => {
            this.post(this.root + "actions/cancel", {
              uid: row.uid,
              col: row.col,
              tst: row.tst,
              usr: row.usr
            }, (d) => {
              if ( d.success ){
                this.getRef('table').updateData();
              }
              else {
                this.alert(bbn._("This didn't work...") + ' <br><br>' + bbn._("Thanks to create a bug with the UID you couldn't cancel"));
              }
            });
          });
        }
      }
    }
  };
})();
