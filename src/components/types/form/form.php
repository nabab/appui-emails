<bbn-form class="bbn-full-screen"
          :source="source"
          ref="form"
          @success="success"
          :action="root + '/actions/types/' + (source.id_note ? 'update' : 'insert')"
>
  <div class="bbn-grid-fields bbn-padded">
    <label><?=_('Object')?></label>
    <bbn-input v-model="source.title"></bbn-input>
    <label><?=_('Text')?></label>
    <bbn-rte v-model="source.content"></bbn-rte>
  </div>
</bbn-form>
