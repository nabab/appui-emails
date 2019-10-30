<bbn-form :source="source.row"
          :data="{ref: ref}"
          ref="form"
          confirm-leave="<?=_("Are you sure you want to leave this form without saving your changes?")?>"
          :action="emails.source.root + 'actions/' + (source.row.id ? 'update' : 'insert')"
          @success="success"
          @failure="failure"          
>
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
    ></bbn-dropdown>
    <label><?=_("Recipients")?></label>
    <bbn-dropdown placeholder="<?=_("Choose")?>"
                  v-model="source.row.recipients"
                  :source="emails.source.recipients"
    ></bbn-dropdown>
    <label><?=_("Sending time")?></label>
    <div>
      <bbn-datetimepicker v-model="source.row.sent"
                          :min="today"
                          value-format="YYYY-MM-DD HH:mm:00"
      ></bbn-datetimepicker>
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
                :remove-url="'file/delete/' + ref"
                v-model="source.row.fichiers"
                :paste="true"
    ></bbn-upload>
    <label><?=_("Text")?></label>
    <div style="height: 400px">
      <div class="bbn-100">
        <bbn-rtef v-model="source.row.content"></bbn-rtef>
      </div>
    </div>
  </div>
</bbn-form>