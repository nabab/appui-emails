<!--bbn-table :source="root + 'data/sent'"
           :pageable="true"
           :editable="false"
           :filterable="true"
           :filters="{
             logic: 'AND',
             conditions: [{
               field: 'status',
               operator: 'eq',
               value: 'success'
             }]
           }"
>
  <bbns-column field="id"
               title="<?=_('ID')?>"
               :hidden=true
  ></bbns-column>
  <bbns-column field="email"
               title="<?=_('e-Mail address')?>"
               type="email"
  ></bbns-column>
  <bbns-column field="subject"
               title="<?=_('Title')?>"
               :render="renderTitre"
  ></bbns-column>
  <bbns-column field="id_mailing"
               title="<?=_('e-Mailing')?>"
               :render="renderMailing"
               :width="100"
               cls="bbn-c"
  ></bbns-column>
  <bbns-column field="status"
               title="<?=_('Status')?>"
               :source="status"
               :render="renderEtat"
               cls="bbn-c"
               :width="80"
  ></bbns-column>
  <bbns-column field="delivery"
               title="<?=_('Date')?>"
               cls="bbn-c"
               type="datetime"
               :width="120"
  ></bbns-column>
  <bbns-column field="read"
               title="<?=_('Read')?>"
               :width="80"
               :hidden="true"
  ></bbns-column>
</bbn-table-->
<appui-emails-table :source="source" 
                    tableSource="data/sent"
                    :filters="{
                      logic: 'AND',
                      conditions: [{
                        field: 'status',
                        operator: 'eq',
                        value: 'success'
                      }]
                    }"
                    context="sent"
></appui-emails-table>