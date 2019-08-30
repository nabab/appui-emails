<div style="padding: 0.5em;overflow: hidden;margin-top:5px"
     v-if="source.default">
  <i :key="source.id_note"
     class="nf nf-fa-check bbn-green"
     
  ></i>
</div>

<div style="padding: 0.5em;overflow: hidden;" v-else>
  <bbn-button :key="source.id_note"
              icon="nf nf-fa-check bbn-red"
              @click="setDefault"
              class=" bbn-button-icon-only"
  ></bbn-button>
</div>
