// Javascript Document
(() => {
  return {
    components: {
      accountForm: {
        props: ['source'],
        template: '#appui-emails-accounts-form-template',
        data(){
          return {
            types: this.closest('bbn-container').getComponent().source.types
          }
        }
      }
    }
  }
})();