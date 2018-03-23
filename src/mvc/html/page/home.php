<bbn-splitter orientation="horizontal">
  <bbn-pane :size="250">
		<bbn-splitter orientation="vertical">
			<bbn-pane>
        <bbn-tree :source="menu"
                  :opened="true"
                  @select="setFilter"
                  ref="tree"
                  :path="treePath"
                  uid="id"
        >
        </bbn-tree>
      </bbn-pane>
			<bbn-pane :size="200">
		  </bbn-pane>
		</bbn-splitter>
	</bbn-pane>
  <bbn-pane>
    <bbn-table ref="table"
               @ready="setSelected"
               :source="source.root + 'data/home'"
               :info="true"
               :pageable="true"
               :sortable="true"
               :filterable="true"
               :multifilter="true"
               :editable="true"
               :toolbar="[{
                 text: '<?=_('New mailing')?>',
                 icon: 'fa fa-plus',
                 command: insert
               }]"
               editor="appui-emails-form"
               :order="[{field: 'envoi', dir: 'DESC'}]"
    >
      <bbn-column title="<?=_("Object")?>"
                  field="objet"
                  :required="true"
      ></bbn-column>
      <bbn-column field="fichiers"
                  :render="renderFiles"
                  :width="50"
                  title="<i class='fa fa-paperclip bbn-xl'></i>"
                  ftitle="<?=_("Nombre de fichiers attachés")?>"
                  type="number"
                  :sortable="false"
      ></bbn-column>
      <bbn-column title="<?=_("Status")?>"
                  field="statut"
                  :width="80"
                  :source="status"
                  :render="(r) => {return get_field(status, 'value', r.statut, 'text')}"
      ></bbn-column>
      <bbn-column title="<?=_("Recipients")?>"
                  field="destinataires"
                  :width="160"
                  :source="source.recipients"
                  :render="(r) => {return get_field(source.recipients, 'value', r.destinataires, 'text')}"
                  :required="true"
      ></bbn-column>
      <bbn-column title="<?=_("Envoi")?>"
                  field="envoi"
                  :width="100"
                  type="date"
                  :required="true"
                  :nullable="true"
      ></bbn-column>
      <bbn-column title="<?=_("Sent")?>"
                  field="num_accuses"
                  :width="60"
                  type="number"
                  :editable="false"
                  :filterable="false"
                  :sortable="false"
      ></bbn-column>
      <bbn-column title="<?=_("Reçus")?>"
                  field="num_accuses"
                  :width="60"
                  :render="renderSent"
                  :editable="false"
                  :filterable="false"
                  :sortable="false"
      ></bbn-column>
      <bbn-column :width="280"
                  ftitle="<?=_("Actions")?>"
                  :buttons="renderButtons"
									cls="bbn-c"
      ></bbn-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>
