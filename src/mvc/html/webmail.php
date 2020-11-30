<!-- HTML Document -->
<div class="bbn-overlay">
  <bbn-splitter orientation="horizontal"
                v-if="source.accounts.length"
                :resizable="true"
                :collapsible="true">
    <bbn-pane :size="250">
      <bbn-tree :source="treeData"
                uid="uid"
                @select="selectFolder"/>
    </bbn-pane>
    <bbn-pane>
      <bbn-splitter :orientation="orientation"
                    :resizable="true"
                    :collapsible="true">
        <bbn-pane size="50%">
          <bbn-toolbar>
          </bbn-toolbar>
          <bbn-table></bbn-table>
        </bbn-pane>
        <bbn-pane>
          <bbn-toolbar>
          </bbn-toolbar>
          <bbn-iframe></bbn-iframe>
        </bbn-pane>
      </bbn-splitter>
    </bbn-pane>
  </bbn-splitter>
  <div class="bbn-overlay bbn-middle" v-else>
    <div class="bbn-block bbn-lpadded">
      <p>
        <?=_("You have no account configured yet")?>
      </p>
      <p>
        <bbn-button @click="createAccount"><?=_("Create a new mail account")?></bbn-button>
      </p>
    </div>
  </div>
</div>