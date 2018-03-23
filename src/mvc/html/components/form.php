<bbn-form :source="source.row"
          :data="{ref: ref}"
          ref="form"
          confirm-leave="<?=_("Êtes-vous sûr de vouloir quitter ce formulaire sans enregistrer vos modifications?")?>"
          :action="emails.source.root + 'actions/' + (source.row.id ? 'update' : 'insert')"
          @success="success"
          @failure="failure"
          class="bbn-full-screen"
>
  <div class="bbn-padded bbn-grid-fields">
    <label><?=_("Recipients")?></label>
    <bbn-dropdown placeholder="<?=_("Choose")?>"
                  v-model="source.row.destinataires"
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
               v-model="source.row.objet"
               maxlength="128"
    ></bbn-input>
    <label><?=_("Attachments")?></label>
    <bbn-upload :save-url="'file/save/' + ref"
                :remove-url="'file/delete/' + ref"
                v-model="source.row.fichiers"
    ></bbn-upload>
    <label><?=_("Text")?></label>
    <bbn-rte rows="30"
             v-model="source.row.texte"
    ></bbn-rte>
    <label><?=_("Sending time")?></label>
    <bbn-datetimepicker v-model="source.row.envoi"
                        :min="today"
                        @change="changeDate"
    ></bbn-datetimepicker>
  </div>
</bbn-form>