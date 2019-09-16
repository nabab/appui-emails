<bbn-form :source="source.row"
          :data="{ref: ref}"
          ref="form"
          confirm-leave="<?=_("Are you sure you want to leave this form without saving your changes?")?>"
          :action="emails.source.root + 'actions/' + (source.row.id ? 'update' : 'insert')"
          @success="success"
          @failure="failure"          
>
  <div class="bbn-padded bbn-grid-fields">
    <label><?=_("Recipients")?></label>
    <bbn-dropdown placeholder="<?=_("Choose")?>"
                  v-model="source.row.recipients"
                  :source="emails.source.recipients"
    ></bbn-dropdown>
    <label><?=_("Letter type")?></label>
    <bbn-dropdown placeholder="<?=_("Choose")?>"
                  v-model="source.row.lettre_type"
                  :source="emails.source.types"
                  source-value="id"
                  @change="loadLettre"
    ></bbn-dropdown>
    <label><?=_("Object")?></label>
    <bbn-input required="required"
               v-model="source.row.title"
               maxlength="128"
    ></bbn-input>
    <label><?=_("Attachments")?></label>
    <bbn-upload :save-url="'file/save/' + ref"
                :remove-url="'file/delete/' + ref"
                v-model="source.row.fichiers"
    ></bbn-upload>
    <label><?=_("Text")?></label>
    <div style="height: 400px">
      <div class="bbn-100">
        <bbn-rtef v-model="source.row.content"></bbn-rtef>
      </div>
    </div>
    <label><?=_("Sending time")?></label>
    <div>
      <bbn-datetimepicker v-model="source.row.envoi"
                          :min="today"
                          @change="changeDate"
      ></bbn-datetimepicker>
    </div>
  </div>
</bbn-form>