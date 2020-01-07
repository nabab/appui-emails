<!--bbn-table :source="source.root + 'data/details'"
           :data="{id: source.id}"
           :info="true"
           :pageable="true"
           :sortable="true"
           :filterable="true"
>
  <bbns-column title="<?=_("Recipient")?>"
              field="email"
              type="email"
  ></bbns-column>
  <bbns-column title="<?=_("État")?>"
              field="status"
              :width="120"
              cls="bbn-c"
              :source="status"
              :render="renderEtat"
  ></bbns-column>
  <bbns-column title="<?=_("Envoi")?>"
              field="delivery"
              type="date"
              :width="150"
              cls="bbn-c"
  ></bbns-column>
</bbn-table-->
<appui-emails-table :source="source"
                    tableSource="data/details"  
                    :tableData="{id: source.id}"
                    :filterable="false"
                    context="details"
                    v-if="source.id"
></appui-emails-table>