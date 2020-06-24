/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:29
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        dataToSend: null,
        ref: (new Date()).getTime(),
        today: moment().format('YYYY-MM-DD HH:mm:ss'),
        emails: null,
        prefilled: false,
        priority: 0,
        isNumLoading: false,
        numRecipients: 0, 
        root: this.closest('bbn-container').getComponent().source.root
      }
    },
    methods: {
      getVersion(d){
        bbn.fn.log('before',this.source)
        this.$set(this.source.row, 'id_note', d.id);
        this.$set(this.source.row, 'id_type', d.id_type);
        this.$set(this.source.row, 'title', d.title);
        this.$set(this.source.row, 'content', d.content);
        this.$set(this.source.row, 'creation', d.creation);
        //this.$set(this.source.row, 'version', d.version);
        this.$set(this.source.row, 'creator', d.id_user);
        this.$set(this.source.row, 'sender', d.sender);
        this.$set(this.source.row, 'recipients', d.recipients);
        this.$set(this.source.row, 'attachments', d.files);
        this.$nextTick(() => {
          this.getRef('editor').onload();
        });
        bbn.fn.log('after',this.source)
        bbn.fn.log('version',d)
      },
      /*
      changeDate(){
        if ( this.source.row.sent < (new Date()) ){
          this.source.row.sent = this.today
        }
      },*/
      failure(){
        appui.error(bbn._('A problem occurred'));
      },
      success(d){
        if ( d.success ){
          let treePath = ['all'];
          if ( this.source.row.sent && this.source.row.sent.length ){
            treePath.push('ready');
          }
          else {
            treePath.push('draft');
          }
          if ( !bbn.fn.isSame(this.emails.treePath, treePath) ){
            bbn.fn.log('errore js',this.emails, this.emails.treePath, treePath)
            this.$set(this.emails, 'treePath', treePath);
          }
          else{
            this.emails.find('bbn-table').updateData();
          }
          if ( d.count ){
            //let home = this.closest('bbn-container').getComponent().find('bbn-container').getComponent();
            this.emails.source.count = d.count;
          }
          if ( this.source.row.id ){
            appui.success(bbn._('Modified'));
          }
          else {
            appui.success(bbn._('Saved'));
          }
          /*if ( t.source.row.count && d.count ){
            t.source.row.count = d.count;
          }*/
        }
        else {
          appui.error(bbn._('Error'));
        }
      },
      loadLettre(id){
        if ( id ){
          this.post(appui.plugins['appui-emails'] + "/actions/get", {id: id}, (e) => {
            if ( e.success && e.template ){
              this.source.row.title = e.template.title;
              this.source.row.content = e.template.content;
            }
          });
        }
      },
      setRecipientsNum(old){
        let url = this.emails.source.root + 'data/mailist/num';
        let odata = {recipients: old, sender: this.source.row.sender};
        if (this.isNumLoading) {
          let idURL = bbn.fn.getRequestId(url, odata);
          if (idURL) {
            bbn.fn.abort(idURL);
          }
        }
        if (this.source.row.recipients) {
          this.isNumLoading = true;
          this.post(url, {recipients: this.source.row.recipients, sender: this.source.row.sender}, (d) => {
            if (d.success) {
              this.numRecipients = d.num || 0;
            }
            else if (this.numRecipients){
              this.numRecipients = 0;
            }
            this.isNumLoading = false;
          }, () => {
            this.isNumLoading = false;
          });
        }
      }
    },
    mounted(){
      this.emails = appui.getRegistered('appui-emails');
      this.dataToSend = {ref: this.ref};
      let fl = this.closest('bbn-floater');
      if (fl && fl.data && fl.data.id_parent){
        this.prefilled = true;
        this.dataToSend.id_parent = fl.data.id_parent;
      }
      this.setRecipientsNum();
    },
    watch: {
      "source.row.recipients"(v, o){
        this.setRecipientsNum(o);
      }
    }
  }
})();