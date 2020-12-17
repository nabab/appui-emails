<!-- HTML Document -->
<div class="bbn-overlay">
  <bbn-splitter orientation="horizontal"
                v-if="source.accounts.length"
                :resizable="true"
                :collapsible="true">
    <bbn-pane :size="250">
      <bbn-tree :source="treeData"
                uid="uid"
                :opened="true"
                storage-full-name="appui-emails-webmail-tree"
                @select="selectFolder"/>
    </bbn-pane>
    <bbn-pane>
      <bbn-splitter :orientation="orientation"
                    :resizable="true"
                    :collapsible="true">
        <bbn-pane size="50%">
          <bbn-toolbar>
          </bbn-toolbar>
          <bbn-table :source="source.sroot + 'webmail'"
                     storage-full-name="appui-emails-webmail-table"
                     :filterable="true"
                     :selection="true"
                     @focus="selectMessage"
                     :multifilter="true"
                     :data="dataObj"
                     ref="table"
                     :sortable="true"
                     :showable="true"
                     :order="[{field: 'date', dir: 'DESC'}]"
                     :pageable="true">
            <bbns-column title="<i class='nf nf-eye'></i>"
                         :ftitle="_('Read')"
                         type="boolean"
                         :width="30"
                         field="read"/>
            <bbns-column title="<i class='nf nf-mdi-paperclip'></i>"
                         :ftitle="_('Attachments')"
                         :width="30"
                         type="number"
                         field="attachments"
                         :render="showAttachments"/>
            <bbns-column :title="_('Date')"
                         type="datetime"
                         :width="120"
                         field="date"/>
            <bbns-column :title="_('From')"
                         editor="bbn-autocomplete"
                         :width="200"
                         :source="source.contacts"
                         field="id_sender"/>
            <bbns-column :title="_('Subject')"
                         :render="showSubject"
                         field="subject"/>
            <bbns-column :title="_('Size')"
                         :width="100"
                         field="size"
                         :hidden="true"/>
          </bbn-table>
        </bbn-pane>
        <bbn-pane>
          <div class="bbn-100" v-if="selectedMail">
            <div class="bbn-overlay">
              <div class="bbn-flex-height">
                <bbn-toolbar class="bbn-m">
                  <bbn-button icon="nf nf-fa-mail_reply"
                              :text="_('Reply')"
                              :notext="true"
                              @click="reply"/>
                  <bbn-button icon="nf nf-fa-mail_reply_all"
                              :text="_('Reply All')"
                              :notext="true"
                              @click="replyAll"/>
                  <bbn-button icon="nf nf-fa-mail_forward"
                              :text="_('Forward')"
                              :notext="true"
                              @click="forward"/>
                  <bbn-button icon="nf nf-mdi-tab_plus"
                              :text="_('Open in a new tab')"
                              :notext="true"
                              @click="openTab"/>
                  <bbn-button icon="nf nf-mdi-window_restore"
                              :text="_('Open in a new window')"
                              :notext="true"
                              @click="openWindow"/>
                  <bbn-button icon="nf nf-fa-archive"
                              :text="_('Archive')"
                              :notext="true"
                              @click="archive"/>
                  <bbn-button icon="nf nf-weather-fire"
                              :text="_('Set as junk')"
                              :notext="true"
                              @click="setAsJunk"/>
                  <bbn-button icon="nf nf-mdi-delete"
                              :text="_('Delete')"
                              :notext="true"
                              @click="deleteMail"/>
                  <bbn-button icon="nf nf-fa-bug"
                              :text="_('Transform in task')"
                              :notext="true"
                              @click="mailToTask"/>
                  <bbn-treedown :placeholder="_('Move to')"
                                class="bbn-wide"
                                :source="treeData"/>
                </bbn-toolbar>
                <div class="bbn-flex-fill">
                  <bbn-frame :src="source.root + 'reader/' + selectedMail.id" class="bbn-100"></bbn-frame>
                </div>
              </div>
            </div>
          </div>
          <div class="bbn-overlay bbn-middle" v-else>
            <div class="bbn-block bbn-large bbn-c" v-text="_('Select an email to see its content here')"/>
          </div>
        </bbn-pane>
      </bbn-splitter>
    </bbn-pane>
  </bbn-splitter>
  <div class="bbn-overlay bbn-middle" v-else>
    <div class="bbn-block bbn-lpadded bbn-lg">
      <p>
        <?=_("You have no account configured yet")?>
      </p>
      <p>
        <bbn-button @click="createAccount"><?=_("Create a new mail account")?></bbn-button>
      </p>
    </div>
  </div>
  <script type="text/x-template" :id="scpName + '-editor'">
    <bbn-form :source="account"
              @success="success"
              :data="{action: 'save'}"
              :validation="() => account.folders.length > 0"
              :action="cp.source.root + 'actions/account'">
      <div class="bbn-overlay" v-show="tree.length">
        <div class="bbn-flex-height">
          <div class="bbn-w-100">
            <div class="bbn-padded">
              <bbn-button @click="backToConfig"><?=_("Back")?></bbn-button>
            </div>
            <div class="bbn-m bbn-b bbn-c">
                <?=_("Choose the folders you want to keep synchronized")?>
            </div>
          </div>
          <div class="bbn-padded bbn-flex-fill">
            <bbn-tree :source="tree"
                      ref="tree"
                      :selection="true"
                      uid="uid"
                      :opened="true"/>
          </div>
		    </div>
	    </div>
      <div class="bbn-w-100" v-show="!tree.length">
        <div class="bbn-grid-fields bbn-padded bbn-m">
          <div class="bbn-label">
            <?=_("Account type")?>
          </div>
          <bbn-dropdown :source="types"
                        source-value="id"
                        placeholder="<?=_("Choose a type of account")?>"
                        v-model="account.type"
                        :required="true"/>

          <div class="bbn-label">
            <?=_("Main eMail address for this account")?>
          </div>
          <bbn-input v-model="account.email"
                     type="email"
                     :required="true"/>

          <div class="bbn-label">
            <?=_("Login")?>
          </div>
          <bbn-input v-model="account.login"
                     :required="true"/>

          <div class="bbn-label">
            <?=_("Password")?>
          </div>
          <bbn-input v-model="account.pass"
                     type="password"
                     :no-save="true"
                     :required="true"/>

          <div v-if="['imap', 'pop3'].includes(accountCode)"
               class="bbn-label">
            <?=_("Incoming server")?>
          </div>
          <bbn-input v-if="['imap', 'pop3'].includes(accountCode)"
                     type="hostname"
                     v-model="account.host"
                     :required="true"/>

          <div class="bbn-grid-full bbn-c"
               v-if="account.host && ['imap', 'pop3'].includes(accountCode)">
            <a href="javascript:;" @click="hasSMTP = !hasSMTP">
              <?=_("Click here to change the outgoing server configuration if it is different")?>
            </a>
          </div>

          <div v-if="hasSMTP && ['imap', 'pop3'].includes(accountCode)"
               class="bbn-label">
            <?=_("Outgoing server")?>
          </div>
          <bbn-input v-if="hasSMTP && ['imap', 'pop3'].includes(accountCode)"
                     v-model="account.smtp"
                     type="hostname"
                     :required="true"/>

          <div class="bbn-grid-full bbn-c bbn-b bbn-state-error"
               v-if="errorState">
            <?=_("Impossible to connect to the mail server")?>
          </div>

    		</div>
	    </div>
    </bbn-form>
  </script>
</div>