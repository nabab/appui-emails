/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 15/06/2018
 * Time: 17:01
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        emails: bbn.vue.closest(this, 'bbn-container').getComponent()
      }
    }, 
  }
})();