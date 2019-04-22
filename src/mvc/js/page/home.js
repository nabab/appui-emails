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
        updateCount: 0,
        disableTree: false
      }
    },
    computed: {
      menu(){
        return [{
          text: bbn._('All'),
          icon: 'nf nf-fa-envelope',
          id: 'all',
          items: [{
            id: 'ready',
            text: bbn._('Ready') + (this.source.count.ready > 0  ? ' (' + this.source.count.ready + ')' : ''),
            icon: 'nf nf-fa-clock',
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
						icon: 'nf nf-fa-spinner',
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
            icon: 'nf nf-fa-envelope',
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
            icon: 'nf nf-fa-pause',
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
            icon: 'nf nf-fa-paint_brush',
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
      },
      getOrder(){
        if ( this.treePath.includes('draft') ){
          return [{
            field: 'bbn_notes_versions.creation',
            dir: 'DESC'
          }];
        }
        else {
          return [{
            field: 'envoi',
            dir: 'DESC'
          }];
        }
      }
    },
    methods: {
      get_field: bbn.fn.get_field,
      renderFiles(row){
        return row.fichiers ?
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
          icon: "nf nf-fa-eye",
          command: this.see
        }];
        if ( (row.statut === 'envoye') || (row.statut === 'en cours') || (row.statut === 'suspendu') ){
         res.push({
           text: bbn._("Open"),
           notext: true,
           icon: "nf nf-fa-th_list",
           command: this.open
         });
        }
        if ( (row.statut === 'pret') ){
         res.push({
           text: bbn._("See recipients"),
           notext: true,
           icon: "nf nf-fa-th_list",
           command: this.openList
         });
        }
        res.push({
          text: bbn._("Duplicate"),
          notext: true,
          icon: "nf nf-fa-copy",
          command: this.duplicate
        });
        if ( row.statut === 'en cours' ){
          res.push({
            text: bbn._("Suspend"),
            notext: true,
            icon: "nf nf-fa-hand_paper",
            command: this.stop
          });
        }
        if ( (row.statut === 'pret') ){
          res.push({
            text: bbn._("Edit"),
            notext: true,
            icon: "nf nf-fa-edit",
            command: this.edit
          }, {
            text: bbn._("Delete"),
            notext: true,
            icon: "nf nf-fa-trash",
            command: this.remove
          });
        }
        if( (row.statut === 'pret') && (row.envoi === null) ){
          res.push({
            text: bbn._("Send"),
            notext: true,
            icon: "nf nf-fa-paper_plane",
            command: this.send
          });
        }
        if ( row.statut !== 'en cours' ){
          res.push({
            text: bbn._("Test"),
            notext: true,
            icon: "nf nf-fa-magic",
            command: this.test
          });
        }
        res.push({
          text: bbn._('Send this email to ') + appui.app.user.name,
          notext: true,
          icon: "nf nf-fa-envelope",
          command: this.selfSend
        });
        return res;
      },
      selfSend(row){
        this.confirm(bbn._('Do you really want to send this email to') + ' ' + appui.app.user.name, () => {
          bbn.fn.post('emails/actions/test', {
            id: row.id,
            users: appui.app.user.id
          }, (d) => {
            if ( d.success ){
              appui.success(bbn._('Email sent'));
            }
          });
        })
      }, 
      insert(){
        return this.$refs.table.insert({}, {
          title: bbn._("New mailing"),
          width: this.getPopup().defaultWidth,
          height: this.getPopup().defaultHeight
        });
      },
      
      edit(row, col, idx){
        return this.$refs.table.edit(row, {
          title: bbn._("Mailing edit"),
          width: this.getPopup().defaultWidth,
          height: this.getPopup().defaultHeight
        }, idx);
      },
      see(row){
        if ( row.id_note ){
          this.getPopup().open({
            width: 1050,
            height: "90%",
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
      openList(row){
        bbn.fn.link('listes/liste/' + row.destinataires);
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
      test(row){
        if ( row.id ){
          this.getPopup().open({
            title: bbn._('Select users'),
            width: 350,
            height: 500,
            component: 'appui-emails-test',
            source: {
              users: null,
              id: row.id
            }
          });
        }
      },
      setFilter(node){
        if ( node.level === 0 ){
          let idx = bbn.fn.search(this.getRef('table').currentOrder, {field: 'bbn_notes_versions.creation'});
          if ( idx > -1 ){
            this.getRef('table').$set(this.getRef('table').currentOrder[idx], 'field', 'envoi');
          }
          this.treePath = ['all'];
          if ( this.$refs.table !== undefined ){
            return this.$refs.table.unsetFilter();
          }
        }
        let idx = bbn.fn.search(this.getRef('table').currentOrder, { field: 'envoi' });
        if ( idx > -1 ){
          this.getRef('table').$set(this.getRef('table').currentOrder[idx], 'field', 'bbn_notes_versions.creation');
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
      openLettersTypesTab() {
        bbn.fn.link(this.source.root + 'page/types');
      },
      openEmailsTab() {
        bbn.fn.link(this.source.root + 'page/emails');
      },
      fixDate(d){
        return moment(d).format('DD/MM/YYYY HH:mm:ss');
      },
      setGetInfo(){
        this.info.getInfo = setInterval(() => {
          bbn.fn.post(this.source.root + 'data/info', {updateCount: this.updateCount === 3}, d => {
            if ( d.success ){
              this.info.current.id = d.data.current.id || '';
              this.info.current.title = d.data.current.title || '';
              this.info.current.idRecipients = d.data.current.recipients || '';
              this.info.current.sent = d.data.current.sent || 0;
              this.info.current.moment = d.data.current.moment || '';
              this.info.next.id = d.data.next.id || '';
              this.info.next.title = d.data.next.title || '';
              this.info.next.idRecipients = d.data.next.recipients || '';
              this.info.next.moment = d.data.next.moment || '';
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
      /* if ( Vue.options.components['appui-emails-form'] !== undefined ){
        delete Vue.options.components['appui-emails-form'];
      }
      if ( Vue.options.components['appui-emails-view'] !== undefined ){
        delete Vue.options.components['appui-emails-view'];
      } */
    },
		mounted(){
      this.clearGetInfo();
			//this.setGetInfo();
		},
		beforeDestroy(){
			this.clearGetInfo();
		}
  }
})();
