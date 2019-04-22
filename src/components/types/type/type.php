<div class="bbn-flex-width">
  <div class="bbn-flex-fill">
    <span v-text="source.type"></span>
    (<span v-text="num"></span>)
  </div>
  <div>
    <bbn-button @click="insert"
                icon="nf nf-fa-plus"
                text="<?=_('Add letter')?>"
    ></bbn-button>
  </div>
</div>