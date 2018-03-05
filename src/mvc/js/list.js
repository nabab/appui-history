// Javascript Document
/* jshint esversion: 6 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        users: bbn.fn.order(bbn.users, 'text', 'ASC')
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
      renderAdh(row){
        if( row.id_adh && row.adh ){
          return '<a class="' + appui.app.get_adherent_class(row.adh_statut) + '"href="adherent/fiche/' + row.id_adh + '">' + row.adh + '</a>';
        }
        else {
          return row.adh;
        }
      },
      renderDate(r){
        return moment(r.dt).format('DD/MM/YYYY HH:mm:ss');
      },
      renderUser(r){
        return appui.app.userName(r.usr);
      },
      renderOperation(r){
        return '<span class="' + r.opr.toLowerCase() + '">' + r.opr + '</span>';
      },
      renderButtons(r){
        return [{
          command: this.seen,
          icon: 'fa fa-eye',
          text: bbn._('Seen'),
          notext: true,
          disabled: (r.opr === 'DELETE') || (r.opr === 'RESTORE')
        }, {
          command: this.undo,
          icon: 'fa fa-trash',
          text: bbn._('Cancel'),
          notext: true
        }];
      },
      seen(r){
        bbn.vue.closest(this, 'bbn-tab').popup().load({
          url: this.source.root + 'detail',
          data: {
            uid: r.uid,
            col: r.col,
            tst: r.tst,
            usr: r.usr
          },
          height: 200,
          width: 700
        });
      },
      undo(r){
        let msg;
        switch ( r.opr ){
          case "INSERT":
            msg = bbn._("supprimer cet enregistrement");
            break;
          case "UPDATE":
            msg = bbn._("revenir à la valeur précédente");
            break;
          case "DELETE":
            msg = bbn._("restaurer cet enregistrement");
            break;
          case "RESTORE":
            msg = bbn._("supprimer cet enregistrement à nouveau");
            break;
        }
        bbn.fn.confirm(bbn._("Voulez-vous vraiment ") + msg + "?", () => {
          bbn.fn.post(this.source.root + "actions/cancel", {
            uid: r.uid,
            col: r.col,
            tst: r.tst,
            usr: r.usr
          }, (d) => {
            if ( d.success ){
              this.$refs.table.updateData();
            }
            else {
              bbn.fn.alert(bbn._("Cela n'a pas fonctionné...") + ' <br><br>' + bbn._("Merci de créer un bug en spécifiant l'entrée de l'historique que vous n'avaez pas pu annuler"));
            }
          });
        });
      }
    }
  };
})();