/**
 * Created by BBN Solutions.
 * User: Mirko Argentino
 * Date: 20/03/2018
 * Time: 17:16
 */
(() => {
  return {
    props: ['source'],
    data(){
      let emails = bbn.vue.closest(this, 'bbn-container').getComponent()
      return {
        link: emails.source.root + 'view/' + this.source.id_note,
        emails: emails
      }
    },
    methods: {
      download(file){
        bbn.fn.log("hhh", {id_media: file.id, id: this.source.id}, file);
        //bbn.fn.postOut(this.emails.source.root + 'actions/download', {id_media: file.id, id: this.source.id});
        bbn.fn.download(this.emails.source.root + 'actions/download', file.name, {id_media: file.id, id: this.source.id});
      }
    },
    mounted(){
      bbn.fn.log(this.source)
    }
  }
})();