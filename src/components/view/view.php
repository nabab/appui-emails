<div :class="{
             'bbn-overlay': true,
             'bbn-flex-height': source.attachments.length > 0
             }">
  <bbn-frame :class="{
                  'bbn-100': true,
                  'bbn-flex-fill': source.attachments.length > 0
                  }" :src="link"></bbn-frame>
  <div v-if="source.attachments.length > 0" class="bbn-header bbn-bordered-top">
    <div class="bbn-reactive bbn-xsmargin bbn-xspadded bbn-iblock bbn-radius bbn-p"
         v-for="(file, name) in source.attachments"
         @click="download(file)">
      <a href="javascript:;" v-text="file.name"></a>
    </div>
  </div>
</div>