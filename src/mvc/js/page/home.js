/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:24
 */
(() => {
  let mailings;
  return {
    props: ['source'],
    data(){
      return {
        pageUrl: '',
        tableURL: '',
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
        treePath: [],
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
        mountedTable: false, 
        nodeId: ''
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
      getField: bbn.fn.getField,
      renderOfficiel(row){
        let st = '';
        if (row.sender) {
          let sender = bbn.fn.search(this.source.senders, 'value', row.sender), 
              text = this.source.senders[sender].text;
          st += '<i title="' + text + '" class="nf nf-fa-circle bbn-lg bbn-' +
            (text === 'Com officielle' ? 'red' : 'blue') +
            '"></i> &nbsp; ';
          if (row.attachments) {
            let att = row.attachments;
            if (bbn.fn.isString(att)) {
              att = JSON.parse(att);
            }
            if (bbn.fn.isArray(att) && att.length) {
              st += '<i class="nf nf-fa-paperclip bbn-m"></i> <strong> &nbsp;' +
                att.length +
                '</strong> &nbsp; ';
            }
          }
          if (row.priority) {
            if (row.priority < 5) {
              st += '<strong>' + bbn._('Prioritary') + '</strong>';
            }
            else if (row.priority > 5) {
              st += bbn._('Non prioritary');
            }
          }
          st += '<br>';
          if (row.total) {
            st += '<span title="' +
              bbn._('Total emails') +
              '"><i class="nf nf-fa-envelope bbn-m"></i> ' +
              (row.total || '-') +
              '&nbsp; </span>';
            if (row.success) {
              st += '<span title="' +
                bbn._('Total sent') +
                '"><i class="nf nf-fa-envelope bbn-m bbn-green"></i> ' +
                (row.success || '-') +
                '&nbsp; </span>'
            }
            if (row.failure) {
              st += '<span title="' +
                bbn._('Total failed') +
                '"><i class="nf nf-fa-envelope bbn-m bbn-red"></i> ' +
                (row.failure || '-') +
                '&nbsp;</span>'
            }
          }
        }
        return st;
      },
      renderRecipients(row){
        if ( row.recipients ){
          let text = bbn.fn.getField(this.source.recipients, 'text', 'value', row.recipients);
          return '<a href="listes/liste/' + row.recipients+'">'+text+'</a>'
        }
        
      },
      renderFiles(row){
        return row.attachments ?
          (bbn.fn.isArray(row.attachments) ? row.attachments.length : JSON.parse(row.attachments).length)
          : '-';
      },
      renderSent(row){
        return row.recus > 0 ?
          Math.round(100 / row.num_accuses * row.recus) + ' %' :
          '-';
      },
      renderButtons(row){
        let res = [{
          text: bbn._("See"),
          notext: true,
          icon: "nf nf-fa-eye",
          action: this.see
        },{
          text: bbn._('Send this email to') + ' ' + appui.app.user.name,
          notext: true,
          icon: "nf nf-fa-envelope",
          action: this.selfSend
        },{
            text: bbn._("Duplicate"),
            notext: true,
            icon: "nf nf-fa-copy",
            action: this.duplicate
          }];
        if ( ['sent', 'sending', 'suspended', 'cancelled'].includes(row.state) && (row.num_accuses > 0)){
          res.push({
            text: bbn._("Open"),
            notext: true,
            icon: "nf nf-fa-th_list",
            action: this.open
          });
        }
        
        
        if ( (row.state === 'ready') ){
          res.push({
            text: bbn._("Edit"),
            notext: true,
            icon: "nf nf-fa-edit",
            action: this.edit
          }/*,{
            text: bbn._("Send"),
            notext: true,
            icon: "nf nf-fa-paper_plane",
            action: this.send
          }*/);
        }
        if ( (row.state === 'ready') || (row.state === 'cancelled') ){
          res.push({
            text: bbn._("Delete"),
            notext: true,
            icon: "nf nf-oct-trashcan",
            action: this.remove
          })
        }
        
        if( (row.state === 'ready') && (row.sent === null) ){
          res.push({
            text: bbn._("Send"),
            notext: true,
            icon: "nf nf-fa-paper_plane",
            action: this.send
          });
        }
        if ( row.state === 'suspended' ){
          res.push({
            text: bbn._("Reactivate mailing"),
            notext: true,
            icon: "nf nf-fa-play_circle_o",
            action: this.play
          });
        }
        if ( row.state !== 'sending' ){
          res.push({
            text: bbn._("Test"),
            notext: true,
            icon: "nf nf-fa-magic",
            action: this.test
          });
        }
        else {
          res.push({
            icon: 'nf nf-mdi-close',
            title: 'Cancel mailing',
            action: this.cancelMailing,
            notext: true
          },{
            text: bbn._("Suspend"),
            notext: true,
            icon: "nf nf-fa-stop_circle_o",
            action: this.stop
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
        this.$refs.table.insert({}, {
          title: bbn._("New mailing"),
          width: this.getPopup().defaultWidth,
          height: this.getPopup().defaultHeight
        });
      },
      
      edit(row, col, idx){
        return this.$refs.table.edit(
          bbn.fn.extend(row, {hasVersions: row.version > 1} ), {
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
            source: row,
            scrollable: false
          });
        }
        else{
          appui.error(bbn._("Error"));
        }
      },
			open(row){
        if ( row.id ){
          this.pageUrl = 'details/' + row.id
          this.closest('bbn-router').route('details/'+ row.id)
          //bbn.fn.link(this.source.root + 'page/details/' + row.id);
        }
      },
      openList(row){
        bbn.fn.link('listes/liste/' + row.recipients);
      },
      duplicate(row, ob){
        if ( row.id ){
          let tmp = bbn.fn.extend({}, row, {state: 'ready', num_accuses: 0, sent: null});
          bbn.fn.happy('tmp')
          bbn.fn.log(tmp, row)
          this.getRef('table').copy(tmp, {
            title: bbn._("Mailing edit"),
            width: this.getPopup().defaultWidth,
            height: this.getPopup().defaultHeight,
            data: {id_parent: row.id}
          }, 0);
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
                //this.$refs.table.updateData();
                appui.success(bbn._('Deleted'));
                this.getRef('table').updateData();
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
        bbn.fn.warning('set filter')
        bbn.fn.log('node before',this.getRef('tree'),this.getRef('tree').selectedNode )
        if ( this.mountedTable ){
          if ( node.data.id ){
            //set the url whan a node is selected from the tree
            this.closest('bbn-router').currentURL = 'home/'+ node.data.id;
            this.nodeId = node.data.id
          }
          if ( node.level === 0 ){
            let idx = bbn.fn.search(this.getRef('table').currentOrder, {field: 'bbn_notes_versions.creation'});
            if ( idx > -1 ){
              this.getRef('table').$set(this.getRef('table').currentOrder[idx], 'field', 'sent');
            }
            //this.treePath = ['all'];
            if ( this.$refs.table !== undefined ){
              return this.$refs.table.unsetFilter();
            }
          }
          let idx = bbn.fn.search(this.getRef('table').currentOrder, { field: 'sent' });
          if ( idx > -1 ){
            this.getRef('table').$set(this.getRef('table').currentOrder[idx], 'field', 'bbn_notes_versions.creation');
          }
          //this.treePath = ['all', node.data.id];
          this.$refs.table.currentFilters = {
            conditions: node.data.filters,
            logic: 'AND'
          };
        }
      },
      setSelected(){
        bbn.fn.happy('set selected')
        let current = this.tableURL;
        let nodes = this.findAll('bbn-tree-node')
        if ( current && (current !== 'home') && nodes.length ){
          let node = bbn.fn.filter(nodes, (a) => {
            return a.data.id === current
          });
          if ( node[0] ){
            node[0].isSelected = true;
            bbn.fn.log('node in setSelected',node[0])
          }
        }
        this.mountedTable = true;
      },
      /*setSelected(){
        let filters = [];
        bbn.fn.happy('set selected')
        bbn.fn.log(this.closest('bbn-router').closest('bbn-container').current)
        for ( let filter of this.$refs.table.currentFilters.conditions ){
          filters.push(bbn.fn.extend({}, filter));
        }
        if ( filters.length ){
          for ( let m of this.menu[0].items ){
            if ( bbn.fn.isSame(filters, m.filters) ){
              this.treePath.push(m.id);
              bbn.fn.warning('filters')
              bbn.fn.log(this.treePath)
              break;
            }
          }
        }
        this.mountedTable = true;
      },*/
      openLettersTypesTab() {
        this.pageUrl = 'types'
        this.closest('bbn-router').route('types')
      },
      openEmailsTab() {
        this.pageUrl = 'ready'
        this.closest('bbn-router').route('ready')
      },
      openEmailsSentTab(){
        this.pageUrl = 'sent'
        this.closest('bbn-router').route('sent')
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
      },
      sourceMenu(a){
        bbn.fn.log("douceMenu", a);
        return {
          menu: [
            {text: 'Hello', value: 'world'}
          ]
        }
      }
    },
    watch: {
      'info.current.idRecipients'(newVal){
        if ( newVal && this.source.recipients ){
          this.info.current.recipients = bbn.fn.getField(this.source.recipients, 'text', 'value', this.info.current.idRecipients);
        }
        else {
          this.info.current.recipients = '';
        }
      },
      'info.next.idRecipients'(newVal){
        if ( newVal && this.source.recipients ){
          this.info.next.recipients = bbn.fn.getField(this.source.recipients, 'text', 'value', this.info.next.idRecipients);
        }
        else {
          this.info.next.recipients = '';
        }
      }
    },
    created(){
      mailings = this;
      let router = this.closest('bbn-router');
      let id = bbn.fn.search(router.views,'url', 'home');
      if ( id > -1 ){
        router.views[id].static = true;
      }
    },
    mounted(){
      appui.register('appui-emails', this);
      this.clearGetInfo();
      let current = this.closest('bbn-router').closest('bbn-container').currentURL,
            bit = current.split('/').pop();
      if (bit === 'home') {
        bit = 'all';
      }
      this.tableURL = bit;
      this.$nextTick(() => {
        if (bit === 'all') {
          let router = this.getRef('tableRouter');
          if (bbn.fn.isVue(router)) {
            router.route('all')
          }
        }
      })
      bbn.fn.happy('MOUNTED', this.tableURL)
    },
		beforeDestroy(){
			this.clearGetInfo();
      appui.unregister('appui-emails');
    },
    components: {
      menu: {
        template: `<bbn-dropdown :source="menu" :template="tpl" @input="select" :placeholder="_('Choose')"></bbn-dropdown>`,
        props: ['source'],
        data(){
          let row = this.source;
          let res = [{
            text: bbn._("See"),
            icon: "nf nf-fa-eye",
            value: 'see'
          },{
            text: bbn._('Send to myself'),
            icon: "nf nf-fa-envelope",
            value: 'selfSend'
          },{
            text: bbn._("Duplicate"),
            icon: "nf nf-fa-copy",
            value: 'duplicate'
          }];
          if ( ['sent', 'sending', 'suspended', 'cancelled'].includes(row.state) && (row.num_accuses > 0)){
            res.push({
              text: bbn._("Open"),
              icon: "nf nf-fa-th_list",
              value: 'open'
            });
          }
          if ( (row.state === 'ready') ){
            res.push({
              text: bbn._("Edit"),
              icon: "nf nf-fa-edit",
              value: 'edit'
            }/*,{
              text: bbn._("Send"),
              notext: true,
              icon: "nf nf-fa-paper_plane",
              action: this.send
            }*/);
          }
          //only mailings without emails sent can be deleted
          if ( ((row.state === 'ready') || (row.state === 'cancelled')) && ( row.total === 0 ) && appui.app.user.isAdmin ){
            res.push({
              text: bbn._("Delete"),
              icon: "nf nf-oct-trashcan",
              value: 'remove'
            })
          }
          
          if( (row.state === 'ready') && (row.sent === null) ){
            res.push({
              text: bbn._("Send"),
              icon: "nf nf-fa-paper_plane",
              value: 'send'
            });
          }
          if ( row.state === 'suspended' ){
            res.push({
              text: bbn._("Reactivate mailing"),
              icon: "nf nf-fa-play_circle_o",
              value: 'play'
            });
          }
          if ( row.state !== 'sending' ){
            res.push({
              text: bbn._("Test"),
              icon: "nf nf-fa-magic",
              value: 'test'
            });
          }
          else {
            res.push({
              icon: 'nf nf-mdi-close',
              title: 'Cancel mailing',
              value: 'cancelMailing'
            },{
              text: bbn._("Suspend"),
              icon: "nf nf-fa-stop_circle_o",
              value: 'stop'
            })  
          }
          return {
            menu: res,
            tpl: `<div class="bbn-w-100 bbn-vxspadded bbn-hspadded"><i :class="'bbn-m bbn-right-space ' + source.icon"></i><div class="bbn-iblock" v-text="source.text"></div></div>` 
          };
  
        },
        methods: {
          select(action){
            if (mailings && bbn.fn.isFunction(mailings[action])) {
              mailings[action](this.source);
            }
          }
        }
      }
    }
  }
})();