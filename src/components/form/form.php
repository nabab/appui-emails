<bbn-form :source="source.row"
          :data="dataToSend"
          v-if="dataToSend"
          ref="form"
          confirm-leave="<?=_("Are you sure you want to leave this form without saving your changes?")?>"
          :action="emails.source.root + 'actions/' + (source.row.id ? 'update' : 'insert')"
          :prefilled="prefilled"
          @success="success"
          @failure="failure"          
>
  <appui-note-toolbar-version :source="source.row" 
                               :data="{id: source.row.id_note}" 
                               @version="getVersion" 
                               v-if="source.row.hasVersions" 
                               :actionUrl="root + 'data/mailing_version'"                              
  ></appui-note-toolbar-version>
  <div class="bbn-padded bbn-grid-fields">

    <div v-if="emails.source.senders.length === 2" class="bbn-grid-full bbn-middle">
      <span v-text="emails.source.senders[0].text"
            :title="emails.source.senders[0].desc"
            class="bbn-iblock bbn-h-100 bbn-spadded bbn-m bbn-b">
      </span>
      <bbn-switch :value="emails.source.senders[1].value"
                  :novalue="emails.source.senders[0].value"
                  v-model="source.row.sender">
      </bbn-switch>
      <span v-text="emails.source.senders[1].text"
            :title="emails.source.senders[1].desc"
            class="bbn-iblock bbn-h-100 bbn-spadded bbn-m bbn-b">
      </span>
    </div>

    <label v-if="emails.source.senders.length > 2"><?=_("Sender email")?></label>
    <bbn-dropdown v-if="emails.source.senders.length > 2"
                  placeholder="<?=_("Choose")?>"
                  v-model="source.row.sender"
                  :source="emails.source.senders"
                  :required="true"
    ></bbn-dropdown>

    <label><?=_("Recipients")?></label>
    <div class="bbn-vmiddle">
      <bbn-dropdown placeholder="<?=_("Choose")?>"
                    v-model="source.row.recipients"
                    :source="emails.source.recipients"
                    :required="true"
                    class="bbn-wide"
      ></bbn-dropdown>
      <div class="bbn-iblock bbn-m bbn-left-space" v-if="source.row.recipients">
        <span class="bbn-anim-blink" v-if="isNumLoading">
          <?=_("Retrieving list...")?>
        </span>
        <span class="bbn-i" v-else>
          <span v-text="numRecipients"></span> <?=_('recipients')?>
        </span>
      </div>
    </div>

    <label><?=_("Sending time")?></label>
    <div>
      <bbn-datetimepicker v-model="source.row.sent"
                          :min="today"
                          class="bbn-wide"
                          :autosize="false"
                          value-format="YYYY-MM-DD HH:mm:00"
      ></bbn-datetimepicker>
    </div>

    <label v-if="source.row.sent"><?=_("Priority")?></label>
    <div v-if="source.row.sent" class="bbn-vmiddle">
      <span v-text="_('Normal')"
            class="bbn-iblock bbn-hmargin">
      </span>
      <bbn-switch v-model="source.row.priority"
                  :value="4"
                  :novalue="5"
                  ></bbn-switch>
      <span v-text="_('High')"
            class="bbn-iblock bbn-hmargin">
      </span>
    </div>

    <!--label><?=_("Letter type")?></label>
    <bbn-dropdown placeholder="<?=_("Choose")?>"
                  v-model="source.row.lettre_type"
                  :source="emails.source.types"
                  source-value="id"
                  @change="loadLettre"
    ></bbn-dropdown-->

    <label><?=_("Object")?></label>
    <bbn-input required="required"
               v-model="source.row.title"
               maxlength="128"
    ></bbn-input>

    <label><?=_("Attachments")?></label>
    <bbn-upload :save-url="'file/save/' + ref"
                :multiple="true"
                v-model="source.row.attachments"
                :paste="true"
    ></bbn-upload>

    <label><?=_("Text")?></label>
    <div style="height: 400px">
      <div class="bbn-100">
        <bbn-rte v-model="source.row.content" :required="true" ref="editor"></bbn-rte>
      </div>
    </div>

  </div>
</bbn-form>