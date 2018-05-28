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
        treePath: ['all'],
				info: {
          current: {
            id: '',
            title: '',
            idRecipients: '',
            recipients: '',
            sent: 0,
            moment: ''
          },
          next: {
            id: '',
            title: '',
            idRecipients: '',
            recipients: '',
            moment: ''
          },
					getInfo: false
				},
        updateCount: 0
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
              operator: 'isnotnull'
            }]
          }, {
            id: 'in_progress',
            text: bbn._('In progress') + (this.source.count.in_progress > 0  ? ' (' + this.source.count.in_progress + ')' : ''),
						icon: 'fa fa-spinner',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'en cours'
            }, {
              field: 'envoi',
              operator: 'isnotnull'
            }]
          }, {
            id: 'sent',
            text: bbn._('Sent') + (this.source.count.sent > 0  ? ' ('+ this.source.count.sent + ')' : ''),
            icon: 'fa fa-send-o',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'envoye'
            }, {
              field: 'envoi',
              operator: 'isnotnull'
            }]
          }, {
            id: 'suspended',
            text: bbn._('Suspended') + (this.source.count.suspended > 0  ? ' ('+ this.source.count.suspended + ')' : ''),
            icon: 'fa fa-pause',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'suspendu'
            }, {
              field: 'envoi',
              operator: 'isnotnull'
            }]
          }, {
            id: 'draft',
            text: bbn._('Draft') + (this.source.count.draft > 0  ? ' ('+ this.source.count.draft + ')' : ''),
            icon: 'fa fa-paint-brush',
            filters: [{
              field: 'statut',
              operator: 'eq',
              value: 'pret'
            }, {
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
        let res = [{
          text: bbn._("See"),
          notext: true,
          icon: "fa fa-eye",
          command: this.see
        }];
        if ( (row.statut === 'envoye') || (row.statut === 'en cours') || (row.statut === 'suspendu') ){
         res.push({
           text: bbn._("Open"),
           notext: true,
           icon: "fa fa-th-list",
           command: this.open
         });
        }
        res.push({
          text: bbn._("Duplicate"),
          notext: true,
          icon: "fa fa-copy",
          command: this.duplicate
        });
        if ( row.statut === 'en cours' ){
          res.push({
            text: bbn._("Suspend"),
            notext: true,
            icon: "fa fa-hand-stop-o",
            command: this.stop
          });
        }
        if ( (row.statut === 'pret') ){
          res.push({
            text: bbn._("Edit"),
            notext: true,
            icon: "fa fa-edit",
            command: this.edit
          }, {
            text: bbn._("Delete"),
            notext: true,
            icon: "fa fa-trash-o",
            command: this.remove
          });
        }
        if( (row.statut === 'pret') && (row.envoi === null) ){
          res.push({
            text: bbn._("Send"),
            notext: true,
            icon: "fa fa-paper-plane-o",
            command: this.send
          });
        }
        return res;
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
        if ( row.id ){
          bbn.fn.link(this.source.root + 'page/details/' + row.id);
        }
			},
      duplicate(row){
        if ( row.id ){
          appui.confirm(bbn._("Are you sure you want duplicate this mailing?"), () => {
            bbn.fn.post(this.source.root + "actions/duplicate", {id: row.id}, (d) => {
              if ( d.success ){
                if ( d.count ){
                  this.source.count = d.count;
                }
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
          appui.confirm(bbn._("Are you sure you want suspend this mailing?"), () => {
            bbn.fn.post(this.source.root + "actions/suspend", {id: row.id}, (d) => {
              if ( d.success ){
								if ( d.count ){
                  this.source.count = d.count;
                }
                row.statut = 'suspendu';
                appui.success(bbn._('Suspended'));
								this.$refs.table.updateData();
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
          appui.confirm(bbn._("Are you sure you want to delete this mailing?"), () => {
            bbn.fn.post(this.source.root + 'actions/delete', {id: row.id}, d => {
              if ( d.success ){
                if ( d.count ){
                  this.source.count = d.count;
                }
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
          appui.confirm(bbn._("Are you sure you want to send this mailing?"), () => {
            bbn.fn.post(this.source.root + 'actions/send', {id: row.id}, (d) => {
              if ( d.success ){
                if ( d.count ){
                  this.source.count = d.count;
                }
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
          this.treePath = ['all'];
          return this.$refs.table.unsetFilter();
        }
        this.treePath = ['all', node.data.id];
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
      },
      openLettersTypes(){
        bbn.fn.link(this.source.root + 'page/types');
      },
      fixDate(d){
        return moment(d).format('DD/MM/YYYY HH:mm:ss');
      },
      setGetInfo(){
        this.info.getInfo = setInterval(() => {
          bbn.fn.post(this.source.root + 'data/info', {updateCount: this.updateCount === 3}, d => {
            if ( d.success ){
              this.info.current.id = d.data.current.id;
              this.info.current.title = d.data.current.title;
              this.info.current.idRecipients = d.data.current.recipients;
              this.info.current.sent = d.data.current.sent;
              this.info.current.moment = d.data.current.moment;
              this.info.next.id = d.data.next.id;
              this.info.next.title = d.data.next.title;
              this.info.next.idRecipients = d.data.next.recipients;
              this.info.next.moment = d.data.next.moment;
              this.updateCount++;
              if ( this.updateCount > 3 ){
                this.updateCount = 0;
                if ( d.count ){
                  this.source.count = d.count;
                }
              }
            }
          });
        }, 3000);
      },
			clearGetInfo(){
				if ( this.info.getInfo ){
					clearInterval(this.info.getInfo);
					this.info.getInfo = false;
				}
			},
      toggleGetInfo(){
        if ( this.info.getInfo ){
          this.clearGetInfo();
        }
        else {
          this.setGetInfo();
        }
      }
    },
    watch: {
      'info.current.idRecipients'(newVal){
        if ( newVal && this.source.recipients ){
          this.info.current.recipients = bbn.fn.get_field(this.source.recipients, 'value', this.info.current.idRecipients, 'text');
        }
        else {
          this.info.current.recipients = '';
        }
      },
      'info.next.idRecipients'(newVal){
        if ( newVal && this.source.recipients ){
          this.info.next.recipients = bbn.fn.get_field(this.source.recipients, 'value', this.info.next.idRecipients, 'text');
        }
        else {
          this.info.next.recipients = '';
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
      if ( Vue.options.components['appui-emails-form'] !== undefined ){
        delete Vue.options.components['appui-emails-form'];
      }
      if ( Vue.options.components['appui-emails-view'] !== undefined ){
        delete Vue.options.components['appui-emails-view'];
      }
      bbn.vue.setComponentRule(this.source.root + 'components/', 'appui-emails');
      bbn.vue.addComponent('form', mixins);
      bbn.vue.addComponent('view', mixins);
      bbn.vue.unsetComponentRule();
    },
		mounted(){
			this.clearGetInfo();
			this.setGetInfo();

		},
		beforeDestroy(){
			this.clearGetInfo();
		}
  }
})();
