// Javascript Document
(() => {
  let cp;
  let scpName = bbn.fn.randomString().toLowerCase();
  return {
    data(){
      return {
        scpName: scpName,
        orientation: 'vertical',
        currentFolder: null,
        selectedMail: null
      };
    },
    computed: {
      dataObj(){
        return {
          id_folder: this.currentFolder
        }
      },
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
          let commonFolder = bbn.fn.getRow(this.source.folder_types, {code: 'folders'});
          bbn.fn.each(ar, a => {
            if (!bbn.fn.getRow(res, {uid: a.uid})) {
              let tmp = bbn.fn.extend({
                id_account: id_account,
              }, a);
              let folder = commonFolder;
              bbn.fn.each(this.source.folder_types, ft => {
                if (ft.names && ft.names.indexOf(a.uid) > -1) {
                  folder = ft;
                  return false;
                }
              });
              tmp.icon = folder.icon;
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
            icon: a.icon,
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
      showAttachments(row){
        if (row.attachments) {
          let attachments = bbn.fn.isString(row.attachments) ? JSON.parse(row.attachments) : row.attachments;
          return attachments.length
        }
        return '-';
      },
      selectFolder(node) {
        this.currentFolder = node.data.id;
      },
      showSubject(row) {
        let st  = row.subject;
        if (!row.is_read) {
          st = '<strong>' + st + '</strong>';
        }
        return st;
      },
      selectMessage(row) {
        this.selectedMail = row;
        bbn.fn.log(row)
      },
      createAccount() {
        this.getPopup({
          width: 500,
          height: 450,
          title: bbn._("eMail account configuration"),
          component: this.$options.components[scpName]
        })
      },
      treeMapper(a) {
        bbn.fn.log(a);
        return {
          text: a.uid
        }
      },
      reply(){
        if (this.selectedMail) {
          
        }
      },
      replyAll(){
        if (this.selectedMail) {
          
        }
      },
      forward(){
        if (this.selectedMail) {
          
        }
      },
      archive(){
        if (this.selectedMail) {
          
        }
      },
      setAsJunk(){
        if (this.selectedMail) {
          
        }
      },
      openTab(){
        if (this.selectedMail) {
          
        }
      },
      openWindow(){
        if (this.selectedMail) {
          
        }
      },
      deleteMail(){
        if (this.selectedMail) {
          
        }
      },
      mailToTask(){
        if (this.selectedMail) {
          
        }
      }
    },
    watch: {
      currentFolder(){
        this.$forceUpdate();
        this.$nextTick(() => {
          this.getRef('table').updateData()
        })
      }
    },
    created(){
      cp = this;
    },
    components: {
      [scpName]: {
        template: '#' + scpName + '-editor',
        data(){
          return {
            cp: cp,
            types: cp.source.types,
            hasSMTP: false,
            lastChecked: null,
            tree: [],
            accountChecker: null,
            errorState: false,
            account: cp.editedAccount || {
              folders: [],
              type: null,
              host: null,
              login: '',
              smtp: null,
              pass: '',
              ssl: 1,
              email: ''
            }
          }
        },
        computed: {
          selectedFolders(){
            if (this.tree.length && this.account.folders.length) {
              return JSON.stringify(this.account.folders);
            }
            return '';
          },
          accountCode(){
            if (this.account.type) {
              return bbn.fn.getField(this.types, 'code', {id: this.account.type});
            }
            return null;
          }
        },
        methods: {
          backToConfig(){
            this.errorState = false;
            this.tree.splice(0, this.tree.length);
            this.account.folders.splice(0, this.account.folders.length);
            this.account.pass = '';
          },
          success(d){
            if (d && d.success) {
              this.closest('bbn-container').reload();
            }
          }
        },
        watch: {
          hasSMTP(v){
            if (v && this.account.host) {
              this.account.smtp = this.account.host;
            }
            else {
              this.account.smtp = null;
            }
          },
          account: {
            deep: true,
            handler(){
              if (this.accountChecker) {
                clearTimeout(this.accountChecker);
              }
              this.accountChecker = setTimeout(() => {
                if (!this.tree.length
                    && this.account.email
                    && bbn.fn.isEmail(this.account.email)
                    && this.account.type
                    && this.account.login
                    && this.account.pass
                ) {
                  let ok = false;
                  if (['imap', 'pop'].includes(this.accountCode)) {
                    if (this.account.host
                        && bbn.fn.isHostname(this.account.host)
                        && (!this.hasSMTP || this.smtp)
                    ) {
                      ok = true;
                    }
                  }
                  else {
                    ok = true;
                  }
                  if (ok) {
                    bbn.fn.post(
                      this.cp.source.root + 'actions/account',
                      bbn.fn.extend({action: 'test'}, this.account),
                      d => {
                        if (d.data) {
                          let checked = [];
                          bbn.fn.each(d.data, a => {
                            if (a.subscribed) {
                              checked.push(a.uid);
                            }
                            this.tree.push(a);
                          });
                          this.errorState = false;
                          this.$nextTick(() => {
                            this.account.folders = checked;
                            this.getRef('tree').checked = this.account.folders;
                            this.getRef('tree').updateData();
                          });
                        }
                        else {
                          this.errorState = true;
                        }
                      }
                    );
                  }
                }
              }, 1000)
            }
          },
          "account.email"(nv, ov) {
						if (nv) {
              if (ov === this.account.login) {
                this.account.login = nv;
              }
              else if (!this.account.login) {
                this.account.login = nv;
              }
            }
          }
        }
      }
    }
  };
})()