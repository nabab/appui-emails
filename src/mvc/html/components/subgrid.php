<bbn-table source="com/accuses"
           :data="{id: source.id}"
           :info="true"
           :pageable="true"
           :sortable="true"
>
  <bbn-column title="<?=_("Recipient")?>"
              field="email"
              type="email"
  ></bbn-column>
  <bbn-column title="<?=_("État")?>"
              field="etat"
              :render="etat"
              :width="120"
  ></bbn-column>
  <bbn-column title="<?=_("Envoi")?>"
              field="envoi"
              type="date"
              :width="150"
  ></bbn-column>
</bbn-table>
