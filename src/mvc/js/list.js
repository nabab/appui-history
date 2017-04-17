// Javascript Document

var tables = [];
for ( var i = 0; i < appui.apst.tables.length; i++ ){
  tables.push({value: appui.apst.tables[i], text: appui.apst.tables[i].substr(5).replace('_', ' ').toTitleCase()});
}

var hist = $("#historique_table").kendoGrid({
  culture: "fr",
  sortable: true,
  scrollable: true,
  pageable: {
    pageSizes: [10, 25, 50, 100, 250],
    refresh: true,
    input: true
  },
  filterable: {
    mode: "row",
    extra: false
  },
  dataSource: {
    serverSorting: true,
    serverFiltering: true,
    serverPaging: true,
    pageSize: 50,
    sort: {
      field: "chrono",
      dir: "desc"
    },
    transport: {
      read: function(e) {
        if ( e.data.filter && e.data.filter.filters ){
          e.data.filter = bbn.fn.correctGridPost(e.data.filter);
        }
        bbn.fn.post("admin/historique", e.data, function(d){
          e.success(d);
        });
      },
    },
    schema:{
      data: "data",
      total: "total",
      model: {
        id: "history_id",
        fields: [{
          field: "history_id",
          type: "number"
        }, {
          field: "chrono",
          type: "number"
        }, {
          field: "operation",
          type: "string"
        }, {
          field: "id_user",
          type: "string"
        }, {
          field: "user",
          type: "string"
        }, {
          field: "tab",
          type: "string"
        }, {
          field: "col",
          type: "string"
        }]
      }
    },
  },
  columns: [
  {
    field: "chrono",
    width: 130,
    title: "Date",
    template: function(d){
      return bbn.fn.fdate(d.chrono, 1);
    },
  }, {
    field: "adh",
    title: "Élément",
    sortable: false,
    filterable: false,
    menu: false,
    template: function(d){
      if ( d.id_adh ){
	      return '<a href="adherent/fiche/' + d.id_adh + '">' + d.adh + '</a>';
      }
      return d.adh;
    },
  }, {
    field: "operation",
    width: 120,
    values: apst.historiques,
    title: "Opération",
    template: function(d){
      return apst.historique_type(d);
    }
  }, {
    field: "tab",
    title: "Table",
    values: tables,
  }, {
    field: "col",
    title: "Colonne(s)",
    sortable: false,
  }, {
    values: appui.apst.users,
    field: "id_user",
    width: 200,
    title: "Auteur",
  }, {
    title: "Actions",
    filterable: false,
    field: "history_id",
    width: 160,
    command: [{
      click: function(e){
        var tr = $(e.target).closest("tr"),
          history_id = this.dataItem(tr).history_id;
        bbn.fn.window("historique_ligne", {history_id: history_id});
      },
      text: "Voir"
    },{
      click: function(e){
        var tr = $(e.target).closest("tr"),
            data = this.dataItem(tr).toJSON(),
            msg;
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
        })
      },
      text: "Annuler"
    }]
  }],
}).data("kendoGrid");
