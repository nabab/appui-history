// Javascript Document
/* jshint esversion: 6 */
(() => {
  return {
    props: {
      source: {}
    },
    methods: {
      renderUser(r){
        return apst.userName(r.id_user);
      },
      renderChrono(r){
        return bbn.fn.date(r.chrono, true);
      },
      renderOperation(r){
        return '<span class="' + r.operation.toLowerCase() + '">' + r.operation + '</span>';
      },
      getButtons(){
        return [{
          command(data){
            bbn.fn.window("historique_ligne", {history_id: data.history_id});
          },
          text: "Voir"
        },{
          command(data){
            let msg;
            switch ( data.operation ){
              case "INSERT":
                msg = "supprimer cet enregistrement";
                break;
              case "UPDATE":
                msg = "revenir à la valeur précédente";
                break;
              case "DELETE":
                msg = "restaurer cet enregistrement";
                break;
              case "RESTORE":
                msg = "supprimer cet enregistrement à nouveau";
                break;
            }
            bbn.fn.confirm("Voulez-vous vraiment " + msg + "?", function(){
              bbn.fn.post("admin/actions/annulation", {history_id: data.history_id}, function(d){
                if ( d.result ){
                  hist.dataSource.read();
                }
                else{
                  bbn.fn.alert("Cela n'a pas fonctionné...<br><br>Merci de créer un bug en spécifiant l'entrée de l'historique que vous n'avaez pas pu annuler");
                }
              });
            });
          },
          text: "Annuler"
        }];
      }
    }
  };
})();