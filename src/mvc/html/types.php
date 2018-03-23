<bbn-table :source="source.categories"
           editable="true"
           :editor="$options.components.form_lettres_types"
           ref="table"
           :order="[{text:'ASC'}]"
           :groupable="true"
           :group-by="3"
           uid="id_note"
>
  <bbn-column title="<?=_("ID")?>"
              field="id_note"
              :width="100"
              :hidden="true"

  ></bbn-column>

  <bbn-column title="<?=_("Défaut")?>"
              field="default"
              :width="50"
              :component="$options.components.def"

  ></bbn-column>

  <bbn-column title="<?=_("Version")?>"
              field="version"
              type="number"
              :width="50"

  ></bbn-column>

  <bbn-column title="<?=_("Type de lettre")?>"
              field="type"
              :component="$options.components.cat"
  ></bbn-column>

  <bbn-column title="<?=_("Objet")?>"
              field="title"
  ></bbn-column>


  <bbn-column title="<?=_("User")?>"
              field="id_user"
              :render="render_user"

  ></bbn-column>

  <bbn-column title="<?=_("Last modified")?>"
              field="creation"
              type="date"

  ></bbn-column>

  <bbn-column title="<?=_("Text")?>"
              field="content"
              :hidden="true"

  ></bbn-column>

  <bbn-column field="id_type"
              :hidden="true"
              :editable="false"
  ></bbn-column>


  <bbn-column width='160'
              :title="_('Actions')"
              :buttons="table_buttons"
  ></bbn-column>

</bbn-table>

