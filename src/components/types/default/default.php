<div style="padding: 0.5em;overflow: hidden;margin-top:5px"
     v-if="source.default">
  <i :key="source.id_note"
     class="fa fa-check bbn-green"
     
  ></i>
</div>

<div style="padding: 0.5em;overflow: hidden;" v-else>
  <bbn-button :key="source.id_note"
              icon="fa fa-check bbn-red"
              @click="setDefault"

  ></bbn-button>
</div>
