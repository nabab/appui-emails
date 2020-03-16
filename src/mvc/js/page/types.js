/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 16:24
 */
(() => {
  return {
    props: ['source'],
    methods: {
      insert(){
        this.getPopup().open({
          width: 800,
          height: '90%',
          component: 'appui-emails-types-form',
          source: {
            empty_categories: this.source.empty_categories,
            id_type: '',
            title: '',
            content: '',
            name: ''
          },
          title: bbn._("New letter type")
        });
      },
      toolbar(){
        return [{
          text: bbn._('Insert new '),
          action: this.insert,
          icon: 'nf nf-fa-plus'
        }]
      },
      renderButtons(row){
        return [{
          text: bbn._("Mod."),
          icon: "nf nf-fa-edit",
          notext: true,
          action: this.edit,
        }, {
          text: bbn._("Suppr."),
          icon: "nf nf-fa-trash",
          notext: true,
          action: this.remove,
          disabled: !!row.default
        }];
      },
      renderUser(row){
        return appui.app.getUserName(row.id_user)
      },
      edit(row){
        this.post(this.source.root + 'actions/types/get', {
          id_note: row.id_note,
          version: row.version
        } , (d) => {
          if ( d.success && d.data ){
            this.getPopup().open({
              width: 800,
              height: '90%',
              component: 'appui-emails-types-form',
              source: bbn.fn.extend(d.data, {
                  hasVersions: d.data.version > 1,
                }
              ), 
              title: bbn._("Edit letter type")
            })
          }
        })
      },
      remove(row){
        if ( row.id_note ){
          appui.confirm(bbn._("Are you sure you want to delete this letter?"), () => {
            this.post(this.source.root + 'actions/types/delete', {id_note: row.id_note}, (d) => {
              if ( d.success ){
								let idx = bbn.fn.search(this.source.categories, 'id_note', row.id_note);
								if ( idx > -1 ){
									this.source.categories.splice(idx, 1);
									this.$refs.table.updateData();
									appui.success(bbn._('Deleted'));
								}
              }
							else {
								appui.error(bbn._('Error'));
							}
            });
          });
        }
      }
    },
    created(){
      let types = this,
          mixins = [{
            data(){
              return {types: types}
            }
          }];
    },
    mounted(){
      this.$nextTick(() => {
        this.getPopup().open({
          width: 850,
          scrollable: false,
          height: 200,
          title: bbn._("Avertissement sur les lettres types"),
          content: '<div class="bbn-overlay bbn-padded"><div class="bbn-b">Attention!</div><br>Ici vous pouvez modifier les lettres types mais elles utilisent un système de "templates" avec lequel il vous faut être très précautionneu. Le mieux est de dupliquer une lettre-type existante et de la modifier. Une fois terminée, mettez-là en défaut si elle est utilisée sur une fonctionnalité sans choix (ex: attestations), et allez la tester dans son contexte. Alors vous pourrez effacer l\'ancienne ou bien la refaire passer en défaut si votre modification renvoie une erreur.</div>'
        });
      });
    }
	}
})();
