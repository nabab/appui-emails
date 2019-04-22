(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-emails'] + '/',
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
    methods: {
      renderEtat(row){
        if ( row.etat ){
          let ico = '',
              color= '';
          switch ( row.etat ){
            case 'echec':
              ico = 'nf nf-fa-times_circle';
              color = 'red';
              break;
            case 'succes':
              ico = 'nf nf-fa-check_circle';
              color = 'green';
              break;
            case 'succes':
              ico = 'nf nf-fa-clock';
              color = 'orange';
              break;
          }
          return '<i class="bbn-large ' + ico + ' bbn-' + color + '"></i>';
        }
      },
      renderMailing(row){
        return row.id_mailing !== null ? '<i class="bbn-large nf nf-fa-check_circle bbn-green"></i>' : '';
      },
      renderTitre(row){
        return row.titre || '<div class="bbn-c"><i class="bbn-large nf nf-fa-envelope"></i></div>';
      }
    }
  }
})();