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
          value: "suspended"
        },{
          text: bbn._("Cancelled"),
          value: "cancelled"
        }, {
          text: bbn._("Ready"),
          value: "ready"
        }, {
          text: bbn._("Sent"),
          value: "sent"
        }, {
          text: bbn._("In progress"),
          value: "sending"
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
        disableTree: false,
        mountedTable: false
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
            icon: 'nf nf-fa-clock_o',
            filters: [{
              field: 'state',
              operator: 'eq',
              value: 'ready'
            }, {
              field: 'sent',
              operator: 'isnotnull'
            }]
          }, {
            id: 'sending',
            text: bbn._('In progress') + (this.source.count.sending > 0  ? ' (' + this.source.count.sending + ')' : ''),
						icon: 'nf nf-fa-spinner',
            filters: [{
              field: 'state',
              operator: 'eq',
              value: 'sending'
            }, {
              field: 'sent',
              operator: 'isnotnull'
            }]
          }, {
            id: 'sent',
            text: bbn._('Sent') + (this.source.count.sent > 0  ? ' ('+ this.source.count.sent + ')' : ''),
            icon: 'nf nf-fa-envelope',
            filters: [{
              field: 'state',
              operator: 'eq',
              value: 'sent'
            }, {
              field: 'sent',
              operator: 'isnotnull'
            }]
          }, {
            id: 'suspended',
            text: bbn._('Suspended') + (this.source.count.suspended > 0  ? ' ('+ this.source.count.suspended + ')' : ''),
            icon: 'nf nf-fa-pause',
            filters: [{
              field: 'state',
              operator: 'eq',
              value: 'suspended'
            }, {
              field: 'sent',
              operator: 'isnotnull'
            }]
          }, {
            id: 'cancelled',
            text: bbn._('Cancelled') + (this.source.count.cancelled > 0  ? ' ('+ this.source.count.cancelled + ')' : ''),
            icon: 'nf nf-fa-close',
            filters: [{
              field: 'state',
              operator: 'eq',
              value: 'cancelled'
            }, {
              field: 'sent',
              operator: 'isnotnull'
            }]
          }, {
            id: 'draft',
            text: bbn._('Draft') + (this.source.count.draft > 0  ? ' ('+ this.source.count.draft + ')' : ''),
            icon: 'nf nf-fa-paint_brush',
            filters: [{
              field: 'state',
              operator: 'eq',
              value: 'ready'
            }, {
              field: 'sent',
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
            field: 'sent',
            dir: 'DESC'
          }];
        }
      }
    },
    methods: {
      get_field: bbn.fn.get_field,
      renderRecipients(row){
        if ( row.recipients ){
          let text = bbn.fn.get_field(this.source.recipients, 'value', row.recipients, 'text');
          return '<a href="listes/liste/' + row.recipients+'">'+text+'</a>'
        }
        
      },
      renderFiles(row){
        return row.fichiers ?
          (bbn.fn.isArray(row.fichiers) ? row.fichiers.length : JSON.parse(row.fichiers).length)
          : '-';
      },
      renderSent(row){
        return (row.state === 'sent') && (row.recus !== 0) ?
          Math.round(100 / row.num_accuses * row.recus) + ' %' :
          '-';
      },
      renderButtons(row){
        let res = [{
          text: bbn._("See"),
          notext: true,
          icon: "nf nf-fa-eye",
          command: this.see
        },{
          text: bbn._('Send this email to') + ' ' + appui.app.user.name,
          notext: true,
          icon: "nf nf-fa-envelope",
          command: this.selfSend
        },{
            text: bbn._("Duplicate"),
            notext: true,
            icon: "nf nf-fa-copy",
            command: this.duplicate
          }];
        if ( ['sent', 'sending', 'suspended', 'cancelled'].includes(row.state) && (row.num_accuses > 0)){
          res.push({
            text: bbn._("Open"),
            notext: true,
            icon: "nf nf-fa-th_list",
            command: this.open
          });
        }
        
        
        if ( (row.state === 'ready') ){
          res.push({
            text: bbn._("Edit"),
            notext: true,
            icon: "nf nf-fa-edit",
            command: this.edit
          },{
            text: bbn._("Send"),
            notext: true,
            icon: "nf nf-fa-paper_plane",
            command: this.send
          });
        }
        if ( (row.state === 'ready') || (row.state === 'cancelled') ){
          res.push({
            text: bbn._("Delete"),
            notext: true,
            icon: "nf nf-oct-trashcan",
            command: this.remove
          })
        }
        
        if( (row.state === 'ready') && (row.sent === null) ){
          res.push({
            text: bbn._("Send"),
            notext: true,
            icon: "nf nf-fa-paper_plane",
            command: this.send
          });
        }
        if ( row.state === 'suspended' ){
          res.push({
            text: bbn._("Reactivate mailing"),
            notext: true,
            icon: "nf nf-fa-play_circle_o",
            command: this.play
          });
        }
        if ( row.state !== 'sending' ){
          res.push({
            text: bbn._("Test"),
            notext: true,
            icon: "nf nf-fa-magic",
            command: this.test
          });
        }
        else {
          res.push({
            icon: 'nf nf-mdi-close',
            title: 'Cancel mailing',
            command: this.cancelMailing
          },{
            text: bbn._("Suspend"),
            notext: true,
            icon: "nf nf-fa-stop_circle_o",
            command: this.stop
          })  
        }
        
        return res;
      },
      cancelMailing(row, obj, idx){
        bbn.fn.log(arguments)
        this.confirm(bbn._('Do you really want to cancel this mailing'), () => {
          this.post('emails/actions/mailing/cancel', {
            id: row.id,
            state: row.state  
          }, (d) => {
            if ( d.success ){
              this.getRef('table').currentData[idx].data.state = 'cancelled'
              appui.success(bbn._('Mailing successfully cancelled'));
            }
            else {
              appui.error(bbn._('Something went wrong while cancelling the mailing'));
            }
          });
        })
      },
      selfSend(row){
        this.confirm(bbn._('Do you really want to send this email to') + ' ' + appui.app.user.name, () => {
          this.post('emails/actions/test', {
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
            title: row.title,
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
        bbn.fn.link('listes/liste/' + row.recipients);
      },
      duplicate(row, ob){
        if ( row.id ){
          appui.confirm(bbn._("Are you sure you want duplicate this mailing?"), () => {
            this.post(this.source.root + "actions/duplicate", {id: row.id}, (d) => {
              if ( d.success && d.id ){
                if ( d.count ){
                  this.source.count = d.count;
                }
                //gets all the nodes to find the one with text  'draft'
                let nodes = this.findAll('bbn-tree-node'),
                  idx = bbn.fn.search(nodes, 'data.id', 'draft')
                if ( idx > -1 ){
                  this.setFilter(nodes[idx]);
                  let copiedRow = bbn.fn.clone(row);
                  copiedRow.id = d.id;
                  copiedRow.state = 'ready'
                  copiedRow.sent = null; 
                  this.getRef('table').edit(copiedRow);
                }
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      stop(row, obj, idx){
        if ( row.id ){
          appui.confirm(bbn._("Are you sure you want suspend this mailing?"), () => {
            this.post(this.source.root + "actions/mailing/change_state", {
              id: row.id,
              state: 'suspended'
            }, (d) => {
              if ( d.success ){
								if ( d.count ){
                  this.source.count = d.count;
                }
                this.getRef('table').currentData[idx].data.state = 'suspended';
                appui.success(bbn._('Suspended'));
              }
              else {
                appui.error(bbn._('Error'));
              }
            });
          });
        }
      },
      play(row, obj, idx){
        if ( row.id ){
          appui.confirm(bbn._("Are you sure you want to reactivate this mailing?"), () => {
            this.post(this.source.root + "actions/mailing/change_state", {
              id: row.id,
              state: 'ready'
            }, (d) => {
              if ( d.success ){
								if ( d.count ){
                  this.source.count = d.count;
                }
                this.getRef('table').currentData[idx].data.state = 'ready';
                appui.success(bbn._('Mailing reactivated'));
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
            this.post(this.source.root + 'actions/mailing/delete', row, d => {
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
            this.post(this.source.root + 'actions/send', {id: row.id}, (d) => {
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
            scrolable: false,
            width: 400,
            height: 600,
            component: 'appui-emails-test',
            source: {
              users: [],
              id: row.id
            }
          });
        }
      },
      setFilter(node){
        if ( this.mountedTable ){
          if ( node.level === 0 ){
            let idx = bbn.fn.search(this.getRef('table').currentOrder, {field: 'bbn_notes_versions.creation'});
            if ( idx > -1 ){
              this.getRef('table').$set(this.getRef('table').currentOrder[idx], 'field', 'sent');
            }
            this.treePath = ['all'];
            if ( this.$refs.table !== undefined ){
              return this.$refs.table.unsetFilter();
            }
          }
          let idx = bbn.fn.search(this.getRef('table').currentOrder, { field: 'sent' });
          if ( idx > -1 ){
            this.getRef('table').$set(this.getRef('table').currentOrder[idx], 'field', 'bbn_notes_versions.creation');
          }
          this.treePath = ['all', node.data.id];
          this.$refs.table.currentFilters = {
            conditions: node.data.filters,
            logic: 'AND'
          };
        }
        
      },
      setSelected(){
        let filters = [];
        for ( let filter of this.$refs.table.currentFilters.conditions ){
          filters.push(bbn.fn.extend({}, filter));
        }
        if ( filters.length ){
          for ( let m of this.menu[0].items ){
            if ( bbn.fn.isSame(filters, m.filters) ){
              this.treePath.push(m.id);
              break;
            }
          }
        }
        this.mountedTable = true;
      },
      openLettersTypesTab() {
        bbn.fn.link(this.source.root + 'page/types');
      },
      openEmailsTab() {
        bbn.fn.link(this.source.root + 'page/emails');
      },
      openEmailsSentTab(){
        bbn.fn.link(this.source.root + 'page/sent');
      },
      fixDate(d){
        return moment(d).format('DD/MM/YYYY HH:mm:ss');
      },
      setGetInfo(){
        this.info.getInfo = setInterval(() => {
          this.post(this.source.root + 'data/info', {updateCount: this.updateCount === 3}, d => {
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
