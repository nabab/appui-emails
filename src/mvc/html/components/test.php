<bbn-form :source="source"
          :scrollable="false"
          class="bbn-full-screen"
          :action="emails.source.root + 'actions/test'"
>
  <div class="bbn-full-screen bbn-hpadded bbn-vspadded">
    <appui-usergroup-picker :multi="true"
                            class="bbn-h-100"
                            v-model="source.users"
    ></appui-usergroup-picker>
  </div>
</bbn-form>