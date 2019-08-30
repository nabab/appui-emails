<bbn-table :source="root + 'data/emails'"
           :pageable="true"
           :editable="false"
           :filterable="true"
           :filters="{
             logic: 'AND',
             conditions: [{
               field: 'etat',
               operator: 'eq',
               value: 'pret'
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
  <bbns-column field="titre"
               title="<?=_('Title')?>"
               :render="renderTitre"
  ></bbns-column>
  <bbns-column field="id_mailing"
               title="<?=_('e-Mailing')?>"
               :render="renderMailing"
               :width="100"
               cls="bbn-c"
  ></bbns-column>
  <bbns-column field="etat"
               title="<?=_('Status')?>"
               :source="status"
               :render="renderEtat"
               cls="bbn-c"
               :width="80"
  ></bbns-column>
  <bbns-column field="envoi"
               title="<?=_('Date')?>"
               cls="bbn-c"
               type="datetime"
               :width="120"
  ></bbns-column>
  <bbns-column field="lu"
               title="<?=_('Saw')?>"
               :width="80"
               :hidden="true"
  ></bbns-column>
</bbn-table>