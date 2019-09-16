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
        ref: (new Date()).getTime(),
        today: moment().format('YYYY-MM-DD HH:mm:ss'),
        emails: bbn.vue.closest(this, 'bbn-container').getComponent()
      }
    },
    methods: {
      changeDate(){
        if ( this.source.row.sent < (new Date()) ){
          this.source.row.sent = new Date()
        }
      },
      failure(){
        appui.error(bbn._('A problem occurred'));
      },
      success(d){
        if ( d.success ){
          let t = this.closest('bbn-container').getComponent(),
              treePath = ['all'];
          if ( this.source.row.sent && this.source.row.sent.length ){
            treePath.push('ready');
          }
          else {
            treePath.push('draft');
          }
          if ( bbn.fn.isSame(t.treePath, treePath) ){
            bbn.vue.find(t, 'bbn-table').updateData();
          }
          else {
            t.$set(t, 'treePath', treePath);
          }
          if ( this.source.row.id ){
            appui.success(bbn._('Modified'));
          }
          else {
            appui.success(bbn._('Saved'));
          }
          if ( t.source.row.count && d.count ){
            t.source.row.count = d.count;
          }
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
      }
    }
  }
})();
