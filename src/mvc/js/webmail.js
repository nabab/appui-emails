// Javascript Document
(() => {
  return {
    data(){
      return {
        orientation: 'vertical'
      };
    },
    computed: {
      treeData(){
        let r = [];
        let fn = (ar, id_account) => {
          let res = [];
          bbn.fn.each(this.source.folder_types, ft => {
            bbn.fn.each(ar, a => {
              if (ft.names && ft.names.indexOf(a.uid) > -1) {
                res.push(bbn.fn.extend({
                  id_account: id_account,
                }, a))
              }
            })
          });
          bbn.fn.each(ar, a => {
            if (!bbn.fn.getRow(res, {uid: a.uid})) {
              let tmp = bbn.fn.extend({
                id_account: id_account,
              }, a);
              if (tmp.items) {
                tmp.items = fn(tmp.items, id_account)
              }
              res.push(tmp);
            }
          })
          return res;
        }
        bbn.fn.each(this.source.folder_types, a => {
          r.push({
            text: a.text,
            uid: a.code,
            names: a.names,
            id: a.id
          });
        })
        bbn.fn.each(this.source.accounts, a => {
          r.push({
            text: a.login,
            uid: a.id,
            items: fn(a.folders, a.id)
          });
        });
        return r;
      }
    },
    methods: {
      selectFolder(){
        bbn.fn.log(arguments);
      },
      createAccount() {
        this.getPopup({
          width: 500,
          height: 450,
          content: "<p>boooo</p>"
        })
      },
      treeMapper(a) {
        bbn.fn.log(a);
        return {
          text: a.uid
        }
      }
    }
  };
})()