/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:24
 */
(() => {
  return {
    props: ['source'],
    methods:{
      etat(row){
        if ( row.etat === 'succes'){
          this.etat_class = 'bbn-green';
          return bbn._('Success');
        }
        if ( row.etat === 'echec'){
          return bbn._('Échec');
        }
        if ( row.etat === 'pret'){
          return bbn._('Prêt');
        }
      },
    }
	}
})();
