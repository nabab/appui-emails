<bbn-form :source="source"
          ref="form"
          @success="success"
          :action="action"
>
  <appui-notes-toolbar-version :source="source" 
                               :data="{id: source.id_note}" 
                               @version="getVersion" 
                               v-if="source.hasVersions" 
                               :actionUrl="root + '/data/version'"                              
  ></appui-notes-toolbar-version>
  <div class="bbn-padded bbn-grid-fields">
    <label v-if="emptyCategories && emptyCategories.length"><?=_('Type')?></label>
    <bbn-dropdown v-if="emptyCategories && emptyCategories.length" 
                  v-model="source.id_type" 
                  :source="emptyCategories"
                  source-value="id"
                  :nullable="false"
    ></bbn-dropdown>
    <label><?=_('Name')?></label>
    <bbn-input v-model="source.name"></bbn-input>
    <label><?=_('Object')?></label>
    <bbn-input v-model="source.title"></bbn-input>
    <label><?=_('Text')?></label>
    <div style="height: 400px;">
      <div class="bbn-h-100">
        <bbn-rte v-model="source.content" ref="editor"></bbn-rte>
      </div>
    </div>
  </div>
</bbn-form>