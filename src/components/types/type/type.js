/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 15:47
 */
(() => {
  return {
    props: ['source'],
    computed:{
      num(){
        return bbn.fn.count(bbn.vue.closest(this, 'bbn-container').getComponent().source.categories, {id_type: this.source.id_type});
      }
    },
    methods: {
      insert(){
        this.getPopup().open({
          width: 800,
          height: '90%',
          component: 'appui-emails-types-form',
          source: {
            id_type: this.source.id_type,
            title: '',
            content: '',
            name: ''
          },
          title: bbn._("New letter type")
        });
      }
    }
  }
})();
