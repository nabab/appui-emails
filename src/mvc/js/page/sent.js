(() => {
  return {
    data(){
      return {
        root: appui.plugins['appui-email'] + '/',
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
        if ( row.status ){
          let ico = '',
              color= '';
          switch ( row.status ){
            case 'failure':
              ico = 'nf nf-fa-times_circle';
              color = 'red';
              break;
            case 'success':
              ico = 'nf nf-fa-check_circle';
              color = 'green';
              break;
            case 'ready':
              ico = 'nf nf-fa-clock_o';
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
        return row.subject || '<div class="bbn-c"><i class="bbn-large nf nf-fa-envelope"></i></div>';
      }
    }
  }
})();