<bbn-form :source="source"
          :scrollable="true"
          class="bbn-full-screen"
          :action="emails.source.root + 'actions/test'"
          confirm-message="<?=_('Are you sure you want to test this mailing?')?>"
>
  <div class="bbn-full-screen bbn-hpadded bbn-vspadded">
    <appui-usergroup-picker :multi="true"
                            class="bbn-h-100"
                            v-model="source.users"
    ></appui-usergroup-picker>
  </div>
</bbn-form>
