(() => {
  return {
    methods: {
      fdatetime: bbn.fn.fdatetime
    },
    components: {
      sent: {
        template: `
          <bbn-progressbar :value="value"
                           :text="text"
                           type="percent"
                           class="bbn-c"
          ></bbn-progressbar>`,
        props: {
          source: {
            type: Object
          }
        },
        computed: {
          text(){
            return `${this.source.success}/${this.source.total}`
          },
          value(){
            return parseInt(this.source.success/this.source.total*100);
          }
        }
      }
    }
  }
})();