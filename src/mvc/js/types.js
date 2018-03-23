/*
// Javascript Document
(function(){
  return function(ele, data){
    $("#SbYBOLDj3q").kendoGrid({
      columns: [{
        title: "ID",
        field: "value",
        width: 100,
        hidden: data.is_dev ? false : true,
      }, {
        title: "Type de lettre",
        field: "text",
      }],
      dataSource: {
        data: bbn.opt.ltypes,
        sort: {
          field: "text",
          dir: "asc"
        }
      },
      detailInit: function(e){
        bbn.fn.post("com/lettres_types", e.data.toJSON(), function(d){
          var table = $("<div/>"),
              $table = table.appendTo(e.detailCell).kendoGrid({
            columns: [{
              title: "Modèle",
              field: "categorie",
              hidden: true
            }, {
              title: "Nom",
              field: "nom",
              width: 200
            }, {
              title: "Objet",
              field: "titre"
            }, {
              title: "Défaut",
              field: "defaut",
              width: 50,
              sortable: false,
              template: function(e){
                var cls = e.defaut ? 'adherent' : 'radie bbn-p';
                return '<i class="fa fa-check ' + cls + '" style="font-size:medium"> </i>';
              }
            }, {
              field: "texte",
              encoded: true,
              title: 'Texte',
              hidden: true,
              editor: function (container, options) {
                var ele = $('<textarea name="' + options.field + '" style="min-width:500px"></textarea>').height(bbn.env.height-300);
                ele.appendTo(container).kendoEditor();
              }
            }, {
              title: "&nbsp;",
              field: "id",
              width: 250,
              command: [{
                name: "edit",
                text: {
                  cancel: "Annuler",
                  update: "Modifier",
                  edit: "Mod."
                }
              }, {
                name: "destroy",
                text: "Suppr."
              }, {
                text: "Duppliquer",
                //className: "fa fa-copy",
                click: function(e){
                  var data = $table.dataItem($(e.target).closest("tr"));
                  bbn.fn.post('com/lettres_types', {action: 'copy', id: data.id}, function(data){
                    d = data;
                    $table.dataSource.read();
                  });
                }
              }]
            }],
            toolbar: [{
              name: "create",
              text: "Nouveau modèle pour " + e.data.text
            }],
            dataSource: {
              transport: {
                parameterMap: function(a){
                  if ( a.models && (a.models[0] !== undefined) ){
                    return a.models[0];
                  }
                  return a;
                },
                read: function(e){
                  e.success(d);
                },
                create: function(e) {
                  bbn.fn.post("com/lettres_types", e.data, function(d){
                    e.success(d);
                  });
                },
                update: function(e) {
                  bbn.fn.post("com/lettres_types", e.data, function(d){
                    e.success(d);
                  });
                },
                destroy: function(e) {
                  bbn.fn.confirm( "Etes-vous sur de vouloir supprimer cette lettre-type?", function(){
                    bbn.fn.post("com/lettres_types", {id: e.data.id}, function(d) {
                      e.success(d);
                    })
                  })
                }
              },
              schema: {
                data: "data",
                model: {
                  id: "id",
                  fields: {
                    nom: {
                      type: "string",
                      validation: {
                        required: true
                      }
                    },
                    titre: {
                      type: "string",
                      validation: {
                        required: true
                      }
                    },
                    categorie: {
                      defaultValue: e.data.value,
                      editable: false
                    },
                    defaut: {
                      defaultValue: 1,
                      editable: false
                    },
                    texte: {
                      type: "string",
                      validation: {
                        required: true
                      }
                    }
                  }
                }
              },
              pageSize: 50
            },
            editable: {
              mode: "popup",
              window: {
                width: bbn.env.width - 100,
                height: bbn.env.height - 100
              }
            },
            sortable: true,
            edit: function (e) {
              bbn.fn.hideUneditable(e);
              e.container
                .find("label[for=texte]")
                .after(' &nbsp; <i style="font-size: medium" class="bbn-p fa fa-question"' +
                  ' onclick="bbn.fn.post(\'com/lettre_explication\', function(d){bbn.fn.popup(d.content, d.title, ' + (bbn.env.width - 100) + ', ' + (bbn.env.height - 100) + ');});"> </i>');
              bbn.fn.analyzeContent(e.container);
              e.container
                .parent()
                .find(".k-window-title:first")
                .html(e.model.id > 0 ? "Nouvelle lettre type" : "Modification de lettre type");
            },
            dataBound: function(e){
              $(e.sender.element).find("tbody tr").each(function(){
                var $tr = $(this),
                    dt = e.sender.dataItem($tr);
                if ( dt.defaut ){
                  $tr.find("a.k-grid-delete").hide();
                }
                $tr.find("i.fa-check").click(function(){
                  var $t = $(this),
                      data = e.sender.dataItem($tr);
                  if ( $t.hasClass("radie") ){
                    bbn.fn.post('com/lettres_types', {id: data.id, defaut: 1, action: 'defaut'}, function(d){
                      if ( d.id ){
                        e.sender.element.find("i.fa-check.adherent").removeClass('adherent').addClass('radie').addClass('bbn-p').closest("tr").find("a.k-grid-delete").show();
                        $t.addClass('adherent').removeClass('bbn-p');
                        $tr.find("a.k-grid-delete").hide();
                      }
                    });
                  }
                  else{
                    return false;
                  }
                });
              });
            }
          }).data("kendoGrid");
        });
      }
    });
    bbn.fn.popup($("#lettres_types_avertissement").html(), "Avertissement sur les lettres types", "60%");
  }
})();
*/
(() => {
  return {
    props: ['source'],
    methods: {
      table_buttons(row){
        let res = [{
          text: "Mod.",
          icon: "fa fa-edit",
          command: this.edit,
        }];
        if ( row.default === 0){
          res.push({
            text: "Suppr.",
            icon: "fa fa-trash",
            command: this.remove
          });
        }
        return res;
      },
      render_user(row){
        return appui.app.getUserName(row.id_user)
      },
      edit(row){
        let tab = bbn.vue.closest(this, 'bbn-tab');
        bbn.fn.post('notes/actions/get', { id_note : row.id_note, id_type: row.id_type, 'default': row.default, type: row.type } , (d) => {
          if ( d.success ){
            bbn.vue.find(tab, 'bbn-popup').open({
              width: 700,
              height: 800,
              component: this.$options.components.form_lettres_types,
              source: d.note,
              title: row.id_note ? bbn._("Modification de la lettre-type") : ''
            })
          }
        })
      },
      remove(row){
        bbn.fn.confirm(bbn._("Souhaitez-vous vraiment supprimer cette lettre-type?"), () => {
          bbn.fn.post('com/lettres_types', { action : 'delete', id_note: row.id_note}, (d) => {
            if ( d.success ){
              this.$refs.table.remove(row);

            }
          });
        });
      },
    },
    mounted(){
      this.$nextTick( () => {
        this.popup({
          width: 850,
          height: 200,
          title: bbn._("Avertissement sur les lettres types"),
          content: '<div class="bbn-padded"><div class="bbn-b">Attention!</div><br>Ici vous pouvez modifier les lettres types mais elles utilisent un système de "templates" avec lequel il vous faut être très précautionneu. Le mieux est de dupliquer une lettre-type existante et de la modifier. Une fois terminée, mettez-là en défaut si elle est utilisée sur une fonctionnalité sans choix (ex: attestations), et allez la tester dans son contexte. Alors vous pourrez effacer l\'ancienne ou bien la refaire passer en défaut si votre modification renvoie une erreur.</div>'
        });
      });
    },
    components: {
      def: {
        name: 'def',
        ref: 'def_button',
        props: ['source'],
        template: `
          <i v-if="source.default" :key="source.id_note" class="fa fa-check bbn-lg bbn-green"></i>
          <bbn-button  :key="source.id_note" v-else icon="fa fa-check bbn-lg bbn-red" @click="makeDefault"></bbn-button>`,
        methods: {
          makeDefault(source){
            let table = bbn.vue.closest(this, 'bbn-table'),
              idx = bbn.fn.search(table.currentData, {id_type: this.source.id_type, default: 1});
            bbn.fn.post('com/lettres_types', { action : 'defaut', id_note: this.source.id_note, id_type: this.source.id_type}, (d) => {
              if ( d.success ){
                this.source.default = 1;
                if ( idx > -1 ){
                  table.$set(table.currentData[idx], 'default', 0);
                }
                bbn.vue.closest(this, 'bbn-tab').getComponent().$refs.table.$forceUpdate();

              }
            });
          }
        }
      },
      form_lettres_types: {
        name: 'form_lettres_types',
        props: ['source'],
        template: '\n' +
        '  <bbn-form class="bbn-full-screen" :source="source" ref="form" @success="success"' +
        ' :action="actions">\n' +
        '    <div class="bbn-grid-fields bbn-padded">\n' +
        '      <label>Objet</label>\n' +
        '      <bbn-input v-model="source.title"></bbn-input>\n' +
        '\n' +
        '      <label>Text</label>\n' +
        '      <bbn-rte v-model="source.content"></bbn-rte>\n' +
        '    </div>\n' +
        '  </bbn-form>',
        methods: {
         success(d){
           let tab = bbn.vue.closest(this, 'bbn-tab'),
             table = tab.getComponent().$refs.table;
           if ( d.success ){
             if ( this.actions === "com/lettres_types/update" ){
               let idx = bbn.fn.search(table.source, { id_note : d.data.id_note});
               this.$nextTick(() => {
                 table.source[idx] = d.data;
                 table.updateData();
               })
             }
             else if ( this.actions === "com/lettres_types/insert" ){
               tab.getComponent().source.categories.push(d.data);
               table.updateData();
             }
           }
         }
        },
        computed: {
          actions(){
            return this.source.id_note ? 'com/lettres_types/update' : 'com/lettres_types/insert';
          }
        }
      },
      cat: {
        props: ['source'],
        template: `
          <div class="bbn-w-100">
            <div class="bbn-block">
              <span v-text="source.type"></span>
              (<span v-text="num"></span>)
            </div>
            <div class="bbn-block" style="float: right">
              <bbn-button @click="insert"
                          icon="fa fa-plus"
                          :text="_('Ajouter une lettre type')"
              ></bbn-button>
            </div>
          </div>`,
        computed:{
          num(){
	          return bbn.fn.count(this.getTable().currentData, {id_type: this.source.id_type});
          }
        },
        methods: {
          getTable(){
            return bbn.vue.closest(this, 'bbn-table');
          },
          insert(){
            let tab = bbn.vue.closest(this, 'bbn-tab').getComponent();
            tab.$refs.table.getPopup().open({
              width: 700,
              height: 800,
              source: {
                id_type: this.source.id_type,
                title: '', content: '',
                type: this.source.type,
                content: '',
                id_user: this.source.id_user,
                id_note: '',
                'default': this.source.default
              },
              component: tab.$options.components.form_lettres_types,
              title: bbn._("Création d'une lettre-type")
            });

          }
	      }
      }
    },
  };
})();