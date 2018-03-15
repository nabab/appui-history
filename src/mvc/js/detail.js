(() => {
  return {
    data(){
      return {
        message: bbn._("L'entrée a été "),
        showBefore: false
      }
    },
    computed: {
      afterText(){
        return this.source.after === null ? 'NULL' : this.source.after;
      },
      beforeText(){
        return this.source.before === null ? 'NULL' : this.source.before;
      }
    },
    mounted(){
      switch ( this.source.operation ){
        case 'INSERT':
          this.message += bbn._("rajoutée");
          break;
        case 'UPDATE':
          this.message += bbn._("modifiée");
          this.showBefore = true;
          break;
        case 'DELETE':
          this.message += bbn._("supprimée");
          break;
        case 'RESTORE':
          this.message += bbn._("restaurée");
          break;
      }
      this.message += bbn._(" par ") + appui.getUserName(this.source.id_user);
    }
  }
})();