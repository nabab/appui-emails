/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 17:16
 */
(() => {
  return {
    props: ['source'],
    data(){
      return {
        link: 'emails/view/' + this.source.id_note,
        emails: bbn.vue.closest(this, 'bbns-tab').getComponent()
      }
    }
  }
})();
