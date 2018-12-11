/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 23/03/2018
 * Time: 15:39
 */
(() => {
  return {
    props: ['source'],
		data(){
			return {
				root: appui.plugins['appui-emails']
			}
		},
    methods: {
      success(d){
        if ( d.success ){
					let t = bbn.vue.closest(this, 'bbns-tab').getComponent(),
							table = bbn.vue.find(t, 'bbn-table');
          if ( this.source.id_note ){
            let idx = bbn.fn.search(t.source.categories, 'id_note', d.data.id_note);
            if ( idx > -1 ){
              bbn.fn.each(d.data, (v, i) => {
                if ( i !== 'content' ){
                  this.$set(t.source.categories[idx], i, v); 
                }
              });
            }
          }
          else {
            t.source.categories.push(d.data);
          }
          table.updateData();
          appui.success(bbn._('Saved'));
        }
      }
    }
  }
})();
