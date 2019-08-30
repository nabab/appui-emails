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
        if ( this.source.row.envoi < (new Date()) ){
          this.source.row.envoi = new Date()
        }
      },
      failure(){
        appui.error(bbn._('A problem occurred'));
      },
      success(d){
        if ( d.success ){
          let t = this.closest('bbns-container').getComponent(),
              treePath = ['all'];
          if ( this.source.envoi && this.source.envoi.length ){
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
          if ( d.count ){
            t.source.count = d.count;
          }
        }
        else {
          appui.error(bbn._('Error'));
        }
      },
      loadLettre(id){
        if ( id ){
          bbn.fn.post("com/lettre_type", {id: id}, (e) => {
            if ( e.success && e.content ){
              this.source.row.title = e.content.titre;
              this.source.row.content = e.content.texte;
            }
          });
        }
      }
    }
  }
})();
