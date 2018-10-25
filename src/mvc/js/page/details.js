/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:24
 */
(() => {
  return {
    data(){
      return {
        status: [{
          text: bbn._('Error'),
          value: 'echec'
        }, {
          text: bbn._('Success'),
          value: 'succes'
        }, {
          text: bbn._('Ready'),
          value: 'pret'
        }]
      }
    },
    methods:{
      renderEtat(row){
        if ( row.etat ){
          let color = '',
            text = '';
          switch (row.etat) {
            case 'succes':
              text = bbn._('Success');
              color = 'green';
              break;
            case 'echec':
              text = bbn._('Error');
              color = 'red';
              break;
            case 'pret':
              text = bbn._('Ready');
              color = 'orange';
              break;
          }
          return '<span class="bbn-' + color + '">' + text + '</span>';
        }
        return '';
      }
    }
	}
})();
