/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 15:32
 */
(() => {
  return {
    props: ['source'],
    methods: {
      setDefault(source){

        this.post(appui.plugins['appui-emails'] + '/actions/types/default', {id_note: this.source.id_note}, (d) => {
          let table = this.closest('bbn-table'),
              defaults = table.findAll('appui-emails-types-default').filter((a) => {
                return (a.source.id_type === this.source.id_type) && (a.source.default === 1);
              });
          if ( d.success ){
            if ( defaults.length ){
              defaults[0].source.default = 0;
            }
            this.$set(this.source, 'default', 1);
            appui.success(bbn._('Saved'));
          }
					else {
						appui.error(bbn._('Error'));
					}
        });
      }
    }
  }
})();
