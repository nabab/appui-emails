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
        let table = bbn.vue.closest(this, 'bbn-table'),
            idx = bbn.fn.search(table.currentData, {id_type: this.source.id_type, default: 1});
        bbn.fn.post(this.types.source.root + 'actions/types/default', {id_note: this.source.id_note}, (d) => {
          if ( d.success ){
            this.source.default = 1;
            if ( idx > -1 ){
              table.currentData[idx].default = 0;
            }
            table.updateData();
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
