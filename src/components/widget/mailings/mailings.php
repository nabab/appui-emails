<div class="bbn-padded bbn-w-100">
  <div class="bbn-w-100">
    <div class="bbn-header bbn-b bbn-spadded bbn-no-border-bottom"><?=_('READY')?></div>
    <div class="bbn-w-100 bbn-bordered bbn-background bbn-no-border-top">
      <div v-if="source.ready && source.ready.length">
        <div v-for="(m, i) in source.ready"
                  class="bbn-padded bbn-grid-fields"
        >
          <label><?=_('Sender')?></label>
          <div v-text="m.sender"></div>
          <label><?=_('Recipients')?></label>
          <div v-text="m.recipients"></div>
          <label><?=_('Title')?></label>
          <div v-text="m.title"></div>
          <label><?=_('Date')?></label>
          <div v-text="fdatetime(m.sent)"></div>
          <label><?=_('To send')?></label>
          <div v-text="m.total"></div>
        </div>
      </div>
      <div v-else class="bbn-padded bbn-c"><?=_('There is no data available')?></div>
    </div>
  </div>
  <div class="bbn-top-space bbn-w-100">
    <div class="bbn-header bbn-b bbn-spadded bbn-no-border-bottom"><?=_('SENT')?></div>
    <div class="bbn-w-100 bbn-bordered bbn-background bbn-no-border-top">
      <div v-if="source.sent && source.sent.length">
        <div v-for="(m, i) in source.sent"
                  class="bbn-padded bbn-grid-fields"
        >
          <label><?=_('Sender')?></label>
          <div v-text="m.sender"></div>
          <label><?=_('Recipients')?></label>
          <div v-text="m.recipients"></div>
          <label><?=_('Title')?></label>
          <div v-text="m.title"></div>
          <label><?=_('Date')?></label>
          <div v-text="fdatetime(m.sent)"></div>
          <label><?=_('Sent')?></label>
          <component :is="$options.components.sent" :source="m"></component>
        </div>
      </div>
      <div v-else class="bbn-padded bbn-c"><?=_('There is no data available')?></div>
    </div>
  </div>
</div>