<i v-if="source.default"
   :key="source.id_note"
   class="fa fa-check bbn-lg bbn-green"
></i>
<bbn-button v-else
            :key="source.id_note"
            icon="fa fa-check bbn-lg bbn-red"
            @click="setDefault"
></bbn-button>
