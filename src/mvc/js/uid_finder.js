// Javascript Document
(() => {
  return {
    data(){
      return {
        uid: '',
        table: '',
        opr: [],
        active: null,
        
      }
    },
    methods: {
      removeAll(){
        this.confirm(bbn._('Are you sure you want to remove all?'), () => {
          this.post(this.source.root + 'actions/delete_all', {
            uid: this.uid, 
         }, (d) => {
            if ( d.success ){
              appui.success(bbn._('Entries successfully removed from db'));
              this.opr = [];
              this.active= null;
              this.table= '';
              this.uid = ''
            }
            else{
              appui.error(bbn._('Something went wrong while deleting all'));
            }
          })
        } );
        
      },
      deleteRow(o, idx){
        this.confirm(bbn._('Are you sure you want to delete this entry from bbn_history?'), () => {
          this.post(this.source.root + 'actions/delete_history_entry', {
         	entry: o, 
          uid: this.uid
        }, (d) => {
          if ( d.success ){
            this.opr.splice(idx,1);
            appui.success(bbn._('Entry successfully deleted from bbn_history'));
          }
          else{
            appui.error(bbn._('Something went wrong while deleting this entry'))
          }
        })
        })
      },
      fdate(date){
        return bbn.fn.fdate(date)
      },
      username(usr){
        return appui.app.getUserName(usr)
      },
      searchUid(){
        if ( this.uid.indexOf('0x') > -1 ){
          appui.error(bbn._('Check the inserted uid'))
        }
        else{
        	if ( this.uid.length === 32) {
            this.post(this.source.root + 'actions/uid_find', {
              uid: this.uid
            }, (d) => {
              if ( d.success ){
                this.table = d.table, 
                this.opr = d.opr, 
                this.active = d.active
              }
              else {
                appui.error(bbn._('Uid not found'))
              }
            });
          } 
          else{
            appui.error(bbn._('Insert an uid before to search'))
          }
        }
        
      }
    }
  }
})();