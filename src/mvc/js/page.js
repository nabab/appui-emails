/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 21/03/2018
 * Time: 20:18
 */
(() => {
  return {
    data(){
      return {
        tableUrl: '',
      }
    },
    mounted(){
      //in case of reroute
      if( this.source.page !== 'home' ){
        //this.closest('bbn-router').route('emails/page/'+this.source.page);
      }
    }
  }
})();