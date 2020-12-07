<bbn-splitter orientation="horizontal"
              class="appui-emails-mailings"
>
  <bbn-pane :size="250" class="bbn-bordered-right">
		<bbn-splitter orientation="vertical">
			<bbn-pane class="bbn-large">
        <bbn-tree :source="menu"
                  :opened="true"
                  @select="setFilter"
                  ref="tree"
                  :path="treePath"
                  uid="id"
                  :style="{'pointer-events': disableTree ? 'none' : 'auto'}"
        ></bbn-tree>
      </bbn-pane>
			<bbn-pane size="50%">
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
    <bbn-router :autoload="false" class="bbn-h-100" :single="true" ref="tableRouter">
      <bbn-container :pinned="true" :load="false" :url="tableURL">
        <bbn-table ref="table"
                   @ready="setSelected"
                   :source="source.root + 'data/home'"
                   :info="true"
                   :pageable="true"
                   uid="id"
                   :sortable="true"
                   :filterable="true"
                   :showable="true"
                   :multifilter="true"
                   :editable="true"
                   :popup="getPopup()"
                   :toolbar="[{
                     text: '<?=_('New mailing')?>',
                     icon: 'nf nf-fa-plus',
                     action: insert,
                   }, {
                     text: '<?=_('Emails ready')?>',
                     icon: 'nf nf-fa-envelope_o',
                     action: openEmailsTab,
                   },{
                     text: '<?=_('Emails sent')?>',
                     icon: 'nf nf-fa-envelope',
                     action: openEmailsSentTab,
                   },{
                     text: '<?=_('Letters types')?>',
                     icon: 'nf nf-fa-list',
                     action: openLettersTypesTab, 
                   }]"
                   editor="appui-emails-form"
                   :order="[{
                     field: 'sent',
                     dir: 'DESC'
                   }]"
                   :tr-class="r => r.priority < 5 ? 'bbn-bg-light-red' : ''"

        >
          <bbns-column title="<?=_("ID")?>"
                       field="id"
                       :filterable="false"
                       :editable="false"
                       :sortable="false"
                       :hidden="true"
                       ></bbns-column>
          <bbns-column title="<?=_("content")?>"
                       field="content"
                       :filterable="false"
                       :editable="false"
                       :sortable="false"
                       :hidden="true"
                       ></bbns-column>             
          <bbns-column title="<?=_("Status")?>"
                       field="state"
                       :width="80"
                       :source="status"
                       ></bbns-column>
          <bbns-column title="<?=_("Infos")?>"
                       field="sender"
                       :render="renderOfficiel"
                       cls="bbn-m"
                       :width="200"
                       ></bbns-column>
          <bbns-column field="priority"
                       title="<?=_("Priority")?>"
                       type="number"
                       :hidden="true"
                       ></bbns-column>
          <bbns-column field="attachments"
                       type="number"
                       :hidden="true"
                       ></bbns-column>
          <bbns-column title="<?=_("Date")?>"
                       field="sent"
                       :width="140"
                       type="datetime"
                       :required="true"
                       :nullable="true"
                       ></bbns-column>
          <bbns-column title="<?=_("Recipients")?>"
                       field="recipients"
                       :width="160"
                       :render="renderRecipients"
                       :required="true"
                       ></bbns-column>
          <bbns-column title="<?=_("Object")?>"
                       field="title"
                       :required="true"
                       ></bbns-column>
          <bbns-column title="<?=_("Sender")?>"
                       field="sender"
                       :width="160"
                       :sortable="false"
                       :source="source.senders"
                       :default="source.senders[0].value"
                       :required="true"
                       :hidden="true"
                       ></bbns-column>
          <bbns-column title="<?=_("Total emails")?>"
                       field="total"
                       :width="60"
                       :hidden="true"
                       type="number"
                       :editable="false"
                       :filterable="false"
                       :sortable="false"
                       ></bbns-column>
          <bbns-column title="<?=_("Emails succeeded")?>"
                       field="success"
                       :hidden="true"
                       type="number"
                       :editable="false"
                       :filterable="false"
                       :sortable="false"
                       ></bbns-column>
          <bbns-column title="<?=_("Emails failed")?>"
                       field="failure"
                       :hidden="true"
                       type="number"
                       :filterable="false"
                       :editable="false"
                       :sortable="false"
                       ></bbns-column>
          <bbns-column title="<?=_("Emails ready")?>"
                       field="ready"
                       :hidden="true"
                       type="number"
                       :editable="false"
                       :sortable="false"
                       ></bbns-column>
          <bbns-column width="100"
                       title="<?=_("Action")?>"
                       :component="$options.components.menu"
                       :source="sourceMenu"
                       >
          </bbns-column>
        </bbn-table>
      </bbn-container>
    </bbn-router>
  </bbn-pane>
</bbn-splitter>