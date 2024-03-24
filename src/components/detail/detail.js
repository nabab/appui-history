// Javascript Document

(() => {
  return {
    data(){
      let items = [];
      if (Array.isArray(this.source.items)) {
        items = this.source.items.map(i => {
          i.json = this.getJSON(i);
          i.showJSON = !!i.json;
          return i;
        });
      }
      return {
        message: bbn._("The entry has been") + ' ',
        showBefore: false,
        items: items
      }
    },
    methods: {
      setShowJSON(val) {
        this.$nextTick(() => {
          this.$forceUpdate();
        });
      },
      getJSON(item) {
        let isDelete = this.source.operation === 'DELETE',
            bef = false,
            aft = isDelete;
        try {
          bef = JSON.parse(item.before);
          if (!isDelete) {
            aft = JSON.parse(item.after);
          }
        }
        catch(e){
          return false;
        }
        if (bef && aft) {
          return JSON.stringify(isDelete ? bef : bbn.fn.diffObj(bef, aft));
        }
      }
    },
    mounted(){
      switch ( this.source.operation ){
        case 'INSERT':
          this.message += bbn._("created");
          break;
        case 'UPDATE':
          this.message += bbn._("modified");
          this.showBefore = true;
          break;
        case 'DELETE':
          this.message += bbn._("deleted");
          break;
        case 'RESTORE':
          this.message += bbn._("restored");
          break;
      }
      this.message += ' ' + bbn._("by") + ' ' + appui.getUserName(this.source.id_user);
    }
  }
})();
