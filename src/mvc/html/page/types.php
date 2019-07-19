<bbn-table :source="source.categories"
           editable="popup"
           ref="table"
           :order="[{field: 'text', dir: 'ASC'}]"
           :groupable="true"
           :group-by="3"
           uid="id_note"
           :toolbar="source.empty_categories.length ? toolbar : []"
>
  <bbns-column title="<?=_("ID")?>"
              field="id_note"
              :width="100"
              :hidden="true"
  ></bbns-column>
  <bbns-column title="<i class='nf nf-fa-check bbn-c bbn-xl'></i>"
              ftitle="<?=_("Default")?>"
              field="default"
              :width="50"
              component="appui-emails-types-default"
              cls="bbn-c"
  ></bbns-column>
  <bbns-column title="<?=_("Version")?>"
              field="version"
              type="number"
              :width="50"
              cls="bbn-c"
  ></bbns-column>
  <bbns-column title="<?=_("Type")?>"
              field="type"
              component="appui-emails-types-type"
  ></bbns-column>
  <bbns-column title="<?=_("Name")?>"
               field="name"
  ></bbns-column>
  <bbns-column title="<?=_("Object")?>"
              field="title"
  ></bbns-column>
  <bbns-column title="<?=_("User")?>"
              field="id_user"
              :render="renderUser"
  ></bbns-column>
  <bbns-column title="<?=_("Last edit")?>"
              field="creation"
              type="date"
              :width="120"
              cls="bbn-c"
  ></bbns-column>
  <bbns-column title="<?=_("Text")?>"
              field="content"
              :hidden="true"
  ></bbns-column>
  <bbns-column field="id_type"
              :hidden="true"
              :editable="false"
  ></bbns-column>
  <bbns-column width='100'
              ftitle="<?=_('Actions')?>"
              :buttons="renderButtons"
              cls="bbn-c"
  ></bbns-column>
</bbn-table>

