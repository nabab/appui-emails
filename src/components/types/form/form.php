<bbn-form class="bbn-full-screen"
          :source="source"
          ref="form"
          @success="success"
          :action="root + '/actions/types/' + (source.id_note ? 'update' : 'insert')"
          :scrollable="false"
>
  <div class="bbn-padded bbn-grid-fields">
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
