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
        today: moment().format('YYYY-MM-DD HH:mm:ss')
      }
    },
    methods: {
      changeDate(){
        if ( this.source.row.envoi < (new Date()) ){
          this.source.row.envoi = new Date()
        }
      },
      failure(){
        appui.error(bbn._('Un problème est survenu'));
      },
      success(d){
        if ( d.success ){
          let t = bbn.vue.closest(this, 'bbns-tab').getComponent();
          bbn.vue.find(t, 'bbn-table').updateData();
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
              this.source.row.objet = e.content.titre;
              this.source.row.texte = e.content.texte;
            }
          });
        }
      }
    }
  }
})();
