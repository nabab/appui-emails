/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:24
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        status: [{
          text: bbn._("Suspended"),
          value: "suspendu"
        }, {
          text: bbn._("Ready"),
          value: "pret"
        }, {
          text: bbn._("Sent"),
          value: "envoye"
        }, {
          text: bbn._("In progress"),
          value: "en cours"
        }],
        menuSelected: null,
        treeReady: false,
        tableReady: false,
        treePath: ['all']
      }
    },
    computed: {
      menu(){
        return [{
          text: bbn._('All'),
          icon: 'fa fa-envelope-o',
          id: 'all',
          items: [{
            id: 'ready',
            text: bbn._('Ready') + (this.source.count.ready > 0  ? ' (' + this.source.count.ready + ')' : ''),
            icon: 'fa fa-clock-o',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'pret'
            }, {
              field: 'envoi',
              operator: 'neq',
              value: null
            }]
          }, {
            id: 'in_progress',
            text: bbn._('In progress') + (this.source.count.in_progress > 0  ? ' (' + this.source.count.in_progress + ')' : ''),
            icon: 'fa fa-paper-plane-o',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'en cours'
            }, {
              field: 'envoi',
              operator: 'neq',
              value: null
            }]
          }, {
            id: 'sent',
            text: bbn._('Sent') + (this.source.count.sent > 0  ? ' ('+ this.source.count.sent + ')' : ''),
            icon: 'fa fa-envelope',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'envoye'
            }, {
              field: 'envoi',
              operator: 'neq',
              value: null
            }]
          }, {
            id: 'suspended',
            text: bbn._('Suspended') + (this.source.count.suspended > 0  ? ' ('+ this.source.count.suspended + ')' : ''),
            icon: 'fa fa-close',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'suspendu'
            },{
              field: 'envoi',
              operator: 'neq',
              value: null
            }]
          }, {
            id: 'draft',
            text: bbn._('Draft') + (this.source.count.draft > 0  ? ' ('+ this.source.count.draft + ')' : ''),
            icon: 'fa fa-paint-brush',
            filters: [{
              field: 'envoi',
              operator: 'isnull'
            }]
          }]
        }];
      }
    },
    methods: {
      get_field: bbn.fn.get_field,
      renderFiles(row){
        return row.fichiers ?
          '<i class="fa fa-paperclip" style="font-size:medium"></i>' +
          ($.isArray(row.fichiers) ? row.fichiers.length : JSON.parse(row.fichiers).length)
          : '-';
      },
      renderSent(row){
        return (row.statut === 'envoye') && (row.recus !== 0) ?
          Math.round(100 / row.num_accuses * row.recus) + ' %' :
          '-';
      },
      renderButtons(row){
        return [{
          text: bbn._("See"),
          notext: true,
          icon: "fa fa-eye",
          command: this.see
        }, {
          text: bbn._("Open"),
          notext: true,
          icon: "fa fa-th-list",
          command: this.open
        }, {
          text: bbn._("Duplicate"),
          notext: true,
          icon: "fa fa-copy",
          command: this.duplicate
        }, {
					text: bbn._("Suspend"),
					notext: true,
					icon: "fa fa-stop",
					command: this.stop,
					disabled: !!(row.statut !== 'en cours')
				}, {
					text: bbn._("Edit"),
					notext: true,
					icon: "fa fa-edit",
					command: this.edit,
					disabled: !!(row.statut !== 'pret')
				}, {
					text: bbn._("Delete"),
					notext: true,
					icon: "fa fa-trash-o",
					command: this.remove,
					disabled: !!(row.statut !== 'pret')
				}, {
					text: bbn._("Send"),
					notext: true,
					icon: "fa fa-paper-plane-o",
					command: this.send,
					disabled: !!((row.statut !== 'pret') && (row.envoi !== null))
				}];
      },
      insert(){
        return this.$refs.table.insert({}, {
          title: bbn._("New mailing"),
          width: 800
        });
      },
      edit(row, col, idx){
        return this.$refs.table.edit(row, {
          title: bbn._("Mailing edit"),
          width: 800
        }, idx);
      },
      see(row){
        if ( row.id_note ){
          this.popup().open({
            width: 850,
            height: 500,
            title: row.objet,
            component: 'appui-emails-view',
            source: row
          });
        }
        else{
          appui.error(bbn._("Error"));
        }
      },
			open(row){
				bbn.fn.link(this.source.root + 'page/details');
			},
      duplicate(row){
        if ( row.id ){
          bbn.fn.confirm(bbn._("Are you sure you want duplicate this mailing?"), () => {
            bbn.fn.post(this.source.root + "actions/duplicate", {id: row.id}, (d) => {
              if ( d.success ){
                appui.success(bbn._('Duplicated'));
                this.$refs.table.updateData();
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      stop(row){
        if ( row.id ){
          bbn.fn.confirm(bbn._("Are you sure you want suspend this mailing?"), () => {
            bbn.fn.post(this.source.root + "actions/suspend", {id: row.id}, (d) => {
              if ( d.success ){
                row.statut = 'suspendu';
                appui.success(bbn._('Suspended'));
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      remove(row){
        if ( row.id ){
          bbn.fn.confirm(bbn._("Are you sure you want to delete this mailing?"), () => {
            bbn.fn.post(this.source.root + 'actions/delete', {id: row.id}, d => {
              if ( d.success ){
                this.$refs.table.updateData();
                appui.success(bbn._('Deleted'));
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      send(row){
        if ( row.id ){
          bbn.fn.confirm(bbn._("Are you sure you want to send this mailing?"), () => {
            bbn.fn.post(this.source.root + 'actions/send', {id: row.id}, (d) => {
              if ( d.success ){
                this.$refs.table.updateData();
                appui.success(bbn._('Saved'));
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      setFilter(node){
        if ( node.level === 0 ){
          return this.$refs.table.unsetFilter();
        }
        this.$refs.table.currentFilters = {
          conditions: node.data.filters,
          logic: 'AND'
        };
      },
      setSelected(){
        let filters = [];
        for ( let filter of this.$refs.table.currentFilters.conditions ){
          filters.push($.extend({}, filter));
        }
        if ( filters.length ){
          for ( let m of this.menu[0].items ){
            if ( bbn.fn.isSame(filters, m.filters) ){
              this.treePath.push(m.id);
              break;
            }
          }
        }
      }
    },
    created(){
      let emails = this,
          mixins = [{
            data(){
              return {emails: emails}
            }
          }];
      bbn.vue.setComponentRule(this.source.root + 'components/', 'appui-emails');
      bbn.vue.addComponent('form', mixins);
      bbn.vue.addComponent('view', mixins);
      bbn.vue.addComponent('subgrid', mixins);
      bbn.vue.unsetComponentRule();
    }
  }
})();
