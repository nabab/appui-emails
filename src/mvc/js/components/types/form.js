/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 15:39
 */
(() => {
  return {
    props: ['source'],
    methods: {
      success(d){
        if ( d.success ){
					let t = bbn.vue.closest(this, 'bbns-tab').getComponent(),
							table = bbn.vue.find(t, 'bbn-table');
          if ( this.source.id_note ){
            let idx = bbn.fn.search(table.currentData, 'id_note', d.data.id_note);
            if ( idx > -1 ){
              table.currentData[idx] = d.data;
            }
          }
          else {
            table.currentData.push(d.data);
          }
          table.updateData();
          appui.success(bbn._('Saved'));
        }
      }
    }
  }
})();
