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
      let emptyCategories = this.source.empty_categories || false;
      if ( this.source.empty_categories ){
        delete this.source.empty_categories;
      }
			return {
        root: appui.plugins['appui-emails'], 
        emptyCategories: emptyCategories
      }
		},
    computed: {
      action(){
        return this.root + '/actions/types/' + (this.source.id_note ? 'update' : 'insert');
      }
    },
    methods: {
      success(d){
        if ( d.success ){
					let t = bbn.vue.closest(this, 'bbn-container').getComponent(),
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
    },
    created(){
      if ( this.emptyCategories ){
        this.$watch('source.id_type', (newVal) => {
          this.source.name = bbn.fn.get_field(this.emptyCategories, 'id', newVal, 'text');
        });
      }
    }
  }
})();
