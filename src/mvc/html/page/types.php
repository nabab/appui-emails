<bbn-table :source="source.categories"
           :editable="false"
           ref="table"
           :order="[{field: 'text', dir: 'ASC'}]"
           :groupable="true"
           :group-by="3"
           uid="id_note"
>
  <bbn-column title="<?=_("ID")?>"
              field="id_note"
              :width="100"
              :hidden="true"
  ></bbn-column>
  <bbn-column title="<i class='fa fa-check bbn-c bbn-xl'></i>"
              ftitle="<?=_("Default")?>"
              field="default"
              :width="50"
              component="appui-emails-types-default"
              cls="bbn-c"
  ></bbn-column>
  <bbn-column title="<?=_("Version")?>"
              field="version"
              type="number"
              :width="50"
              cls="bbn-c"
  ></bbn-column>
  <bbn-column title="<?=_("Type")?>"
              field="type"
              component="appui-emails-types-type"
  ></bbn-column>
  <bbn-column title="<?=_("Object")?>"
              field="title"
  ></bbn-column>
  <bbn-column title="<?=_("User")?>"
              field="id_user"
              :render="renderUser"
  ></bbn-column>
  <bbn-column title="<?=_("Last edit")?>"
              field="creation"
              type="date"
              :width="120"
              cls="bbn-c"
  ></bbn-column>
  <bbn-column title="<?=_("Text")?>"
              field="content"
              :hidden="true"
  ></bbn-column>
  <bbn-column field="id_type"
              :hidden="true"
              :editable="false"
  ></bbn-column>
  <bbn-column width='100'
              ftitle="<?=_('Actions')?>"
              :buttons="renderButtons"
              cls="bbn-c"
  ></bbn-column>
</bbn-table>

