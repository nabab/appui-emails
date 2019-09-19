(() => {
  return {
    data(){
      return {
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
    computed:{
      toolbar(){
        let res  = [{
              text: bbn._('Check/uncheck all emails'),
              icon: 'nf nf-fa-check',
              command: this.checkAll,
              class: 'bbn-bg-teal bbn-white'
            },{
              text: bbn._('Cancel selected emails'),
              icon: 'nf nf-fae-thin_close',
              command: this.cancelSelected,
              class: 'bbn-bg-teal bbn-white',
          }];
          
        return res;
      },
    },
    methods: {
      renderButtons(row){
        let res = [];
          
        if ( row.status === 'ready' ){
          res.push({
            icon: 'nf nf-mdi-close',
            title: bbn._('Cancel email'),
            command: this.cancelEmail,
            cls: 'bbn-button-icon-only'
          });
        }
        if ( row.status === 'cancelled' ){
          res.push({
            icon: 'nf nf-weather-refresh',
            title: bbn._('Change state to ready'),
            command: this.changeState,
            cls: 'bbn-button-icon-only'
          });
        }
        if ( (row.status === 'cancelled') || (row.status === 'ready') ){
          res.push({
            title: bbn._("Delete"),
            notext: true,
            icon: "nf nf-oct-trashcan",
            command: this.remove
          })
        }
        
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
      checkAll(){
        let cbs = this.find('bbn-table').findAll('bbn-checkbox');
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
        let res = [], 
          table = this.find('bbn-table'), 
          selected = table.currentSelected;

        if ( selected.length > 1 ){
          this.confirm(bbn._('Are you sure you want to cancel all selected emails? '), () => {
            bbn.fn.each(table.currentSelected, (v, i) => {
              res.push( table.currentData[v].data);
            })
            this.post(this.root + 'actions/email/cancel', {selected: res}, (d) => {
              if (d.success){
                table.currentSelected = [];
                appui.success(bbn._('Emails successfully cancelleded'));
                table.updateData()
                
                
              }
            })
          })
        }
        else {
          this.alert(bbn._('Remove the single row'))
        }
        bbn.fn.log(this.find('bbn-table').currentSelected)
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
        return row.id_mailing !== null ? '<i class="bbn-large nf nf-fa-check_circle bbn-green"></i>' : '';
      },
      renderTitre(row){
        return row.subject || '<div class="bbn-c"><i class="bbn-large nf nf-fa-envelope"></i></div>';
      }
    }
  }
})();