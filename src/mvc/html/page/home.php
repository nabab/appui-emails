<bbn-splitter orientation="horizontal"
              class="appui-emails-mailings"
>
  <bbn-pane :size="250" class="bbn-bordered-right">
		<bbn-splitter orientation="vertical">
			<bbn-pane>
        <bbn-tree :source="menu"
                  :opened="true"
                  @select="setFilter"
                  ref="tree"
                  :path="treePath"
                  uid="id"
                  :style="{'pointer-events': disableTree ? 'none' : 'auto'}"
        >
        </bbn-tree>
      </bbn-pane>
			<bbn-pane :size="300">
				<div class="bbn-100 bbn-block info">
          <div class="bbn-header bbn-vmiddle title">
            <span><strong><?=_('LIVE INFO')?></strong></span>
            <bbn-switch @change="toggleGetInfo" :checked="!!info.getInfo"></bbn-switch>
          </div>
					<div v-if="info.current.id"
							 class="bbn-block bbn-w-100"
					>
            <div class="bbn-header bbn-c"><?=_('IN PROGRESS')?></div>
						<div class="bbn-spadded">
              <div><strong><?=_('Title')?>:</strong> {{info.current.title}}</div>
              <div><strong><?=_('Recipients')?>:</strong> {{info.current.recipients}}</div>
              <div><strong><?=_('Started')?>:</strong> {{fixDate(info.current.moment)}}</div>
              <div><strong><?=_('Sent')?>:</strong> {{info.current.sent}}</div>
            </div>
					</div>
					<div v-if="info.next.id"
							 class="bbn-block bbn-w-100"
					>
						<div class="bbn-header bbn-c"><?=_('NEXT')?></div>
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
               :showable="true"
               :multifilter="true"
               :editable="true"
               :toolbar="[{
                 text: '<?=_('New mailing')?>',
                 icon: 'nf nf-fa-plus',
                 command: insert,
                 class:'bbn-bg-teal bbn-white bbn-medium'
               }, {
                 text: '<?=_('Emails ready')?>',
                 icon: 'nf nf-fa-envelope_o',
                 command: openEmailsTab,
                 class:'bbn-bg-teal bbn-white bbn-medium'
               },{
                 text: '<?=_('Emails sent')?>',
                 icon: 'nf nf-fa-envelope',
                 command: openEmailsSentTab,
                 class:'bbn-bg-teal bbn-white bbn-medium'
               },{
                 text: '<?=_('Letters types')?>',
                 icon: 'nf nf-fa-list',
                 command: openLettersTypesTab, 
                 class:'bbn-bg-teal bbn-white bbn-medium'
               }]"
               editor="appui-emails-form"
               :order="[{
                 field: 'sent',
                 dir: 'DESC'
               }]"
               
    >
      <bbns-column title="<?=_("ID")?>"
                  field="id"
                  :editable="false"
                  :sortable="false"
                  :hidden="true"
      ></bbns-column>
      <bbns-column title="<?=_("Object")?>"
                  field="title"
                  :required="true"
      ></bbns-column>
      <bbns-column field="fichiers"
                  :render="renderFiles"
                  :width="50"
                  title="<i class='nf nf-fa-paperclip bbn-xl'></i>"
                  ftitle="<?=_("Number of attached files")?>"
                  type="number"
                  :sortable="false"
                  :filterable="false"
      ></bbns-column>
      <bbns-column title="<?=_("Status")?>"
                  field="state"
                  :width="80"
                  :source="status"
      ></bbns-column>
      <bbns-column title="<?=_("Sender")?>"
                  field="sender"
                  :width="160"
                  :sortable="false"
                  :source="source.senders"
                  :required="true"
                  :hidden="true"
      ></bbns-column>
      <bbns-column title="<?=_("Recipients")?>"
                  field="recipients"
                  :width="160"
                  :render="renderRecipients"
                  
                  :required="true"
      ></bbns-column>
      <bbns-column title="<?=_("Send")?>"
                  field="sent"
                  :width="100"
                  type="date"
                  :required="true"
                  :nullable="true"
      ></bbns-column>
      <bbns-column title="<?=_("Sent")?>"
                  field="num_accuses"
                  :width="60"
                  type="number"
                  :editable="false"
                  :filterable="false"
                  :sortable="false"
      ></bbns-column>
      <bbns-column title="<?=_("Received")?>"
                  field="num_accuses"
                  :width="60"
                  :render="renderSent"
                  :editable="false"
                  :filterable="false"
                  :sortable="false"
      ></bbns-column>
      <bbns-column :width="130"
                  ftitle="<?=_("Actions")?>"
                  :buttons="renderButtons"
									cls="bbn-l appui-emails-tbuttons bbn-buttons-grid"
      ></bbns-column>
    </bbn-table>
  </bbn-pane>
</bbn-splitter>
