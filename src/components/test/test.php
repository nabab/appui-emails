<bbn-form :source="source"
          :scrollable="false"
          :action="emails.source.root + 'actions/test'"
          confirm-message="<?=_('Are you sure you want to test this mailing?')?>"
>
  
  <appui-usergroup-picker :multi="true"
                          class="bbn-overlay"
                          v-model="source.users"
                          :scrollable="false"
  ></appui-usergroup-picker>
  
</bbn-form>
