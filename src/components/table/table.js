(() => {
  return {
    data(){
      return {
        isAutorizedUser: appui.app.user.isAdmin && appui.app.user.isDev,
        root: appui.plugins['appui-emails'] + '/',
        status: [{
          text: bbn._('Error'),
          value: 'echec'
        }, {
          text: bbn._('Success'),
          value: 'succes'
        }, {
          text: bbn._('Ready'),
          value: 'pret'
        }]
      }
    },
    props: {
      context: {
        type: String
      },
      filters:{
        type: Object,
        default(){
          return {}
        }
      },
      filterable:{
        type: Boolean,
        default: true
      },
      tableSource:{
        type: String
      },
      tableData:{
        type: Object,
        default(){
          return {}
        }
      }
    },
    computed: {
      disableButton(){
        return this.getRef('toolbar').disableButtons
      },
      disableButtonCancel(){
        return this.getRef('toolbar').disableButtonCancel
      }
    },
    methods: {
      renderFiles(row){
        if(row.attachments && row.attachments.length){
          return row.attachments.length
        }
      },
      renderPriority(row){
        if ( row.priority === 5 ){
          return '<i title="'+ bbn._('Normal priority') +'" class="bbn-large nf nf-fa-dot_circle_o bbn-orange"></i>'
        }
        else if ( row.priority < 5 ){
          return '<i title="'+ bbn._('High priority') +'" class="bbn-large nf nf-fa-dot_circle_o bbn-red"></i>'
        }
        else if ( row.priority > 5 ){
          return '<i title="'+ bbn._('Low priority') +'" class="bbn-large nf nf-fa-dot_circle_o bbn-green"></i>'
        }
      },
      renderButtons(row){
        let res = [];
          
        if ( row.status === 'ready' ){
          res.push({
            icon: 'nf nf-mdi-close',
            title: bbn._('Cancel email'),
            action: this.cancelEmail,
            cls: 'bbn-button-icon-only', 
            notext: true
          });
        }
        if ( row.status === 'cancelled' ){
          res.push({
            icon: 'nf nf-weather-refresh',
            title: bbn._('Change state to ready'),
            action: this.changeState,
            cls: 'bbn-button-icon-only',
            notext:true
          });
        }
        if ( appui.app.user.isAdmin && appui.app.user.isDev && ( (row.status === 'cancelled') || (row.status === 'ready') || (row.status === 'success') )){
          res.push({
            title: ((row.status === 'success') && !appui.app.user.isAdmin) ? bbn._('Only admin users can delete sent emails') : bbn._("Delete the email from the database"),
            notext: true,
            icon: "nf nf-oct-trashcan",
            action: this.remove, 
            disabled: ((row.status === 'success') && !appui.app.user.isAdmin)
          })
        }
        /*if ( (row.status === 'success') && (this.context !== 'sent' ) ){
          res.push({
            title: bbn._("Success"),
            text: bbn._("Success"),
            icon: "nf nf-fa-check",
            disabled: true,
            class: 'bbn-bg-teal'
          })
        }*/
        
        return res;
      },
      changeState(row, obj, idx){
        this.confirm(bbn._('Do you want to change the state of this email to "Ready"? '), () => {
          this.post(this.root + 'actions/email/change_state', row, (d) => {
            if ( d.success ){
              this.getRef('table').currentData[idx].data.status = 'ready'
              appui.success(bbn._('Email status changed'))
            }
            else{
              appui.error(bbn._('Something went wrong while changing the email status'))
            }
          })
        })
      },
      remove(row, obj, idx){
        this.confirm(bbn._('Do you want to completely delete this email? '), () => {
          //if the context is 'sent' send the id_user to the controller, there will be checked again if the user is an admin (only admin users can delete mails with status 'success')
          ((this.context === 'sent') || (this.context === 'details')) ? bbn.fn.extend(row, {id_user: appui.app.user.id}) : '';
          this.post(this.root + 'actions/email/delete', row, (d) => {
            if ( d.success ){
              this.getRef('table').currentData.splice(idx,1)
              appui.success(bbn._('Email successfully deleted'))
            }
            else{
              appui.error(bbn._('Something went wrong while deleting the email'))
            }
          })
        })
      },
      //cancel a single email
      cancelEmail(row, obj, idx){
        bbn.fn.log(row, idx)
        this.confirm(bbn._('Do you want to cancel this email? '), () => {
          this.post(this.root + 'actions/email/cancel', row, (d) => {
            if ( d.success ){
              this.getRef('table').currentData[idx].data.status = 'cancelled'
              appui.success(bbn._('Email successfully cancelled'))
            }
            else{
              appui.error(bbn._('Something went wrong while cancelling the email'))
            }
          })
        })
      },
      renderEtat(row){
        if ( row.status ){
          let ico = '',
              color= '',
              title= '';
          switch ( row.status ){
            case 'failure':
              ico = 'nf nf-fa-times_circle';
              color = 'red';
              title = bbn._('Email failed')
              break;
            case 'success':
              ico = 'nf nf-fa-check_circle';
              color = 'green';
              title = bbn._('Email sent')
              break;
            case 'ready':
              ico = 'nf nf-fa-clock_o';
              color = 'orange';
              title = bbn._('Email ready')  
              break;
            case 'cancelled':
              ico = 'nf nf-fa-hand_stop_o';
              color = 'red';
              title = bbn._('Email cancelled')
              break;  
          }
          return '<i title="'+ title +' " class="bbn-large ' + ico + ' bbn-' + color + '"></i>';
        }
      },
      renderMailing(row){
        return row.id_mailing !== null ? '<i class="bbn-large nf nf-fa-check_circle bbn-green"></i>' : '-';
      },
      renderTitre(row){
        return row.subject || '<div class="bbn-c"><i class="bbn-large nf nf-fa-envelope"></i></div>';
      }
    },
    components: {
      'toolbar': {
        data(){
          return {
            table: false, 
            root: '', 
            context: '',
            isAutorizedUser: appui.app.user.isAdmin && appui.app.user.isDev
          }
        },
        template: `
          <div class="bbn-xspadded bbn-l bbn-header bbn-vmiddle bbn-h-100" ref="toolbar">
            <bbn-button text="`+ bbn._('Check/uncheck all emails')+`" 
                        icon="nf nf-fa-check"
                        @click="checkAll"
                        class="bbn-bg-teal bbn-white"
                        
            ></bbn-button>
            <bbn-button text="`+bbn._('Cancel selected emails')+`"
                        icon="nf nf-fae-thin_close"
                        @click="cancelSelected"
                        class="bbn-bg-teal bbn-white"
                        :disabled="disableButtonCancel"
                        :title="(disableButtonCancel && !disableButtons) ? _('Only emails with READY status can be cancelled') : _('Cancel selected emails')"
            ></bbn-button>
            <bbn-button text="`+ bbn._('Delete selected emails') +`"
                        icon="nf nf-oct-trashcan"
                        @click="deleteSelected"
                        :disabled="( context === 'ready' ) ? disableButtons : (disableButtons || !isAutorizedUser) "
                        class="bbn-bg-teal bbn-white"
                        :title="(!isAutorizedUser) ? _('Only admin and dev users can remove sent emails') :  _('Remove selected emails') " 

            ></bbn-button>
            <bbn-button text="`+ bbn._('Delete all emails') +`"
                        icon="nf nf-oct-trashcan"
                        @click="deleteAll"
                        class="bbn-bg-teal bbn-white"
                        title="`+ bbn._('Delete all ready or cancelled emails') +`"
                        v-if="(context === 'ready') && isAutorizedUser"
            ></bbn-button>
          </div>`,
        props: ['source'],
        computed: {
          //the button of toolbar 'cancel selected emails' will be enabled only if there are rows selected in the table and only if the state of selected rows is 'ready'
          disableButtonCancel(){
            if ( this.table ){
              let selected = this.table.currentSelected, 
                  nr_ready = 0;
              
              bbn.fn.each(selected, (v, i) => {
                if ( this.table.currentData[v] && this.table.currentData[v].data.status === 'ready' ){
                  nr_ready++;
                }
              })
              if ( nr_ready > 0 ){
                return false;
              }
              else if ( !selected.length || (nr_ready === 0) ) {
                return true;
              }
            }
            else{
              return true
            }
          },
          //disable buttons just basing on the number of rows selected in the table
          disableButtons(){
            if ( this.table && ( this.table.currentSelected.length > 1 ) ){
              return false;
            }
            else {
              return true
            }
          },
          
        }, 
        methods: {
          checkAll(){
            let cbs = this.table.findAll('bbn-checkbox');
            bbn.fn.log(cbs)
            if ( Array.isArray(cbs) ){
              let checked = cbs.filter((cb) => {
                return !!cb.state;
              });
              if ( checked.length ){
                checked.forEach((cb) => {
                  cb.toggle();
                });
              }
              else {
                cbs.forEach((cb) => {
                  if ( !cb.state ){
                    cb.toggle();
                  }
                });
              }
            }
          },
          //cancel all selected emails
          cancelSelected(){
            let res = [];
            if ( this.table.currentSelected && this.table.currentSelected.length > 1 ){
              this.confirm(bbn._('Are you sure you want to cancel all selected emails? '), () => {
                bbn.fn.each(this.table.currentSelected, (v, i) => {
                  if ( this.table.currentData[v].data.status === 'ready' ){
                    res.push( this.table.currentData[v].data);
                  }
                })
                this.post(this.root + 'actions/email/cancel', {selected: res}, (d) => {
                  if (d.success){
                    this.table.currentSelected = [];
                    appui.success(bbn._('Emails successfully cancelled'));
                    this.table.updateData()
                  }
                })
              })
            }
            else {
              this.alert(bbn._('Change the status or remove the email using the buttons of the row'))
            }
          },
          deleteSelected(){
            if ( this.table.currentSelected && this.table.currentSelected.length > 1 ){
              this.confirm(bbn._('Are you sure you want to completely delete all selected emails? '), () => {
                let res = [];
                bbn.fn.each(this.table.currentSelected, (v, i) => {
                  if ( (this.table.currentData[v].data.status === 'ready') || (this.table.currentData[v].data.status === 'cancelled') ||
                  (this.table.currentData[v].data.status === 'success' && this.isAutorizedUser)){
                    res.push( this.table.currentData[v].data);
                  }
                })
                //if the user is admin send the id user to the controller to check again isAdmin
               
                this.post(this.root + 'actions/email/delete', {
                  selected: res,
                  id_user: this.isAutorizedUser ? appui.app.user.id : false
                  }, (d) => {
                  if (d.success){
                    this.table.currentSelected = [];
                    appui.success(bbn._('Emails successfully deleted'));
                    this.table.updateData()
                  }
                })
              })
            }
            else {
              this.alert(bbn._('Change the status or remove the email using the buttons of the row'))
            }
          },
          deleteAll(){
            this.confirm(bbn._('Are you really sure you want to completely delete all ready emails? '), () => {
              let table = this.closest('appui-emails-table'),
                  id = table.source ? table.source.id : null;
              this.post(this.root + 'actions/email/delete_all', {id_user: appui.app.user.id}, (d) => {
                if (d.success){
                  appui.success(d.num + ' ' + bbn._('emails successfully deleted'));
                  this.table.updateData()
                }
              })
            })
          },
        },
        mounted(){
          this.table = this.closest('appui-emails-table').find('bbn-table');
          this.root = this.closest('appui-emails-table').root;
          this.context = this.closest('appui-emails-table').context;

        }
      }, 
    }
  }
})();