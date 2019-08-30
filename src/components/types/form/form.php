<div class="bbn-padded">
  <bbn-form :source="source"
            ref="form"
            @success="success"
            :action="action"
            :scrollable="false"
  >
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
      <div style="overflow: initial">
        <bbn-rte v-model="source.content"></bbn-rte>
      </div>
    </div>
  </bbn-form>
</div>
