<div class="bbn-flex-width">
  <div class="bbn-flex-fill">
    <span v-text="source.type"></span>
    (<span v-text="num"></span>)
  </div>
  <div>
    <bbn-button @click="insert"
                icon="fa fa-plus"
                text="<?=_('Add letter')?>"
    ></bbn-button>
  </div>
</div>