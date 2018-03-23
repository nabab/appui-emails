/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 17:38
 */
(() => {
  return {
    props: ['source'],
    methods:{
      etat(row){
        if ( row.etat === 'succes'){
          this.etat_class = 'bbn-green'
          return 'Success'
        }
        if ( row.etat === 'echec'){
          return 'Échec'
        }
        if ( row.etat === 'pret'){
          return 'Prêt'
        }
      },
    }
  }
})();