<bbn-table :source="root + tableSource"
           :pageable="true"
           :editable="false"
           :filterable="filterable"
           :selection="true"
           :toolbar="$options.components['toolbar']"
           :filters="filters"
           :data="tableData"
           ref="table"
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
               v-if="context !== 'details'"
  ></bbns-column>
  <bbns-column field="id_mailing"
               title="<?=_('e-Mailing')?>"
               :render="renderMailing"
               :width="100"
               cls="bbn-c"
               v-if="context !== 'details'"
               :filterable="false"
  ></bbns-column>
  <bbns-column field="status"
               title="<?=_('Status')?>"
               :source="status"
               :render="renderEtat"
               cls="bbn-c"
               :width="80"
               :filterable="false"
  ></bbns-column>
  <bbns-column field="delivery"
               title="<?=_('Date')?>"
               cls="bbn-c"
               type="date"
               :width="120"
  ></bbns-column>
  <bbns-column field="read"
               title="<?=_('Read')?>"
               :width="80"
               :hidden="true"
  ></bbns-column>
  <bbns-column :render="renderFiles"
               field="attachments" 
               cls="bbn-c"
               :width="50"
               title="<i class='nf nf-fa-paperclip bbn-xl'></i>"
               ftitle="<?=_("Number of attached files")?>"
               :sortable="false"
               :filterable="false"
  ></bbns-column>
  <bbns-column field="priority"
               ftitle="<?=_('Priority')?>"
               title="<i class='nf nf-mdi-truck_fast'></i>"
               :width="30"
               cls="bbn-c"
               :render="renderPriority"
               :filterable="false"
  ></bbns-column>
  <bbns-column width="120"
               :cls="{
                 'bbn-buttons-flex': (context !== 'sent'),
                 'bbn-c' : true
               }"
              :buttons="renderButtons"
  >
  </bbns-column>
</bbn-table>