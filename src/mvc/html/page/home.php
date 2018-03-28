<bbn-splitter orientation="horizontal"
              class="appui-emails-mailings"
>
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
			<bbn-pane :size="300">
				<div class="bbn-100 k-block info">
          <div class="k-header bbn-vmiddle title">
            <span><strong><?=_('LIVE INFO')?></strong></span>
            <bbn-switch @change="toggleGetInfo" :checked="true"></bbn-switch>
          </div>
					<div v-if="info.current.id"
							 class="k-block"
					>
            <div class="k-header bbn-c"><?=_('CURRENT')?></div>
						<div class="bbn-spadded">
              <div><strong><?=_('Title')?>:</strong> {{info.current.title}}</div>
              <div><strong><?=_('Recipients')?>:</strong> {{info.current.recipients}}</div>
              <div><strong><?=_('Started')?>:</strong> {{fixDate(info.current.moment)}}</div>
              <div><strong><?=_('Sent')?>:</strong> {{info.current.sent}}</div>
            </div>
					</div>
					<div v-if="info.next.id"
							 class="k-block"
					>
						<div class="k-header bbn-c"><?=_('NEXT')?></div>
            <div class=" bbn-spadded">
              <div><strong><?=_('Title')?>:</strong> {{info.next.title}}</div>
              <div><strong><?=_('Recipients')?>:</strong> {{info.next.recipients}}</div>
              <div><strong><?=_('Start')?>:</strong> {{fixDate(info.next.moment)}}</div>
            </div>
					</div>
				</div>
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
               }, {
                 text: '<?=_('Letters types')?>',
                 icon: 'fa fa-list',
                 command: openLettersTypes
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
                  ftitle="<?=_("Number of attached files")?>"
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
      <bbn-column title="<?=_("Send")?>"
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
      <bbn-column title="<?=_("Received")?>"
                  field="num_accuses"
                  :width="60"
                  :render="renderSent"
                  :editable="false"
                  :filterable="false"
                  :sortable="false"
      ></bbn-column>
      <bbn-column :width="200"
                  ftitle="<?=_("Actions")?>"
                  :buttons="renderButtons"
									cls="bbn-c"
      ></bbn-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>
