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
          value: 'failure'
        }, {
          text: bbn._('Success'),
          value: 'success'
        }, {
          text: bbn._('Ready'),
          value: 'ready'
        }]
      }
    },
    methods:{
      renderEtat(row){
        if ( row.status ){
          let color = '',
            text = '';
          switch (row.status) {
            case 'success':
              text = bbn._('Success');
              color = 'green';
              break;
            case 'failure':
              text = bbn._('Error');
              color = 'red';
              break;
            case 'ready':
              text = bbn._('Ready');
              color = 'orange';
              break;
          }
          return '<span class="bbn-' + color + '">' + text + '</span>';
        }
        return '';
      }
    },
    beforeMount(){
      bbn.fn.happy('before mount detail')
      bbn.fn.log(bbn.env.params, this.source.id)
    }
	}
})();
