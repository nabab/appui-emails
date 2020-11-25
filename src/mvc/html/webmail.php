<!-- HTML Document -->
<bbn-splitter orientation="horizontal"
              :resizable="true"
              :collapsible="true">
	<bbn-pane :size="250">
    <bbn-tree :source="treeData"/>
  </bbn-pane>
  <bbn-pane>
    <bbn-splitter :orientation="orientation"
                  :resizable="true"
                  :collapsible="true">
      <bbn-pane>
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